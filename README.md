# 🐘 PHP server API

A lightweight RESTful API built with pure PHP and PostgreSQL that fetches and stores data from the [PokéAPI](https://pokeapi.co/). Designed for frontend consumption (e.g., Next.js, Vue, React), this backend handles database persistence, querying, and serves CORS-enabled endpoints.

---

## 🚀 Features

- ✅ Automatic database schema creation (Pokémon, Types, and relations)
- ✅ Initial seeding of first 150 Pokémon from PokéAPI
- ✅ RESTful endpoints:
  - `GET /api/pokemon` — List all Pokémon
  - `GET /api/pokemon/:id` — Get a single Pokémon by ID
- ✅ CORS support for frontend access
- ✅ No frameworks required — pure PHP

---

## 🛠️ Technologies

- **PHP** (vanilla, no frameworks)
- **PostgreSQL**
- **PDO** for DB interaction
- **Composer** (autoloading)
- **PokéAPI** as external data source

---

## 📦 Requirements

- PHP >= 8.1
- PostgreSQL (running locally or remote)
- Composer
- [Optional] pgAdmin for managing the DB
