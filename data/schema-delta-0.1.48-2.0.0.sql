create table lmqueue (
	queueId int(11) NOT NULL AUTO_INCREMENT,
	typeId int(11) NOT NULL,
	activityId int(11) NOT NULL,
	runs int(11) NOT NULL,
	queueCreateTimestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	singleton tinyint(3) NOT NULL,
	stuctureId bigint(11) DEFAULT NULL,
	PRIMARY KEY ( queueId )
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=utf8
