# 🛒 E-Procurement API (Laravel 10)

A RESTful API for managing user authentication, vendor registration, and product catalogs. Built with **Laravel 10** and **Sanctum** for secure, token-based access.

---

## 📦 Features

- ✅ User registration & login (token-based auth using Laravel Sanctum)
- 🏢 Vendor registration and listing
- 📦 Product CRUD (Create, Read, Update, Delete) linked to vendor
- 🔐 Auth-protected routes with access control

---

## 🚀 Getting Started

### 1. Clone the Repository

- git clone https://github.com/your-username/e-procurement-api.git
- cd e-procurement-api

### 2. Install Dependencies
composer install

### 3. Setup Environment
- cp .env.example .env
- php artisan key:generate

### 4. Run Migrations
php artisan migrate

### 5. Serve the Application
php artisan serve

### API will run at: http://localhost:8000/api

## 🔐 Authentication
Uses Laravel Sanctum for API token authentication.

Endpoints:
| Method | Endpoint    | Description   | Auth Required |
| ------ | ----------- | ------------- | ------------- |
| POST   | `/register` | Register user | ❌             |
| POST   | `/login`    | Login user    | ❌             |
| POST   | `/logout`   | Logout user   | ✅             |


After login, use the access_token in headers:

Authorization: `Bearer {token}`

## 📁 API Endpoints
Vendors
| Method | Endpoint   | Description         |
| ------ | ---------- | ------------------- |
| GET    | `/vendors` | List your vendors   |
| POST   | `/vendors` | Register new vendor |


## 📦 Products
| Method | Endpoint         | Description          |
| ------ | ---------------- | -------------------- |
| GET    | `/products`      | List your products   |
| POST   | `/products`      | Create new product   |
| GET    | `/products/{id}` | Show product details |
| PUT    | `/products/{id}` | Update product       |
| DELETE | `/products/{id}` | Delete product       |

### 🛠 Built With
- Laravel 10
- Sanctum
- PHP 8.1+
- MySQL
