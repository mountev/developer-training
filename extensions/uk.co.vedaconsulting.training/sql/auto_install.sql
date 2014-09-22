DROP TABLE IF EXISTS `civicrm_training_settings`;

CREATE TABLE `civicrm_training_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique mailchimp sync id',
  `name`  varchar(255)   NOT NULL COMMENT 'unique name for setting',
  `value` varchar(255)   NOT NULL COMMENT 'data associated with setting', 
  PRIMARY KEY ( `id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
