# T4ESTHETICS - Project Analysis & Documentation

**Generated:** {{ date('Y-m-d') }}  
**Project Type:** Salon/Aesthetics Management System  
**Framework:** Laravel 10.x  
**Frontend:** Vue.js 3 + Bootstrap 5

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [Architecture](#architecture)
4. [Project Structure](#project-structure)
5. [Modules](#modules)
6. [Authentication & Authorization](#authentication--authorization)
7. [Database Structure](#database-structure)
8. [API Endpoints](#api-endpoints)
9. [Frontend Architecture](#frontend-architecture)
10. [Features & Functionality](#features--functionality)
11. [Configuration](#configuration)
12. [Development Setup](#development-setup)
13. [Key Controllers](#key-controllers)
14. [Models](#models)
15. [Testing](#testing)
16. [Deployment Notes](#deployment-notes)
17. [Future Improvements](#future-improvements)

---

## Project Overview

**T4ESTHETICS** is a comprehensive salon and aesthetics management system designed to handle:
- Multi-branch salon operations
- Appointment booking and scheduling
- Customer management
- Employee/staff management
- Service and product catalog
- Financial management (tax, commissions, earnings)
- Reporting and analytics
- Multi-language support

**System Type:** SaaS (Software as a Service)  
**Base Framework:** Laravel Starter Kit (nasirkhan/laravel-starter)  
**UI Framework:** Hope UI Pro Design System

---

## Technology Stack

### Backend
- **PHP:** ^8.0.2
- **Laravel:** ^10.10
- **Database:** MySQL/MariaDB (configurable)
- **Authentication:** Laravel Sanctum (API), Laravel Breeze (Web)
- **Module System:** nwidart/laravel-modules (v10.0)

### Frontend
- **JavaScript Framework:** Vue.js 3.2.45
- **UI Library:** Bootstrap 5.3.0 + Bootstrap Vue Next
- **State Management:** Pinia 2.0
- **Form Validation:** Vee-Validate 4.5 + Yup
- **HTTP Client:** Axios 1.2
- **Build Tool:** Laravel Mix 6.0
- **CSS Preprocessor:** Sass 1.56

### Key Libraries & Packages

#### Backend Packages
- **spatie/laravel-permission** (^6.1) - Role & Permission management
- **spatie/laravel-medialibrary** (^10.15) - Media file management
- **spatie/laravel-activitylog** (^4.7) - Activity logging
- **spatie/laravel-backup** (^8.4) - Database/File backups
- **yajra/laravel-datatables-oracle** (^10.11) - DataTables integration
- **maatwebsite/excel** (^3.1) - Excel import/export
- **razorpay/razorpay** (^2.8) - Payment gateway
- **livewire/livewire** (^3.2) - Full-stack framework components
- **barryvdh/laravel-dompdf** (^2.0) - PDF generation

#### Frontend Packages
- **@fullcalendar/core** (^6.1.7) - Calendar component for bookings
- **@vueup/vue-quill** (^1.2.0) - Rich text editor
- **flatpickr** (^4.6.13) - Date/time picker
- **sweetalert2** (^11.6.13) - Beautiful alerts
- **datatables.net-bs5** (^1.11.2) - Advanced tables
- **vue-i18n** (^9.2.2) - Internationalization
- **vue3-tel-input** (^1.0.4) - Telephone input component

### Notification Services
- **laravel-notification-channels/onesignal** (^2.5) - OneSignal push notifications
- **laravel-notification-channels/webpush** (^7.1) - Web push notifications
- Firebase Cloud Messaging (FCM) support

---

## Architecture

### Modular Architecture
The application follows a **modular architecture** using `nwidart/laravel-modules`. Each module is self-contained with:
- Controllers
- Models
- Views
- Routes
- Migrations
- Resources (API)
- Services
- Events & Listeners

### Multi-Tenant SaaS
The system is designed as a **multi-tenant SaaS application** with:
- Company-based settings
- Company-based authentication
- Company-based data isolation
- Branch management within companies

### Design Patterns Used
- **MVC (Model-View-Controller)**
- **Repository Pattern** (implicit in module structure)
- **Service Providers** for dependency injection
- **Resource Controllers** for RESTful APIs
- **API Resources** for data transformation

---

## Project Structure

```
T4ESTHETICS/
├── app/                          # Core application code
│   ├── Broadcasting/            # Broadcasting channels (FCM, Webhooks)
│   ├── Console/                 # Artisan commands
│   ├── Currency/                # Currency handling facade
│   ├── DataTables/              # DataTable implementations
│   ├── Events/                  # Event classes
│   ├── Exceptions/              # Exception handlers
│   ├── Exports/                 # Excel export classes
│   ├── Helpers/                 # Helper classes
│   ├── Http/
│   │   ├── Controllers/         # Application controllers
│   │   │   ├── Auth/            # Authentication controllers
│   │   │   └── Backend/         # Admin panel controllers
│   │   ├── Kernel.php           # HTTP kernel
│   │   ├── Livewire/            # Livewire components
│   │   ├── Middleware/          # Custom middleware
│   │   ├── Requests/            # Form request validators
│   │   └── Resources/           # API resource transformers
│   ├── Jobs/                    # Queue jobs
│   ├── Listeners/               # Event listeners
│   ├── Mail/                    # Email templates
│   ├── Models/                  # Eloquent models
│   ├── Notifications/           # Notification classes
│   ├── Providers/               # Service providers
│   ├── Trait/                   # Reusable traits
│   └── View/                    # View composers
├── bootstrap/                    # Bootstrap files
├── config/                       # Configuration files
├── database/
│   ├── factories/               # Model factories
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── Modules/                      # Modular packages
│   ├── Booking/                 # Booking management
│   ├── Service/                 # Service catalog
│   ├── Customer/                # Customer management
│   ├── Employee/                # Staff management
│   ├── Product/                 # Product inventory
│   └── [29 other modules...]
├── public/                       # Public assets
│   ├── css/                     # Compiled CSS
│   ├── js/                      # Compiled JavaScript
│   ├── images/                  # Static images
│   └── modules/                 # Module public assets
├── resources/
│   ├── css/                     # Source CSS
│   ├── js/                      # Vue.js source files
│   ├── sass/                    # Sass source files
│   └── views/                   # Blade templates
├── routes/
│   ├── api.php                  # API routes
│   ├── web.php                  # Web routes
│   ├── auth.php                 # Authentication routes
│   └── channels.php             # Broadcasting channels
├── storage/                      # Storage directory
├── tests/                        # Test files
└── vendor/                       # Composer dependencies
```

---

## Modules

The application has **32 active modules** (as per `modules_statuses.json`):

### Core Business Modules

1. **Booking** - Appointment scheduling and management
2. **Service** - Service catalog with categories
3. **Customer** - Customer profiles and management
4. **Employee** - Staff/employee management
5. **QuickBooking** - Quick booking interface
6. **Subscriptions** - Subscription packages
7. **Package** - Service packages

### Product & Inventory Modules

8. **Product** - Product inventory management
9. **Logistic** - Shipping and logistics
10. **Location** - Location management (countries, states, cities)
11. **Tag** - Tagging system

### Financial Modules

12. **Tax** - Tax management
13. **Commission** - Commission calculation
14. **Earning** - Staff earnings tracking
15. **Tip** - Tip management

### Configuration Modules

16. **Branch** - Multi-branch management
17. **Currency** - Currency management
18. **Language** - Multi-language support
19. **Constant** - System constants
20. **Holiday** - Holiday management
21. **BussinessHour** - Business hours configuration
22. **CustomField** - Custom field management

### Content Management Modules

23. **Page** - Static pages
24. **Slider** - Banner/slider management
25. **Promotion** - Promotional offers
26. **MenuBuilder** - Dynamic menu builder

### System Modules

27. **NotificationTemplate** - Email/SMS templates
28. **LikeModule** - Like/favorite functionality
29. **World** - World data (countries, etc.)
30. **Installer** - Setup wizard module

### Module Status
All modules are currently **enabled** (status: `true` in `modules_statuses.json`)

---

## Authentication & Authorization

### Authentication Methods

1. **Web Authentication**
   - Standard email/password login
   - Email verification
   - Password reset
   - Social login (via Laravel Socialite)

2. **API Authentication**
   - Laravel Sanctum token-based authentication
   - Token generation on login
   - Token revocation on logout

3. **Multi-Role System**
   - Admin
   - Employee
   - Customer
   - Custom roles via Spatie Permission

### Authorization

- **Role-Based Access Control (RBAC)** using `spatie/laravel-permission`
- Permissions assigned to roles
- Users can have multiple roles
- Middleware for permission checking
- Route-level permission protection

### Key Files
- `app/Http/Controllers/Auth/` - Authentication controllers
- `app/Http/Middleware/` - Auth middleware
- `routes/auth.php` - Auth routes
- `config/permission.php` - Permission configuration

---

## Database Structure

### Core Tables

- **users** - User accounts
- **roles** - User roles
- **permissions** - System permissions
- **model_has_roles** - User-role assignments
- **model_has_permissions** - Direct user permissions
- **branches** - Branch/location data
- **settings** - System settings (JSON storage)

### Module Tables
Each module typically creates its own tables:
- `bookings`, `booking_items`
- `services`, `service_categories`
- `customers`, `customer_addresses`
- `employees`, `employee_branches`
- `products`, `product_variations`
- `orders`, `order_items`
- And many more...

### Relationships
- **Polymorphic Relations** - Used for media attachments
- **Multi-tenancy** - Company/branch scoping
- **Soft Deletes** - Many tables support soft deletion

---

## API Endpoints

### Authentication API (`/api/`)
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `POST /api/social-login` - Social media login
- `POST /api/forgot-password` - Password reset request
- `GET /api/logout` - User logout
- `POST /api/update-profile` - Update user profile
- `POST /api/change-password` - Change password
- `POST /api/delete-account` - Delete account

### Public API
- `GET /api/branch-list` - List all branches
- `GET /api/user-detail` - Get user details
- `GET /api/dashboard-detail` - Dashboard data
- `GET /api/branch-configuration` - Branch config
- `GET /api/branch-detail` - Branch details
- `GET /api/branch-service` - Branch services
- `GET /api/branch-review` - Branch reviews
- `GET /api/branch-employee` - Branch employees
- `GET /api/branch-gallery` - Branch gallery
- `POST /api/app-configuration` - App configuration

### Protected API (Requires `auth:sanctum`)
- `POST /api/branch/assign/{id}` - Assign branch
- `GET/POST/PUT/DELETE /api/branch` - Branch CRUD
- `GET/POST/PUT/DELETE /api/user` - User CRUD
- `GET/POST/PUT/DELETE /api/setting` - Settings CRUD
- `GET/POST/PUT/DELETE /api/notification` - Notifications
- `GET /api/notification-list` - Notification list
- `GET /api/gallery-list` - Global gallery
- `GET /api/search-list` - Search functionality
- `POST /api/add-address` - Add address
- `GET /api/address-list` - List addresses
- `GET /api/remove-address` - Remove address
- `POST /api/edit-address` - Edit address
- `POST /api/verify-slot` - Verify booking slot

### Web Routes (`/app/`)
All web routes are prefixed with `/app` and require authentication:
- Dashboard, Settings, Users, Branches
- Reports (Daily Booking, Overall Booking, Payout, Staff, Order)
- Notifications, Backups
- Module management
- Role & Permission management

---

## Frontend Architecture

### Vue.js Structure

```
resources/js/
├── components/          # Reusable Vue components
├── helpers/            # Helper functions
├── store/              # Pinia stores
├── views/              # Page components
├── plugins/            # Vue plugins
├── mixins/             # Vue mixins
├── app.js              # Main entry point
└── [Module folders]/   # Module-specific Vue files
```

### State Management
- **Pinia** stores for:
  - User authentication state
  - Booking state
  - Settings state
  - UI state (modals, notifications)

### Form Handling
- **Vee-Validate** with **Yup** schemas
- Vue components for form inputs
- Validation messages in multiple languages

### UI Components
- Bootstrap 5 components
- Custom Vue components
- Hope UI Pro design system
- Font Awesome icons
- Custom SCSS styling

### Build Process
- **Laravel Mix** for asset compilation
- `npm run dev` - Development build
- `npm run prod` - Production build (minified)
- `npm run watch` - Watch mode for development

---

## Features & Functionality

### Core Features

1. **Multi-Branch Management**
   - Create and manage multiple salon branches
   - Branch-specific settings
   - Branch employee assignment
   - Branch gallery images
   - Branch-specific business hours

2. **Appointment Booking**
   - Calendar-based booking system
   - Slot availability checking
   - Service selection
   - Employee assignment
   - Booking status management
   - Quick booking option

3. **Customer Management**
   - Customer profiles
   - Address management
   - Booking history
   - Pre-consultation forms
   - Consent forms (PDF generation)
   - Customer packages/subscriptions

4. **Service Management**
   - Service catalog
   - Category/subcategory organization
   - Service pricing
   - Duration settings
   - Service images
   - Custom fields support

5. **Staff/Employee Management**
   - Employee profiles
   - Branch assignment
   - Service assignment
   - Commission settings
   - Earnings tracking
   - Payout reports

6. **Product Management**
   - Product catalog
   - Inventory tracking
   - Product variations
   - Brand management
   - Stock management
   - Product orders

7. **Financial Management**
   - Tax configuration
   - Commission calculations
   - Staff earnings
   - Tip management
   - Payment processing (Razorpay)
   - Financial reports

8. **Reporting**
   - Daily booking reports
   - Overall booking reports
   - Payout reports
   - Staff reports
   - Order reports
   - Export to Excel

9. **Notifications**
   - Email notifications
   - SMS notifications
   - Push notifications (OneSignal, FCM)
   - In-app notifications
   - Notification templates

10. **Settings & Configuration**
    - System settings
    - Company settings
    - Branch settings
    - Module management
    - Language management
    - Currency management

### Advanced Features

- **Multi-language Support** (English, Arabic, German, Greek, Spanish, French)
- **Multi-currency Support**
- **Backup System** (Database & Files)
- **Activity Logging**
- **File Manager** (Media Library)
- **Menu Builder** (Dynamic menus)
- **Page Builder** (Static pages)
- **Promotion System** (Discounts, offers)
- **Subscription Packages**
- **Review/Rating System** (Like module)
- **Holiday Management**
- **Business Hours Configuration**
- **Custom Fields** for entities
- **Webhook Support** (Incoming & Outgoing)

---

## Configuration

### Environment Variables (`.env`)

Key configuration options:
```
APP_NAME=T4ESTHETICS
APP_ENV=local|production
APP_DEBUG=true|false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=salon
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log|pusher|redis
CACHE_DRIVER=file|redis
QUEUE_CONNECTION=sync|redis|database

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=

RAZORPAY_KEY=
RAZORPAY_SECRET=

ONESIGNAL_APP_ID=
ONESIGNAL_REST_API_KEY=

FCM_SERVER_KEY=
```

### Key Configuration Files

- `config/app.php` - Application settings
- `config/auth.php` - Authentication config
- `config/permission.php` - Permission settings
- `config/database.php` - Database config
- `config/filesystems.php` - File storage
- `config/mail.php` - Email settings
- `config/constant.php` - System constants
- `config/setting_fields.php` - Settings UI definition
- `modules_statuses.json` - Module enable/disable status

---

## Development Setup

### Prerequisites
- PHP 8.0.2 or higher
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Web server (Apache/Nginx) or PHP built-in server

### Installation Steps

1. **Clone Repository**
   ```bash
   git clone [repository-url]
   cd T4ESTHETICS
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**
   Edit `.env` file:
   ```
   DB_DATABASE=salon
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate:fresh --seed
   # OR separately:
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets**
   ```bash
   npm run dev        # Development
   npm run prod       # Production
   npm run watch      # Watch mode
   ```

7. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

8. **Run Application**
   ```bash
   php artisan serve
   ```

9. **Access Application**
   - Web: `http://localhost:8000`
   - Installer: `http://localhost:8000/installer` (if available)

### Development Commands

```bash
# Clear caches
composer clear-all              # Clears all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Code Style
./vendor/bin/pint              # Laravel Pint (code fixer)
composer fix-cs                # PHP CS Fixer

# IDE Helper
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta

# Module Commands
php artisan module:build [ModuleName]
php artisan module:make [ModuleName]
```

---

## Key Controllers

### Authentication Controllers
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` - Login/logout
- `app/Http/Controllers/Auth/RegisteredUserController.php` - Registration
- `app/Http/Controllers/Auth/API/AuthController.php` - API authentication

### Backend Controllers
- `app/Http/Controllers/Backend/BackendController.php` - Dashboard
- `app/Http/Controllers/Backend/BranchController.php` - Branch management
- `app/Http/Controllers/Backend/UserController.php` - User management
- `app/Http/Controllers/Backend/SettingController.php` - Settings
- `app/Http/Controllers/Backend/NotificationsController.php` - Notifications
- `app/Http/Controllers/Backend/BackupController.php` - Backup management

### System Controllers
- `app/Http/Controllers/RoleController.php` - Role management
- `app/Http/Controllers/PermissionController.php` - Permission management
- `app/Http/Controllers/ModuleController.php` - Module management
- `app/Http/Controllers/LanguageController.php` - Language switching
- `app/Http/Controllers/ReportsController.php` - Reports generation
- `app/Http/Controllers/PreconsultationController.php` - Pre-consultation forms

### Module Controllers
Each module has its own controllers in `Modules/[ModuleName]/Http/Controllers/`

---

## Models

### Core Models
- `app/Models/User.php` - User model (with roles)
- `app/Models/Branch.php` - Branch model
- `app/Models/Role.php` - Role model (Spatie)
- `app/Models/Permission.php` - Permission model (Spatie)
- `app/Models/Setting.php` - Settings model
- `app/Models/Notification.php` - Notification model
- `app/Models/Address.php` - Address model
- `app/Models/Modules.php` - Module registry

### Base Model
- `app/Models/BaseModel.php` - Base model with common functionality

### Traits
- `app/Models/Traits/HasSlug.php` - Slug generation
- `app/Models/Traits/HasHashedMediaTrait.php` - Media handling

### Module Models
Each module typically defines its models in `Modules/[ModuleName]/Entities/`

---

## Testing

### Test Structure
```
tests/
├── Browser/           # Dusk browser tests
├── Feature/           # Feature tests
└── Unit/              # Unit tests
```

### Running Tests
```bash
php artisan test              # Run all tests
php artisan dusk              # Run browser tests
```

### Test Configuration
- PHPUnit configuration: `phpunit.xml`
- Dusk configuration: `.env` (BROWSER_DRIVER)

---

## Deployment Notes

### Production Checklist

1. **Environment Configuration**
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Configure production database
   - Set secure `APP_KEY`

2. **Optimization**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm run prod
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

4. **Queue Workers**
   - Configure supervisor for queue workers
   - Set up cron jobs for scheduled tasks

5. **Web Server**
   - Configure Apache/Nginx
   - Set up SSL certificate
   - Configure domain

6. **Backup Strategy**
   - Configure backup schedule
   - Set up remote backup storage

### Security Considerations
- Enable HTTPS
- Use strong passwords
- Keep dependencies updated
- Regular backups
- Monitor activity logs
- Restrict file permissions

---

## Future Improvements

### Suggested Enhancements

1. **Performance**
   - Implement Redis caching
   - Database query optimization
   - Image optimization and CDN
   - API response caching

2. **Features**
   - Mobile app (React Native mentioned in README)
   - Advanced analytics dashboard
   - Customer loyalty program
   - Online payment gateway integration
   - SMS gateway integration
   - Advanced reporting with charts

3. **Code Quality**
   - Increase test coverage
   - Code documentation (PHPDoc)
   - API documentation (Swagger/OpenAPI)
   - Code review process

4. **DevOps**
   - CI/CD pipeline setup
   - Automated testing
   - Docker containerization
   - Staging environment

5. **User Experience**
   - Progressive Web App (PWA)
   - Real-time notifications
   - Advanced search functionality
   - Better mobile responsiveness

---

## Module Development Guide

### Creating a New Module

1. **Generate Module**
   ```bash
   php artisan module:make ModuleName
   ```

2. **Module Structure**
   ```
   Modules/ModuleName/
   ├── Entities/           # Models
   ├── Http/
   │   ├── Controllers/
   │   ├── Middleware/
   │   └── Requests/
   ├── Resources/
   │   ├── assets/
   │   │   ├── js/
   │   │   └── sass/
   │   └── views/
   ├── Routes/
   │   ├── api.php
   │   └── web.php
   ├── Database/
   │   ├── migrations/
   │   └── seeders/
   ├── Providers/
   │   └── ModuleServiceProvider.php
   └── module.json
   ```

3. **Register Module**
   - Update `modules_statuses.json`
   - Run `php artisan module:enable ModuleName`

---

## API Documentation Notes

### Authentication Header
```
Authorization: Bearer {token}
```

### Response Format
```json
{
  "status": true|false,
  "message": "Success message",
  "data": {...}
}
```

### Error Format
```json
{
  "status": false,
  "message": "Error message",
  "errors": {
    "field": ["Error detail"]
  }
}
```

---

## Translation System

### PHP/Blade Files
```php
__('key')                    // Simple translation
__('module.key')             // Module-specific
trans('key')                 // Alternative
```

### Vue Files
```javascript
$t('key')                    // Simple translation
$t('module.key')             // Module-specific
```

### Language Files
Located in `lang/{locale}/`:
- `menu.php` - Menu translations
- `messages.php` - General messages
- `validation.php` - Validation messages
- Module-specific files

Supported languages:
- English (`en`)
- Arabic (`ar`)
- German (`de`)
- Greek (`el`)
- Spanish (`es`)
- French (`fr`)

---

## Important Notes

### Code Style
- Use Laravel Pint for code formatting
- Follow PSR-12 coding standards
- Write meaningful commit messages

### Git Hooks
- Husky configured for pre-commit hooks
- Can run tests before push

### Module Dependencies
- Modules can depend on each other
- Check `module.json` for dependencies

### Database Migrations
- Each module has its own migrations
- Run `php artisan migrate` to apply all

### Asset Compilation
- Module assets compiled with main app
- Use Laravel Mix for processing

---

## Support & Resources

### Documentation Links
- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Vue.js Documentation](https://vuejs.org/)
- [Laravel Modules Documentation](https://docs.laravelmodules.com/)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)

### Base Project
- Forked from: `nasirkhan/laravel-starter`
- Design System: Hope UI Pro

---

## Changelog Template

When making modifications, document them here:

### [Date] - [Version/Change Description]
- **Modified:** [File/Module]
- **Change:** [Description]
- **Reason:** [Why the change was made]
- **Impact:** [What this affects]

---

**End of Documentation**

*This document should be updated regularly as the project evolves.*

