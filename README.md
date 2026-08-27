# IT Helpdesk - Swiss-Belinn Pekanbaru

Aplikasi Web IT Helpdesk yang dirancang khusus untuk memanajemen pelaporan masalah (ticketing), inventarisasi peralatan (equipment), serta pencatatan tugas rutin (seperti pembersihan PC dan penggantian Thermal Paste) oleh departemen IT di hotel Swiss-Belinn Pekanbaru.

## 🚀 Fitur Utama

- **Ticketing System**: Karyawan dari berbagai divisi (Department) dapat membuat tiket pelaporan masalah IT (Hardware, Software, Network). Tim IT dapat memproses, menugaskan, dan menyelesaikan tiket.
- **Equipment Inventory**: Pencatatan inventaris perangkat IT (PC, Printer, Router, dll.) beserta informasi staf pengguna perangkat tersebut (Assigned User).
- **Cleaning & Maintenance Tasks**: Fitur khusus untuk mencatat log pemeliharaan rutin perangkat, seperti pembersihan debu PC dan penggantian Thermal Paste secara berkala.
- **User & Department Management**: Pengelolaan data karyawan (User) beserta jabatannya (Job Title) dan divisinya (Department). Terdapat 3 Role utama: `Employee`, `IT Support`, dan `IT Admin`.
- **Dynamic Dashboard**: Ringkasan data (statistik tiket, daftar prioritas, dsb.) yang disesuaikan berdasarkan login (Admin/IT atau Karyawan).
- **Modern UI/UX**: Tampilan dinamis, interaktif, dan modern menggunakan Tailwind CSS dengan nuansa tema merah khas Swiss-Belinn.

## 🛠️ Tech Stack

- **Framework**: Laravel 11 (PHP 8.3)
- **Frontend**: Blade Templating Engine + Tailwind CSS (v4)
- **Database**: PostgreSQL (Hosted on [Supabase](https://supabase.com))
- **Authentication**: Laravel Breeze

## ⚙️ Instalasi & Menjalankan di Lokal

### Prasyarat:
- PHP >= 8.3
- Composer
- Node.js & NPM

### Langkah-langkah:

1. **Clone repository ini**
   ```bash
   git clone https://github.com/KeijiChoco14/it-helpdesk-swissbell.git
   cd it-helpdesk-swissbell
   ```

2. **Install dependensi PHP dan Node.js**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file konfigurasi `.env`:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan masukkan kredensial Supabase PostgreSQL Anda:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=aws-0-[REGION].pooler.supabase.com
   DB_PORT=6543
   DB_DATABASE=postgres
   DB_USERNAME=postgres.[PROJECT_REF]
   DB_PASSWORD=password_anda
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeding Dummy Data**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Build Frontend Assets (Tailwind CSS)**
   ```bash
   npm run build
   # atau untuk mode development (hot-reload):
   npm run dev
   ```

7. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Aplikasi akan berjalan di `http://localhost:8000`.

## 🔐 Akun Default (Seeder)

Setelah menjalankan `migrate:fresh --seed`, Anda bisa login menggunakan akun Admin default berikut:

- **Email**: `admin@hotel.com`
- **Password**: `password`

Anda juga dapat masuk sebagai staf biasa (untuk mensimulasikan karyawan membuat tiket) menggunakan akun dari database hasil *seeding*.

## 📝 Lisensi

Proyek ini dibuat untuk keperluan internal manajemen IT di Swiss-Belinn Pekanbaru. Hak cipta dilindungi oleh ketentuan perusahaan.
