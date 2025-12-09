OJT Management Portal

This is a school project that uses two back-end languages: PHP and Node.js. The system manages internship processes for different user roles such as Admin, Adviser, Supervisor, and Intern.

Features
- Login system with different user roles
- Interns can submit work hours
- Automatic calculation of hours completed and remaining
- Advisers and supervisors can check requirements and progress
- Admin can manage programs, interns, and other records
- Displays worktrack history
- Internship automatically becomes "Completed" when hours remaining reaches zero

Technologies Used
Server Side (PHP):
- PHP
- MySQL database
- Apache (via WAMP/XAMPP)

Client Side (Node.js):
- Node.js and Express
- EJS templates
- MySQL2
- HTML and CSS

Project Structure
web-systems-development/
  ClientSide/
    public/
    views/
    index.js
    login.js
  ServerSide/
    html/
    css/
    img/
    db.php
  ojt-websys.sql

Database Setup
1. Create a database named ojt_websys
2. Import ojt-websys.sql

Default User Accounts
Admin: admin / admin
Supervisor: supervisor / supervisor
Adviser: adviser / adviser
Intern 1: intern1 / intern1
Intern 2: intern2 / intern2
Intern 3: intern3 / intern3

Running the Server Side (PHP)
1. Place project folder inside C:\wamp64\www\
2. Start WAMP
3. Open: http://localhost/web-systems-development/ServerSide/html/server_index.php

Running the Client Side (Node.js)
1. cd ClientSide
2. node index.js
3. Open: http://localhost:3333

ClientSide Batch File
@echo off
title OJT ClientSide Server
cd /d "%~dp0ClientSide"
start "Node Server" cmd /k "node index.js"
timeout /t 2 >nul
start "" "http://localhost:3333"
pause

ServerSide Batch File
@echo off
title OJT ServerSide (PHP)
start "" "http://localhost/web-systems-development/ServerSide/html/server_index.php"
pause

Notes
- PHP and Node.js run separately but use the same database.
- MySQL must be running.
- Project is for academic use.