Nama : Hafiz al farizi
Nim : 251101014
kelas : TI2B
# HMSI - Sistem Informasi Pendaftaran & Manajemen Anggota

Sistem informasi untuk Himpunan Mahasiswa Sistem Informasi dengan fitur pendaftaran anggota dan dashboard admin.

## Tech Stack
- **Backend**: Native PHP (PDO)
- **Frontend**: Tailwind CSS (CDN)
- **Database**: MySQL
- **Bahasa**: Indonesia

## Struktur Folder
```
/project-root
├── /admin              # Dashboard admin
│   ├── index.php       # Dashboard utama
│   ├── users.php       # Manajemen user (CRUD)
│   ├── login.php       # Login admin
│   └── logout.php      # Logout
├── /pages              # Halaman publik
│   ├── home.php        # Beranda
│   ├── tentang.php     # Tentang HMSI
│   └── pendaftaran.php # Form pendaftaran
├── /includes           # File pendukung
│   ├── db.php          # Koneksi database
│   ├── header.php      # Template header + navbar
│   └── footer.php      # Template footer
├── database.sql        # Skema database
├── .htaccess           # Konfigurasi keamanan
└── index.php           # Entry point
```

## Cara Install
1. Buat database `hmsi_db` di MySQL
2. Import file `database.sql`
3. Sesuaikan koneksi database di `includes/db.php`
4. Akses website melalui browser

## Login Admin
- **Username**: `admin`
- **Password**: `admin123`

## Fitur
- Pendaftaran mahasiswa dengan validasi
- Login admin dengan session
- Dashboard dengan statistik
- CRUD data mahasiswa
- Update status pendaftaran (Pending/Diterima/Ditolak)
- Pencarian dan filter data
- Responsive design (mobile-friendly)
- Keamanan: password hash (bcrypt) + prepared statements

## Tema Warna
- Primary Green: #1B5E20
- Secondary Green: #2E7D32
- Light Green: #E8F5E9
- Accent Cream: #FFF8E1
