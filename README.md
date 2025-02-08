SimgosAppData adalah aplikasi tambahan yang dirancang untuk melengkapi aplikasi SIMGOS Kemenkes. 
Aplikasi ini memanfaatkan database SIMGOS, dan untuk database usersimgos dibuat terpisah sebagai 
tempat penyimpanan informasi pengguna.
Tujuan dari pembuatan aplikasi ini adalah untuk memudahkan Administrator SIMGOS dalam pencarian data dan 
informasi pelayanan, tanpa harus login ke aplikasi SIMGOS. 
Aplikasi ini juga menampilkan data-data yang dapat digunakan untuk pengambilan keputusan oleh pejabat  
yang berwenang berkaitan dengan pelayanan dengan memanfaatkan SIMGOS sebagai aplikasi untuk RME.

Ikhtisar Deployment:

    SimgosAppData telah diuji di OS Linux
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
        Jalankan composer install untuk menginstal dependensi PHP. Disarankan menggunakan 
        PHP versi 8.2 ke atas
        Jalankan npm install untuk menginstal dependensi Node.js. Disarankan menggunakan Node.js versi 18.20.4 
        dan NPM versi 10.7 ke atas

    4. Generate Kunci Aplikasi:
        Jalankan php artisan key:generate.

    5. Bangun Aset Frontend:
        Jalankan npm run build untuk mengompilasi aset untuk produksi. Langkah ini menghilangkan kebutuhan 
        untuk menjalankan npm run dev di server.

Setup Database:

Di folder database, Anda akan menemukan usersimgos.sql, yang berisi struktur untuk database usersimgos. 

    6. Buat database usersimgos di server MySQL Anda.
        Impor file usersimgos.sql menggunakan salah satu metode berikut:
        Terminal: Jalankan mysql -u [username] -p usersimgos < usersimgos.sql.
        Alat GUI: Gunakan DBeaver, HeidiSQL, atau aplikasi serupa.

Kustomisasi:

    7. Untuk mengubah nama tampilan nama rumah sakit, klinik atau organisasi pengguna, dapat dilakukan 
       dengan merubah value HOSPITAL_NAME pada file .env
    8. Untuk penyesuaian timezone, lakukan perubahan APP_TIMEZONE value pada file .env 
    9. Untuk perhitungan BOR menggunakan jumlah tempat tidur berdasarkan keputusan pimpinan organisasi atau  
       yang digunakan untuk perhitungan, lakukan perubahan nilai TTIDUR untuk jumlah tahun ini dan 
       TTIDURLALU untuk jumlah tahun lalu, yaitu pada .env 
    
    perhatikan contoh dan catatan pada .env.example
    
    10. Untuk akses khusus pengguna manajemen, dapat di buat user yang mengandung kata Manajemen, maka hanya
        akan menampilkan menu Beranda, Satusehat Sinkronisasi, Laporan dan Informasi saja
    11. Untuk petugas loket pendaftaran yang membutuhkan menu jadwal kontrol dan konsul, dapat di buat user 
        yang mengandung kata Pendaftaran, maka akan hanya tampil menu Beranda dan Kunjungan


Memperbarui Aplikasi:

Untuk menarik pembaruan terbaru:

    Linux: Navigasi ke /var/www/html/SimgosAppData dan jalankan git pull di terminal atau download repo,
    kemudian extract serta  ulangi langkah 2 hingga 5 di atas.
    Selanjutnya lakukan perintah composer update dan npm update, mengantisipasi jika terdapat perubahan 
    pada laravel package dan node package. Setelah itu lakukan perintah npm run build, agar tidak perlu
    menjalankan npm server untuk mengakses aplikasi.
    Windows: Jika dideploy di Windows, kloning ulang repositori dan ulangi langkah 2 hingga 5 di atas.

Tip : 
Fork repositori ini untuk mendapatkan informasi perubahan terbaru pada aplikasi ini.

Notes : 
Untuk mengetahui detail perubahan terbaru dapat di lihat pada realease

Lisensi :
Aplikasi ini dapat digunakan secara bebas dengan harapan identitas pengembang tetap dipertahankan. 
