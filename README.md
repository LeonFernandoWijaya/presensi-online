# Presensi Online 🚀

Sistem **Presensi Online** adalah aplikasi berbasis web yang memudahkan pengelolaan kehadiran secara digital. Dibangun menggunakan **Laravel** (Blade & PHP) dan JavaScript.

## Fitur Utama ✨

- Login & otorisasi pengguna 🔐
- Pencatatan kehadiran secara real-time 🕒
- Dashboard statistik kehadiran 📊
- Pengelolaan data staff/manager 👥

---

## Instalasi 🛠️

Ikuti langkah-langkah berikut untuk menjalankan project ini secara lokal:

### 1. Clone Repository

```bash
git clone https://github.com/LeonFernandoWijaya/presensi-online.git
cd presensi-online
```

### 2. Install Dependency Composer & NPM

```bash
composer install
npm install
```

### 3. Copy & Konfigurasi File Environment

```bash
cp .env.example .env
```
Edit file `.env` dan sesuaikan konfigurasi database serta variabel lainnya sesuai kebutuhan.

### 4. Generate Key Aplikasi

```bash
php artisan key:generate
```

### 5. Migrasi & Seeder Database

```bash
php artisan migrate --seed
```

### 6. Build Asset Frontend

```bash
npm run dev
```

### 7. Jalankan Server Lokal

```bash
php artisan serve
```
Aplikasi dapat diakses di `http://localhost:8000` 🚦

---

## Kontribusi 🤝

Kontribusi sangat terbuka! Silakan buat **issue** atau **pull request**.

---

## Lisensi 📄

Project ini menggunakan lisensi [MIT](LICENSE).

---

Selamat mencoba dan semoga bermanfaat! 🎉