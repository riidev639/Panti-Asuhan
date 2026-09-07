# Deployment Panti Asuhan Moment

Project ini adalah Laravel 12 dengan PHP 8.2+, Vite/Tailwind, SQLite, autentikasi berbasis session, serta upload foto dan video ke disk `public`.

## Persyaratan server

- PHP 8.2 atau lebih baru.
- Extension PHP: `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `PDO`, `pdo_sqlite`, `session`, `tokenizer`, `xml`, dan `zip`.
- Composer 2.
- Apache dengan `mod_rewrite` dan `AllowOverride All`, atau Nginx dengan `try_files` ke `public/index.php`.
- Document root harus menunjuk ke folder `public`, bukan root project.
- Folder `storage`, `bootstrap/cache`, folder `database`, dan file `database/database.sqlite` harus dapat ditulis oleh user PHP/web server.
- Penyimpanan server harus persisten. Jangan memakai filesystem sementara/ephemeral karena database SQLite dan upload media disimpan di disk lokal.
- `upload_max_filesize` dan `post_max_size` minimal 40 MB agar upload video 30 MB dapat diterima.

OpenAI Sites/static hosting tidak dapat menjalankan project ini secara langsung karena aplikasi membutuhkan runtime PHP, session server, SQLite persisten, dan upload file.

## Backup sebelum deployment

Simpan salinan berikut di luar folder yang dipublikasikan:

- `.env`
- `database/database.sqlite`
- `storage/app/public`

Jangan menjalankan `migrate:fresh`, `db:wipe`, atau menghapus database production.

## Konfigurasi environment

Salin `.env.production.example` menjadi `.env`, lalu:

1. Isi `APP_URL` dengan URL HTTPS production tanpa trailing slash.
2. Pertahankan `APP_KEY` yang sudah digunakan aplikasi. Jika instalasi benar-benar baru dan belum memiliki data/session, jalankan `php artisan key:generate` satu kali.
3. Pastikan `APP_ENV=production` dan `APP_DEBUG=false`.
4. Untuk SQLite, jangan mendefinisikan `DB_DATABASE` agar Laravel memakai `database/database.sqlite`.

Jangan pernah meletakkan `.env` di dalam folder `public` atau mengirimkannya ke repository.

## Build dan instalasi

Jalankan pada release directory:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
npm ci
npm run build
php artisan optimize:clear
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika server tidak menyediakan Node.js, jalankan `npm ci && npm run build` secara lokal lalu ikut unggah folder `public/build`.

## Permission Linux

Sesuaikan nama user web server pada provider. Contoh umum:

```bash
chown -R deploy:www-data storage bootstrap/cache database
find storage bootstrap/cache database -type d -exec chmod 775 {} \;
find storage bootstrap/cache database -type f -exec chmod 664 {} \;
```

Hindari permission `777`.

## Apache

File `public/.htaccess` sudah berisi front-controller Laravel. Virtual host harus mengarah ke `public`:

```apache
DocumentRoot /var/www/panti-asuhan/public

<Directory /var/www/panti-asuhan/public>
    AllowOverride All
    Require all granted
</Directory>
```

## Nginx

Konfigurasi minimum:

```nginx
root /var/www/panti-asuhan/public;
index index.php;
client_max_body_size 40M;

location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    fastcgi_pass unix:/run/php/php8.2-fpm.sock;
}

location ~ /\. {
    deny all;
}
```

## Pemeriksaan setelah publish

```bash
php artisan about --only=environment,cache,drivers
php artisan migrate:status
php artisan route:list
```

Kemudian periksa `/up`, halaman utama, login/logout, dashboard, galeri foto/video, members, timeline, file `/storage/...`, upload, edit, dan hapus oleh pemilik. Jika terjadi 500, periksa `storage/logs/laravel.log` dan error log PHP-FPM/Apache/Nginx.
