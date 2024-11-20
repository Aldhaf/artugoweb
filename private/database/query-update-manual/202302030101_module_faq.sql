CREATE TABLE ms_answer (
	id INT NOT NULL AUTO_INCREMENT,
	description VARCHAR(300),
	created_at DATETIME NULL DEFAULT NOW(),
	PRIMARY KEY (`id`),
	INDEX `ms_answer_idx` (`description`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;

CREATE TABLE ms_faq (
	id INT NOT NULL AUTO_INCREMENT,
	category_id INT NOT NULL,
	subcategory_id INT NOT NULL,
	question VARCHAR(300),
	keywords VARCHAR(300),
	created_at DATETIME NULL DEFAULT NOW(),
	PRIMARY KEY (`id`),
	INDEX `ms_faq_idx` (`category_id`, `subcategory_id`, `question`, `keywords`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;

CREATE TABLE ms_faq_answer (
	id INT NOT NULL AUTO_INCREMENT,
	faq_id INT NOT NULL,
	answer_id INT NOT NULL,
	created_at DATETIME NULL DEFAULT NOW(),
	sequence INT NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	INDEX `ms_faq_answer_idx` (`faq_id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;