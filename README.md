## Setup
#### 1. This setup tutorial is tested on Ubuntu 16.04 LTS
#### 2. Make sure under root
```
# sudo su
```
#### 3. Update Ubuntu
```
# apt-get update
```
#### 4. Install mininet
```
# apt-get install mininet
```
#### 5. Download mininet
Because with step number 4 does not include `mininet/utils/m`, you need to download it manually. It use to access pingall from web UI.
```
# cd /home/ubuntu/
# git clone git://github.com/mininet/mininet
```
#### 6. Install ryu controller
```
# apt-get install python-pip
# pip install ryu
```
#### 7. Install python module
```
# pip install WebOb
# pip install paste
# pip install psutil
```
#### 8. Download SDNColdBrew repository
```
# cd /home/ubuntu/
# git clone https://github.com/dektiram/SDNColdBrew.git
# chown -R www-data.www-data SDNColdBrew/site/
```
#### 9. Download sflow-RT
```
# wget https://inmon.com/products/sFlow-RT/sflow-rt.tar.gz
# tar -xvzf sflow-rt.tar.gz
```
#### 10. Install java
Running sflow-RT need java.
```
# apt-get install default-jre
```
#### 11. Install apache, PHP and MySQL
```
# apt-get install openssl apache2 libapache2-mod-php php php-mbstring php-mcrypt php-xml php-mysql php-curl mysql-server
# mysql_secure_installation
```
#### 12. Create and import database
```
# mysql -u root -p
	mysql> create database sdn_coldbrew;
	mysql> CREATE USER 'sdncoldbrew'@'localhost' IDENTIFIED BY 'sdncoldbrew';
	mysql> GRANT ALL PRIVILEGES ON sdn_coldbrew.* TO 'sdncoldbrew'@'localhost' WITH GRANT OPTION;
	mysql> quit
	
# mysql -u root -p sdn_coldbrew < SDNColdBrew/database/sdn_coldbrew.sql
```
#### 13. Configuring apache
```
# vi /etc/apache2/sites-available/sdncoldbrew.conf
	Alias /sdncoldbrew /home/ubuntu/SDNColdBrew/site
	<Directory /home/ubuntu/SDNColdBrew/site>
		Options Indexes FollowSymLinks
			AllowOverride All
			DirectoryIndex index.php
			Require all granted
			Order allow,deny
			Allow from all
	</Directory>
	
# cd /etc/apache2/sites-enabled/
# ln -s ../sites-available/default-ssl.conf default-ssl.conf
# ln -s ../sites-available/sdncoldbrew.conf sdncoldbrew.conf
# cd /etc/apache2/mods-enabled/
# ln -s ../mods-available/ssl.load ssl.load
# ln -s ../mods-available/rewrite.load rewrite.load
# systemctl restart apache2
```
#### 14. Make shell capture file directory
```
# cd /home/ubuntu/
# mkdir shell-capture
```
#### 15. Running SDNColdBrew internal script in screen
```
# screen
# cd /home/ubuntu/SDNColdBrew/internal-service/
# python sdn-coldbrew.py
CTRL+A+D
```
#### 16. Access dashboard
Go to URL https://[YourIP]/sdncoldbrew/

Username : superadmin

Password : superadmin
#### 17. Change SDNColdBrew setting
Change setting with your environment or if you follow this setup step setting like image below :
![alt text](https://github.com/dektiram/SDNColdBrew/raw/tarom/images/default_settings.png)
#### 18. Installation video
This video link is guide to do installation step. Visit [Installation video](https://www.youtube.com/watch?v=lbyivwjvc3I)
## Screenshot
![alt text](https://github.com/dektiram/SDNColdBrew/raw/tarom/images/controller.png)
![alt text](https://github.com/dektiram/SDNColdBrew/raw/tarom/images/topology.png)
![alt text](https://github.com/dektiram/SDNColdBrew/raw/tarom/images/ofctl_rest_api_1.png)
![alt text](https://github.com/dektiram/SDNColdBrew/raw/tarom/images/ofctl_rest_api_2.png)
![alt text](https://github.com/dektiram/SDNColdBrew/raw/tarom/images/topo_rest_api.png)
## Demo
