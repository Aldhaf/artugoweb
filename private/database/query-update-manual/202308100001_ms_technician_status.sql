ALTER TABLE ms_service_center ADD sc_code VARCHAR(50) NULL;

CREATE TABLE ms_technician_type (
	id INT NOT NULL AUTO_INCREMENT,
	code VARCHAR(20) NOT NULL,
	description VARCHAR(100) NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;

INSERT INTO ms_technician_type (code,description) VALUES ('ASC-TF','Teknisi ASC Teknisi Frelance');


ALTER TABLE ms_technician ADD technician_type_id INT NULL;
ALTER TABLE ms_technician CHANGE technician_type_id technician_type_id INT NULL AFTER technician_id;
