# Opvenio HR Management System

A comprehensive Human Resources Management System built with Laravel, featuring advanced security with Two-Factor Authentication (2FA).

## 🚀 Features

### Core HR Modules
- **Competency Management**: Framework and competency management
- **Training Management**: Training catalog and course management  
- **Learning Management**: Assessment categories and quiz system
- **Employee Self-Service**: Employee portal and self-service features
- **Succession Planning**: Career development and succession planning

### Security Features
- **Two-Factor Authentication (2FA)**: Enhanced security using TOTP
- **Custom 2FA Implementation**: Built with Laravel Fortify and Jetstream
- **Recovery Codes**: Backup authentication method
- **Session Management**: Secure session handling

### User Interface
- **Modern Dashboard**: Clean, responsive design with Tailwind CSS
- **Custom Navigation**: Sidebar and navbar with intuitive navigation
- **Real-time Updates**: Dynamic content updates and notifications
- **Mobile Responsive**: Works seamlessly on all devices

## 🛠️ Technology Stack

- **Framework**: Laravel 11.x
- **Authentication**: Laravel Jetstream + Fortify
- **Frontend**: Livewire, Alpine.js, Tailwind CSS
- **Database**: MySQL (multi-database architecture)
- **Icons**: Boxicons
- **Security**: Two-Factor Authentication (TOTP)

## 📋 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/ChirVan/opvenio-hr2.git
   cd opvenio-hr2
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   - Configure your database settings in `.env`
   - Run migrations:
   ```bash
   php artisan migrate
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start the application**
   ```bash
   php artisan serve
   ```

## 🔐 Two-Factor Authentication Setup

1. **Navigate to Profile**: Click Profile in the navbar
2. **Enable 2FA**: Go to Two Factor Authentication section
3. **Scan QR Code**: Use Google Authenticator or similar app
4. **Confirm Setup**: Enter verification code to complete setup
5. **Save Recovery Codes**: Store backup codes securely

## 🎯 Usage

### For Administrators
- Access comprehensive HR modules
- Manage employee competencies and training
- Monitor system security and user access
- Configure system settings and permissions

### For Employees
- Self-service portal for personal information
- Access to training materials and assessments
- View competency frameworks and gap analysis
- Manage account security settings

## 🏗️ Project Structure

```
app/
├── Modules/
│   ├── competency_management/
│   ├── learning_management/
│   ├── training_management/
│   └── succession_planning/
├── Http/Controllers/Auth/
└── Models/

resources/views/
├── auth/
├── layouts/
├── profile/
└── dashboard.blade.php
```

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍🎓 Academic Project

This is a capstone project developed as part of academic requirements, showcasing modern web development practices and enterprise-level HR system implementation.
