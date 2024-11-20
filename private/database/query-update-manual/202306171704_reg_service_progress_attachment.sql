CREATE TABLE reg_service_progress_attachment (
	id INT NOT NULL AUTO_INCREMENT,
	progress_id INT NOT NULL,
	path_file VARCHAR(250) NULL,
	file_ext CHAR(10) NULL,
    description VARCHAR(250) NULL,
	created_at DATETIME NULL DEFAULT NOW(),
	created_by INT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;