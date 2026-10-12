# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Daftar Online** is a Laravel 12 application designed for Indonesian court working units (Satker Pengadilan Mahkamah Agung RI) to collect and manage pre-registration data for "Pengguna Lain" (other users) registering for E-Court. Public applicants submit identity and banking details, court staff review and issue E-Court credentials, and the system sends notifications via WhatsApp and custom webhooks.

## Common Commands

### Development Server
```bash
# Run server, queue listener, and Vite concurrently
composer run dev

# Or run individually:
php artisan serve
php artisan queue:listen --tries=1
npm run dev
```

### Build & Assets
```bash
npm run build       # Vite production build
npm run dev         # Vite HMR dev server
```

### Testing
```bash
php artisan test                                    # Run all tests
php artisan test --filter=ExampleTest               # Run a single test class
php artisan test tests/Feature/ExampleTest.php       # Run a specific test file
php artisan test --filter=test_method_name          # Run a single test method
```

### Code Style & Formatting
```bash
php vendor/bin/pint         # Automatically fix PHP styling issues (Laravel Pint)
php vendor/bin/pint --test  # Check code style without applying changes
```

### Database & Migrations
```bash
php artisan migrate                  # Run database migrations (SQLite by default)
php artisan db:seed                  # Seed initial admin user, settings, and default webhook
php artisan migrate:fresh --seed     # Reset and re-seed database
```

### Docker & Deployment
```bash
./deploy.sh                          # Builds Docker image and runs container on port 8002
```

## High-Level Architecture

### Core Workflow
1. **Public Intake Wizard (`/step-1`, `/step-2`, `/timeline/{hash_id}`):**
   - **Step 1:** Applicant fills identity information (`IdentityStepWizard` / `POST /api/permohonan/step-1`).
   - **Step 2:** Applicant uploads bank account details and KTP image (`BankAccountStepWizard` / `POST /api/permohonan/step-2/{identity_id}`).
   - **Timeline:** Applicant monitors application status (`TimeLineWizard` / `GET /api/permohonan/status/{identity_id}`).
   - **Search:** `/search` allows applicants to resume incomplete registrations or look up their timeline by phone number.

2. **Admin Operations (Authenticated):**
   - **Dashboard (`/dashboard`):** Monthly registration stats chart using ApexCharts.
   - **Identity Management (`/permohonan-akun`, `/identity/{hash_id}`):** List, review, download KTP documents, reject applications with reason, and delete applicant records.
   - **E-Court Account Assignment (`FormECourtAccount`):** Admin enters created E-Court username/password into `ecourt_accounts`.
   - **Webhooks & Logs (`/web-hooks`, `/webhook-logs`):** Configure dynamic webhook destinations for applicant lifecycle events and inspect execution history.
   - **Court Settings (`/settings/admin_ecourt`):** Configure Satker name and admin WhatsApp contact.

3. **REST API (`routes/api.php`):**
   - Headless API mirror for the registration steps, documented in `apispec.yaml`.

### Data Protection & Cryptography Patterns
- **Field-Level Encryption:** Sensitive applicant fields (`nomor_kependudukan` / NIK and `nomor_telepon` in `identities`, `nomor_rekening` in `bank_accounts`) are encrypted at rest using Laravel's `Crypt::encryptString` and decrypted on access.
- **Blind Indexing for Search:** Because encrypted fields cannot be searched directly in SQL, search hashes are stored in companion tables (`sensitive_identity_keys`, `sensitive_bank_account_keys`) using `hash_hmac('sha256', $value, config('app.key'))`. When querying by phone or NIK, always hash with HMAC SHA-256 before searching these tables.
- **Obfuscated Route Identifiers:** Public URLs do not expose sequential database IDs. `vinkla/hashids` encodes and decodes integer IDs. `AppServiceProvider` binds `{hash_id}` route parameters to `App\Service\HashRouteId`. Models use the `UseHashedId` trait.

### Event & Webhook Architecture
- **Events:** Key domain actions dispatch events (`IdentityCreateEvent`, `IdentityUpdateEvent`, `IdentityDeleteEvent`, `IdentityRejectedEvent`, `ECourtAccountCreatedEvent`, `EcourtAccountUpdateEvent`, `EcourtAccountDeleteEvent`).
- **Webhook Dispatchers:** `WebHookListener` and `IdentityWebHookListener` intercept these events, find active entries in `web_hooks`, replace template variables (e.g., `{NO_WA_USER}`, `{USER_ECOURT}`, `{PASS_ECOURT}`), send HTTP GET/POST requests with optional custom headers/auth tokens, and record results in `web_hook_logs`.
- **WhatsApp Integration:** `ECourtAccountCreatedListener` triggers `WhatsappClient` (configured via `WA_API_BASE_URL` in `config/services.php`) to send automated notifications to applicants when their E-Court accounts are provisioned.

### UI & Livewire Conventions
- Uses **Livewire 3** with Bootstrap 5 and Tabler Icons.
- Standard layouts are located in `resources/views/components/layouts/`:
  - `app.blade.php`: Authenticated admin dashboard with sidebar.
  - `wizard.blade.php`: Public-facing multi-step registration flow.
  - `auth.blade.php`: Login and password recovery views.
- Some wizard components (such as `SearchIdentityWizard` and `TimeLineWizard`) render UI programmatically via `Spatie\Html\Elements` in PHP rather than pure Blade files.
