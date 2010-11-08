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

create table if not exists groups (
	id integer primary key auto_increment,
	groupname varchar(64) not null unique,
	description varchar(1024),
	creation_time datetime,
	last_modification_time datetime,
	creator integer not null,
	foreign key (creator) references users (id)
);

create table if not exists group_members (
	uid integer,
	gid integer,
	primary key (uid, gid),
	foreign key (uid) references users (id),
	foreign key (gid) references groups (id)
);
