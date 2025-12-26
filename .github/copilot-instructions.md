# TimeSheet Application - AI Agent Instructions

## Architecture Overview

**Multi-tenant SaaS time tracking app** built with Laravel 11 + Livewire 3 + Bootstrap 5.3 (no SPA framework).

### Tenant Isolation Pattern
- **Global scopes** on all models automatically filter by `tenant_id` (see `Client.php`, `Project.php`)
- First registered user becomes tenant admin, creates tenant automatically
- Middleware stack: `auth` → `tenant` (validates tenant_id) → `tenant.admin` (admin routes only)
- TimeRegistrations use **user-scoped** global scope (users see only their own data)

### Key Models & Relationships
```
Tenant (1) → (N) Users [is_admin flag for permissions]
       (1) → (N) Clients → (N) Projects [hourly_rate, active status]
User (1) → (N) TimeRegistrations → (1) Client, (1) Project
```

## Critical Development Patterns

### Livewire Components (Primary UI Pattern)
- **Full-page components** use `->layout('layouts.app')->title('Page Title')` 
- Must have **single root element** in views (Livewire requirement)
- Example: `Dashboard.php` handles calendar with inline form modals

### Model Conventions
- All models use **UUIDs** (`HasUuids` trait) not auto-increment IDs
- SoftDeletes on Client/Project models for audit trail
- Status constants with `getStatuses()` static method (see `TimeRegistration::STATUS_*`)
- Global scopes defined in `booted()` method for automatic filtering

### Database
- SQLite-only support

## UI/UX Patterns

### Bootstrap Native Dark Mode
- Uses `data-bs-theme="dark"` attribute (NOT custom CSS classes)
- Toggle via `toggleTheme()` JavaScript, persisted in localStorage

### Calendar Implementation
- Lives in `dashboard.blade.php` (NOT separate component)
- Week-based grid with status badges (ready_to_invoice, invoiced, paid, non_paid)
- Inline styles for calendar inside single root `<div>` (Livewire constraint)

## Common Pitfalls

1. **Multi-root Livewire views** will error - keep `<style>` tags inside root `<div>`
2. **Global scopes** filter automatically - no need to `where('tenant_id')` in queries
3. **Project availability** checked via `canRegisterTime()` method (active status + date range)
4. **Route naming** convention: `app.{resource}.{action}` (e.g., `app.clients.index`)

