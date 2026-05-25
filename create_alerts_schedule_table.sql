CREATE TABLE IF NOT EXISTS `alerts_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `message_type` varchar(255) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `msg_category_id` varchar(200) DEFAULT NULL,
  `schedule_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
