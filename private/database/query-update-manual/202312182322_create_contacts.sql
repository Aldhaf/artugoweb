CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `code` varchar(60) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `contacts_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE wa_messages_queue MODIFY COLUMN status enum('draft', 'waiting','onprogress','completed','canceled','uncompleted') NOT NULL DEFAULT 'waiting';