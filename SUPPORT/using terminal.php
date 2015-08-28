mysql> USE photo_gallery;
Database changed
mysql> CREATE TABLE users(
    -> id int(11) NOT NULL auto_increment,
    -> username varchar(50) NOT NULL,
    -> password varchar(40) NOT NULL,
    -> first_name varchar(30) NOT NULL,
    -> last_name varchar(30) NOT NULL,
    -> PRIMARY KEY (id)
    -> );
Query OK, 0 rows affected (0.35 sec)

mysql> GRANT ALL PRIVILEGES ON photo_gallery.*
    -> TO 'akiryk'@'localhost'
    -> IDENTIFIED BY '1forget';
Query OK, 0 rows affected (0.32 sec)

mysql> 
