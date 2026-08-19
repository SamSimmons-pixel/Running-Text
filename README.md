# 📘 Technical Handover & Deployment Runbook: Running Text

## 1. System Overview & Tech Stack
- **Framework:** Laravel 12 (PHP 8.2+)
- **Frontend / Bundler:** Blade, Vanilla JS / Livewire, Vite, Tailwind CSS
- **Web Server:** Nginx (Reverse Proxy & Static file serving)
- **Database:** MariaDB 10.x / MySQL 8.x
- **Real-time / WebSocket Engine:** Laravel Reverb
- **Process Manager:** Supervisor

---

## 2. Server & Environment Details
- **Origin Server (Internal):** `http://10.112.115.18`
- **Public Domain (Proxied):** `https://runningtext.rodja.studio`
- **Project Root Directory:** `/var/www/Running-Text`
- **Web Root (Nginx root):** `/var/www/Running-Text/public`
- **Application User:** `www-data` (atau user deployment Anda)

---

## 3. Database Access
- **Host:** `127.0.0.1` (Port `3306`)
- **Database Name:** `running`
- **Database User:** `laravel`
- **Database Password:** *(Tersimpan di file `.env` server)*
- **Cara Akses:**
  - CLI: `mysql -u laravel -p running`
  - GUI: DBeaver / TablePlus via SSH Tunnel ke `10.112.115.18`

---

## 4. Background Services & Daemons
Aplikasi membutuhkan **Supervisor** untuk menjaga 2 proses background tetap aktif:

| Process Name | Command | Fungsi |
| :--- | :--- | :--- |
| `runningtext-reverb` | `php artisan reverb:start --host=0.0.0.0 --port=8080` | WebSocket Server notifikasi real-time |
| `runningtext-queue` | `php artisan queue:work` | Background task / Queue handler |

- **File Konfigurasi Supervisor:** `/etc/supervisor/conf.d/running-text.conf`

---

## 5. SOP Maintenance & Deployment Update
Lakukan langkah-langkah berikut setiap kali ada pembaruan kode di server produksi:

```bash
# 1. Pindah ke direktori proyek
cd /var/www/Running-Text

# 2. Masuk ke mode maintenance (opsional, jika update besar)
php artisan down

# 3. Tarik kode terbaru dari git
git pull origin main

# 4. Update dependensi backend & migrasi database
composer install --no-dev --optimize-autoloader
php artisan migrate --force

# 5. Build ulang asset frontend
npm install
npm run build

# 6. Optimasi Cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Restart Service Background
sudo supervisorctl restart all

# 8. Hidupkan kembali aplikasi
php artisan up
