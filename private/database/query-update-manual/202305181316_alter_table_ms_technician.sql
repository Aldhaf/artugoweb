ALTER TABLE ms_technician ADD COLUMN join_date datetime DEFAULT NULL;
ALTER TABLE ms_technician ADD COLUMN status tinyint DEFAULT 0 NOT NULL;
UPDATE ms_technician SET status = 1 WHERE 0=0;