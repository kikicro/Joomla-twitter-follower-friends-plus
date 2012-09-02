CREATE TABLE IF NOT EXISTS #__twitter_friends_followers (
						`datetime` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
						`screen_name` VARCHAR(250) NULL DEFAULT NULL,
						`name` VARCHAR(250) NULL DEFAULT NULL,
						`profile_image_url` VARCHAR(250) NULL DEFAULT NULL,
						`description` VARCHAR(250) NULL DEFAULT NULL,
						`ff` INT(1) DEFAULT 0
					)
					COLLATE='utf8_general_ci';