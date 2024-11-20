CREATE TABLE ms_products_files (
	id INT NOT NULL AUTO_INCREMENT,
	product_id INT NOT NULL,
	mime_type VARCHAR(20) NULL,
	description VARCHAR(250) NULL,
	path_file VARCHAR(250) NULL,
	file_url VARCHAR(200) NULL,
	path_file_thumbnail VARCHAR(250) NULL,
	file_url_thumbnail VARCHAR(200) NULL,
	created_at DATETIME NULL DEFAULT NOW(),
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;