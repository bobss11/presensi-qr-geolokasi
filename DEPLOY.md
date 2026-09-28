# Panduan Deploy Cloud (24/7 Online Tanpa PC Nyala)

Aplikasi Web Presensi Mahasiswa Berbasis QR Dinamis & Geolokasi ini dapat di-hosting ke cloud agar dapat diakses kapan saja oleh dosen dan mahasiswa melalui smartphone tanpa memerlukan laptop/PC menyala terus-menerus.

Berikut adalah 3 opsi terbaik, mulai dari yang termudah & gratis hingga hosting berbayar:

---

## 🌟 OPSI 1: Railway.app (Paling Mudah, Cepat & Otomatis) - REKOMENDASI

Railway adalah platform cloud PaaS modern yang secara otomatis mendeteksi aplikasi Laravel dan menyediakan database MySQL dengan 1 klik.

### Langkah-Langkah:

### 1. Upload Kodingan ke GitHub
1. Buka [GitHub.com](https://github.com/) dan buat repository baru (misal: `presensi-qr-geolokasi`, set ke *Private* atau *Public*).
2. Di terminal folder proyek ini, jalankan perintah berikut (ganti URL dengan repository GitHub Anda):
   ```bash
   git remote add origin https://github.com/USERNAME-ANDA/presensi-qr-geolokasi.git
   git branch -M main
   git push -u origin main
   ```

### 2. Hubungkan ke Railway
1. Buka [Railway.app](https://railway.app/) dan klik **Login with GitHub**.
2. Klik tombol **New Project** $\rightarrow$ pilih **Deploy from GitHub repo**.
3. Pilih repository `presensi-qr-geolokasi`.

### 3. Buat Database MySQL
1. Di dalam canvas project Railway, klik tombol **New** $\rightarrow$ pilih **Database** $\rightarrow$ klik **Add MySQL**.
2. Railway otomatis menyiapkan database MySQL cloud dalam hitungan detik.

### 4. Konfigurasi Environment Variables (Variabel Lingkungan)
1. Klik pada service aplikasi Laravel Anda $\rightarrow$ buka tab **Variables**.
2. Tambahkan variabel berikut:
   * `APP_NAME` = `Presensi Mahasiswa`
   * `APP_ENV` = `production`
   * `APP_DEBUG` = `false`
   * `APP_KEY` = *(Salin nilai APP_KEY dari file `.env` lokal Anda)*
   * `DB_CONNECTION` = `mysql`
   * `DB_HOST` = `${{MySQL.MYSQLHOST}}`
   * `DB_PORT` = `${{MySQL.MYSQLPORT}}`
   * `DB_DATABASE` = `${{MySQL.MYSQLDATABASE}}`
   * `DB_USERNAME` = `${{MySQL.MYSQLUSER}}`
   * `DB_PASSWORD` = `${{MySQL.MYSQLPASSWORD}}`

### 5. Generate Domain Publik HTTPS
1. Buka tab **Settings** pada service aplikasi Laravel $\rightarrow$ cari bagian **Networking** $\rightarrow$ klik **Generate Domain**.
2. Anda akan mendapatkan URL HTTPS permanen (contoh: `https://presensi-mahasiswa-production.up.railway.app`).
3. Tambahkan satu variabel lagi di tab **Variables**:
   * `APP_URL` = `https://presensi-mahasiswa-production.up.railway.app`

### 6. Jalankan Migrasi & Seeder Database
Di tab **Deployments** $\rightarrow$ klik tombol menu titik tiga $\rightarrow$ **View Logs** atau buka terminal web Railway $\rightarrow$ jalankan:
```bash
php artisan migrate --force --seed
```

✅ **Selesai!** Aplikasi Anda sekarang online 24 jam nonstop dengan HTTPS resmi.

---

## 🌐 OPSI 2: Shared Hosting / cPanel (Niagahoster, Hostinger, DomaiNesia, dll.)

Jika Anda memiliki paket hosting murah (cPanel / DirectAdmin):

### Langkah-Langkah:
1. **Export Database XAMPP:**
   * Buka browser ke `http://localhost/phpmyadmin`.
   * Pilih database `presensi_db` $\rightarrow$ klik menu **Export** $\rightarrow$ klik tombol **Export** (file `.sql` akan terunduh).
2. **Compress File Proyek:**
   * Buat file `.zip` dari semua file proyek (kecuali folder `vendor`, `node_modules`, dan file `cloudflared.exe`).
3. **Upload ke cPanel:**
   * Buka **cPanel** $\rightarrow$ **File Manager** $\rightarrow$ upload dan ekstrak file `.zip` di luar folder `public_html` (atau di dalam direktori root hosting Anda).
   * Pindahkan isi dari folder `public/` proyek ke dalam `public_html/`.
   * Edit file `public_html/index.php`:
     Ubah path autoload dan bootstrap agar mengarah ke folder proyek Anda:
     ```php
     require __DIR__.'/../vendor/autoload.php';
     $app = require_once __DIR__.'/../bootstrap/app.php';
     ```
4. **Buat Database MySQL di cPanel:**
   * Buka menu **MySQL Databases** di cPanel $\rightarrow$ buat database dan user baru, lalu berikan hak akses penuh (*All Privileges*).
   * Buka **phpMyAdmin** di cPanel $\rightarrow$ pilih database baru $\rightarrow$ klik **Import** $\rightarrow$ upload file `.sql` yang tadi diexport.
5. **Sesuaikan `.env`:**
   * Edit file `.env` di File Manager cPanel, isi `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai dengan akun database cPanel Anda.
   * Pastikan `APP_URL` menggunakan domain Anda dengan awalan `https://`.
6. **Aktifkan SSL:**
   * Buka menu **SSL/TLS Status** di cPanel $\rightarrow$ klik **Run AutoSSL** agar HTTPS aktif.

---

## 💻 OPSI 3: VPS Linux Ubuntu (DigitalOcean, IDCloudHost, Linode, AWS)

Cocok untuk kontrol penuh dengan Ubuntu 22.04 / 24.04:

```bash
# 1. Update & Install Nginx, PHP 8.3, MariaDB
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mariadb-server php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip git unzip composer

# 2. Clone Repository
cd /var/www
sudo git clone https://github.com/USERNAME-ANDA/presensi-qr-geolokasi.git presensi
cd presensi

# 3. Setup Dependencies & Permissions
composer install --no-dev --optimize-autoloader
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 4. Setup Environment & Database
cp .env.example .env
php artisan key:generate
php artisan migrate --force --seed

# 5. Pasang SSL Gratis (Let's Encrypt)
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d domainanda.com
```

---

## 📌 Rekomendasi:
Untuk mahasiswa atau pengujian cepat tugas/skripsi, **Opsi 1 (Railway.app)** adalah yang paling direkomendasikan karena:
1. Gratis / ada saldo trial bulanan.
2. Tidak perlu pusing konfigurasi server web, Nginx, maupun sertifikat SSL.
3. Langsung mendapatkan link HTTPS resmi yang otomatis mengizinkan kamera scanner dan GPS di smartphone.
