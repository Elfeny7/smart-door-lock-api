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
```sudo php artisan storage:link```  
  
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

5. jalankan  
```sudo npm run dev```  

# CI/CD dengan Cloud Build (GCP)  
1. Buat Host Connection lalu hubungkan dengan akun github  
  
2. Hubungkan dengan repository dengan klik 'LINK REPOSITORY'

3. Buka Trigger, lalu Create Trigger, Trigger yang paling dekat adalah Taiwan

4. Event pilih push to a branch, Pilih 2nd gen dan pilih Cloud Build configuration file (cloudbuild.yaml)  

5. Buat file cloudbuild.yaml di direktori root project, kurang lebih isi dari cloudbuild.yaml adalah sbb:  

```
steps:
  # Step 0: Install mysqli (php-mysql) package
  # - name: 'gcr.io/cloud-builders/gcloud'
  #   args:
  #     - 'compute'
  #     - 'ssh'
  #     - 'smart-door-lock-vm'  # compute engine vm name
  #     - '--zone=asia-southeast2-a'  # compute engine zone
  #     - '--command=sudo apt-get update && sudo apt-get install -y mysql-server php-mysql && sudo systemctl restart apache2'

  # Step 1: Deploy the application to Compute Engine VM
  - name: 'gcr.io/cloud-builders/gcloud'
    args:
      - 'compute'
      - 'ssh'
      - 'smart-door-lock-vm'  # compute engine vm name
      - '--zone=asia-southeast2-a'  # compute engine zone instance
      - '--command=cd /var/www/html/smart-door-lock-api && git pull origin main'
    dir: '/var/www/html/smart-door-lock-api'  # location cloned project

  # Step 2: Database migration
  - name: 'gcr.io/cloud-builders/gcloud'
    args:
      - 'compute'
      - 'ssh'
      - 'smart-door-lock-vm'  # compute engine vm name
      - '--zone=asia-southeast2-a'  # compute engine zone instance instance
      - '--command=cd /var/www/html/smart-door-lock-api && php artisan migrate --force'
    dir: '/var/www/html/smart-door-lock-api'  # location cloned project
# Optionally, you can specify the timeout for the entire build process
timeout: '1200s'
```

6. Lalu push projek ke main, nanti projek yang ada pada vm otomatis menjalankan command-command tersebut dan alhasil kode otomatis terupdate.

# Membuat Baris dari MySQL ke Tabel BigQuery (GCP)

1. Buat dataset dan tabel pada big query  
  
2. Pada project, masukan perintah   
```composer require google/cloud-bigquery```  
jika versi php tidak mumpuni, install versi lama dengan cara buka composer.json, pada require, tambnahkan  
"google/cloud-bigquery": "^1.3"  

3. install dependensi
```composer update```  
```composer install```  

4. Pada bagian .env tambahkan berikut  
GOOGLE_APPLICATION_CREDENTIALS=/path/to/your/keyfile.json  
GOOGLE_PROJECT_ID=YOUR-PROJECT-ID  
GOOGLE_DATASET_ID=YOUR-DATASET-ID  
GOOGLE_TABLE_ID=YOUR-TABLE-ID  

5. File json tersebut didapat dari console gcp, iam and admin, service account, pilih account yg digunakan, pilih keys, create key, lalu json.  

6. Simpan json tersebut, karena ini file secret, anda tidak bisa push dengan json tersebut, nanti kita letakkan ke server secara manual. nanti letakkan /var/www/html/project/key.json  

7. Buat file baru pada app/Providers/AppServiceProvider.php dan tambahkan kode berikut:  
```
<?php

namespace App\Providers;

use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Support\ServiceProvider;

class BigQueryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BigQueryClient::class, function ($app) {
            return new BigQueryClient([
                'keyFilePath' => env('GOOGLE_APPLICATION_CREDENTIALS'),
                'projectId' => env('GOOGLE_PROJECT_ID'),
            ]);
        });
    }

    public function boot()
    {
        //
    }
}
```  

8. Daftarkan service provider tadi ke config/app.php lalu bagian 'providers' => [ ]  
```App\Providers\BigQueryServiceProvider::class,```  

9. Lalu tambahkan kode seperti yang ada pada LogController.php pada repository ini. Perubahan tersebut adalah menambahkan fungsi untuk menambahkan baris pada bigquery table ketika fungsi store atau post dijalankan, jadi menambahkan baris pada database server sekaligus ke big query.  

10. Push ke main agar di server juga berubah  

11. Setelah push, jangan lupa composer update dan composer install di server.  

12. Upload file key.json tadi ke compute engine gcp  

13. setelah upload, copy file tersebut ke directory project dengan cara  
```sudo cp /home/ikmalfaris50/key.json /var/www/html/projek/```  

14. isi .env dengan yang tadi
```
GOOGLE_APPLICATION_CREDENTIALS=/path/to/your/keyfile.json  
GOOGLE_PROJECT_ID=YOUR-PROJECT-ID  
GOOGLE_DATASET_ID=YOUR-DATASET-ID  
GOOGLE_TABLE_ID=YOUR-TABLE-ID 
```
