<?php

declare(strict_types=1);

/*
 * Public insert-only endpoint for completed and best-effort abandoned tasks.
 * All request, origin, size, and field validation is centralized in bootstrap.
 * This endpoint intentionally implements no read, update, or delete operation.
 */
require_once dirname(__DIR__, 3) . '/src/bootstrap.php';

$input = read_json_body();
$pid = require_pid($input);
$condition = require_allowed_value($input, 'condition', STUDY_CONDITIONS);
$placement = require_allowed_value($input, 'placement', STUDY_PLACEMENTS);
$payload = require_payload($input);
verify_payload_identity($payload, $pid, $condition, $placement);

// Abandonment is explicit; every other valid submission is a completed record.
$recordType = (($payload['abandoned'] ?? false) === true) ? 'abandoned' : 'completed';
$pdo = database();
enforce_rate_limit($pdo);

try {
    $id = uuid_v4();
    $statement = $pdo->prepare(
        'INSERT INTO task_events '
        . '(id, pid, study_condition, placement, record_type, payload) '
        . 'VALUES (?, ?, ?, ?, ?, ?)'
    );
    $statement->execute([
        $id,
        $pid,
        $condition,
        $placement,
        $recordType,
        encode_payload($payload),
    ]);
} catch (Throwable $error) {
    error_log('Task event insert failed: ' . $error->getMessage());
    fail(503, 'record_not_saved');
}

respond(201, ['ok' => true, 'id' => $id]);
