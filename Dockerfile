FROM golang:1.19.2-alpine AS build-go
RUN apk add build-base
RUN apk --no-cache add git
RUN go install -v github.com/projectdiscovery/subfinder/v2/cmd/subfinder@latest
RUN go install -v github.com/projectdiscovery/httpx/cmd/httpx@latest
RUN go install -v github.com/projectdiscovery/nuclei/v2/cmd/nuclei@latest
RUN go install -v github.com/projectdiscovery/dnsx/cmd/dnsx@latest
RUN go install github.com/lc/gau/v2/cmd/gau@latest
RUN go install github.com/tomnomnom/assetfinder@latest


FROM alpine:3.17

ARG user
ARG uid

#packages installing
RUN apk -U upgrade --no-cache \
    && apk add --no-cache bind-tools ca-certificates nmap chromium-chromedriver chromium maven openjdk11 php-fpm php-cli php-zip php-mbstring php-xml php-mysqli php-pdo php-curl php-bcmath php-dom php-ctype php-cli python3-dev py3-pip composer git


RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user
# Set working directory
WORKDIR /var/www/sheye

COPY --from=build-go /go/bin/subfinder /var/www/sheye/scanners/subfinder
COPY --from=build-go /go/bin/httpx /var/www/sheye/scanners/httpx
COPY --from=build-go /go/bin/nuclei /var/www/sheye/scanners/nuclei
COPY --from=build-go /go/bin/dnsx /var/www/sheye/scanners/dnsx
COPY --from=build-go /go/bin/gau /var/www/sheye/scanners/gau
COPY --from=build-go /go/bin/assetfinder /var/www/sheye/scanners/assetfinder
RUN git clone https://github.com/maurosoria/dirsearch -o /var/www/sheye/scanners/dirsearch
RUN cd /var/www/sheye/scanners/dirsearch && pip3 install -r requirements.txt
RUN cd /var/www/sheye/scanners/screenshot && mvn package

USER $user
COPY ./init.sh /
#ENTRYPOINT /init.sh
ENTRYPOINT ["bash", "/init.sh"]