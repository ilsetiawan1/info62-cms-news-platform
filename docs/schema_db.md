-- =========================
-- DATABASE
-- =========================
CREATE DATABASE infoseputar62_db;
USE info_seputar62;

-- =========================
-- 1. USERS
-- =========================
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','super_admin') DEFAULT 'admin',
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =========================
-- 2. CATEGORIES
-- =========================
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    parent_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_categories_parent
    FOREIGN KEY (parent_id) REFERENCES categories(id)
    ON DELETE SET NULL
);

-- =========================
-- 3. ARTICLES
-- =========================
CREATE TABLE articles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    cover_image VARCHAR(255),
    
    category_id BIGINT UNSIGNED NOT NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    
    status ENUM('draft','published') DEFAULT 'draft',
    published_at DATETIME NULL,
    
    -- SEO
    meta_title VARCHAR(255),
    meta_description TEXT,
    keywords TEXT,
    
    -- Auto Fetch
    source_url TEXT NULL,
    
    -- Statistik ringan
    views_count INT DEFAULT 0,
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_articles_category
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_articles_user
    FOREIGN KEY (author_id) REFERENCES users(id)
    ON DELETE CASCADE
);

-- =========================
-- 4. ARTICLE VIEWS
-- =========================
CREATE TABLE article_views (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    article_id BIGINT UNSIGNED NOT NULL,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_views_article
    FOREIGN KEY (article_id) REFERENCES articles(id)
    ON DELETE CASCADE
);

-- =========================
-- 5. SETTINGS
-- =========================
CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) UNIQUE,
    `value` TEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =========================
-- INDEXING (PENTING BANGET)
-- =========================
CREATE INDEX idx_articles_status ON articles(status);
CREATE INDEX idx_articles_category ON articles(category_id);
CREATE INDEX idx_articles_published ON articles(published_at);
CREATE INDEX idx_views_article ON article_views(article_id);
CREATE INDEX idx_views_created ON article_views(created_at);

-- =========================
-- SAMPLE DATA (OPTIONAL BIAR LANGSUNG TES)
-- =========================

-- Super Admin
INSERT INTO users (name, email, password, role)
VALUES (
    'Super Admin',
    'admin@info62.com',
    '$2y$10$examplehashedpassword', -- ganti pakai bcrypt Laravel
    'super_admin'
);

-- Kategori Utama
INSERT INTO categories (name, slug) VALUES
('Nasional', 'nasional'),
('Teknologi', 'teknologi'),
('Olahraga', 'olahraga');

-- Sub Kategori
INSERT INTO categories (name, slug, parent_id) VALUES
('Politik', 'politik', 1),
('Gadget', 'gadget', 2);

-- Setting Website
INSERT INTO settings (`key`, `value`) VALUES
('site_name', 'Info Seputar +62'),
('meta_description', 'Portal berita digital terpercaya'),
('logo', '/images/logo.png'),
('facebook', 'https://facebook.com'),
('instagram', 'https://instagram.com');
