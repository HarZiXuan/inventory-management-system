Inventory Management System

This is a web-based Inventory Management System built with PHP, MySQL, and JavaScript. The system allows users to manage inventory, track stock levels, and process sales.

📌 Prerequisites

1. Before running this project, ensure you have the following installed:

2. Go to <https://sourceforge.net/projects/wampserver/files/> to download WampServer. Press the download latest version).

3. Download git <https://git-scm.com/downloads/win>


Web Browser (Chrome, Firefox, Edge, etc.)

🚀 Installation & Setup

Step 1: Clone the Repository

Open Command Prompt (cmd) and navigate to the www directory of WAMP:

cd C:\wamp64\www

Then, clone the repository:

git clone https://github.com/HarZiXuan/inventory-management-system.git

Step 2: Import Database

Open phpMyAdmin (http://localhost/phpmyadmin)

Create a new database named inventorymanagementsystem

Click on the database, go to the Import tab

Upload inventorymanagementsystem.sql (found in the project folder inside inventory-management-system)

Click Go to import the database

Step 3: Configure Database Connection

Open the project folder: C:\wamp64\www\inventory-management-system

Find and edit config.php (or db.php, depending on your setup)

Update the database credentials:

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventorymanagementsystem";
?>

Step 4: Start WampServer

Open WampServer and ensure it’s running (icon should be green)

Open your browser and go to:

http://localhost/inventory-management-system/Login.php

Step 5: Login Credentials

Default login credentials:

username: harzixuan

password: 1234

🔧 Troubleshooting

If http://localhost/inventory-management-system/ is not working:

Make sure WAMP is running

Check if Apache & MySQL services are started

If you see #1046 - No database selected in phpMyAdmin:

Ensure you created the database first before importing

📜 License

This project is for educational purposes only. You are free to modify and use it as needed.

Developed by HarZiXuan 🚀
