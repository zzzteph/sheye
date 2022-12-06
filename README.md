

<p align="center">
  <img src="https://github.com/zzzteph/sheye/blob/main/public/logo.png?raw=true">
</p>


# ShrewdEye

**ShrewdEye** (sheye) is a set of utilities bundled into a single automated workflow to improve, simplify, and speed up resource discovery and vulnerabilities finding.




# Setup

## Docker

First you need to install docker on your system. After that navigate ```docker-compose``` folder and run ```run.sh``` or type next commands

```
docker-compose up -d 
sleep 30
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed --class=ScannerSeeder
docker-compose exec app php artisan db:seed --class=ScanEntrySeeder
docker-compose exec app php artisan db:seed --class=TemplateSeeder
docker-compose exec app php artisan add:user admin admin

```


## Manual setup 

Please, take a look at [Wiki](https://github.com/zzzteph/sheye/wiki/Setup) for manual setup guide.



# Scanners and tools

- [amass](https://github.com/OWASP/Amass)
- [subfinder](https://github.com/projectdiscovery/subfinder)
- [assetfinder](https://github.com/tomnomnom/assetfinder)
- [gau](https://github.com/lc/gau)
- [dnsx](https://github.com/projectdiscovery/dnsx)
- [nmap](https://nmap.org/)
- [nuclei](https://github.com/projectdiscovery/nuclei)
- [dirsearch](https://github.com/maurosoria/dirsearch)
- [screenshot](https://github.com/zzzteph/screenshot)












