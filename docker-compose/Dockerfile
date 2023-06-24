FROM golang:1.20.5-alpine AS build-go
RUN apk add build-base
RUN apk --no-cache add git
# RUN go install -v github.com/OWASP/Amass/v3/...@master
RUN go install -v github.com/owasp-amass/amass/v3/...@master
RUN go install -v github.com/projectdiscovery/subfinder/v2/cmd/subfinder@latest
RUN go install -v github.com/projectdiscovery/httpx/cmd/httpx@latest
RUN go install -v github.com/projectdiscovery/nuclei/v2/cmd/nuclei@latest
RUN go install -v github.com/projectdiscovery/dnsx/cmd/dnsx@latest
RUN go install github.com/lc/gau/v2/cmd/gau@latest
RUN go install github.com/tomnomnom/assetfinder@latest



FROM php:8.1.12-fpm-alpine3.16


#packages installing
RUN apk -U upgrade --no-cache \
    && apk add --no-cache autoconf bind-tools ca-certificates chromium chromium-chromedriver composer curl g++ gcc git imagemagick libffi-dev libgcc libpcap libpcap-dev libssl3 libstdc++ libtool linux-headers lua-dev make maven musl-dev mysql-client nginx nmap nmap-scripts openjdk17 openssl-dev php-bcmath php-calendar php-cli php-ctype php-curl php-dom php-exif php-ffi php-fileinfo php-fpm php-ftp php-gettext php-iconv php-mbstring php-mysqli php-opcache php-pdo php-phar php-posix php-shmop php-simplexml php-sockets php-sysvmsg php-sysvsem php-sysvshm php-tokenizer php-xml php-xmlreader php-xmlwriter php-xsl php-zip py3-pip python3-dev
RUN docker-php-ext-install mysqli pdo_mysql exif pcntl bcmath 


COPY . /var/www/html/
RUN mkdir /var/www/html/scanners/amass_linux
COPY --from=build-go /go/bin/amass /var/www/html/scanners/amass_linux/amass
COPY --from=build-go /go/bin/subfinder /var/www/html/scanners/subfinder
COPY --from=build-go /go/bin/httpx /var/www/html/scanners/httpx
COPY --from=build-go /go/bin/nuclei /var/www/html/scanners/nuclei
COPY --from=build-go /go/bin/dnsx /var/www/html/scanners/dnsx
COPY --from=build-go /go/bin/gau /var/www/html/scanners/gau
COPY --from=build-go /go/bin/assetfinder /var/www/html/scanners/assetfinder
RUN git clone https://github.com/maurosoria/dirsearch /var/www/html/scanners/dirsearch
RUN cd /var/www/html/scanners/dirsearch && pip3 install -r requirements.txt
RUN cd /var/www/html/scanners/screenshot && mvn package
RUN cd /var/www/html/ && composer install
RUN cd /var/www/html/ && php artisan storage:link
RUN cd /var/www/html/ && chmod 777 -R storage/
RUN cd /var/www/html/ && chmod 777 *.log
RUN cp /var/www/html/.env.docker /var/www/html/.env
RUN mv /var/www/html/docker-compose/nginx/sheye.conf /etc/nginx/http.d/default.conf

EXPOSE 80 443
RUN mv /var/www/html/init.sh /init.sh
ENTRYPOINT ["sh", "/init.sh"]

