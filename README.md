# PHP Insecure and Secure Login System

## Overview
This project demonstrates both insecure and secure implementations of a PHP login system using SQLite3.

## Branches
- **insecure** → Contains vulnerabilities like SQL Injection, XSS, and weak session management.
- **secure** → Fixes all vulnerabilities and includes security best practices.

## Installation
1. Clone the repository:
   ```sh
   git clone https://github.com/yourusername/php-secure-login.git
   cd php-secure-login

php -S localhost:8000 -t public
http://localhost:8000/login.php?error=<h1>Session ID: <script>document.write(document.cookie);</script></h1>
http://localhost:8000/login.php?error=<script>alert('Session ID: ' + document.cookie);</script>
