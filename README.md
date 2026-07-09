# Gorio Homestay API

## 📝 Overview
Gorio Homestay API is a robust, scalable backend platform designed for comprehensive property and booking management. It seamlessly connects Hosts, Clients, and Admins, handling everything from listing properties and managing rooms to processing bookings, generating dynamic reports, and facilitating secure authentication. 

## 🚀 Core Features
*   **Secure Authentication & Authorization:** Implements robust OAuth2 security via Laravel Passport and detailed Role-Based Access Control (RBAC) using Spatie Permission.
*   **Comprehensive Property Management:** Advanced CRUD capabilities for managing Houses, Rooms, and Host profiles.
*   **End-to-End Booking Flow:** Streamlined handling of customer Orders, Vouchers/Discounts, and booking Timelines.
*   **Multi-Platform API Endpoints:** Cleanly separated architectures for Admin, Client, and Mobile applications to ensure optimal payload delivery.
*   **Notifications & Alerts:** Integrated support for Firebase Push Notifications, Email/SMS, and real-time Telegram Bot alerts for system monitoring.
*   **OTP & Device Verification:** Secure phone number validation and trusted device management.
*   **Localization:** Multi-language support using Spatie Translatable for dynamic, globalized content delivery.
*   **Advanced Reporting:** Comprehensive dashboard analytics and automated Excel data exports for administrators.

## 🛠️ Technical Highlights
*   **Apiato Architecture (Porto Pattern):** Adopts the Porto Software Architectural Pattern, providing extreme modularity by separating concerns into `Ship` and `Container` layers. This ensures the codebase remains maintainable, scalable, and domain-driven.
*   **Service & Repository Patterns:** Strictly separates business logic (Actions/Tasks) from data access (Repositories) for highly testable and clean code.
*   **Form Requests & Data Validation:** Utilizes Laravel Form Requests for strict incoming payload validation, ensuring data integrity before reaching the controllers.
*   **API Resources (Transformers):** Employs fractal-based Transformers to format and standardize JSON responses, keeping the API contract clean and consistent.
*   **Asynchronous Queues & Jobs:** Offloads heavy tasks (like sending notifications and processing reports) to background queues, utilizing Redis for optimal performance.
*   **Database Optimization:** Leverages Eloquent Eager Loading (`with()`) to solve N+1 query problems and maintain high API responsiveness at scale.

## 📦 Tech Stack
*   **Language:** PHP 8.2+
*   **Framework:** Laravel (via Apiato Core 13.x)
*   **Database & Cache:** MySQL / PostgreSQL / SQLite, Redis, Memcached
*   **Authentication:** Laravel Passport (OAuth2)
*   **Filesystem:** Local & AWS S3 Integration
*   **Tools & Libraries:** Docker, Intervention Image, Laravel Excel, Telegram Bot SDK, Firebase (Kreait), PHP-CS-Fixer, Larastan

## 💻 Local Installation & Setup

```bash
# 1. Clone the repository and navigate to the project directory
git clone <repository-url>
cd gorio/homestay-api

# 2. Install PHP dependencies via Composer
composer install

# 3. Setup environment variables and generate app key
cp .env.example .env
php artisan key:generate

# 4. Run database migrations and seed initial data
php artisan migrate --seed

# 5. Start the local development server
php artisan serve
```
