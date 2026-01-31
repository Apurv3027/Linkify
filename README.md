# 🔗 Linkify — URL Shortener & File Sharing Platform

[![Live Demo](https://img.shields.io/badge/Live-Demo-success)](https://linkify-master-nsmfrc.laravel.cloud/)

**Linkify** is a modern URL shortener built with **Laravel 12** and **Bootstrap 5.3.2**, featuring:

- Shorten long URLs instantly  
- Upload files (images & videos) and generate shareable short links  
- User dashboard with recent links and click tracking  
- Custom authentication (session-based)  
- Soft delete links with confirmation modal  
- Guest access for quick URL shortening

<!-- --- -->

## 🌐 Live Demo

Try the application live without any setup:

🔗 **Linkify Demo**  
👉 https://linkify-master-nsmfrc.laravel.cloud/

### What you can test
- Shorten long URLs  
- Upload images or videos and get shareable links  
- Track click counts in real time  
- Experience the responsive, modern UI

<!-- --- -->

## ✨ Features

### Public Features

- Shorten URLs without an account  
- Guest session links (stored in session)  
- Copy short link to clipboard  

### Authenticated User Features

- File uploads (image/video)  
- Link management dashboard  
- Track clicks per link  
- Delete links with custom confirmation modal (soft delete)  
- Restore deleted links (optional extension)

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

- Set up database credentials in .env

<!-- ### Update .env with your database credentials:
```bash
DB_DATABASE=linkify
DB_USERNAME=root
DB_PASSWORD=
``` -->

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

<!-- ### Visit the app at:
👉 http://127.0.0.1:8000 -->

Your app should now be accessible at http://localhost:8000.

<!-- --- -->

## 🔑 Authentication

- Custom session-based authentication
- Login stores user info in session (user_id, user_name)
- Links are tied to the logged-in user

<!-- --- -->

<!-- ## 📂 Project Structure
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
 ``` -->

<!-- --- -->

### 📂 Database Structure

#### Links Table

| Column       | Type      | Description                     |
|------------- |---------- |--------------------------------|
| id           | bigint    | Primary key                     |
| user_id      | bigint    | Reference to the user (nullable) |
| original_url | text      | Original URL (nullable if file) |
| file_path    | text    | Uploaded file path (nullable)   |
| type         | text    | 'url' or 'file'                 |
| short_code   | text    | Unique 6-character short code   |
| clicks       | integer   | Number of clicks                |
| downloads       | integer   | Number of downloads                |
| created_at   | timestamp | Creation timestamp              |
| updated_at   | timestamp | Last update timestamp           |
| deleted_at   | timestamp | Soft delete timestamp           |

<!-- --- -->

## 🛠 Usage

- Open the app in your browser
- Guest users can shorten URLs
- Authenticated users can upload files and track links
- Click “Delete” to remove a link (soft delete)
- Copy short links using the clipboard button

<!-- --- -->

## 💻 Frontend

- Responsive, modern UI built with Bootstrap 5
- Dashboard shows recent links with click count
- Line chart showing clicks over time
- Recent activity feed
- Custom modal confirmation for deleting links
- Pagination for links table

<!-- --- -->

## 📝 Notes

- File uploads: jpg, jpeg, png, mp4, mov, avi
- Max upload size: 50MB
- Guests cannot upload files

<!-- --- -->

## 📈 Future Enhancements

- 📊 Detailed analytics dashboard
- 📱 QR code generation
- 🌙 Dark mode UI
- 🧠 Admin panel
- 🗑️ Restore soft deleted links

<!-- --- -->

## 👨‍💻 Author

**Apurv Patel** \
Full-Stack Developer (Laravel | Flutter | Node.js)

This project was built as a portfolio-ready application with clean UI and scalable backend architecture.
