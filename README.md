# Deploy laravel backend to GCP  
### Install Dependency  
1. Buat instance, centang http, pilih ubuntu 24 x86/x64  
<img src="tutorials/0.png" width="500"/>  
  
2. Buat firewall rule untuk port 3000 dan 8000
<img src="tutorials/1.png" width="500"/>  
  
3. Masuk ssh, lalu update package  
```sudo apt-get update```  
```sudo apt-get upgrade```  
  
4. Install apache  
```sudo apt-get install apache2```  
restart apache dengan  
```sudo service apache2 restart```  
  
5. install zip  
```sudo apt-get install zip unzip```  
  
6. install dependency lainnya  
```sudo apt install phpmyadmin php-mbstring php-zip php-gd php-json php-curl```  
  
7. install mysql  
```sudo apt install mysql-server```  
  

### Membuat Database dan Konfigurasi  
  
8. Masuk ke mysql  
```sudo mysql -u root```   
  
9. Buat Database (dalam kasus ini db_smart_door_lock)  
```CREATE DATABASE db_smart_door_lock;```  
Cek database  
```SHOW DATABASES;```  
  
10. Buat User dan passwordnya  
```CREATE USER `ikmal`@`localhost` IDENTIFIED BY 'ikmal123';```  
  
11. Izinkan user pada database  
```GRANT ALL PRIVILEGES ON db_smart_door_lock.* TO `ikmal`@`localhost`;```  
lalu  
```FLUSH PRIVILEGES;```  
lalu keluar dari mysql  
```exit;```  

### Clone Repo dan Migrate  
12. cd ke ```/var/www/html``` lalu clone repo api  
  
13. cd ke repo dan ubah .env.example menjadi .env  
```sudo cp .env.example .env```  
  
14. sesuaikan isi .env sesuai dengan database, username, dan password  
```sudo nano .env```  
  
15. kembali ke path utama dengan cd, lalu install composer  
```curl -sS https://getcomposer.org/installer | php```  
```sudo mv composer.phar /usr/local/bin/composer```  
```sudo chmod +x /usr/local/bin/composer```  
cek jika composer sudah terinstall  
```composer -v```  

16. Set ownership project  
```sudo chown www-data:www-data -R /var/www/html/smart-door-lock-api```   
  
17. cd ke project, lalu composer update, lalu composer install
```cd /var/www/html/smart-door-lock-api/```  
```sudo composer update```  
```sudo composer install```  
  
18. generate key, jwt secret (jika menggunakan jwt), dan migrate
```sudo php artisan key:generate```  
```sudo php artisan jwt:secret```  
```sudo php artisan migrate```  
  
19. Jalankan server dengan host 0.0.0.0  
```sudo php artisan serve --host=0.0.0.0```  

Jika muncul error fatal: detected dubious ownership in repository at '/var/www/html/smart-door-lock-api'  
```git config --global --add safe.directory /var/www/html/smart-door-lock-api```  
```git config --global --get safe.directory```  

# Deploy NextJS to GCP  
1. Install nodejs  
```sudo apt install nodejs```  
  
2. Install npm  
```sudo apt install npm```  

3. Buat .env.local, ubah localhost dengan 'eksternal-ip' vm 
```sudo nano .env.local```  
```NEXT_PUBLIC_API_BACKEND = 'http://localhost:8000'```  

4. install dependency project  
```sudo npm install```  

5. 