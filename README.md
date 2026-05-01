# 📰 Info Seputar +62

A modern news portal platform built with Laravel 12, designed for efficient article management and clean reading experience.

---

## 🚀 Features

### 🔐 Admin Panel
- Login system (custom route: `/seputaradmin/login`)
- Role-based access (Admin & Super Admin)
- Dashboard (basic structure)
- Manage users (planned)

### 📝 Article Management
- Create, edit, delete articles
- Draft & publish system
- Upload cover image
- SEO fields (meta title, description, keywords)

### 🔗 Auto Fetch (Planned)
- Fetch article from external URL
- Auto clean content (remove tags, format text)

### 🗂️ Categories
- Parent & sub category (hierarchical)

---

## 🌐 Public Features
- Homepage with latest articles
- Article detail page
- Clean & readable layout
- SEO-friendly structure

---

## 🧱 Tech Stack

- **Framework**: Laravel 12
- **Language**: PHP 8+
- **Database**: MySQL
- **Styling**: Tailwind CSS v4
- **Auth**: Laravel Breeze (customized)

---

## ⚙️ Installation

```bash
git clone https://github.com/ilsetiawan1/info62-cms-news-platform.git
cd info62-cms-news-platform

composer install
npm install

cp .env.example .env
php artisan key:generate