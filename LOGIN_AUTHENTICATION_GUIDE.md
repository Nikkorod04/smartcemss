# SmartCEMES - Login & Authentication Setup Guide

## ✅ System Components Implemented

### 1. Authentication Routes
- ✅ **Staff Login**: `POST /login` 
  - Email + Password authentication
  - Route name: `login`
  
- ✅ **Faculty Token Login**: `POST /faculty-login`
  - Access token authentication
  - Route name: `faculty.login`
  - Faculty must have valid, non-expired token

### 2. Controllers
- ✅ **AuthenticatedSessionController** (Staff login) - Breeze default
- ✅ **FacultyAuthController** (New - Faculty token login)
  - `store()` - Authenticate via token and create session
  - `destroy()` - Logout functionality

### 3. Models & Relationships
```
User (Director/Secretary)
├── Has One → Faculty (optional, for faculty data)
└── Many Authentication Sessions

Faculty
├── Belongs To → User
└── Has Many → ExtensionToken

ExtensionToken
├── Belongs To → Faculty
└── Fields: token (unique), expires_at, generated_by
```

### 4. Login Page Features (Resources/Views/Auth/login.blade.php)
- **Tab Navigation** with Alpine.js
- **Staff Tab**: Email + Password form, "Remember Me", "Forgot Password" link
- **Faculty Tab**: Access Token form with instructions
- **Design**: LNU Blue/Gold theme with animations
- **Responsive**: Mobile and desktop friendly

### 5. Admin Dashboard Layout
- **Sidebar Navigation** (hidden on mobile, visible on md+)
- **Top Header** with user profile and notifications
- **Role-Based Menu**: Director sees admin options (Faculty, Access Tokens, Audit Logs)
- **Animations**: Smooth transitions and fade-ins

---

## 🔐 Test Credentials

### Staff Login
```
Email:    director@smartcemes.test
Password: password123
Role:     Director (Full access)
```

```
Email:    secretary@smartcemes.test
Password: password123
Role:     Secretary (Limited access)
```

### Faculty Token Login
```
Token 1: smartcemes_token_test_001 (Expires: 1 year)
Token 2: smartcemes_token_test_002 (Expires: 6 months)
Faculty: Prof. Roberto Garcia
```

---

## 🚀 How to Test

### 1. Start Development Servers
```bash
# Terminal 1: Laravel Development Server
php artisan serve
# Access at http://localhost:8000

# Terminal 2: Vite Asset Compiler
npm run dev
# Compiles CSS/JS in real-time
```

### 2. Test Staff Login
1. Go to http://localhost:8000
2. Enter: `director@smartcemes.test` / `password123`
3. Click "Log In"
4. Should see Dashboard with full admin sidebar

### 3. Test Faculty Login
1. Go to http://localhost:8000
2. Click "I'm a Faculty" tab
3. Enter token: `smartcemes_token_test_001`
4. Click "Access as Faculty"
5. Should log in and see Dashboard

### 4. Test Logout
1. Click user profile in top-right
2. Click "Logout"
3. Should return to login page

---

## 📁 File Structure

```
routes/
├── auth.php (Added faculty login route)
└── web.php (Login as landing page)

app/Http/Controllers/Auth/
├── FacultyAuthController.php (NEW - Token-based auth)
├── AuthenticatedSessionController.php (Staff auth)
└── ...

app/Models/
├── User.php (Updated with role field)
├── Faculty.php (with relationships)
├── ExtensionToken.php (Updated timestamps)
└── ...

resources/views/
├── auth/login.blade.php (Redesigned with tabs)
├── dashboard.blade.php (New admin layout)
├── layouts/guest.blade.php (Updated styling)
└── components/
    ├── admin-layout.blade.php (Sidebar + header)
    ├── stat-card.blade.php
    ├── card.blade.php
    ├── alert.blade.php
    └── ...

resources/css/
└── app.css (Custom LNU theme)

database/seeders/
└── TestUserSeeder.php (NEW - Test credentials)
```

---

## 🎨 Design System

### Color Scheme
- **Primary Blue**: #003599 (LNU Blue)
- **Accent Gold**: #F6B800 (LNU Gold)
- **Hover Effect**: Elements turn gold on hover

### Animations
- **Page Load**: Fade-in + slight scale effect
- **Tab Switch**: Smooth transitions between tabs
- **Sidebar**: Slide-in animation
- **Header**: Slide-in from right

### Components
- **btn-primary**: Blue buttons for main actions
- **btn-secondary**: Gold buttons for secondary actions
- **card**: Reusable card containers with shadow
- **stat-card**: KPI cards with icons and trends

---

## 🔄 Authentication Flow

### Staff Login Flow
```
1. User submits email + password
2. AuthenticatedSessionController validates credentials
3. User session created
4. User redirected to /dashboard
5. Dashboard middleware checks auth status
6. Admin layout displays with role-based menu
```

### Faculty Token Login Flow
```
1. Faculty enters access token
2. FacultyAuthController looks up token in database
3. Checks if token exists and not expired
4. Retrieves associated Faculty → User relationship
5. Creates session for that User
6. Redirected to /dashboard
7. Can access same dashboard as staff (but sees limited menu)
```

---

## 🛡️ Security Features

- ✅ Password hashing with bcrypt
- ✅ Session token regeneration after login
- ✅ Logout invalidates session
- ✅ Token expiration tracking
- ✅ Email verification ready (migration exists)
- ✅ Middleware protection on authenticated routes

---

## ⚙️ Configuration Changes Made

### config/app.php
```php
'name' => env('APP_NAME', 'SmartCEMES'),  // Changed from 'Laravel'
```

### .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartcemesdb
DB_USERNAME=root
DB_PASSWORD=
```

---

## 📋 Next Steps

1. **Implement CRUD Operations**
   - Extension Programs management
   - Communities management
   - Beneficiaries management

2. **Set Up Role-Based Access Control**
   - Director routes/actions
   - Secretary routes/actions
   - Faculty read-only views

3. **Implement Additional Features**
   - Calendar integration (FullCalendar.js)
   - Needs Assessment uploads
   - Budget tracking
   - Attendance management

4. **Set Up AI Integration**
   - Mistral-7B API connection
   - OCR text cleaning
   - Report generation

---

## 🐛 Troubleshooting

### "Route [faculty.login] not defined" Error
- **Solution**: Routes were added to `routes/auth.php`
- Make sure to clear cache: `php artisan route:cache --clear`

### Page shows generic Laravel layout instead of custom theme
- **Solution**: Run `npm run dev` to compile CSS
- Make sure Vite server is running

### "No application encryption key has been set" Error
- **Solution**: Run `php artisan key:generate`

### Faculty token doesn't work
- **Check**:
  1. Token exists in database: `SELECT * FROM extension_tokens`
  2. Token is not expired: `expires_at > NOW()`
  3. Faculty exists: `SELECT * FROM faculties WHERE faculty_id = X`
  4. User exists: `SELECT * FROM users WHERE id = X`

---

## 📞 Support

For issues or questions about the SmartCEMES authentication system, refer to:
- System Blueprint: `SYSTEM_BLUEPRINT.txt`
- Database Structure: Migration files in `database/migrations/`
- Model Relationships: Model files in `app/Models/`
