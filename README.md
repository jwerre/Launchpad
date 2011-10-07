# [Launchpad - A content management system](http://www.supersquared.com)

## To install:
1. Copy files to your server
2. Login to MySQL and create a new database 
	`$ mysql -u root -p`
	`$ CREATE DATABASE [myNewDatabase]`
	`$ USE [myNewDatabase]`
3. Import the sql located in your document root into your database
	`$ SOURCE /my/web/root/launchpad.sql`
4. Enter your database, username and password in the launchpad configuration file.
	`$ vim /my/web/root/config.php`
	`define('DB_SERVER', 'localhost');`
	`define('DB_NAME', 'my_database');`
	`define('DB_USER', 'my_username');`
	`define('DB_PASSWORD', 'my_password');` 
5. Login to the admin pages at www.mydomain.com/admin. The default username and password is 'super'. (Once you log create a new superuser account and delete the default superuser)

## System Requirements
- Unix based operating system. (You can try it in a Windows environment but it hasn't been tested)
- [MySQL 5](http://dev.mysql.com/downloads/mysql)
- [PHP 5.3](http://us2.php.net/downloads.php#v5)
- [PHP Data Objects (PDO)](http://php.net/manual/en/book.pdo.php)
- [GD Image](http://us2.php.net/manual/en/book.image.php)
