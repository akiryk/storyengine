USER TABLE: 5 columns

CREATE TABLE users (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
admin BOOLEAN NOT NULL, 
firstname VARCHAR(16), 
lastname VARCHAR(16), 
username VARCHAR(16), 
hashed_password VARCHAR(40)
)

____________________________________
STORIES
CREATE TABLE stories (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
title VARCHAR(255), 
)


____________________________________
STORIES_USERS JOIN TABLE

CREATE TABLE stories_users (
story_id INT NOT NULL, 
user_id INT NOT NULL, 
PRIMARY KEY (story_id, user_id)
)

STORY BLOCKS (CHAPTERS)
CREATE TABLE chapters (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
content TEXT,
story_id INT NOT NULL,
beginning BOOLEAN NOT NULL DEFAULT '0',
endpoint BOOLEAN NOT NULL DEFAULT '0')


CREATE OPTIONS TABLE
CREATE TABLE options (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
content VARCHAR(255),
child_id INT)

CHAPTER_OPTION
CREATE TABLE chapter_option (
chapter_id INT NOT NULL, 
option_id INT NOT NULL, 
PRIMARY KEY (chapter_id, option_id)
)
