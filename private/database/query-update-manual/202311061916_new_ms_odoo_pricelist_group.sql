CREATE TABLE `ms_odoo_pricelist_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL,
  `odoo_pricelist_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ms_odoo_pricelist_group_unique_odoo_pricelist_id` (`odoo_pricelist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE ms_technician ADD odoo_user_id INT(11) DEFAULT NULL;
ALTER TABLE reg_service ADD (odoo_so_id INT(11) DEFAULT NULL, odoo_so_number VARCHAR(30) DEFAULT NULL );

ALTER TABLE wa_messages ADD description varchar(50) NOT NULL;
ALTER TABLE wa_messages CHANGE description description varchar(50) NOT NULL AFTER id;