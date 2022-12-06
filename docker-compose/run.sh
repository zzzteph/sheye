#docker rm -f $(docker ps -qa)
#docker rmi -f $(docker images -aq)
#yes| docker volume prune
docker-compose up -d 
sleep 30
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed --class=ScannerSeeder
docker-compose exec app php artisan db:seed --class=ScanEntrySeeder
docker-compose exec app php artisan db:seed --class=TemplateSeeder
docker-compose exec app php artisan add:user admin admin
