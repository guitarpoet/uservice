create table if not exists users (
	id integer primary key auto_increment,
	email varchar(64) not null unique,
	username varchar(64) not null unique,
	password varchar(16) not null,
	register_time datetime,
	register_ip varchar(16),
	last_login_ip varchar(16),
	last_login_time datetime,
	login_count integer default 0,
	salt char(6),
	status tinyint(1)
);
