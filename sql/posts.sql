--Posts
CREATE TABLE posts (
	post_id		INT(11) NOT NULL AUTO_INCREMENT, 	
	post_date 	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	content 	TEXT NOT NULL,
	PRIMARY KEY (post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--Followed posts
CREATE TABLE followed_posts (
	user_id INT(11) NOT NULL,
	post_id INT(11) NOT NULL,
	PRIMARY KEY (user_id, post_id),
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	FOREIGN KEY (post_id) REFERENCES posts(post_id),	
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--Created posts
CREATE TABLE created_posts (
	user_id INT(11) NOT NULL,
	post_id INT(11) NOT NULL,
	PRIMARY KEY (user_id, post_id),
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	FOREIGN KEY (post_id) REFERENCES posts(post_id),	
) ENGINE=InnoDB DEFAULT CHARSET=utf8;