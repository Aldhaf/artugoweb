ALTER TABLE reg_service DROP COLUMN odoo_so_id;
ALTER TABLE reg_service DROP COLUMN odoo_so_number;

CREATE TABLE `reg_service_so` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `service_id` INT NOT NULL,
  `so_id` INT NOT NULL,
  `so_number` VARCHAR(30) NOT NULL,
  `so_date` DATE NOT NULL,
  `deduct_point` DECIMAL(21,2) NOT NULL DEFAULT 0,
  `so_items` LONGTEXT NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `reg_service_so_service_id_idx` (`service_id`),
  INDEX `reg_service_so_so_id_idx` (`so_id`)
);

ALTER TABLE ms_members ADD COLUMN total_point DECIMAL(21,2) NOT NULL DEFAULT 0;
ALTER TABLE ms_members ADD COLUMN used_point DECIMAL(21,2) NOT NULL DEFAULT 0;
ALTER TABLE ms_members ADD COLUMN balance_point DECIMAL(21,2) NOT NULL DEFAULT 0;

ALTER TABLE reg_warranty DROP COLUMN total_point;
ALTER TABLE reg_warranty DROP COLUMN used_point;
ALTER TABLE reg_warranty DROP COLUMN balance_point;