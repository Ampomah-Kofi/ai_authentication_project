<?php

declare(strict_types=1);

/*
 * Shared security boundary for the two public study endpoints.
 *
 * The browser is untrusted. Every request is independently constrained here
 * before an endpoint can open a database connection or write research data.
 * Server secrets are read only from protected process environment variables.
 */
const STUDY_CONDITIONS = ['A_fresh', 'B_chained'];
const STUDY_PLACEMENTS = ['top', 'bottom'];

/** Return a trimmed environment value or fail when a required value is absent. */
function env_value(string $name, ?string $default = null): string
{
    $value = getenv($name);
    if ($value === false || trim($value) === '') {
        if ($default !== null) {
            return $default;
        }
        throw new RuntimeException("Missing required server configuration: {$name}");
    }

    return trim($value);
}

/** Send the complete JSON response and terminate so execution cannot continue. */
function respond(int $status, array $body = []): never
{
    http_response_code($status);
    if ($status !== 204) {
        echo json_encode($body, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }
    exit;
}

/** Return a stable public error code without exposing exception or SQL details. */
function fail(int $status, string $code): never
{
    respond($status, ['ok' => false, 'error' => $code]);
}

/**
 * Apply API response headers and reject unapproved origins, methods, and media
 * types. Origin validation is defense in depth; it is not authentication and
 * does not replace rate limiting or infrastructure monitoring.
 */
function initialize_request(): void
{
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    header('Pragma: no-cache');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

    try {
        $allowedOrigin = rtrim(env_value('STUDY_ALLOWED_ORIGIN'), '/');
    } catch (Throwable $error) {
        error_log($error->getMessage());
        fail(503, 'service_not_configured');
    }

    $originParts = parse_url($allowedOrigin);
    if ($originParts === false
        || ($originParts['scheme'] ?? '') !== 'https'
        || !isset($originParts['host'])
        || isset($originParts['user'])
        || isset($originParts['pass'])
        || (isset($originParts['path']) && $originParts['path'] !== '')
        || isset($originParts['query'])
        || isset($originParts['fragment'])) {
        error_log('STUDY_ALLOWED_ORIGIN must be an HTTPS origin with no path, query, or fragment.');
        fail(503, 'service_not_configured');
    }

    $requestOrigin = isset($_SERVER['HTTP_ORIGIN'])
        ? rtrim((string) $_SERVER['HTTP_ORIGIN'], '/')
        : '';

    if ($requestOrigin === '' || !hash_equals($allowedOrigin, $requestOrigin)) {
        fail(403, 'origin_not_allowed');
    }

    header("Access-Control-Allow-Origin: {$allowedOrigin}");
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Max-Age: 600');
    header('Vary: Origin');

    $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? ''));
    if ($method === 'OPTIONS') {
        respond(204);
    }
    if ($method !== 'POST') {
        header('Allow: POST, OPTIONS');
        fail(405, 'method_not_allowed');
    }

    $contentType = strtolower(trim(explode(';', (string) ($_SERVER['CONTENT_TYPE'] ?? ''))[0]));
    if ($contentType !== 'application/json') {
        fail(415, 'json_content_type_required');
    }
}

/** Read one bounded JSON object; never trust Content-Length as the sole limit. */
function read_json_body(): array
{
    $maxBytes = filter_var(
        env_value('STUDY_MAX_BODY_BYTES', '131072'),
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1024, 'max_range' => 1048576]]
    );
    if ($maxBytes === false) {
        fail(503, 'invalid_server_configuration');
    }

    $declaredBytes = isset($_SERVER['CONTENT_LENGTH']) ? (int) $_SERVER['CONTENT_LENGTH'] : 0;
    if ($declaredBytes > $maxBytes) {
        fail(413, 'payload_too_large');
    }

    $raw = file_get_contents('php://input', false, null, 0, $maxBytes + 1);
    if ($raw === false || $raw === '') {
        fail(400, 'empty_request_body');
    }
    if (strlen($raw) > $maxBytes) {
        fail(413, 'payload_too_large');
    }

    try {
        $decoded = json_decode($raw, true, 64, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        fail(400, 'invalid_json');
    }

    if (!is_array($decoded) || array_is_list($decoded)) {
        fail(422, 'json_object_required');
    }

    return $decoded;
}

/** Extract a non-empty bounded string from an untrusted request object. */
function require_string(array $input, string $key, int $maxLength = 128): string
{
    $value = $input[$key] ?? null;
    if (!is_string($value) || $value === '' || strlen($value) > $maxLength) {
        fail(422, "invalid_{$key}");
    }

    return $value;
}

/** Accept only the 80-bit random participant-code format generated by the UI. */
function require_pid(array $input): string
{
    $pid = require_string($input, 'pid', 64);
    if (preg_match('/^P-[A-F0-9]{20}$/D', $pid) !== 1) {
        fail(422, 'invalid_pid');
    }

    return $pid;
}

/** Require exact membership in a server-owned allowlist. */
function require_allowed_value(array $input, string $key, array $allowed): string
{
    $value = require_string($input, $key, 32);
    if (!in_array($value, $allowed, true)) {
        fail(422, "invalid_{$key}");
    }

    return $value;
}

/** Require a non-empty JSON object for the research payload. */
function require_payload(array $input): array
{
    $payload = $input['payload'] ?? null;
    if (!is_array($payload) || array_is_list($payload) || $payload === []) {
        fail(422, 'invalid_payload');
    }

    return $payload;
}

/** Prevent contradictory envelope and payload identifiers from being stored. */
function verify_payload_identity(array $payload, string $pid, string $condition, string $placement): void
{
    if (($payload['pid'] ?? null) !== $pid
        || ($payload['condition'] ?? null) !== $condition
        || ($payload['placement'] ?? null) !== $placement) {
        fail(422, 'payload_identity_mismatch');
    }
}

/** Open a native-prepared PDO connection using server-side credentials only. */
function database(): PDO
{
    try {
        $host = env_value('STUDY_DB_HOST');
        $port = env_value('STUDY_DB_PORT', '3306');
        $name = env_value('STUDY_DB_NAME');
        $user = env_value('STUDY_DB_USER');
        $password = env_value('STUDY_DB_PASSWORD');

        if (filter_var($port, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 65535]]) === false) {
            throw new RuntimeException('Invalid STUDY_DB_PORT');
        }

        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (Throwable $error) {
        error_log('Study database connection failed: ' . $error->getMessage());
        fail(503, 'database_unavailable');
    }
}

/**
 * Enforce a fixed-window request limit. The application stores a rotating HMAC
 * of the direct peer address, not the raw address. Reverse-proxy deployments
 * must be reviewed with IT so one proxy address does not represent all users.
 */
function enforce_rate_limit(PDO $pdo): void
{
    $secret = env_value('STUDY_RATE_LIMIT_SECRET');
    if (strlen($secret) < 32) {
        error_log('STUDY_RATE_LIMIT_SECRET must contain at least 32 characters.');
        fail(503, 'service_not_configured');
    }

    $maxRequests = filter_var(
        env_value('STUDY_RATE_LIMIT_MAX', '30'),
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => 1000]]
    );
    $windowSeconds = filter_var(
        env_value('STUDY_RATE_LIMIT_WINDOW_SECONDS', '60'),
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 10, 'max_range' => 3600]]
    );
    if ($maxRequests === false || $windowSeconds === false) {
        fail(503, 'invalid_server_configuration');
    }

    $remoteAddress = (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $clientHash = hash_hmac('sha256', gmdate('Y-m-d') . '|' . $remoteAddress, $secret);
    $bucket = intdiv(time(), $windowSeconds);

    try {
        $pdo->beginTransaction();
        $insert = $pdo->prepare(
            'INSERT IGNORE INTO study_rate_limits (client_hash, window_bucket, request_count) VALUES (?, ?, 0)'
        );
        $insert->execute([$clientHash, $bucket]);

        $update = $pdo->prepare(
            'UPDATE study_rate_limits SET request_count = request_count + 1, updated_at = CURRENT_TIMESTAMP(3) '
            . 'WHERE client_hash = ? AND window_bucket = ?'
        );
        $update->execute([$clientHash, $bucket]);

        $select = $pdo->prepare(
            'SELECT request_count FROM study_rate_limits WHERE client_hash = ? AND window_bucket = ? FOR UPDATE'
        );
        $select->execute([$clientHash, $bucket]);
        $count = (int) $select->fetchColumn();
        $pdo->commit();

        if (random_int(1, 100) === 1) {
            $pdo->exec('DELETE FROM study_rate_limits WHERE updated_at < (UTC_TIMESTAMP() - INTERVAL 2 DAY)');
        }
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Study rate limiter failed: ' . $error->getMessage());
        fail(503, 'rate_limiter_unavailable');
    }

    if ($count > $maxRequests) {
        header("Retry-After: {$windowSeconds}");
        fail(429, 'rate_limit_exceeded');
    }
}

/** Generate an RFC 4122 version-4 identifier for a database record. */
function uuid_v4(): string
{
    $bytes = random_bytes(16);
    $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
    $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
    $hex = bin2hex($bytes);

    return substr($hex, 0, 8) . '-'
        . substr($hex, 8, 4) . '-'
        . substr($hex, 12, 4) . '-'
        . substr($hex, 16, 4) . '-'
        . substr($hex, 20, 12);
}

/** Encode a validated PHP array as JSON and fail closed on encoding errors. */
function encode_payload(array $payload): string
{
    try {
        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        fail(422, 'invalid_payload_encoding');
    }
}

/** Convert a strict ISO-8601 participant timestamp to a UTC MariaDB value. */
function parse_iso_datetime(string $value): string
{
    if (strlen($value) > 40
        || preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d{1,6})?(?:Z|[+-]\d{2}:\d{2})$/D', $value) !== 1) {
        fail(422, 'invalid_requested_at');
    }

    try {
        $date = new DateTimeImmutable($value);
    } catch (Throwable) {
        fail(422, 'invalid_requested_at');
    }

    $dateErrors = DateTimeImmutable::getLastErrors();
    if ($dateErrors !== false
        && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0)) {
        fail(422, 'invalid_requested_at');
    }

    return $date->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s.v');
}

// Last-resort boundary: details go to protected logs, never to the client.
set_exception_handler(static function (Throwable $error): never {
    error_log('Unhandled study API error: ' . $error->getMessage());
    fail(500, 'internal_error');
});

initialize_request();
