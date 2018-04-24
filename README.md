## Setup
1. Make sure under root
	```
	$ sudo su
	```
2. Update Ubuntu
	```
	$ apt-get update
	```
3. Install mininet
	```
	$ apt-get install mininet
	```
4. Install ryu controller
	```
	$ apt-get install python-pip
	$ pip install ryu
	```
5. Download SDNColdBrew repository
	```
	$ cd /home/ubuntu/
	$ git clone https://github.com/dektiram/SDNColdBrew.git
	$ chown -R www-data.www-data SDNColdBrew/site/
	```
6. Download sflow-RT
	```
	$ wget https://inmon.com/products/sFlow-RT/sflow-rt.tar.gz
	$ tar -xvzf sflow-rt.tar.gz
	```
7. Install apache, PHP and MySQL
	```
	$ apt-get install openssl apache2 libapache2-mod-php php php-mbstring php-mcrypt php-xml php-mysql mysql-server
	$ mysql_secure_installation
	```
8. Create and import database
	```
	$ mysql -u root -p
		mysql> create database sdn_coldbrew;
		mysql> CREATE USER 'sdncoldbrew'@'localhost' IDENTIFIED BY 'sdncoldbrew';
		mysql> GRANT ALL PRIVILEGES ON sdn_coldbrew.* TO 'sdncoldbrew'@'localhost' WITH GRANT OPTION;
		mysql> quit
		
	$ mysql -u root -p sdn_coldbrew < SDNColdBrew/database/sdn_coldbrew.sql
	```
9. Configuring apache
	```
	$ vi /etc/apache2/sites-available/sdncoldbrew.conf
		Alias /sdncoldbrew /home/ubuntu/SDNColdBrew/site
		<Directory /home/ubuntu/SDNColdBrew/site>
				Options Indexes FollowSymLinks
				AllowOverride All
				DirectoryIndex index.php
				Require all granted
				Order allow,deny
				Allow from all
		</Directory>
		
	$ cd /etc/apache2/sites-enabled/
	$ ln -s ../sites-available/default-ssl.conf default-ssl.conf
	$ ln -s ../sites-available/sdncoldbrew.conf sdncoldbrew.conf
	$ cd /etc/apache2/mods-enabled/
	$ ln -s ../mods-available/ssl.load ssl.load
	$ ln -s ../mods-available/rewrite.load rewrite.load
	$ systemctl restart apache2
	```
10. Make shell capture file directory
	```
	$ cd /home/ubuntu/
	$ mkdir shell-capture
	```
11. Running SDNColdBrew internal script in screen
	```
	$ screen
	$ cd /home/ubuntu/SDNColdBrew/internal-service/
	$ python sdn-coldbrew.py
	CTRL+A+D
	```
12. Change SDNColdBrew setting
13. Installation video
## Demo