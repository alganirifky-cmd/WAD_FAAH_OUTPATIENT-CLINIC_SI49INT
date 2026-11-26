CREATE DATABASE IF NOT EXISTS klinik_db;
USE klinik_db;

CREATE TABLE IF NOT EXISTS dokter_0004 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_dokter VARCHAR(20) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    gelar VARCHAR(50),
    jenis_kelamin VARCHAR(20) NOT NULL,
    sip VARCHAR(50) NOT NULL,
    spesialisasi VARCHAR(50) NOT NULL,
    hari_praktik VARCHAR(255),
    email VARCHAR(100),
    telepon VARCHAR(20) NOT NULL
);