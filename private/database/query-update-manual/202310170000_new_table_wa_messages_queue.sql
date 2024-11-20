CREATE TABLE wa_messages_queue (
	id INT NOT NULL AUTO_INCREMENT,
	message_id INT NOT NULL,
	status ENUM('waiting', 'onprogress', 'completed', 'canceled') NOT NULL DEFAULT 'waiting',
	created_at DATETIME NULL DEFAULT NOW(),
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;

CREATE TABLE wa_messages (
	id INT NOT NULL AUTO_INCREMENT,
	content VARCHAR(1000),
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;

CREATE TABLE wa_messages_recipients (
	id INT NOT NULL AUTO_INCREMENT,
	message_id INT NOT NULL,
	name VARCHAR(50),
	number VARCHAR(20),
	status ENUM('waiting', 'sent', 'cancel') NOT NULL DEFAULT 'waiting',
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;


CREATE TABLE wa_session (
	id INT NOT NULL AUTO_INCREMENT,
	client_id varchar(50) DEFAULT NULL,
	qr_code VARCHAR(300),
	status ENUM('waiting', 'used') NOT NULL DEFAULT 'waiting',
	PRIMARY KEY (`id`),
	UNIQUE KEY `wa_session_unique_client_id` (`client_id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;