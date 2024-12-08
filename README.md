SimgosAppData adalah modul ekstensi yang dirancang untuk melengkapi aplikasi Simgos Kemenkes. Modul ini terintegrasi dengan database simgos yang sudah ada untuk manajemen data, sementara database terpisah usersimgos untuk informasi pengguna.

Ikhtisar Deployment:

    SimgosAppData hanya diuji di OS Linux
    Server: Ubuntu 22.04 & Ubuntu 24.04
    Database: MySQL
    PHP versi 8.2
    Setup Multiple Database: pada .env

Instruksi Deployment:

    1. Navigasi ke Direktori Deployment:
        Linux: /var/www/html/SimgoAppData

    2. Setup Lingkungan:
        Salin file .env.example menjadi .env.
        Sesuaikan file .env sesuai dengan petunjuk yang diberikan.

    3. Install Dependencies:
        Jalankan composer install untuk menginstal dependensi PHP. Disarankan menggunakan PHP versi 8.2 ke atas
        Jalankan npm install untuk menginstal dependensi Node.js. Disarankan menggunakan Node.js versi 18.20.4 dan NPM versi 10.7 ke atas

    4. Generate Kunci Aplikasi:
        Jalankan php artisan key:generate.

    5. Bangun Aset Frontend:
        Jalankan npm run build untuk mengompilasi aset untuk produksi. Langkah ini menghilangkan kebutuhan untuk menjalankan npm run dev di server.

Setup Database:

Di folder database, Anda akan menemukan usersimgos.sql, yang berisi struktur untuk database usersimgos. Untuk menyiapkannya:

    6. Buat database usersimgos di server MySQL Anda.
    Impor file usersimgos.sql menggunakan salah satu metode berikut:
        Terminal: Jalankan mysql -u [username] -p usersimgos < usersimgos.sql.
        Alat GUI: Gunakan DBeaver, HeidiSQL, atau aplikasi serupa.

Kustomisasi:

    7. Untuk mengubah nama tampilan nama rumah sakit, klinik atau organisasi pengguna, dapat dilakukan dengan merubah value HOSPITAL_NAME pada file .env
    8. Untuk penyesuaian timezone, lakukan perubahan APP_TIMEZONE value pada file .env 

Memperbarui Aplikasi:

Untuk menarik pembaruan terbaru:

    Linux: Navigasi ke /var/www/html/SimgosAppData dan jalankan git pull origin master di terminal.
    Windows: Jika dideploy di Windows, kloning ulang repositori dan ulangi langkah 2 hingga 7 di atas.

Tip : Fork repositori ini untuk tetap terupdate dengan perubahan terbaru secara real-time.

Notes : Untuk mengetahui detail perubahan terbaru dapat di lihat pada realease

Lisensi
Aplikasi ini memungkinkan penggunaan bebas dengan syarat identitas pengembang (seperti footer) tetap dipertahankan. Detail lengkapnya dapat dilihat di LICENSE.


