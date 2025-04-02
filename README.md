# AxisBot Website

A comprehensive website for AxisBot with user and admin functionality.

## Project Structure

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP, MySQL
- **Authentication:** Custom PHP authentication system

## Setup Instructions

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Setup Steps

1. **Install a local development environment:**
   - XAMPP (cross-platform): [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - WAMP (Windows): [https://www.wampserver.com/](https://www.wampserver.com/)
   - MAMP (Mac): [https://www.mamp.info/](https://www.mamp.info/)

2. **Set up the project:**
   - Clone/extract the project files to your web server's document root (e.g., `htdocs` folder in XAMPP)
   - Create a MySQL database named `axisbot_db`
   - Update database credentials in `backend/config/database.php`

3. **Access the website:**
   - Start Apache and MySQL services from your development environment
   - Open your browser and navigate to:
     - `http://localhost/axisbot/` (if placed directly in the document root)
     - Or `http://localhost/path-to-project/` (if in a subdirectory)

4. **First-time setup:**
   - The first time you access the site, the database tables will be created automatically
   - Register a new user through the registration form
   - To create an admin, manually update the user's role in the database:
     ```sql
     UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
     ```

## Available Pages

### Public Pages
- `index.html` - Home page
- `about.html` - About page
- `services.html` - Services page
- `packages.html` - Packages page
- `contact.php` - Contact form page
- `login.php` - User login page
- `register.php` - User registration page

### User Dashboard
- `backend/views/user/dashboard.php` - User dashboard

### Admin Dashboard
- `backend/views/admin/dashboard.php` - Admin dashboard
- `backend/views/admin/users.php` - User management
- `backend/views/admin/messages.php` - Message management

## Key Features

1. **User Authentication:**
   - Registration, login, logout
   - Password reset functionality
   - Welcome email to new users

2. **Contact Form:**
   - Message submission for visitors
   - Admin review and management of messages

3. **User Management:**
   - Admin can view all users
   - Admin can manage user roles

4. **Responsive Design:**
   - Mobile-friendly interface
   - Modern UI with gradient backgrounds

## Known Issues and Limitations

1. Email functionality requires proper SMTP configuration
2. Social login buttons are placeholders and not functional yet

## License

Copyright © 2023 AxisBot. All Rights Reserved 