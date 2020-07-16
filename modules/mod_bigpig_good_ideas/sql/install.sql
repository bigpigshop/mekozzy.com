CREATE TABLE IF NOT EXISTS `#__mekozzy_bigpig_good_ideas` (
	`good_ideas_id` int(11) NOT NULL AUTO_INCREMENT,
	`link` int(11) NOT NULL,
	`author` varchar(256) default null,
	`description` varchar(256) default null,
	`create_time` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `modification_time` DATETIME ON UPDATE CURRENT_TIMESTAMP
	`rate` int(11) default null,
	`comments` varchar(256) default null,
	PRIMARY KEY (`good_ideas_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


INSERT IGNORE INTO `#__mekozzy_bigpig_good_ideas` (`good_ideas_id`, `link`, `author`,`description`,`create_time`,`modification_time`,`rate`,`comments`)
VALUES
	(NULL ,'mekozzy/images/noimages.png', 'mekozzy','description',Now(),Now(),4,''),
	(NULL ,'mekozzy/images/noimages.png', 'mekozzy','description',Now(),Now(),5,''),
	(NULL ,'mekozzy/images/noimages.png', 'mekozzy','description',Now(),Now(),4,'')
