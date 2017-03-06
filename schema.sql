create table user (
	username varchar(255) primary key,
	password varchar(255),
	fname varchar(127),
	lname varchar(127),
	email varchar(255));

create table hiscores (
	name varchar(127),
	hiscore INTEGER,
	PRIMARY KEY(name,hiscore),
	FOREIGN KEY(name) REFERENCES user(username));
