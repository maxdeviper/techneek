CREATE TABLE IF NOT EXISTS `quick_access`(
	`phone_no` INT(13) NOT NULL PRIMARY KEY,
	`access_code` VARCHAR(4) NOT NULL,
	`time_generated` TIMESTAMP NOT NULL,
	`accessed` ENUM('true','false') DEFAULT 'false'
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `user`(
	`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`dob` DATETIME NOT NULL,
	`sex` ENUM('male','female'),
	`phone_no` INT(13) NOT NULL,
	`location` TEXT,
	`profile_pix` TEXT,
	`time_joined` TIMESTAMP,
	FOREIGN KEY (`phone_no`) REFERENCES quick_access(`phone_no`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `likes`(
	`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`what` INT(11) NOT NULL,
	`by_who` INT(11) NOT NULL,
	`time` TIMESTAMP,
	`type` VARCHAR(50)
);
CREATE TABLE IF NOT EXISTS `messages`(
	`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`sender_id` INT(11) NOT NULL,
	`reciever_id` INT(11),
	`message` TEXT,
	`time_sent` TIMESTAMP,
	`time_recieved` TIMESTAMP,
	FOREIGN KEY (`sender_id`,`reciever_id`) REFERENCES user(`id`)
);
CREATE TABLE IF NOT EXISTS `favorites`(
	`inviter_id` INT(11) NOT NULL ,
	`invitee_id`INT(11) NOT NULL,
	`time_of_invite` TIMESTAMP ,
	`time_of_acceptance` TIMESTAMP,
	`blocked` ENUM('false','true') DEFAULT 'false',	
	FOREIGN KEY (`inviter_id`,`invitee_id`) REFERENCES user(`id`)
);

CREATE TABLE IF NOT EXISTS `videos`(
	`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`owner_id` INT(11) NOT NULL,
	`tags` TEXT ,
	`time_posted` TIMESTAMP ,
	`time_modified` TIMESTAMP ,
	FOREIGN KEY (`owner_id`) REFERENCES user(`id`)
)

CREATE TABLE IF NOT EXISTS `how_about`(
	`id`INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	`owner_id` INT(11) NOT NULL.
	`time_posted `TIMESTAMP ,
	`time_edited` TIMESTAMP ,
	FOREIGN KEY (`owner_id`) REFERENCES user(`id`)
)
