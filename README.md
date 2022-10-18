


#

```
sudo apt-get install python3-dev python3-pip nginx php-fpm golang default-jdk wget maven nginx curl unzip xvfb libxi6 libgconf-2-4 python2.7 php-cli unzip screen mc mariadb-server php-zip php-mbstring php-xml php-dev git php-mysql php-pdo php-curl php-bcmath php-dom php-ctype chromium-chromedriver chromium-browser nmap

sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
sudo php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php composer-setup.php
sudo php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer




sudo composer install


cd scanner
wget https://github.com/OWASP/Amass/releases/download/v3.20.0/amass_linux_arm64.zip
sudo rm -f amass_linux_arm64.zip

git clone https://github.com/tomnomnom/assetfinder
cd assetfinder
GOOS=linux GOARCH=arm64 go build
mv assetfinder ../
cd ../
rm -rf assetfinder/

wget https://github.com/projectdiscovery/subfinder/releases/download/v2.5.4/subfinder_2.5.4_linux_arm64.zip
sudo unzip subfinder_2.5.4_linux_arm64.zip
sudo rm -f subfinder_2.5.4_linux_arm64.zip

sudo go install -v github.com/projectdiscovery/httpx/cmd/httpx@latest
sudo go install -v github.com/projectdiscovery/nuclei/v2/cmd/nuclei@latest
sudo go install -v github.com/projectdiscovery/dnsx/cmd/dnsx@latest
sudo go install github.com/lc/gau/v2/cmd/gau@latest

cd screenshot
mvn package


git clone https://github.com/maurosoria/dirsearch.git --depth 1
pip3 install -r requirements.txt
chmod 777 -R dirsearch
sudo  chmod 777 -R storage/


sudo mysql -e "UPDATE mysql.user SET Password = PASSWORD('12345') WHERE User = 'root'"
sudo mysql -e "DELETE FROM mysql.user WHERE User='';"
sudo mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');"
sudo mysql -e "DROP DATABASE IF EXISTS test;"
sudo mysql -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
sudo mysql -e "create database scanner";
sudo mysql -e "CREATE USER 'scanner'@'localhost' IDENTIFIED BY 'scanner'";
sudo mysql -e "GRANT ALL PRIVILEGES ON scanner . * TO 'scanner'@'localhost'";
sudo mysql -e "FLUSH PRIVILEGES";


php artisan migrate


sudo chmod 777 *.log

php artisan add:user admin admin





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