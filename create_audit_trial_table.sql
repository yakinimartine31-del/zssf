CREATE TABLE IF NOT EXISTS `audit_trial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity` varchar(200) DEFAULT NULL,
  `items` text,
  `module` varchar(200) DEFAULT NULL,
  `action` varchar(200) DEFAULT NULL,
  `old` varchar(200) NOT NULL,
  `new` varchar(200) NOT NULL,
  `maker` varchar(200) DEFAULT NULL,
  `maker_time` varchar(200) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
