FROM golang:1.19.2-alpine AS build-go
RUN apk add build-base
RUN apk --no-cache add git
#RUN go install -v github.com/OWASP/Amass/v3/...@master
#RUN go install -v github.com/projectdiscovery/subfinder/v2/cmd/subfinder@latest
#RUN go install -v github.com/projectdiscovery/httpx/cmd/httpx@latest
#RUN go install -v github.com/projectdiscovery/nuclei/v2/cmd/nuclei@latest
#RUN go install -v github.com/projectdiscovery/dnsx/cmd/dnsx@latest
#RUN go install github.com/lc/gau/v2/cmd/gau@latest
#RUN go install github.com/tomnomnom/assetfinder@latest



FROM php:8.2-rc-fpm-alpine3.16

#packages installing
RUN apk -U upgrade --no-cache \
    && apk add --no-cache bind-tools ca-certificates nmap chromium-chromedriver chromium maven openjdk11 php-fpm php-cli php-zip php-mbstring php-xml php-mysqli php-pdo php-curl php-bcmath php-dom php-ctype php-cli python3-dev py3-pip composer git



WORKDIR /var/www/html
RUN git clone https://github.com/zzzteph/sheye /var/www/html
RUN mkdir /var/www/html/scanners/amass
#COPY --from=build-go /go/bin/amass /var/www/html/scanners/amass/amass
#COPY --from=build-go /go/bin/subfinder /var/www/html/scanners/subfinder
#COPY --from=build-go /go/bin/httpx /var/www/html/scanners/httpx
#COPY --from=build-go /go/bin/nuclei /var/www/html/scanners/nuclei
#COPY --from=build-go /go/bin/dnsx /var/www/html/scanners/dnsx
#COPY --from=build-go /go/bin/gau /var/www/html/scanners/gau
#COPY --from=build-go /go/bin/assetfinder /var/www/html/scanners/assetfinder
#RUN git clone https://github.com/maurosoria/dirsearch /var/www/html/scanners/dirsearch
#RUN cd /var/www/html/scanners/dirsearch && pip3 install -r requirements.txt
#RUN cd /var/www/html/scanners/screenshot && mvn package
#RUN cd /var/www/html/ && composer install
RUN cd /var/www/html/ php artisan storage:link
RUN cd /var/www/html/ && chmod 777 -R storage/
RUN cd /var/www/html/ && chmod 777 *.log
#COPY ./init.sh /
#ENTRYPOINT /init.sh
#ENTRYPOINT ["sh"]

