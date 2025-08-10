# 🎬 CineFell – Full-Stack Movie Mood Recommendation App

**CineFell** is a full-featured, multilingual movie database and recommendation app that lets users explore movies based on **mood**, **genre**, and **language**.

It includes user registration/login, favorites, and an admin dashboard. Built using PHP, MySQL, HTML, CSS, and JavaScript and runs on a local server using **XAMPP**.

---

## 🌟 Features

- 😄 Mood-based movie filtering (Happy, Sad, Romantic, Scared, etc.)
- 🔐 User authentication (Register/Login/Logout)
- 💾 Save movies to favorites
- 🛠 Admin panel for managing moods, movies, and users
- 🖼 Poster images and trailers embedded
- 💬 Modular code with includes for header/footer/config
- 📱 Responsive layout using HTML, CSS, and JS

---

## 💻 Tech Stack

| Layer      | Technology                  |
|------------|-----------------------------|
| Frontend   | HTML, CSS, JavaScript       |
| Backend    | PHP                         |
| Database   | MySQL (phpMyAdmin via XAMPP)|
| Server     | Apache via XAMPP            |

---

## 🗂 Project Structure

cinefell/
├── admin/ # Admin panel for managing users, movies, moods
│ ├── index.php
│ ├── logout.php
│ ├── moods.php
│ ├── movies.php
│ └── users.php
│
├── assets/ # Static assets (CSS, JS, images)
│ ├── css/
│ │ ├── admin.css
│ │ └── style.css
│ ├── images/
│ └── js/
│ └── script.js
│
├── includes/ # Reusable PHP includes
│ ├── auth.php
│ ├── config.php
│ ├── footer.php
│ └── header.php
│
├── sql/
│ └── cinefell.sql # Database schema export
│
├── .htaccess # Apache config (for URL rewriting or security)
├── favourites.php # User favorites logic
├── index.php # Homepage / landing page
├── login.php # Login page
├── logout.php # Logout logic
├── mood-selection.php # Mood filter UI
├── profile.php # User profile
├── recommendation.php # Recommendations logic
├── register.php # User registration
└── toggle-favourite.php # AJAX toggle for saving/removing favorites

### 🗃 Importing the Database

1. Open phpMyAdmin (via XAMPP): [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Create a new database, e.g., `cinefell`
3. Go to the "Import" tab
4. Choose the file: `sql/cinefell.sql`
5. Click "Go" to import all tables and data
