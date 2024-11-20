CREATE TABLE ms_service_center_users (
	id INT NOT NULL AUTO_INCREMENT,
	users_id INT NOT NULL,
    sc_id INT NOT NULL,
	status INT NOT NULL DEFAULT 1,
	deleted_by INT NULL,
	created_at DATETIME NULL,
	created_by INT NULL,
	updated_at DATETIME NULL,
	updated_by INT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;