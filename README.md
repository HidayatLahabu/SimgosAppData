Modul Simgos dibuat sebagai pelengkap aplikasi Simgos Kemenkes

Setelah clonning, jalankan :

1. composer install
2. npm install
3. php artisan key:generate -> jika key belum ada
4. npm run build -> agar tidak harus menjalankan npm run dev di server

Pada folder database, terdapat usersimgos.sql, yang di gunakan untuk membuat database di server yang akan di deploy SimgosAppData. 
Caranya, buatkan database usersimgo terlebih dahulu, kemudian jalan usersimgos.sql di terminal menggukan perintah mysql restore, atau gunakan dbeaver atau heidi sql atau aplikasi sejenisnya.

Untuk merubah nama rumah sakit, lakukan perubahan pada value HOSPITAL_NAME di file .env

Untuk menarik perubahan terbaru, bisa menggunakan perintah git pull origin master di /var/www/html/modul-simgos/ menggunakan terminal, selanjutnya akan SimgosAppData akan di update dengan menu-menu baru yang telah di tambahkan 
