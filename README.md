# GKJW Sidoarjo - Pembangunan Gereja & Multimedia System

Platform web donasi modern untuk pembangunan Gereja GKJW Sidoarjo, dilengkapi dengan sistem Verifikasi Pembayaran berbasis OCR (Optical Character Recognition) dan Dashboard Admin yang responsif.

## ✨ Fitur Utama
*   **Public Landing Page**: Desain modern (Wildvine Theme), mobile-responsive, dengan progress bar donasi real-time.
*   **Sistem Upload Bukti Transfer**:
    *   Terintegrasi dengan **Tesseract.js** untuk scan otomatis nominal & tanggal dari foto struk/mutasi.
    *   Notifikasi **SweetAlert2** yang modern dan informatif.
*   **Admin Dashboard**:
    *   **Login Aman** (Session-based).
    *   **Manajemen Program (Baru)**: Tambah, Edit, dan Hapus program donasi secara dinamis (Unlimited Programs).
    *   **Verifikasi Transfer**: Review hasil scan OCR vs Gambar Asli dengan keamanan validasi data.
    *   **Auto-Delete Policy**: Gambar bukti transfer **otomatis dihapus** dari server setelah diverifikasi/ditolak (Hemat Storage & Privasi Terjamin).
    *   **Manajemen Donatur**: Database donatur terpusat yang terhubung otomatis dengan program terkait.
    *   **Link Database**: Tombol cepat menuju Google Sheets (Database Lengkap).

## 🚀 Panduan Instalasi (Hostinger / cPanel)

Program ini didesain agar sangat ringan (**~10 MB**) dan mudah di-deploy di hosting PHP manapun (Hostinger, Niagahoster, dll).

### 1. Upload File
Upload seluruh folder project ke `public_html` di File Manager hosting Anda.

### 2. Buat Database
Buat database baru di MySQL Databases (misal: `u12345_gkjw`). Catat Nama Database, Username, dan Password.

### 3. Konfigurasi Database
Rename file `db_config.example.php` menjadi `db_config.php`.
Edit file tersebut dan masukkan detail database Anda:
```php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'u12345_user');
define('DB_PASSWORD', 'password_anda');
define('DB_NAME', 'u12345_gkjw');
```

### 4. Jalankan Instalasi Otomatis (Master Script)
Buka browser dan akses alamat website Anda diikuti `/init_db.php`.
Contoh: `www.gkjwsidoarjo.com/init_db.php`

Script ini akan otomatis:
1.  Membuat semua tabel (`donatur_ac`, `donatur_multimedia`, `transactions`, `users`).
2.  Mereset data (Truncate) agar bersih.
3.  Membuat akun admin default.

> **PENTING**: Setelah instalasi selesai, **HAPUS** atau **RENAME** file `init_db.php` agar tidak dijalankan ulang oleh orang lain!

---

---

## 🔐 Akses Admin
Halaman login admin berada di: `/dashboard/login.php`

**Default Credentials:**
*   Username: `admin`
*   Password: `admin123`

*(Segera ubah password melalui database atau tambahkan fitur ganti password jika diperlukan)*

---

## 🛠️ Tech Stack
*   **Backend**: Native PHP 7.4 / 8.x (Tanpa Framework berat).
*   **Frontend**: HTML5, CSS3 (Custom Wildvine Theme), Bootstrap 5.
*   **Database**: MySQL / MariaDB.
*   **Libraries**:
    *   `Tesseract.js` (Client-side OCR).
    *   `SweetAlert2` (Modern Popups).
    *   `Chart.js` (Grafik - *optional/disabled*).
    *   `DataTables` (Tabel Admin interaktif).
    *   `IonIcons` (Ikon Vektor).

---

## 📂 Struktur Folder
*   `assets/`: CSS, Gambar, JS, Font.
*   `dashboard/`: Halaman admin (AC, Multimedia, Verifikasi, Login).
*   `uploads/`: Folder sementara untuk bukti transfer (pastikan permission **755** atau **777**).
*   `init_db.php`: Script instalasi database.
*   `process_receipt.php`: Logic pemrosesan upload & OCR.

---

**Developed for GKJW Sidoarjo.**
*Lightweight, Fast, and Secure.*

---

## 🎨 Wildvine Style Guide

**Google Fonts**:
```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Roboto:wght@300;400;500;700&family=Oswald:wght@600&display=swap" rel="stylesheet">
```

**Colors (CSS Variables)**:
```css
--pistachio: hsl(86, 45%, 54%); /* Primary Green */
--eerie-black-1: hsl(0, 0%, 13%); /* Dark Background */
--baby-powder: hsl(86, 41%, 97%); /* Light Text */
--bright-yellow-crayola: hsl(40, 100%, 50%); /* Accent */
```

**Typography**:
*   Headings: `Oswald`
*   Body: `Roboto`
*   UI Elements: `Inter`

**Spacing**:
*   Section Padding: `80px`
*   Card Shadow: `0 5px 20px -8px hsla(0, 0%, 0%, 0.15)`
