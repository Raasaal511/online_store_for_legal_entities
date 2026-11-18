CREATE DATABASE IF NOT EXISTS snabplus
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE snabplus;

CREATE TABLE IF NOT EXISTS applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    contact_name VARCHAR(150) NOT NULL,
    phone VARCHAR(32) NOT NULL,
    email VARCHAR(190) NOT NULL,
    status ENUM('new', 'in_progress', 'done', 'rejected') NOT NULL DEFAULT 'new',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_applications_status (status),
    INDEX idx_applications_created_at (created_at),
    INDEX idx_applications_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
