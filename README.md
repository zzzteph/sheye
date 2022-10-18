
## Reveal the hidden
**ShrewdEyeScanner** (sheye) is a set of utilities bundled into a single automated workflow to improve, simplify, and speed up resource discovery and vulnerabilities finding.


## Setup

*TODO - Dockerize app to simplify installation.*


Install requirement dependencies

```
sudo apt-get install python3-dev python3-pip nginx php-fpm golang default-jdk wget maven nginx curl unzip xvfb libxi6 libgconf-2-4 python2.7 php-cli unzip screen mc mariadb-server php-zip php-mbstring php-xml php-dev git php-mysql php-pdo php-curl php-bcmath php-dom php-ctype chromium-chromedriver chromium-browser nmap
```

Install **composer**


```
sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
sudo php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php composer-setup.php
sudo php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
```



Extract sources to folder, F.E **/var/www/html**

Navigate **/var/www/html**

Make some folders and files writable
```
chmod 777 -R storage/
chmod 777 *.log
```

### Prepare database

```
mysql -e "UPDATE mysql.user SET Password = PASSWORD('12345') WHERE User = 'root'"
mysql -e "DELETE FROM mysql.user WHERE User='';"
mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');"
mysql -e "DROP DATABASE IF EXISTS test;"
mysql -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
mysql -e "create database scanner";
mysql -e "CREATE USER 'scanner'@'localhost' IDENTIFIED BY 'scanner'";
mysql -e "GRANT ALL PRIVILEGES ON scanner . * TO 'scanner'@'localhost'";
mysql -e "FLUSH PRIVILEGES";
```

Run composer and migrations from **/var/www/html**

```
composer install
php artisan migrate
```

### Scanners setup


All scanners should be located at **scanners** folder (**/var/www/html/scanners**)

1. **amass**
```
wget https://github.com/OWASP/Amass/releases/download/v3.20.0/amass_linux_arm64.zip
unzip amass_linux_arm64.zip
rm -f amass_linux_arm64.zip
```


2. **assetfinder**

Below you can find how to compile assetfinder for RaspberiPI (arm64)

```
git clone https://github.com/tomnomnom/assetfinder
cd assetfinder
GOOS=linux GOARCH=arm64 go build
mv assetfinder ../
cd ../
rm -rf assetfinder/
```

3. **subfinder**

```
wget https://github.com/projectdiscovery/subfinder/releases/download/v2.5.4/subfinder_2.5.4_linux_arm64.zip
unzip subfinder_2.5.4_linux_arm64.zip
rm -f subfinder_2.5.4_linux_arm64.zip
```

4. **httpx**

```
sudo go install -v github.com/projectdiscovery/httpx/cmd/httpx@latest
```

5. **nuclei**
```
sudo go install -v github.com/projectdiscovery/nuclei/v2/cmd/nuclei@latest
```

6. **dnsx**
```
sudo go install -v github.com/projectdiscovery/dnsx/cmd/dnsx@latest
```

7. **gau**
```
sudo go install github.com/lc/gau/v2/cmd/gau@latest
```

8. **screenshoter**

```
cd screenshot
mvn package
```

9. **dirsearch**

```
git clone https://github.com/maurosoria/dirsearch.git --depth 1
cd dirsearch
pip3 install -r requirements.txt
cd ../
chmod 777 -R dirsearch
```


### Add user

Add **admin** users with **admin** password

```
php artisan add:user admin admin
```


### Nginx configuration

Configure nginx to serve the app

```
server {
listen 80;
    server_name _;
    root /var/www/html/public;
    large_client_header_buffers 4 16k;
    index index.php;

    client_max_body_size 100m;
    charset utf-8;

  location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
