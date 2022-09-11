<p>sudo docker-compose up -d </p>
<p>sudo docker exec mz-apigateway-fpm composer install</p>
<p>sudo docker exec mz-apigateway-fpm npm install</p>
<p>cp .env.docker .env </p>
<p>sudo docker exec mz-apigateway-fpm php artisan key:generate</p>
<p>sudo docker exec mz-apigateway-fpm npm run dev</p>
