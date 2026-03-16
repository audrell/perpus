# Example App (Laravel)

Dokumentasi ini berisi langkah instalasi lengkap untuk menjalankan aplikasi Laravel ini di lokal.

## 1) Prasyarat

Pastikan software berikut sudah terpasang:

-   PHP 8.2+ (disarankan sesuai versi di environment Laragon Anda)
-   Composer 2+
-   Node.js 18+ dan npm
-   MySQL/MariaDB
-   Git (opsional, untuk clone repository)

Jika menggunakan Laragon di Windows:

-   Nyalakan service **Apache/Nginx** dan **MySQL** dari Laragon.
-   Pastikan folder project berada di direktori web server (contoh: `C:\laragon\www\example-app`).

## 2) Clone / Siapkan Source Code

Jika project sudah ada, lewati langkah clone.

```bash
git clone <url-repository> example-app
cd example-app
```

## 3) Install Dependency PHP (Composer)

Jalankan:

```bash
composer install
```

Perintah ini akan menginstall seluruh package backend Laravel berdasarkan `composer.json`.

## 4) Install Dependency Frontend (Node)

Jalankan:

```bash
npm install
```

Perintah ini akan menginstall package frontend sesuai `package.json`.

## 5) Konfigurasi Environment (.env)

Salin file environment:

```bash
copy .env.example .env
```

Jika Anda menggunakan terminal non-Windows:

```bash
cp .env.example .env
```

Lalu edit nilai berikut di `.env` sesuai konfigurasi lokal Anda:

```env
APP_NAME="Example App"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=example_app
DB_USERNAME=root
DB_PASSWORD=
```

> Catatan: Sesuaikan `APP_URL`, `DB_USERNAME`, dan `DB_PASSWORD` dengan setting Laragon/local Anda.

## 6) Generate Application Key

Jalankan:

```bash
php artisan key:generate
```

## 7) Buat Database

Buat database baru di MySQL/MariaDB, misalnya:

-   Nama database: `example_app`

Anda bisa membuatnya lewat HeidiSQL, phpMyAdmin, atau CLI MySQL.

## 8) Jalankan Migration & Seeder

Untuk membuat seluruh tabel:

```bash
php artisan migrate
```

Jika ingin sekaligus isi data awal:

```bash
php artisan db:seed
```

Atau jalankan keduanya sekaligus:

```bash
php artisan migrate --seed
```

## 9) Build Asset Frontend

Untuk development (hot reload):

```bash
npm run dev
```

Untuk build production:

```bash
npm run build
```

## 10) Menjalankan Aplikasi

Opsi A (Laravel built-in server):

```bash
php artisan serve
```

Akses di browser: `http://127.0.0.1:8000`

Opsi B (via Laragon virtual host):

-   Pastikan root project sudah terbaca oleh web server Laragon.
-   Akses URL lokal sesuai host Laragon Anda (contoh: `http://example-app.test`).

## 11) Akun Awal (Jika Seeder Menyediakan)

Project ini memiliki seeder terkait user/role/permission pada folder `database/seeders`.

Jika setelah seeding akun belum diketahui, cek implementasi seeder berikut:

-   `database/seeders/UserTableSeeder.php`
-   `database/seeders/RoleTableSeeder.php`
-   `database/seeders/PermissionTableSeeder.php`
-   `database/seeders/DatabaseSeeder.php`

## 12) Menjalankan Test

Jalankan test backend dengan:

```bash
php artisan test
```

Atau:

```bash
vendor\\bin\\phpunit
```

## 13) Troubleshooting Umum

1. **Error koneksi database**

    - Pastikan MySQL aktif.
    - Cek kembali `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

2. **Class/file tidak terbaca setelah pull update**

    - Jalankan:
        ```bash
        composer dump-autoload
        ```

3. **Perubahan env tidak terbaca**

    - Jalankan:
        ```bash
        php artisan config:clear
        php artisan cache:clear
        ```

4. **Asset CSS/JS tidak muncul**
    - Pastikan `npm run dev` sedang berjalan saat development.
    - Atau build ulang dengan `npm run build`.

## 14) Ringkasan Perintah Cepat

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

Selesai. Aplikasi siap dijalankan untuk development lokal.
