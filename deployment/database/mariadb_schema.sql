-- MariaDB 10.6+ schema for the University-hosted study API.
-- Run this as a database administrator, then grant the PHP application account
-- only the permissions shown in the commented example at the end.

CREATE TABLE IF NOT EXISTS task_events (
  id CHAR(36) NOT NULL,
  pid VARCHAR(64) NOT NULL,
  study_condition ENUM('A_fresh', 'B_chained') NOT NULL,
  placement ENUM('top', 'bottom') NOT NULL,
  record_type ENUM('completed', 'abandoned') NOT NULL,
  payload JSON NOT NULL,
  created_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (id),
  KEY idx_task_events_pid (pid),
  KEY idx_task_events_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS task_withdrawals (
  id CHAR(36) NOT NULL,
  pid VARCHAR(64) NOT NULL,
  study_condition ENUM('A_fresh', 'B_chained') NOT NULL,
  placement ENUM('top', 'bottom') NOT NULL,
  requested_at DATETIME(3) NOT NULL,
  payload JSON NOT NULL,
  created_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (id),
  KEY idx_task_withdrawals_pid (pid),
  KEY idx_task_withdrawals_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Serializes assignment-block creation so concurrent requests cannot create
-- overlapping blocks or assign the same slot twice.
CREATE TABLE IF NOT EXISTS study_assignment_state (
  allocator_id TINYINT UNSIGNED NOT NULL,
  next_block_number BIGINT UNSIGNED NOT NULL DEFAULT 1,
  updated_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (allocator_id)
) ENGINE=InnoDB DEFAULT CHARSET=ascii COLLATE=ascii_bin;

INSERT IGNORE INTO study_assignment_state (allocator_id, next_block_number)
VALUES (1, 1);

-- Each block contains all four 2x2 cells exactly once. The PHP allocator
-- shuffles every block before inserting it and assigns the
-- next unclaimed slot within one transaction. A participant code is assigned
-- at most once, so retries and refreshes return the original study cell.
CREATE TABLE IF NOT EXISTS study_assignment_slots (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  block_number BIGINT UNSIGNED NOT NULL,
  block_size TINYINT UNSIGNED NOT NULL,
  block_position TINYINT UNSIGNED NOT NULL,
  study_condition ENUM('A_fresh', 'B_chained') NOT NULL,
  placement ENUM('top', 'bottom') NOT NULL,
  pid VARCHAR(64) NULL,
  assigned_at DATETIME(3) NULL,
  created_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (id),
  UNIQUE KEY uq_assignment_block_position (block_number, block_position),
  UNIQUE KEY uq_assignment_pid (pid),
  KEY idx_assignment_available (assigned_at, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Stores a daily-HMAC pseudonym of REMOTE_ADDR for short-lived rate limiting.
-- It never stores the raw network address. Operational cleanup removes old
-- buckets automatically, and the DBA may also schedule a daily cleanup.
CREATE TABLE IF NOT EXISTS study_rate_limits (
  client_hash CHAR(64) NOT NULL,
  window_bucket BIGINT UNSIGNED NOT NULL,
  request_count INT UNSIGNED NOT NULL DEFAULT 0,
  updated_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (client_hash, window_bucket),
  KEY idx_rate_limits_updated_at (updated_at)
) ENGINE=InnoDB DEFAULT CHARSET=ascii COLLATE=ascii_bin;

-- Example least-privilege application account permissions. Replace the
-- account/host to match the University environment and execute as a DBA.
-- GRANT INSERT ON ai_authentication_study.task_events TO 'study_writer'@'localhost';
-- GRANT INSERT ON ai_authentication_study.task_withdrawals TO 'study_writer'@'localhost';
-- GRANT SELECT, UPDATE ON ai_authentication_study.study_assignment_state TO 'study_writer'@'localhost';
-- GRANT SELECT, INSERT, UPDATE ON ai_authentication_study.study_assignment_slots TO 'study_writer'@'localhost';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON ai_authentication_study.study_rate_limits TO 'study_writer'@'localhost';
-- FLUSH PRIVILEGES;
