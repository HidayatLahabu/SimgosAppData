SimgosAppData adalah modul ekstensi yang dirancang untuk melengkapi aplikasi Simgos Kemenkes. Modul ini terintegrasi dengan mulus dengan database simgos yang sudah ada untuk manajemen data, sementara database terpisah usersimgos didedikasikan untuk informasi pengguna.

Ikhtisar Deployment:

    SimgosAppData hanya diuji di OS Linux
    Server: Ubuntu 22.04 & Ubuntu 24.04
    Database: MySQL
    Setup Multiple Database: pada .env

Instruksi Deployment:

    1. Navigasi ke Direktori Deployment:
        Linux: /var/www/html/SimgoAppData

    2. Setup Lingkungan:
        Salin file .env.example menjadi .env.
        Sesuaikan file .env sesuai dengan petunjuk yang diberikan.

    3. Install Dependencies:
        Jalankan composer install untuk menginstal dependensi PHP.
        Jalankan npm install untuk menginstal dependensi Node.js.

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

    7. Untuk mengubah nama rumah sakit, perbarui nilai HOSPITAL_NAME dalam file .env.

Memperbarui Aplikasi:

Untuk menarik pembaruan terbaru:

    Linux: Navigasi ke /var/www/html/SimgosAppData dan jalankan git pull origin master di terminal.
    Windows: Jika dideploy di Windows, kloning ulang repositori dan ulangi langkah 2 hingga 7 di atas.

Tip : Fork repositori ini untuk tetap terupdate dengan perubahan terbaru secara real-time.

Notes : Untuk mengetahui detail perubahan terbaru dapat di lihat pada realease

