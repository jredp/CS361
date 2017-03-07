----------------
--Entity Table
----------------
CREATE TABLE users (
	user_id		INT(11) NOT NULL AUTO_INCREMENT, 	
	user_name 	VARCHAR(25) NOT NULL,				
	first_name 	VARCHAR(50) NOT NULL,				
	last_name 	VARCHAR(50) NOT NULL,				
	user_pass 	VARCHAR(25) NOT NULL,				
	user_email	VARCHAR(50) NOT NULL,				
	user_zip 	VARCHAR(10) NOT NULL,				
	user_level 	INT(1) DEFAULT 1, 					
	create_dt 	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	UNIQUE INDEX user_name_unique (user_name),
	PRIMARY KEY (user_id)
)	ENGINE=InnoDB DEFAULT CHARSET=utf8;

----------------
--Insert Example
----------------
INSERT INTO users 
(user_name, first_name, last_name, user_pass, user_email, user_zip)
VALUES ([uname], [fname], [lname], [pass], [email], [zip])
