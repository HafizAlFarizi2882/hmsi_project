-- Database: hmsi_db
-- Create database (run this first if database doesn't exist)
-- CREATE DATABASE IF NOT EXISTS hmsi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE hmsi_db;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'mahasiswa') NOT NULL DEFAULT 'mahasiswa',
    jurusan VARCHAR(100) DEFAULT NULL,
    fakultas VARCHAR(100) DEFAULT NULL,
    alamat TEXT DEFAULT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') DEFAULT NULL,
    status ENUM('pending', 'diterima', 'ditolak') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin
-- Password: admin123 (hash sudah benar)
INSERT INTO users (username, email, password, role, jurusan, fakultas, alamat, jenis_kelamin, status) VALUES
('admin', 'admin@hmsi.ac.id', '$2b$10$5AylNAbJYyi1JLCAXjMp1e/SOtQ5uOHXCXy4beNco4pt.TDvcBoTm', 'admin', 'Sistem Informasi', 'Teknik', 'Kampus Utama', 'Laki-laki', 'diterima');

-- Note: Default admin password is "admin123"
