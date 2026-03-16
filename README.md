# Sabil E-Commerce Platform

## Table of Contents
1. Project Overview
2. Business Idea
3. Features
4. Installation Steps
5. Database Setup
6. Test Accounts
7. Hosting Link
8. Deployment
9. Technology Stack
10. Security Features
11. Project Structure
12. Version Control
13. Contributors


## Project Overview
Sabil is an e-commerce web application developed as part of the CS2TP Team Project.  
The platform simulates a real-world online perfume retail store where customers can browse products, manage their accounts, and place orders through an intuitive web interface.

The system also provides administrative functionality that allows authorized users to manage products, inventory, and customer orders. The goal of the project is to design and deploy a functional, secure, and user-friendly online shopping system using modern web development technologies.

---

## Business Idea
Sabil is an online fragrance retail platform designed to provide customers with a smooth and aesthetically pleasing online shopping experience. The system allows users to explore a range of perfume products organized into categories, add items to a basket, and complete orders using a simulated checkout system.

The platform also includes an administrative interface which allows staff to manage inventory, monitor customer orders, and maintain product listings. The project demonstrates the implementation of core e-commerce features including authentication, product management, and database-driven transactions.

---

## Features

### Customer Features
- User registration and login system
- Secure session-based authentication
- Browse products by category
- Product search functionality
- Add items to basket
- Checkout with dummy payment system
- View order history
- Manage personal account details
- Save favourite products
- Dark / Light mode interface

### Admin Features
- Admin authentication and dashboard
- View and manage customer orders
- Manage product listings
- Update product stock levels
- Monitor inventory status

### General Features
- Responsive user interface
- Secure database interaction using prepared statements
- Structured backend architecture using PHP
- Persistent login sessions
- Hosted online for continuous access

---

## Installation Steps

1. git clone. https://github.com/JudyAlazemi/Team-30.git
2. Place the project folder inside the **XAMPP `htdocs` directory**.
3. Start **Apache** and **MySQL** using the XAMPP control panel.
4. Open **phpMyAdmin** and create the project database.
5. Import the provided SQL database file.
6. Update database credentials in:
 backend/config/db.php


---

## Database Setup

1. Open **phpMyAdmin**.
2. Create a new database: cs2team30_db
3. Import the SQL file included in the project:
4. Ensure the following tables are created:

- users
- products
- orders
- order_items
- favourites
- reviews
- newsletter_subscribers
- categories
- admins
- quiz_results
- site_reviews


These tables support the core functionality of the platform including authentication, product management, and order processing.

---

## Test Accounts

### Admin Account
Email: admin@example.com  
Password: Admin123

### Customer Account
Email: jane@s.com  
Password: 12345

These accounts allow testers to access both the customer interface and the administrative dashboard.

---

## Hosting Link

Live Website:  
http://cs2team30.cs2410-web01pvm.aston.ac.uk/index.php


---



## Deployment
The system is deployed using **Webmin / Virtualmin**, which manages the hosting environment.

Deployment involves:

- Uploading project files to the server
- Configuring database connections
- Ensuring Apache services are active
- Linking the domain to the project directory

This allows the website to run in a hosted environment outside the local development system.

---

## Technology Stack

### Frontend
- HTML – webpage structure
- CSS – layout and visual design
- JavaScript – interactive behaviour

### Backend
- PHP – server-side scripting
- Apache – web server
- phpMyAdmin – database administration
- Webmin / Virtualmin – server management

### Database
- MySQL

### Design Tools
- Figma (UI wireframes and interface design)

### Development Tools
- XAMPP
- Visual Studio Code

### Version Control
- GitHub

### Project Management
- Trello

### Deployment
- University hosting server

---

## Security Features
Basic security practices implemented in the system include:

- Password hashing using `password_hash()`
- Password verification using `password_verify()`
- Session-based authentication
- Prepared SQL statements to prevent SQL injection
- Controlled access to protected pages

These mechanisms help ensure secure handling of user credentials and data.

---

## Project Structure

Team-30/
─ assets/
─ css/ # Stylesheets for layout and design
─ js/ # JavaScript interaction scripts
─ images/ # Product and interface images
─ fonts/ # Custom fonts

─ backend/
─ config/ # Database connection and session configuration
─ controllers/ # Handles form submissions and user requests
─ models/ # Database interaction logic
─ database/ # Database scripts and structure
─ includes/ # Shared backend components


This structure improves code organisation and makes the project easier to maintain and extend.

---

## Version Control

The project source code is managed using **GitHub**.  
Git was used to track code changes, manage collaboration between team members, and maintain version history throughout the development process.

---

## Contributors

Team 30 – CS2TP

**Backend Development**
- Sara Ghareeb – Authentication system, session management, and database integration

**Frontend Development**
- Judy – Interface layout and page implementation
- Chandni – Frontend styling and responsive design
- Iman – Page development and UI components

**UI Design**
- Novejot – Figma wireframes and interface planning

**Testing & Documentation**
- Prabhjot – Testing and documentation support
- Rihad – Feature validation and testing

**Deployment & Coordination**
- Abdulwahab – Deployment assistance and project coordination

---
