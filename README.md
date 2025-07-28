
---

````md
# Laravel Semantic Search – Bluehole Assessment

A Laravel application that:

- Imports categories from an Excel file
- Converts each category into vector embeddings using Jina AI
- Allows users to perform semantic search in plain English
- Returns top matching categories using cosine similarity

---

## Setup Instructions

### 1. Clone Repository

```bash
git clone https://github.com/your-username/bluehole-laravel-assessment.git
cd bluehole-laravel-assessment
````

### 2. Install Dependencies

```bash
composer install
composer require maatwebsite/excel
```

### 3. Create Environment File

```bash
cp .env.example .env
```

Update your `.env` file:

```env
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=

JINA_API_KEY=your_jina_api_key
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

---

## Import Categories

Place `categories.xlsx` in the project root.

Run:

```bash
php artisan import:categories
```

This will:

* Import categories from the Excel file
* Convert keywords into vector embeddings using Jina AI
* Store them in the database

---

## Start the Application

```bash
php artisan serve
```

Visit:

```
http://localhost:8000/search
```

---

## Jina AI

* Sign up: [https://cloud.jina.ai](https://cloud.jina.ai)
* Copy your API key
* Add it to `.env` as:

```env
JINA_API_KEY=your_jina_api_key
```

---

