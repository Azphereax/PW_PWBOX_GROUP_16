CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(256) NOT NULL,
  `birthdate` datetime NOT NULL,
  `description` varchar(4096) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `folders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `Folders` varchar(100) NOT NULL,
  `owner` varchar(20) NOT NULL,
  `user_share` varchar(100) NOT NULL,
  `role`  varchar (10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

