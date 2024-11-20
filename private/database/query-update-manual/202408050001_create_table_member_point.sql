ALTER TABLE ms_products ADD COLUMN base_point DECIMAL(21,2) NOT NULL DEFAULT 0;
ALTER TABLE reg_warranty ADD COLUMN total_point DECIMAL(21,2) NOT NULL DEFAULT 0;
ALTER TABLE reg_warranty ADD COLUMN used_point DECIMAL(21,2) NOT NULL DEFAULT 0;
ALTER TABLE reg_warranty ADD COLUMN balance_point DECIMAL(21,2) NOT NULL DEFAULT 0;

CREATE TABLE `member_point` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` int DEFAULT NULL,
  `warranty_id` int DEFAULT NULL,
  `description` CHAR(250) DEFAULT NULL,
  `type` ENUM('first', 'additional') NOT NULL DEFAULT 'first',
  `expired_at` datetime DEFAULT NULL,
  `value` DECIMAL(21,2) NOT NULL DEFAULT 0,
  `used` DECIMAL(21,2) NOT NULL DEFAULT 0,
  `balance` DECIMAL(21,2) NOT NULL DEFAULT 0,
  `status` ENUM('waiting','approved','rejected') NULL DEFAULT 'waiting',
  `created_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `member_point_idx` (`member_id`, `warranty_id`)
);

CREATE TABLE `member_point_adjustment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` int DEFAULT NULL,
  `warranty_id` int DEFAULT NULL,
  `point_id` int NULL,
  `trx_date` datetime DEFAULT NULL,
  `description` CHAR(250) DEFAULT NULL,
  `ref_number` CHAR(50) DEFAULT NULL,
  `type` ENUM('in', 'out') NOT NULL DEFAULT 'in',
  `value` DECIMAL(21,2) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `member_point_adjustment_idx` (`member_id`, `warranty_id`, `point_id`, `type`, `ref_number`)
);