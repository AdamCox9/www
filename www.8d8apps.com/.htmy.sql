CREATE TABLE `mp3s` (
  `id` int(6) AUTO_INCREMENT PRIMARY KEY,
  `filename` varchar(100) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '0',
  `author` varchar(200) NOT NULL DEFAULT '0',
  `website` varchar(200) NOT NULL DEFAULT '0',
  `email` varchar(200) NOT NULL DEFAULT '0',
  `verified` int(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `devices` (
  `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
  `key` VARCHAR(36) UNIQUE,
  `time` int(11) default NULL
) ENGINE=InnoDB;

CREATE TABLE `generatedmp3s` (
  `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
  `filename` VARCHAR(100) UNIQUE,
  `deviceid` INTEGER NOT NULL,
  `ip` VARCHAR(16) NOT NULL,
  `name` VARCHAR(36),
  `time` int(11) default NULL,
  FOREIGN KEY (deviceid) REFERENCES devices(id) ON DELETE CASCADE
) ENGINE=InnoDB;

