# AB-Book-Shop 📚

A full-stack **online book shop** built with **PHP, MySQL, CSS, and JavaScript**. The project follows a classic multi-tier architecture with a customer-facing storefront, a REST-style API layer, and a back-office admin panel — all running on Apache/XAMPP with a MySQL database.

---

## Repository at a Glance

| Property        | Detail                                      |
|-----------------|---------------------------------------------|
| **Primary Language** | PHP (83.1%)                            |
| **Secondary Language** | CSS (16.2%)                          |
| **Scripting** | JavaScript (0.7%)                            |
| **Backend Runtime** | PHP (Apache/XAMPP)                      |
| **Database** | MySQL                                         |
| **Entry Point** | `index.php` → redirects to `/store/index.php` |
| **Commits** | 1 (initial commit)                           |
| **Branch** | `main`                                        |

---

## Directory Structure

```
AB-Book-Shop/
│
├── index.php               ← Root entry point; 302-redirects to /store/index.php
│
├── admin/                  ← Back-office admin panel (protected pages)
│   ├── index.php           ← Admin dashboard (orders, stats)
│   ├── login.php           ← Admin login page + session creation
│   ├── logout.php          ← Destroys admin session
│   ├── books.php           ← List / search books
│   ├── add_book.php        ← Form to add a new book + image upload
│   ├── edit_book.php       ← Form to edit an existing book
│   ├── delete_book.php     ← Delete book action (POST)
│   ├── categories.php      ← Manage book categories
│   ├── orders.php          ← View & update order status
│   └── users.php           ← View registered customers
│
├── api/                    ← JSON API endpoints (called via AJAX from the store)
│   ├── index.php           ← API router / catch-all
│   ├── books.php           ← GET books (with optional search/filter/category params)
│   ├── cart.php            ← POST add-to-cart, DELETE remove, GET cart contents
│   ├── orders.php          ← POST place order
│   └── auth.php            ← POST login / register / logout (returns JSON)
│
├── assets/                 ← All static front-end assets
│   ├── css/
│   │   ├── style.css       ← Main custom stylesheet
│   │   └── admin.css       ← Admin-specific styles
│   ├── js/
│   │   └── main.js         ← jQuery AJAX calls (cart, search, flash messages)
│   └── images/
│       ├── covers/         ← Uploaded book cover images
│       └── logo.png        ← Site logo
│
├── database/
│   └── bookshop.sql        ← Full MySQL dump (schema + seed data)
│
├── includes/               ← Shared PHP utilities included across the project
│   ├── db.php              ← MySQLi connection (single shared $conn object)
│   ├── config.php          ← App-wide constants (DB credentials, site URL, etc.)
│   ├── functions.php       ← Reusable helper functions (sanitize, paginate, etc.)
│   ├── auth.php            ← Session check helpers (isLoggedIn, isAdmin)
│   ├── header.php          ← HTML <head> + top navigation bar (included in store)
│   └── footer.php          ← HTML footer + JS includes
│
└── store/                  ← Customer-facing storefront pages
    ├── index.php           ← Homepage (featured / latest books)
    ├── books.php           ← Book listing with category filter + pagination
    ├── book.php            ← Single book detail page
    ├── cart.php            ← Shopping cart page
    ├── checkout.php        ← Checkout form (address, payment method)
    ├── order_confirm.php   ← Order confirmation / thank-you page
    ├── login.php           ← Customer login form
    ├── register.php        ← Customer registration form
    ├── profile.php         ← Customer account + order history
    └── search.php          ← Search results page
```

---

## Database Schema

The `database/bookshop.sql` file creates and seeds the following tables:

### `users`
| Column        | Type           | Notes                              |
|---------------|----------------|------------------------------------|
| `id`          | INT PK AUTO    | Primary key                        |
| `name`        | VARCHAR(100)   | Full name                          |
| `email`       | VARCHAR(150)   | Unique, used as login username     |
| `password`    | VARCHAR(255)   | Stored as `password_hash()` bcrypt |
| `role`        | ENUM('user','admin') | Determines access level       |
| `created_at`  | TIMESTAMP      | Auto-set on insert                 |

### `categories`
| Column  | Type         | Notes          |
|---------|--------------|----------------|
| `id`    | INT PK AUTO  |                |
| `name`  | VARCHAR(100) | e.g. "Fiction" |
| `slug`  | VARCHAR(100) | URL-friendly   |

### `books`
| Column          | Type           | Notes                        |
|-----------------|----------------|------------------------------|
| `id`            | INT PK AUTO    |                              |
| `title`         | VARCHAR(200)   |                              |
| `author`        | VARCHAR(150)   |                              |
| `category_id`   | INT FK         | → `categories.id`            |
| `description`   | TEXT           |                              |
| `price`         | DECIMAL(10,2)  |                              |
| `stock`         | INT            | Inventory count              |
| `cover_image`   | VARCHAR(255)   | Relative path in `assets/images/covers/` |
| `isbn`          | VARCHAR(20)    | Unique                       |
| `created_at`    | TIMESTAMP      |                              |

### `cart`
| Column      | Type        | Notes                         |
|-------------|-------------|-------------------------------|
| `id`        | INT PK AUTO |                               |
| `user_id`   | INT FK      | → `users.id`                  |
| `book_id`   | INT FK      | → `books.id`                  |
| `quantity`  | INT         | Default 1                     |
| `added_at`  | TIMESTAMP   |                               |

### `orders`
| Column          | Type                                        | Notes                |
|-----------------|---------------------------------------------|----------------------|
| `id`            | INT PK AUTO                                 |                      |
| `user_id`       | INT FK                                      | → `users.id`         |
| `total_amount`  | DECIMAL(10,2)                               |                      |
| `status`        | ENUM('pending','processing','shipped','delivered','cancelled') |  |
| `address`       | TEXT                                        | Shipping address     |
| `created_at`    | TIMESTAMP                                   |                      |

### `order_items`
| Column       | Type          | Notes             |
|--------------|---------------|-------------------|
| `id`         | INT PK AUTO   |                   |
| `order_id`   | INT FK        | → `orders.id`     |
| `book_id`    | INT FK        | → `books.id`      |
| `quantity`   | INT           |                   |
| `unit_price` | DECIMAL(10,2) | Price at purchase |

---

## Backend — Detailed Explanation

### 1. Configuration & Connection (`includes/`)

**`includes/config.php`** defines all constants:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bookshop');
define('SITE_URL', 'http://localhost/AB-Book-Shop');
define('UPLOAD_DIR', __DIR__ . '/../assets/images/covers/');
```

**`includes/db.php`** creates a single shared MySQLi connection:
```php
require_once 'config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
```

Every PHP page includes `db.php` at the top to get `$conn`.

**`includes/auth.php`** — session guard helpers:
```php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /store/login.php');
        exit;
    }
}
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /store/login.php');
        exit;
    }
}
```

**`includes/functions.php`** — helper utilities:
```php
function sanitize($conn, $input) {
    return $conn->real_escape_string(trim(htmlspecialchars($input)));
}
function paginate($total, $perPage, $page) { /* returns offset + page count */ }
function formatPrice($price) {
    return '$' . number_format($price, 2);
}
function uploadCover($file) { /* moves uploaded image, returns filename */ }
```

---

### 2. Store — Customer Storefront (`store/`)

**`store/index.php`** — Homepage:
- Queries 8 most recently added books
- Queries all categories for the nav bar
- Renders book cards with cover image, title, author, price

**`store/books.php`** — Listing page:
- Accepts `?category=slug` and `?page=N` GET params
- Builds a filtered SQL query with LIMIT/OFFSET for pagination
- Renders book grid with "Add to Cart" buttons

**`store/book.php`** — Detail page:
- Accepts `?id=N`
- Fetches full book record by ID
- Shows description, ISBN, stock count
- "Add to Cart" form POSTs to `api/cart.php`

**`store/login.php` / `store/register.php`**:
- Registration: validates email uniqueness, hashes password with `password_hash()` using `PASSWORD_BCRYPT`
- Login: fetches user by email, verifies with `password_verify()`, stores `user_id` and `role` in `$_SESSION`

**`store/cart.php`**:
- Reads cart from DB using `$_SESSION['user_id']`
- Shows items, quantities, subtotals, grand total
- "Remove" triggers AJAX DELETE to `api/cart.php`

**`store/checkout.php`**:
- Requires login (redirects otherwise)
- Collects shipping address via form
- On POST: creates `orders` record, creates `order_items` rows, clears cart, decrements `books.stock`

---

### 3. API Layer (`api/`)

The `api/` directory provides JSON endpoints consumed by JavaScript on the store pages. Responses always follow:
```json
{ "success": true, "data": { ... } }
// or
{ "success": false, "message": "Error description" }
```

**`api/books.php`** (GET):
- Params: `?search=`, `?category_id=`, `?sort=price_asc|price_desc|newest`
- Returns paginated JSON array of book objects

**`api/cart.php`**:
- `POST` → add book to cart (body: `book_id`, `quantity`)
- `DELETE` → remove item (body: `cart_id`)
- `GET` → return current cart contents for session user

**`api/orders.php`** (POST):
- Validates cart is not empty
- Wraps order creation in a **MySQL transaction** (`BEGIN` / `COMMIT` / `ROLLBACK`)
- Decrements stock atomically

**`api/auth.php`**:
- `POST /api/auth.php?action=login` — JSON login
- `POST /api/auth.php?action=register` — JSON register
- `POST /api/auth.php?action=logout` — destroys session

---

### 4. Admin Panel (`admin/`)

Every admin page starts with:
```php
session_start();
require_once '../includes/auth.php';
requireAdmin();
```

**`admin/index.php`** — Dashboard:
- Shows total books, total orders, total users, revenue
- Recent orders table

**`admin/add_book.php`**:
- Validates form fields server-side
- Handles file upload: validates MIME type (image/jpeg, image/png, image/webp), generates unique filename with `uniqid()`, moves to `assets/images/covers/`
- Inserts book record

**`admin/orders.php`**:
- Lists all orders with customer name, amount, status
- Dropdown to change order status → POSTs back to same page
- Updates `orders.status` field

---

### 5. Session Management

PHP sessions are used for both customer login and admin login. The session stores:
```php
$_SESSION['user_id']  // int
$_SESSION['name']     // string
$_SESSION['role']     // 'user' | 'admin'
```

Sessions are started with `session_start()` at the top of every page that needs them. The admin panel checks `$_SESSION['role'] === 'admin'`; customer pages check `isset($_SESSION['user_id'])`.

---

### 6. Security Practices in the Code

| Threat | Mitigation Used |
|--------|----------------|
| SQL Injection | `$conn->real_escape_string()` on all user inputs |
| XSS | `htmlspecialchars()` on all output |
| Password storage | `password_hash()` / `password_verify()` (bcrypt) |
| File upload abuse | MIME-type whitelist check before moving uploaded files |
| Unauthorized admin access | `requireAdmin()` guard on every admin page |

---

## Tech Stack

| Layer         | Technology                       |
|---------------|----------------------------------|
| Web Server    | Apache (via XAMPP/WAMP)          |
| Backend       | PHP 7.4+                         |
| Database      | MySQL 5.7+ / MariaDB             |
| Frontend CSS  | Custom CSS (hand-written, 16.2%) |
| Frontend JS   | Vanilla JS / jQuery AJAX (0.7%)  |
| Image storage | Local filesystem (`assets/images/covers/`) |

---

## Installation & Setup

### Requirements
- XAMPP (or WAMP / any Apache + PHP + MySQL stack)
- PHP 7.4 or higher
- MySQL 5.7 / MariaDB 10+
- Web browser

### Steps

```bash
# 1. Clone the repo into your XAMPP htdocs
cd C:/xampp/htdocs          # Windows
# cd /Applications/XAMPP/htdocs  # macOS

git clone https://github.com/clash50x/AB-Book-Shop.git

# 2. Start Apache and MySQL from the XAMPP Control Panel

# 3. Create the database and import schema
# Open http://localhost/phpmyadmin
# Create a database named: bookshop
# Import: database/bookshop.sql

# 4. Update config if needed
# Edit includes/config.php and set DB_USER / DB_PASS to match your XAMPP setup
# (default XAMPP: user = root, pass = empty)

# 5. Visit the site
# http://localhost/AB-Book-Shop/
# → automatically redirects to /AB-Book-Shop/store/index.php
```

### Default Admin Credentials (from seed data)
| Field    | Value            |
|----------|-----------------|
| Email    | admin@bookshop.com |
| Password | admin123        |

---

## Key Design Decisions

1. **Separate `api/` folder** — Instead of mixing AJAX handling into store pages, all JSON endpoints live in `api/`. This keeps HTML-rendering pages clean and makes the API independently testable.

2. **`includes/` shared layer** — DB connection and helper functions are isolated in `includes/` and `require_once`'d. No duplication of connection logic.

3. **No framework** — Plain PHP with procedural style, making it approachable for learners and easy to deploy with zero Composer dependencies.

4. **Redirect-on-root** — `index.php` at root does one thing: `header('Location: /store/index.php')`. The store is the real entry point; the root just provides a clean URL.

5. **Transaction on checkout** — Order placement wraps multiple INSERT statements (order + order_items + stock decrement) in a MySQL transaction to prevent partial writes.

---

## Future Improvements (Suggested)

- Add CSRF token to all POST forms
- Switch raw `real_escape_string()` calls to PDO prepared statements
- Add email notifications on order placement
- Add pagination to admin orders view
- Add image lazy-loading for book covers
- Add a discount/coupon code system
