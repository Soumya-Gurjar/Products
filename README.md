# Laravel Admin Products & Cart API

This project is a Laravel-based application that includes an Admin Panel for product management and REST APIs for customer cart and checkout functionality using Laravel Sanctum.

---

## Tech Stack
- Laravel 10/11
- PHP 8.2
- MySQL
- Laravel Sanctum (API Authentication)

---

## Setup Instructions

1. Clone the Repository
```bash
git clone https://github.com/Soumya-Gurjar/Products.git
cd backend

2. Install Dependencies
composer install

3. Environment Setup
cp .env.example .env
php artisan key:generate
Update database credentials in the .env file.

4. Run Migrations & Seeders
php artisan migrate:fresh --seed

5. Start the Application
php artisan serve
Application will be available at:http://127.0.0.1:8000

Admin Login (Seeded)

Email: admin@example.com

Password: password

Admin Login URL:

http://127.0.0.1:8000/admin/login

API Authentication (Sanctum)

Users must register or login to obtain an API token.

All protected APIs require Bearer Token authentication.

API Endpoints
Auth APIs
Method	Endpoint
POST	/api/register
POST	/api/login
POST	/api/logout
Cart APIs (Authenticated)
Method	Endpoint
POST	/api/cart/items
GET	/api/cart
PATCH	/api/cart/items/{product_id}
DELETE	/api/cart/items/{product_id}
POST	/api/cart/checkout
API Testing (Curl Examples)
Register
curl -X POST http://127.0.0.1:8000/api/register \
-H "Content-Type: application/json" \
-d '{"name":"Test User","email":"test@example.com","password":"password"}'

Login
curl -X POST http://127.0.0.1:8000/api/login \
-H "Content-Type: application/json" \
-d '{"email":"test@example.com","password":"password"}'

Add to Cart
curl -X POST http://127.0.0.1:8000/api/cart/items \
-H "Authorization: Bearer TOKEN" \
-H "Content-Type: application/json" \
-d '{"product_id":1,"qty":2}'

View Cart
curl -X GET http://127.0.0.1:8000/api/cart \
-H "Authorization: Bearer TOKEN"

Update Cart Item
curl -X PATCH http://127.0.0.1:8000/api/cart/items/1 \
-H "Authorization: Bearer TOKEN" \
-H "Content-Type: application/json" \
-d '{"qty":5}'

Delete Cart Item
curl -X DELETE http://127.0.0.1:8000/api/cart/items/1 \
-H "Authorization: Bearer TOKEN"

Checkout
curl -X POST http://127.0.0.1:8000/api/cart/checkout \
-H "Authorization: Bearer TOKEN"

Logout
curl -X POST http://127.0.0.1:8000/api/logout \
-H "Authorization: Bearer TOKEN"

Testing

Feature tests are implemented for critical flows:

Add to cart merges duplicate items correctly

Checkout fails when stock is insufficient and does not deduct stock or clear cart

Run tests using:

php artisan test

Key Features

Admin authentication and product management (CRUD)

Secure customer APIs using Sanctum

Cart module with quantity merge logic

Checkout with database transaction and stock validation

Feature tests for cart and checkout flows
