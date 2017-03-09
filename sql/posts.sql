-- usage: msyql -u [username] -p [dbname] < posts.sql

drop table if exists created_posts;
drop table if exists followed_posts;
drop table if exists posts;
drop table if exists users;

-- copy the users ddl from tablecreation so we can run one script
CREATE TABLE users (
	user_id		INT(11) NOT NULL AUTO_INCREMENT, 	
	user_name 	VARCHAR(25) NOT NULL,				
	first_name 	VARCHAR(50) NOT NULL,				
	last_name 	VARCHAR(50) NOT NULL,				
	user_pass 	VARCHAR(25) NOT NULL,				
	user_email	VARCHAR(50) NOT NULL,				
	user_zip 	INT(5) NOT NULL,				
	user_level 	INT(1) DEFAULT 1, 					
	create_dt 	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	UNIQUE INDEX user_name_unique (user_name),
	PRIMARY KEY (user_id)
)	ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Posts
CREATE TABLE posts (
	post_id		INT(11) NOT NULL AUTO_INCREMENT, 	
	post_date 	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	content 	TEXT NOT NULL,
	user_id 	int not null,
	post_img 	varchar(255),
	PRIMARY KEY (post_id),
	foreign key (user_id) references users(user_id)
) 	ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Followed posts
CREATE TABLE followed_posts (
	user_id INT(11) NOT NULL,
	post_id INT(11) NOT NULL,
	PRIMARY KEY (user_id, post_id),
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	FOREIGN KEY (post_id) REFERENCES posts(post_id)	
) 	ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Created posts
-- I don't think we need this table, userid should be attribute of posts
-- a user can have many posts, but a post can have only one 'creator'
CREATE TABLE created_posts (
	user_id INT(11) NOT NULL,
	post_id INT(11) NOT NULL,
	PRIMARY KEY (user_id, post_id),
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	FOREIGN KEY (post_id) REFERENCES posts(post_id)	
) 	ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- could be a useful view for the landing page
create view all_posts
as
select u.user_id, p.post_id, p.post_date, p.content, p.post_img, f.post_id followed, u.user_zip
from users u
left join posts p on p.user_id = u.user_id
left join followed_posts f on u.user_id = f.user_id;

-- i think there may be an issue with using an int for zip code. this one is for boston
insert into users (user_name, first_name, last_name, user_pass, user_email, user_zip)
values ('test1', 'test', 'one', 'password', 'test1@test.com', 01841);

insert into users (user_name, first_name, last_name, user_pass, user_email, user_zip)
values ('test2', 'test', 'two', 'password', 'test2@test.com', 90210);

insert into users (user_name, first_name, last_name, user_pass, user_email, user_zip)
values ('test3', 'test', 'three', 'password', 'test3@test.com', 55105);

insert into posts (content, user_id, post_img)
values ('my first post', (select user_id from users where user_name = 'test1'), 'post1_img.jpg');

insert into posts (content, user_id)
values ('my second post has more content than my first', (select user_id from users where user_name = 'test1'));

insert into posts (content, user_id)
values ('this is a post from the second user', (select user_id from users where user_name = 'test2'));

insert into followed_posts (user_id, post_id)
values ((select user_id from users where user_name = 'test1'),
	(select post_id from posts where content = 'this is a post from the second user'));

insert into followed_posts (user_id, post_id)
values ((select user_id from users where user_name = 'test2'),
	(select post_id from posts where content = 'my first post'));

