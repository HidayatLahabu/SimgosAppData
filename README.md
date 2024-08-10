Modul Simgos dibuat sebagai pelengkap aplikasi Simgos Kemenkes

Setelah clonning, jalankan :

1. kemudian masuk ke folder tempat aplikasi di deploy, jika di linux maka di folder /var/www/html/SimgoAppData, jika di windows xampp adanya di htdocs
2. copy **.env.example** menjadi **.env**
3. lakukan penyesuaian isi file **.env** sesuai catatan yang ada di dalamnya
4. kemudian ketik perintah **composer install** di terminal
5. kemudian ketik perintah **npm install** di terminal
6. selanjutnya ketik perintah **php artisan key:generate** 
7. selanjutnya ketik peritah **npm run build**, agar tidak harus menjalankan npm run dev di server

Pada folder database, terdapat usersimgos.sql, yang di gunakan untuk membuat database di server yang akan di deploy SimgosAppData. 
Caranya, buatkan database usersimgo terlebih dahulu, kemudian jalan usersimgos.sql di terminal menggukan perintah mysql restore, atau gunakan dbeaver atau heidi sql atau aplikasi sejenisnya.

Untuk merubah nama rumah sakit, lakukan perubahan pada value HOSPITAL_NAME di file .env

Untuk menarik perubahan terbaru, bisa menggunakan perintah git pull origin master di /var/www/html/modul-simgos/ menggunakan terminal, selanjutnya akan SimgosAppData akan di update dengan menu-menu baru yang telah di tambahkan 
