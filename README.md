# TimeSheet - Time Registration Application

A comprehensive multi-tenant time registration application built with Laravel for self-employed professionals.

## Features

### Multi-Tenancy
- Complete tenant isolation
- First user becomes tenant admin
- Invite team members to your organization

### User Management
- **Regular Users**: Can create and manage their own time registrations
- **Tenant Admins**: Can manage clients, projects, and invite users

### Core Functionality
- **Time Registrations**: Simple date + duration tracking (no start/end times)
- **Client Management**: Store client information (admin only)
- **Project Management**: Projects linked to clients with:
  - Status tracking (active/inactive/completed)
  - Start and end dates for availability control
  - Hourly rates for automatic revenue calculation
- **Timesheets**: View and print timesheets per client
  - Filter by day, week, or month
  - Automatic revenue calculation
  - Professional print layout

### Technical Features
- Modern Bootstrap UI
- Server-side rendered Blade templates (no Vue/React)
- Comprehensive authorization policies
- Global scopes for automatic tenant filtering
- Responsive design for mobile and desktop

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/PostgreSQL
- Node.js & NPM (for asset compilation)

### Setup Steps

1. **Install Dependencies**
```bash
composer install
npm install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Configuration**
Edit `.env` and configure your database:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=timesheet
DB_USERNAME=root
DB_PASSWORD=
```

4. **Run Migrations**
```bash
php artisan migrate
```

5. **Build Assets** (optional)
```bash
npm run build
```

6. **Start Development Server**
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## Application Structure

### Routes
- `/` - Landing page with features and registration
- `/login` - User login
- `/register` - New account registration (creates tenant)
- `/app/*` - Main application (requires authentication)

### Main Application Routes (`/app`)
**All Users:**
- `/app/dashboard` - Dashboard with statistics
- `/app/registrations` - Manage time registrations
- `/app/timesheets` - View and print timesheets

**Admin Only:**
- `/app/clients` - Manage clients
- `/app/projects` - Manage projects
- `/app/invitations` - Send team invitations

## Database Schema

### Tables
- `tenants` - Organizations
- `users` - Users with tenant association and admin flag
- `clients` - Clients (tenant-scoped)
- `projects` - Projects linked to clients (tenant-scoped)
- `time_registrations` - Time entries (user-scoped)
- `invitations` - Team invitation system

### Key Relationships
- Users belong to Tenants
- Clients belong to Tenants
- Projects belong to Clients and Tenants
- Time Registrations belong to Users, Clients, and Projects

## Usage Guide

### For New Users
1. Visit the landing page and click "Get Started Free"
2. Fill in company name, your details, and password
3. A new tenant is automatically created with you as admin
4. Start by creating clients and projects
5. Begin logging time

### For Tenant Admins
1. **Create Clients**: Add your clients with contact information
2. **Create Projects**: Link projects to clients, set rates and dates
3. **Invite Team Members**: Send invitations via email
4. **Manage Resources**: Edit/delete clients and projects as needed

### For Regular Users
1. **Log Time**: Create time registrations for active projects
2. **View History**: Browse all your time entries
3. **Generate Timesheets**: Filter by client and period, then print

### Time Registration Rules
- Projects must be "active" status
- Current date must be within project start/end dates
- Duration in hours (supports decimals, e.g., 1.5 for 1.5 hours)
- Users can only see/edit their own registrations

## Security Features

### Multi-Tenant Isolation
- Global scopes automatically filter data by tenant
- Middleware ensures users have valid tenant association
- Authorization policies verify tenant ownership

### Role-Based Access
- Admin middleware protects management routes
- Policies control CRUD operations
- Users can only access their own time registrations

## Customization

### Changing Hourly Rate Format
Edit the migration or model to change decimal precision for `hourly_rate`.

### Adding Fields
1. Create migration to add column
2. Update model's `$fillable` array
3. Add to validation rules in controllers
4. Update views to include new field

### Email Configuration
To enable invitation emails:
1. Configure mail settings in `.env`
2. Uncomment mail sending in `InvitationController`
3. Create invitation email Mailable class

## Development Notes

- Models use global scopes for automatic tenant/user filtering
- The `canRegisterTime()` method on Project checks availability
- Revenue is calculated dynamically using project hourly rates
- Bootstrap 5.3 is loaded via CDN for quick development

## Future Enhancements

Potential features to add:
- Email invitation system with acceptance flow
- User profile management
- Export timesheets to PDF/Excel
- Time registration templates
- Project budgets and tracking
- Invoice generation
- Multi-currency support
- API for mobile apps

## About Laravel


Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
