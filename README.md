# PHP Insecure and Secure Blog Application System

## Overview
This project demonstrates both insecure and secure implementations of a PHP Blog application system using SQLite3.

## Branches
- **insecure** → Contains vulnerabilities like SQL Injection, XSS, and weak session management.
- **secure** → Fixes all vulnerabilities and includes security best practices.

## Installation
1. **Clone the repository:**

   git clone https://github.com/yourusername/php-secure-login.git
   cd php-secure-login

2. Start the PHP server:

   php -S localhost:8000 -t public

3. Open the app in your browser:

   http://localhost:8000/index.php

# Testing done

- User Registeration (UC-1 v1.6)
- User Login (UC-2 v1.6)
- Profile View (UC-3 v1.6)

# Use cases Covered

- UC-1: Register User – Password mismatch, weak password warnings, unique username enforcement

- UC-2: Login User – Handles incorrect credentials, correct login, and session creation.

- UC-3: View Profile – Displays user role and username securely; requires active session.

# Vulnerabilities (Insecure Branch)

- SQL Injection
- Cross-Site Scription (XSS)
- CSRF
- Plaintext Password storage

# Security Implementation (Secure Branch)

- Password validation and hashing
- Secure session handling
- CSRF protection via tokens
- Output escaping for XSS prevention
- CSP and security headers