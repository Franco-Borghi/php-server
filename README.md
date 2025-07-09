# 🐘 PHP server API

A lightweight RESTful API built with pure PHP and PostgreSQL that fetches and stores data from the [PokéAPI](https://pokeapi.co/). Designed for frontend consumption (e.g., Next.js, Vue, React), this backend handles database persistence, querying, and serves CORS-enabled endpoints.

---

## 🚀 Features

- ✅ Automatic database schema creation
- ✅ Initial seeding of first 20 items
- ✅ RESTful endpoints:
  - `GET /api/products` — List all products
  - `POST /api/products` — Create new product
- ✅ CORS support for frontend access
- ✅ No frameworks required — pure PHP

---

## 🛠️ Technologies

- **PHP** (vanilla, no frameworks)
- **PostgreSQL**
- **PDO** for DB interaction
- **Composer** (autoloading)

---

## 📦 Requirements

- PHP >= 8.1
- PostgreSQL (running locally or remote)
- Composer
- [Optional] pgAdmin for managing the DB

---

## 🌐 API Endpoints

All responses are in JSON format. These endpoints are public and CORS-enabled for frontend integration.

### - List all products

Method: GET

URL: [https://php-server-73s6.onrender.com/api/products](https://php-server-73s6.onrender.com/api/products)

Description: Returns a list of the products.

### - Post new product

Method: POST

URL: [https://php-server-73s6.onrender.com/api/products](https://php-server-73s6.onrender.com/api/products)

Description: Create a new product.
