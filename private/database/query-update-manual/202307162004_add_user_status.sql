ALTER TABLE `users` ADD COLUMN status INT(10) UNSIGNED NOT NULL AFTER remember_token;
ALTER TABLE `users` ADD COLUMN join_date DATE NULL AFTER status;
UPDATE users SET status=1 WHERE status=0;