# GlowBabe E-commerce System

A modern, full-stack e-commerce application built to provide a seamless shopping experience for beauty products. This project features a robust RESTful API backend powered by **Laravel** and a dynamic, responsive frontend built with **React Router v7 (Remix)** and **Tailwind CSS**.

## 🚀 Features

### 🛍️ Customer Experience
- **Browse & Search**: Explore products with category filtering and real-time search.
- **Shopping Cart**: Add items, manage quantities, and view total costs.
- **Secure Checkout**: Streamlined checkout process with multiple payment method placeholders (PayMongo, Card).
- **User Accounts**: Secure registration, login (with OTP support), and profile management.
- **Order History**: Track order status from "Pending" to "Completed".
- **Responsive Design**: Optimized for both desktop and mobile devices.

### 🛠️ Admin Management
- **Dashboard**: View key business metrics and quick links.
- **Product Management**: 
  - Create, edit, and delete products.
  - Manage stock levels with automatic "Out of Stock" handling.
  - Upload and manage product images.
- **Order Management**: 
  - View all customer orders.
  - Update order statuses (Pending, Processing, Shipped, Out for Delivery, Completed, Canceled).
  - View payment details and customer notes.

## 🏗️ Tech Stack

### Frontend
- **Framework**: [React Router v7](https://reactrouter.com/) (formerly Remix)
- **Language**: TypeScript
- **Styling**: [Tailwind CSS](https://tailwindcss.com/)
- **HTTP Client**: Axios
- **Icons**: Heroicons / SVG

### Backend
- **Framework**: [Laravel](https://laravel.com/)
- **Database**: PostgreSQL
- **Authentication**: Laravel Sanctum
- **API**: RESTful API architecture

## ⚙️ Installation & Setup

Follow these steps to set up the project locally.

### Prerequisites
- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL

### 1. Backend Setup (Laravel)

Navigate to the backend directory:
```bash
cd backend-laravel
```

Install PHP dependencies:
```bash
composer install
```

Copy the environment file and configure your database settings:
```bash
cp .env.example .env
# Edit .env to match your MySQL credentials
```

Generate the application key:
```bash
php artisan key:generate
```

Run migrations and seed the database (creates default admin/categories):
```bash
php artisan migrate --seed
```

Link the storage directory for images:
```bash
php artisan storage:link
```

Start the development server:
```bash
php artisan serve
```
The API will run at `http://localhost:8000`.

### 2. Frontend Setup (React Router)

Open a new terminal and navigate to the frontend directory:
```bash
cd frontend-remix/my-react-router-app
```

Install Node dependencies:
```bash
npm install
```

Start the development server:
```bash
npm run dev
```
The application will run at `http://localhost:5173`.

## 🔑 Default Credentials

**Admin Account:**
- **Email**: `ranelaesgana@gmail.com` (or check `DatabaseSeeder.php`)
- **Password**: `password`

## 📸 Screenshots

*(Add screenshots of your application here)*

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
