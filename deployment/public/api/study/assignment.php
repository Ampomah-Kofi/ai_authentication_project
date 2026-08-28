<?php

declare(strict_types=1);

/*
 * Idempotent server-side assignment for the 2x2 study design.
 *
 * Permuted blocks of four keep allocation balanced across the four cells. The
 * order within each block is cryptographically shuffled. The allocator state
 * is locked for the short transaction so concurrent requests cannot claim the
 * same slot. Repeating a request with the same 80-bit participant code returns
 * the original assignment.
 */
require_once dirname(__DIR__, 3) . '/src/bootstrap.php';

/** Cryptographically shuffle an array using Fisher-Yates. */
function secure_shuffle(array &$values): void
{
    for ($index = count($values) - 1; $index > 0; $index--) {
        $swapIndex = random_int(0, $index);
        [$values[$index], $values[$swapIndex]] = [$values[$swapIndex], $values[$index]];
    }
}

$input = read_json_body();
$pid = require_pid($input);
$pdo = database();
enforce_rate_limit($pdo);

try {
    $pdo->beginTransaction();

    // This row is the allocator mutex. Holding it serializes slot creation and
    // assignment for only the duration of this small transaction.
    $state = $pdo->query(
        'SELECT next_block_number FROM study_assignment_state '
        . 'WHERE allocator_id = 1 FOR UPDATE'
    );
    $nextBlockNumber = $state->fetchColumn();
    if ($nextBlockNumber === false) {
        throw new RuntimeException('Assignment allocator state is missing.');
    }

    $existing = $pdo->prepare(
        'SELECT study_condition, placement FROM study_assignment_slots WHERE pid = ? LIMIT 1'
    );
    $existing->execute([$pid]);
    $assignment = $existing->fetch();
    $created = false;

    if ($assignment === false) {
        $available = $pdo->query(
            'SELECT id, study_condition, placement FROM study_assignment_slots '
            . 'WHERE pid IS NULL ORDER BY id LIMIT 1 FOR UPDATE'
        );
        $slot = $available->fetch();

        if ($slot === false) {
            $blockNumber = (int) $nextBlockNumber;
            $blockSize = 4;
            $cells = [];
            for ($repeat = 0; $repeat < intdiv($blockSize, 4); $repeat++) {
                foreach (STUDY_CONDITIONS as $condition) {
                    foreach (STUDY_PLACEMENTS as $placement) {
                        $cells[] = [$condition, $placement];
                    }
                }
            }
            secure_shuffle($cells);

            $insert = $pdo->prepare(
                'INSERT INTO study_assignment_slots '
                . '(block_number, block_size, block_position, study_condition, placement) '
                . 'VALUES (?, ?, ?, ?, ?)'
            );
            foreach ($cells as $offset => [$condition, $placement]) {
                $insert->execute([
                    $blockNumber,
                    $blockSize,
                    $offset + 1,
                    $condition,
                    $placement,
                ]);
            }

            $advance = $pdo->prepare(
                'UPDATE study_assignment_state SET next_block_number = next_block_number + 1 '
                . 'WHERE allocator_id = 1'
            );
            $advance->execute();

            $firstSlot = $pdo->prepare(
                'SELECT id, study_condition, placement FROM study_assignment_slots '
                . 'WHERE block_number = ? AND block_position = 1 FOR UPDATE'
            );
            $firstSlot->execute([$blockNumber]);
            $slot = $firstSlot->fetch();
            if ($slot === false) {
                throw new RuntimeException('New assignment block has no first slot.');
            }
        }

        $claim = $pdo->prepare(
            'UPDATE study_assignment_slots SET pid = ?, assigned_at = UTC_TIMESTAMP(3) '
            . 'WHERE id = ? AND pid IS NULL'
        );
        $claim->execute([$pid, $slot['id']]);
        if ($claim->rowCount() !== 1) {
            throw new RuntimeException('Assignment slot was not claimed.');
        }

        $assignment = [
            'study_condition' => $slot['study_condition'],
            'placement' => $slot['placement'],
        ];
        $created = true;
    }

    $pdo->commit();
} catch (Throwable $error) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Study assignment failed: ' . $error->getMessage());
    fail(503, 'assignment_unavailable');
}

respond($created ? 201 : 200, [
    'ok' => true,
    'pid' => $pid,
    'condition' => $assignment['study_condition'],
    'placement' => $assignment['placement'],
]);
