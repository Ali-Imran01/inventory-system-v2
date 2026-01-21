# 📦 IMS V2: Enterprise Inventory Management System

A high-fidelity, real-time Inventory Management System built with Laravel and React. Designed for speed, security, and enterprise organization.

---

## 🚀 Key Features

### 🔹 Core Inventory Engine
- **Real-time Stock Tracking**: Automatic balance calculation based on history.
- **Multi-Warehouse Support**: Manage stock across different locations.
- **Metadata Central**: Standardized Category and Unit of Measurement management.

### 🔹 Intelligence & Automation
- **Advanced BI Dashboard**: Real-time visual analytics (Category Distribution, Stock Trends, Warehouse Levels).
- **Low Stock Alerts**: Instant visual warnings for critical stock thresholds.
- **Bulk Excel Import**: Rapid data migration from legacy spreadsheets.
- **Barcode/QR Integration**: Built-in scanning support for mobile handling.

### 🔹 Security & Governance
- **Role-Based Access Control (RBAC)**: Distinct permissions for Admin, Staff, and Viewer.
- **Audit Logging**: Full transparency with records of all system actions.
- **Document Generation**: Automated PDF Stock Receipts and Inventory Valuation reports.

---

## 🛠️ Technology Stack

- **Backend**: Laravel 10 (PHP 8.2), MySQL/MariaDB
- **Frontend**: React, Vite, TailwindCSS
- **Key Packages**: 
    - `maatwebsite/excel` (Import/Export)
    - `barryvdh/laravel-dompdf` (PDF Engine)
    - `recharts` (Visual Analytics)
    - `lucide-react` (Iconography)
    - `sweetalert2` (Interactive Modals)

---

## ⚙️ Installation & Setup

### 1. Prerequisites
- PHP 8.2+ (Must enable `ext-zip` extension)
- Composer
- Node.js & NPM
- MySQL/MariaDB

### 2. Backend Setup
```bash
# Clone the repository
git clone [repository-url]
cd inventory-system-v2

# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env, then migrate
php artisan migrate --seed
```

### 3. Frontend Setup
```bash
# Install NPM packages
npm install

# Run development server
npm run dev
```

---

## 📂 Project Structure

- `app/Imports/`: Data mapping for Excel migration.
- `app/Services/AuditService.php`: Centralized logging for transparency.
- `resources/js/pages/`: Modular React components for each enterprise feature.
- `routes/api.php`: Full RESTful API documentation.

## 🔮 Future Roadmap
See the [future_enhancements.md](file:///c:/laragon/www/inventory-system-v2/future_enhancements.md) for planned features like AI forecasting and E-commerce sync.

---

## 📄 License
Custom Enterprise License. All rights reserved.
