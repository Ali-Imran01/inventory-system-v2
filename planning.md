# 📦 Inventory Management System (IMS)

**Tech Stack:** React + Laravel 12 (API) + TailwindCSS + SweetAlert2 + MySQL 8  
**Primary Focus:** Mobile-first, responsive UI, production-ready architecture

---

## 1. Project Objective

Build a **modern, mobile-responsive Inventory Management System** suitable for SMEs, warehouses, and internal company usage. The system must support real-time stock tracking, role-based access, and clean separation between frontend (React) and backend (Laravel 12 API).

---

## 2. Technology Stack (Final)

### Frontend
- React (Vite)
- TailwindCSS (mobile-first)
- SweetAlert2 (confirmation & error handling)
- Axios (API communication)
- Zustand / Context API (state management)

### Backend
- Laravel 12 (REST API only)
- Laravel Sanctum (SPA authentication)
- MySQL 8

---

## 3. Mobile-First UI Principles (MANDATORY)

- Design starts from **320px width**
- Touch-friendly buttons (min 44px height)
- Sticky bottom actions for mobile (Save / Submit)
- Tables converted to cards on mobile
- Drawer-based navigation instead of sidebar

> ❗ Desktop layout is an enhancement, not the base design.

---

## 4. User Roles & Access Control

| Role | Permissions |
|-----|------------|
| Admin | Full system access |
| Staff | Stock in / stock out |
| Viewer | Read-only access |

Implemented via Laravel Policies + Middleware.

---

## 5. Core Modules

1. Authentication
2. Product Management
3. Category & Unit Management
4. Supplier Management
5. Stock In / Stock Out
6. Inventory Tracking
7. Low Stock Alert
8. Audit Log
9. Reporting (CSV export)

---

## 6. Database Design (MySQL)

### users
- id
- name
- email
- password
- role (admin | staff | viewer)
- created_at
- updated_at

### categories
- id
- name
- description

### units
- id
- name (pcs, box, kg)

### products
- id
- sku (unique)
- name
- category_id
- unit_id
- cost_price
- sell_price
- min_stock
- created_at
- updated_at

### suppliers
- id
- name
- phone
- email
- address

### stock_movements
- id
- product_id
- type (IN | OUT)
- quantity
- reference
- user_id
- created_at

### audit_logs
- id
- user_id
- action
- payload (JSON)
- created_at

---

## 7. Stock Calculation Rule

Stock is **never stored directly** in the product table.

```
Available Stock = SUM(IN) - SUM(OUT)
```

All validation is enforced at API level.

---

## 8. Backend Architecture (Laravel 12)

```
app/
 ├── Http/Controllers/API
 ├── Http/Requests
 ├── Http/Resources
 ├── Services
 ├── Repositories
 ├── Policies
```

### Example API Endpoints

```
POST   /api/login
GET    /api/me

GET    /api/products
POST   /api/products
PUT    /api/products/{id}
DELETE /api/products/{id}

POST   /api/stock/in
POST   /api/stock/out
GET    /api/stock/history
```

---

## 9. Frontend Structure (React)

```
src/
 ├── api/
 ├── components/
 ├── pages/
 │   ├── auth/
 │   ├── dashboard/
 │   ├── products/
 │   ├── stock/
 │   └── reports/
 ├── hooks/
 ├── store/
 └── utils/
```

---

## 10. SweetAlert2 Usage Rules

- Confirmation before delete or stock-out
- Error handling from API responses
- Success feedback after actions

No native `alert()` allowed.

---

## 11. Security Requirements

- Sanctum token authentication
- Role-based middleware
- FormRequest validation
- Rate limiting on auth endpoints
- Audit logging for critical actions

---

## 12. Reporting (Phase 1)

- Stock movement by date
- Low stock list
- CSV export (admin only)

---

## 13. Development Phases

### Phase 1 (MVP)
- Auth
- Product CRUD
- Stock in / out
- Mobile dashboard

### Phase 2
- Supplier
- Low stock alerts
- Audit log

### Phase 3
- Reports
- CSV export
- Performance optimization

### Phase 4 (Enterprise Expansion)
- Multi-Warehouse / Location support
- Barcode & QR Code scanner integration
- PDF Document generation (Purchase Orders/Receipts)
- Advanced Analytics (Charts/Valuation)
- Team & Permission Management UI

---

## 14. Final Notes (Straight Talk)

- Mobile-first is **not optional**
- React + API is the correct approach
- Do not mix Blade with React
- This system is scalable and commercial-ready

---

**Status:** Planning Complete  
**Next Step:** ERD / API contract / UI wireframe

