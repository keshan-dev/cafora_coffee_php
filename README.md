# ☕ Cafora Coffee - Web Application

## 📖 Overview  

*Cafora Coffee* is a full-stack *web-based coffee shop management system* designed to streamline product showcasing, customer interaction, and administrative control.  
The system is built to provide:  
- An *intuitive shopping experience* for customers.  
- A *secure management dashboard* for administrators.  
- Scalable architecture to support future growth (online payments, order tracking, mobile app integration).  

This project was developed as part of academic and professional practice, focusing on *modern UI/UX, **security best practices, and **clean code principles*.  

---
## ✨ Key Features  

### 👥 User-Side (Frontend)
- 📦 *Product Catalog* – Browse coffee varieties with names, descriptions, prices, and availability.  
- 🔎 *Search & Filter* (planned) – Find coffee by category, type, or price range.  
- 📱 *Responsive Design* – Works seamlessly on desktop, tablet, and mobile.  
- 📝 *Contact Form* – Customers can reach out for inquiries or feedback.

### 🔐 Admin-Side (Backend)
- 🛠 *Admin Authentication* – Secure login system with role-based access.  
- 📊 *Dashboard Overview* – Quick stats of stock, issued products, and messages.  
- 🧾 *Product Management* – Add, edit, or delete coffee products.  
- 📦 *Stock & Issued Tracking* – Manage inventory without auto-deducting stock.  
- 📬 *Message Management* – View and delete customer contact messages.  

---
## 🏗 Tech Stack  

| Layer       | Technology Used |
|-------------|-----------------|
| *Frontend* | HTML5, CSS3, JavaScript (Vanilla), Google Fonts, Ionicons |
| *Backend*  | PHP (Procedural with PDO for DB interactions) |
| *Database* | MySQL (Structured schema with relational integrity) |
| *Server*   | Apache (XAMPP/WAMP/LAMP stack) |

---
## 📂 Project Structure 

cafora-coffee/
│-- admin/ # Admin dashboard & management files
│ │-- admin_dashboard.php
│ │-- products.php
│ │-- messages.php
│
│-- assets/ # Images, icons, fonts, and static files
│
│-- includes/ # Reusable PHP includes
│ │-- auth.php # Authentication & access control
│ │-- database_connection.php# Database connection (PDO)
│
│-- public/ # User-facing files
│ │-- index.php # Homepage
│ │-- products.php # Coffee product catalog
│ │-- contact.php # Contact form
│
│-- sql/ # Database scripts
│ │-- cafora.sql
│
│-- style.css # Global styles
│-- README.md # Project documentation
## ⚙ Installation & Setup  

### 🔧 Prerequisites  
Make sure you have the following installed:  
- [XAMPP / WAMP / LAMP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.x)  
- Git  

### 🚀 Steps to Run Locally  

1. *Clone the repository*  
   ```bash
   git clone https://github.com/keshan-dev/cafora_coffee_php.git
   
2.Move into your server root directory

For XAMPP: htdocs/
For WAMP: www/

3.Setup the Database

Open phpMyAdmin and create a database called coffee_shop.
Import sql/cafora.sql.

5.Start the Application

Start Apache & MySQL in your local server.
Visit: http://localhost/cafora-coffee   