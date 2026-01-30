# 🔗 Linkify — URL Shortener & File Sharing Platform

Linkify is a clean, lightweight URL shortener built with **Laravel** that allows users to generate short links for long URLs or uploaded files (images & videos).  
Designed with a modern UI and simple workflow, Linkify is ideal as a **portfolio project** or foundation for a SaaS product.

<!-- --- -->

## ✨ Features

- 🔗 Shorten long URLs instantly
- 📁 Upload images & videos and generate shareable links
- 📊 Track click counts for each short link
- 🖼 Preview support for uploaded images
- 🎥 Video file linking
- 📱 Fully responsive UI (Bootstrap 5)
- ⚡ Fast & lightweight Laravel backend

<!-- --- -->

## 🛠️ Tech Stack

- **Backend:** Laravel  
- **Frontend:** Blade + Bootstrap 5  
- **Database:** MySQL  
- **Storage:** Laravel File Storage  
- **Styling:** Custom CSS + Bootstrap  
- **Security:** CSRF protection, file validation

<!-- --- -->

## 🚀 Getting Started

Follow the steps below to run the project locally.

### 1️⃣ Clone the repository
```bash
git clone https://github.com/your-username/linkify.git
cd linkify
```

### 2️⃣ Install dependencies
```bash
composer install
```

### 3️⃣ Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

### Update .env with your database credentials:
```bash
DB_DATABASE=linkify
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Run migrations
```bash
php artisan migrate
```

### 5️⃣ Create storage symlink
```bash
php artisan storage:link
```

### 6️⃣ Start the development server
```bash
php artisan serve
```

### Visit the app at:
👉 http://127.0.0.1:8000

<!-- --- -->

## 📂 Project Structure
```bash
app/
 ├── Http/Controllers/LinkController.php
 ├── Models/Link.php
routes/
 ├── web.php
resources/views/
 ├── linkify.blade.php
storage/
 ├── app/public
 ```

<!-- --- -->

## 📈 Future Enhancements

- 👤 User authentication
- 📊 Detailed analytics dashboard
- 📱 QR code generation
- 🌙 Dark mode UI
- 🧠 Admin panel

<!-- --- -->

## 👨‍💻 Author

**Apurv Patel** \
Full-Stack Developer (Laravel | Flutter | Node.js)

This project was built as a portfolio-ready application with clean UI and scalable backend architecture.
