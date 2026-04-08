# SmartCEMES - Community Extension Management System

A comprehensive Laravel-based extension management system for tracking community extension programs, faculty activities, and rendered hours with director approval workflow.

## 🚀 Quick Start

### Prerequisites
- PHP 8.1+
- Composer
- MySQL 8.0+
- Node.js 16+ & npm
- Git

### Installation Steps

#### 1. Clone Repository
```bash
git clone https://github.com/Nikkorod04/smartcemss.git
cd smartcemss
```

#### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

#### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Database Configuration
Edit `.env` file and set your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartcemesdb
DB_USERNAME=root
DB_PASSWORD=
```

#### 5. Database Migration & Seeding
```bash
# Run migrations
php artisan migrate

# Seed test data (optional)
php artisan db:seed --class=TestUserSeeder
```

#### 6. Build Frontend Assets
```bash
# Development build
npm run dev

# Production build
npm run build
```

#### 7. Start Development Server
```bash
# Run Laravel development server
php artisan serve

# In another terminal, run Vite dev server (optional)
npm run dev
```

Visit `http://localhost:8000` in your browser.

---

## 📋 Test Credentials

### Director Account
- **Email**: director@smartcemes.test
- **Password**: password123

### Secretary Account
- **Email**: secretary@smartcemes.test
- **Password**: password123

---

## ✨ Core Features

### Faculty Management
- ✅ Create, read, update, delete faculty members
- ✅ Assign faculty to extension programs and activities
- ✅ Director-only access control

### Access Tokens
- ✅ Generate tokens with flexible expiration (Never, X days, Specific date)
- ✅ Copy token to clipboard
- ✅ Revoke tokens with confirmation modal
- ✅ View token expiration status and activity logs

### Rendering Hours Submission (Coming Soon)
- 🔄 Faculty submit rendered hours with attachments
- 🔄 Director approval workflow
- 🔄 Hours history and reports

---

## 📁 Project Structure

```
smartcemss/
├── app/
│   ├── Http/Controllers/FacultyController.php
│   ├── Livewire/
│   │   ├── GenerateTokenModal.php
│   │   └── RevokeTokenModal.php
│   └── Models/
│       ├── Faculty.php
│       ├── ExtensionToken.php
│       └── FacultyAvailability.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── faculties/
│   │   ├── livewire/
│   │   └── ...
│   └── css/
├── routes/
│   └── web.php
└── ...
```

---

## 🔒 Configuration

### Key Files to Update

#### `.env`
```env
APP_NAME=SmartCEMES
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartcemesdb
DB_USERNAME=root
DB_PASSWORD=
```

#### `config/app.php`
- Timezone: `'timezone' => 'Asia/Manila'`
- Locale: `'locale' => 'en'`

---

## 🗄️ Database Tables

| Table | Purpose |
|-------|---------|
| `users` | System users (Director, Secretary) |
| `faculties` | Faculty member records |
| `extension_tokens` | Access tokens for faculty |
| `faculty_availabilities` | Faculty availability calendar |
| `activities` | Extension activities/programs |
| `activity_faculty` | Faculty assignments to activities |
| `activity_log` | Activity audit log |

---

## 🛠️ Development

### Local Development Server
```bash
php artisan serve
# Server runs at http://localhost:8000
```

### Compile Assets (Development)
```bash
npm run dev
# Watches for file changes and recompiles
```

### Compile Assets (Production)
```bash
npm run build
# Optimizes CSS and JS for production
```

### Database Commands
```bash
# Run migrations
php artisan migrate

# Rollback last batch
php artisan migrate:rollback

# Reset all migrations
php artisan migrate:reset

# Seed database
php artisan db:seed

# Refresh (reset + seed)
php artisan migrate:refresh --seed
```

---

## 🧪 Testing

### Run Tests
```bash
php artisan test
```

### Check PHP Syntax
```bash
php -l app/Http/Controllers/FacultyController.php
```

---

## 📝 Activity Logging

All faculty operations are automatically logged:
- Faculty created/updated/deleted
- Tokens generated/revoked
- Status changes

View logs in `storage/logs/` or database `activity_log` table.

---

## 🚨 Troubleshooting

### Composer Issues
```bash
# Clear composer cache
composer clear-cache
composer dump-autoload
```

### Asset Issues
```bash
# Clear frontend cache
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Database Issues
```bash
# Reset database and seed
php artisan migrate:fresh --seed
```

### Permission Issues (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
chown -R $USER:$USER .
```

---

## 📞 Support

For issues, create an issue on the [GitHub repository](https://github.com/Nikkorod04/smartcemss).

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Author

**Nikko Rodriguez**  
LNU Extension Management System

---

## 📅 Version

**Version 1.0.0** - Faculty Module with Token Management  
Last Updated: April 8, 2026

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
