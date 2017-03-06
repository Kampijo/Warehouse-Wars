create table appuser (
	username varchar(255) primary key,
	password varchar(255),
	email varchar(255));

create table scores (
	username varchar(255),
	score INTEGER,
	PRIMARY KEY(username,score),
	FOREIGN KEY(username) REFERENCES appuser(username));
