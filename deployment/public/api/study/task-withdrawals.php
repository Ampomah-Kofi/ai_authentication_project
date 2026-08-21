<?php

declare(strict_types=1);

/*
 * Public insert-only endpoint for withdrawal requests. Recording a request does
 * not delete data automatically; authorized researchers must verify and process
 * it under the final IRB-approved procedure.
 */
require_once dirname(__DIR__, 3) . '/src/bootstrap.php';

$input = read_json_body();
$pid = require_pid($input);
$condition = require_allowed_value($input, 'condition', STUDY_CONDITIONS);
$placement = require_allowed_value($input, 'placement', STUDY_PLACEMENTS);
$requestedAt = parse_iso_datetime(require_string($input, 'requested_at', 40));
$payload = require_payload($input);
verify_payload_identity($payload, $pid, $condition, $placement);

if (($payload['requested'] ?? null) !== true) {
    fail(422, 'invalid_withdrawal_request');
}

$pdo = database();
enforce_rate_limit($pdo);

try {
    $id = uuid_v4();
    $statement = $pdo->prepare(
        'INSERT INTO task_withdrawals '
        . '(id, pid, study_condition, placement, requested_at, payload) '
        . 'VALUES (?, ?, ?, ?, ?, ?)'
    );
    $statement->execute([
        $id,
        $pid,
        $condition,
        $placement,
        $requestedAt,
        encode_payload($payload),
    ]);
} catch (Throwable $error) {
    error_log('Task withdrawal insert failed: ' . $error->getMessage());
    fail(503, 'record_not_saved');
}

respond(201, ['ok' => true, 'id' => $id]);
