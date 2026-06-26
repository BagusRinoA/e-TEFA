# e-TEFA Kompeni - Sistem Informasi Manajemen Produk dan Penjualan Berbasis Website

## Deskripsi Proyek

Sistem ini adalah platform web lengkap yang menggabungkan fitur e-commerce (dengan integrasi payment gateway Midtrans), forum diskusi komunitas, sistem artikel/blog, serta sistem loyalitas pelanggan (Reward Points). Dibangun menggunakan framework Laravel dengan frontend modern menggunakan Tailwind CSS dan Vite. Sistem ini dirancang untuk memberikan pengalaman pengguna yang komprehensif dalam berbelanja produk hidroponik, berpartisipasi dalam komunitas, dan mengumpulkan poin hadiah.

## Fitur Utama

### 🛒 E-Commerce

- **Katalog Produk**: Tampilan produk dengan kategori, harga, stok, dan gambar
- **Detail Produk**: Informasi lengkap produk dengan deskripsi dan spesifikasi
- **Keranjang Belanja**: Sistem checkout untuk proses pembelian
- **Manajemen Pesanan**: Tracking status pesanan dari pemesanan hingga pengiriman
- **Payment Gateway**: Integrasi pembayaran otomatis menggunakan Midtrans

### 🎁 Sistem Loyalitas & Poin (Rewards)

- **Pengumpulan Poin**: Dapatkan poin dari setiap transaksi pembelian
- **Katalog Penukaran**: Tukarkan poin dengan barang/reward menarik
- **Riwayat Penukaran**: Lacak riwayat penukaran poin (Redemption Transactions)

### 💬 Forum Diskusi

- **Pertanyaan Forum**: Sistem tanya jawab komunitas
- **Kategori Diskusi**: Pengorganisasian topik berdasarkan kategori
- **Sistem Reply**: Balasan terstruktur untuk setiap pertanyaan
- **Tag System**: Penandaan topik dengan tag untuk pencarian mudah

### 📝 Sistem Artikel

- **Artikel/Blog**: Konten informatif dengan excerpt dan gambar
- **Kategori Artikel**: Pengelompokan artikel berdasarkan topik
- **Simpan Artikel**: Fitur bookmark untuk artikel favorit
- **Dashboard Artikel Tersimpan**: Akses cepat ke artikel yang disimpan

### 👤 Manajemen Pengguna

- **Authentication**: Sistem login dan registrasi
- **Role-based Access**: Pemisahan antara user biasa dan admin
- **Profile Management**: Edit profil, password, dan pengaturan privasi
- **Dashboard User**: Ringkasan aktivitas dan artikel tersimpan

### 🔧 Panel Admin

- **Dashboard Admin**: Overview sistem dan statistik
- **Manajemen Produk**: Tambah, edit, hapus produk
- **Manajemen Pesanan**: Monitoring dan update status pesanan
- **Manajemen User**: Kontrol akses dan data pengguna
- **Laporan Penjualan**: Analisis performa bisnis
- **Konfigurasi Loyalitas**: Atur perolehan poin dan batas minimum (Loyalty Config)
- **Item Penukaran**: Manajemen daftar barang/reward yang bisa ditukar (Redemption Items)
- **Transaksi Penukaran**: Verifikasi dan kelola klaim penukaran poin user

## Teknologi yang Digunakan

### Backend

- **Laravel 12**: Framework PHP untuk backend
- **Laravel Sanctum**: Authentication API
- **MySQL**: Database utama
- **Eloquent ORM**: Object-Relational Mapping
- **Midtrans SDK**: Integrasi gerbang pembayaran otomatis

### Frontend

- **Blade Templates**: Template engine Laravel
- **Tailwind CSS**: Framework CSS utility-first
- **Vite**: Build tool dan development server
- **Axios**: HTTP client untuk API calls

### Development Tools

- **Composer**: Dependency management PHP
- **NPM**: Package management JavaScript
- **PHPUnit**: Testing framework
- **Laravel Pail**: Development server

## Struktur Database

### Tabel Utama

- `users`: Data pengguna dengan role (user/admin)
- `products`: Katalog produk dengan detail lengkap
- `orders`: Data pesanan dan status pembayaran
- `order_items`: Item dalam setiap pesanan
- `forum_questions`: Pertanyaan forum dengan kategori dan tag
- `forum_replies`: Balasan untuk pertanyaan forum
- `articles`: Konten artikel/blog
- `saved_articles`: Relasi artikel yang disimpan user
- `reports`: Sistem pelaporan (untuk admin)
- `loyalty_configurations`: Pengaturan sistem poin
- `redemption_items`: Daftar hadiah untuk tukar poin
- `redemption_transactions`: Riwayat transaksi penukaran poin

## Instalasi dan Setup

### Persyaratan Sistem

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js dan NPM
- MySQL 8.0+
- Git

### Langkah Instalasi

1. **Clone Repository**

    ```bash
    git clone <repository-url>
    cd web_temp
    ```

2. **Install Dependencies PHP**

    ```bash
    composer install
    ```

3. **Setup Environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Konfigurasi Database**
    - Buat database baru di MySQL
    - Update konfigurasi di file `.env`:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pw_test_web
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Setup Database dengan Fresh Migration**

    ```bash
    php artisan migrate:fresh --seed
    ```

    Perintah ini akan:
    - ✅ Drop semua tabel
    - ✅ Jalankan migrations
    - ✅ Buat akun admin dan test user

6. **Buat Storage Symlink (Untuk Gambar/Media)**

    ```bash
    php artisan storage:link
    ```

7. **Install Dependencies Frontend**

    ```bash
    npm install
    ```

8. **Build Assets**
    ```bash
    npm run build
    ```

### Default Akun Login

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

#### Admin Account

- **URL**: http://localhost:8000/login
- **Username**: `admin`
- **Password**: `admin`
- **Akses**: `/admin/dashboard`

#### Test User Account

- **Username**: `user`
- **Password**: `user`
- **Akses**: `/dashboard`

### Menjalankan Aplikasi

#### Development Mode

```bash
composer run dev
```

Perintah ini akan menjalankan:

- Laravel server di `http://localhost:8000`
- Queue worker untuk background jobs
- Vite dev server untuk hot reload
- Laravel Pail untuk logging

### Production Build

```bash
npm run build
php artisan serve
```

## Quick Start Guide

### 1. Setup Pertama Kali

```bash
# Clone project
git clone <repository-url>
cd web_temp

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database dengan seeder (creates admin & test user)
php artisan migrate:fresh --seed

# Buat link storage untuk gambar produk/profil
php artisan storage:link

# Build frontend
npm run build

# Jalankan server
php artisan serve
```

### 2. Akses Aplikasi

- **Website**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin/dashboard
    - Username: `admin` / Password: `admin`
- **User Dashboard**: http://localhost:8000/dashboard
    - Username: `user` / Password: `user`

### 3. Development Mode (Recommended)

```bash
composer run dev
```

Ini akan menjalankan:

- Laravel server (http://localhost:8000)
- Vite dev server (hot reload)
- Queue worker
- Logging real-time

## File Penting

| File                                  | Deskripsi                 |
| ------------------------------------- | ------------------------- |
| `.env.example`                        | Template environment file |
| `routes/web.php`                      | Definisi semua routes     |
| `app/Models/`                         | Eloquent models           |
| `app/Http/Controllers/`               | Controller classes        |
| `database/migrations/`                | Database schema           |
| `database/seeders/DatabaseSeeder.php` | Data seeding              |
| `resources/views/`                    | Blade templates           |

## Model Relationships

```
User
├── hasMany Orders
├── hasMany SavedArticles
├── hasMany ForumQuestions
└── hasMany ForumReplies

Order
├── belongsTo User
└── hasMany OrderItems

OrderItem
├── belongsTo Order
└── belongsTo Product

ForumQuestion
├── belongsTo User
└── hasMany ForumReplies

ForumReply
├── belongsTo User
└── belongsTo ForumQuestion

Article
└── hasMany SavedArticles

SavedArticle
├── belongsTo User
└── belongsTo Article

RedemptionTransaction
├── belongsTo User
└── belongsTo RedemptionItem
```

## Troubleshooting

### Database Error saat Migrate

```bash
php artisan migrate:fresh --seed
```

### Port 8000 sudah digunakan

```bash
php artisan serve --port=8080
```

### Assets tidak ter-update

```bash
npm run build
```

### Clear cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Struktur Folder

```
├── app/                    # Kode aplikasi Laravel
│   ├── Http/Controllers/   # Controller classes
│   ├── Models/            # Eloquent models
│   └── Providers/         # Service providers
├── database/              # Migrations dan seeders
│   ├── migrations/        # Schema database
│   └── seeders/          # Data awal
├── public/                # Assets publik
├── resources/             # Views dan assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── views/            # Blade templates
├── routes/                # Route definitions
│   └── web.php           # Web routes
├── storage/               # File storage
├── tests/                 # Unit dan feature tests
└── vendor/                # Composer dependencies
```

## API Endpoints

### Public Routes

- `GET /` - Halaman utama
- `GET /about` - Halaman tentang
- `GET /contact` - Kontak
- `GET /privacy` - Kebijakan privasi
- `GET /terms` - Syarat dan ketentuan
- `GET /products` - Katalog produk
- `GET /products/{id}` - Detail produk
- `GET /forum` - Daftar forum
- `GET /forum/{id}` - Detail pertanyaan forum
- `GET /articles` - Daftar artikel
- `GET /articles/{id}` - Detail artikel
- `GET /checkout` - Halaman checkout

### Authentication Routes

- `GET /login` - Form login
- `POST /login` - Proses login
- `POST /logout` - Proses logout
- `GET /register` - Form registrasi
- `POST /register` - Proses registrasi

### Protected Routes (Authentication Required)

- `GET /dashboard` - Dashboard user
- `GET /dashboard/saved-articles` - Artikel tersimpan
- `GET /profile/edit` - Form edit profil
- `PUT /profile` - Update profil
- `PUT /password` - Update password
- `POST /profile/privacy` - Update pengaturan privasi
- `DELETE /profile` - Hapus akun
- `GET /forum/create` - Form buat pertanyaan
- `POST /forum` - Buat pertanyaan forum
- `POST /articles/{id}/save-toggle` - Simpan/unsave artikel

### Admin Routes (Admin Only - Prefix: `/admin`)

- `GET /admin/dashboard` - Dashboard admin
- `GET /admin/sales` - Laporan penjualan
- `GET /admin/orders` - Manajemen pesanan
- `GET /admin/users` - Manajemen pengguna
- `GET /admin/products/create` - Form tambah produk

## Testing

Jalankan test suite dengan perintah:

```bash
php artisan test
```

## Deployment

### Environment Variables

Pastikan file `.env` dikonfigurasi dengan benar untuk production:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_CONNECTION=mysql`
- `DB_HOST=your-database-host`
- `DB_DATABASE=your-database-name`
- `DB_USERNAME=your-username`
- `DB_PASSWORD=your-password`

### Build untuk Production

```bash
npm run build
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lebih lanjut.

## Dukungan

Untuk pertanyaan atau dukungan teknis, silakan buat issue di repository GitHub atau hubungi tim development.

---

**Dibangun dengan ❤️ menggunakan Laravel Framework**

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
"# e-tefa" 
