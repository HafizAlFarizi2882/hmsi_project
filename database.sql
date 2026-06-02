
CREATE DATABASE IF NOT EXISTS hmsi_db 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;


USE hmsi_db;
CREATE TABLE IF NOT EXISTS users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(50) NOT NULL UNIQUE,
    email           VARCHAR(100) NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    role            ENUM('admin', 'mahasiswa') NOT NULL DEFAULT 'mahasiswa',
    jurusan         VARCHAR(100) DEFAULT NULL,
    fakultas        VARCHAR(100) DEFAULT NULL,
    alamat          TEXT DEFAULT NULL,
    jenis_kelamin   ENUM('Laki-laki', 'Perempuan') DEFAULT NULL,
    status          ENUM('pending', 'diterima', 'ditolak') DEFAULT 'pending',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO users (
    username, 
    email, 
    password, 
    role, 
    jurusan, 
    fakultas, 
    alamat, 
    jenis_kelamin, 
    status
) VALUES (
    'admin',
    'admin@hmsi.ac.id',
    '$2b$10$5AylNAbJYyi1JLCAXjMp1e/SOtQ5uOHXCXy4beNco4pt.TDvcBoTm',
    'admin',
    'Sistem Informasi',
    'Teknik',
    'Kampus Utama',
    'Laki-laki',
    'diterima'
);
INSERT INTO users (
    username, email, password, role, 
    jurusan, fakultas, alamat, jenis_kelamin, status
) VALUES (
    'budi2024',
    'budi@student.ac.id',
    '$2b$10$5AylNAbJYyi1JLCAXjMp1e/SOtQ5uOHXCXy4beNco4pt.TDvcBoTm',
    'mahasiswa',
    'Sistem Informasi',
    'Teknik',
    'Jl. Merdeka No. 123, Jakarta',
    'Laki-laki',
    'pending'
);

INSERT INTO users (
    username, email, password, role, 
    jurusan, fakultas, alamat, jenis_kelamin, status
) VALUES (
    'ani2024',
    'ani@student.ac.id',
    '$2b$10$5AylNAbJYyi1JLCAXjMp1e/SOtQ5uOHXCXy4beNco4pt.TDvcBoTm',
    'mahasiswa',
    'Sistem Informasi',
    'Teknik',
    'Jl. Sudirman No. 45, Bandung',
    'Perempuan',
    'diterima'
);

INSERT INTO users (
    username, email, password, role, 
    jurusan, fakultas, alamat, jenis_kelamin, status
) VALUES (
    'citra2024',
    'citra@student.ac.id',
    '$2b$10$5AylNAbJYyi1JLCAXjMp1e/SOtQ5uOHXCXy4beNco4pt.TDvcBoTm',
    'mahasiswa',
    'Sistem Informasi',
    'Teknik',
    'Jl. Ahmad Yani No. 78, Surabaya',
    'Perempuan',
    'ditolak'
);
