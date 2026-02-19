# RBAC Workflow Management System (CodeIgniter 4)

A Role-Based Access Control (RBAC) based Workflow Management System built using CodeIgniter 4, implementing secure request handling, controlled status transitions, and activity logging.
This system allows **Users, Managers, and Admins** to manage and track workflow requests with proper authorization, logging, and status transitions.

---

## 🚀 Project Overview

This application implements a structured workflow system where:

* Users can create and manage requests.
* Managers can review and approve/reject requests.
* Admins can close/reopen requests and view full history.
* All status transitions are validated and logged.
* Role-based permissions are strictly enforced.

---

## 🛠 Built With

* PHP 8+
* CodeIgniter 4
* MySQL
* Bootstrap 5
* AJAX (for filtering & sorting)

---

## 👥 Roles & Permissions

### 👤 User

* Create request
* Edit request (only when status = Needs Clarification)
* Resubmit request
* View own requests
* Filter & sort requests

### 👨‍💼 Manager

* View submitted requests
* Approve
* Reject
* Mark as "Needs Clarification"
* Filter by category, priority & status

### 👨‍💻 Admin

* Close request
* Reopen request
* View full request history logs
* Filter by role, status & date range
* Soft delete requests

---

## 🔄 Workflow Status Flow

Submitted
│
├── Approved → Closed
├── Rejected
└── Needs Clarification → Resubmitted → Approved

All transitions are validated via `StatusTransitionModel`.

---

## 🗂 Project Structure

```
app/
├── Controllers/
│   ├── Admin.php
│   ├── Auth.php
│   ├── BaseController.php
│   ├── Dashboard.php
│   ├── RequestController.php
│
├── Models/
│   ├── RequestModel.php
│   ├── LogModel.php
│   ├── StatusTransitionModel.php
│   ├── UserModel.php
│
├── Views/
│   ├── admin/
│   ├── dashboards/
│   ├── auth/
│   ├── partials/
│   ├── layouts/
│
public/
writable/
.env
queries.sql
```

---

## 🔐 Security Features

* CSRF Protection
* Server-side validation
* Role-based route filters
* Status transition validation
* Password hashing
* Session-based authentication

---

## 🏗 System Architecture

The system follows MVC architecture provided by CodeIgniter 4:

- Controllers handle request processing and role validation.
- Models manage database interaction and business logic.
- Views render dynamic UI using Bootstrap.
- AJAX is used for asynchronous filtering and sorting.

---

## 📊 Features

* AJAX-based filtering & sorting
* Date range filtering (Admin)
* Status logs for every transition
* Resubmission tracking
* Pagination
* Flash messages
* Soft delete functionality

---

## 🗄 Database Tables

- users
- requests
- logs
- status_transitions

---

## ⚙ Installation Guide

### 1️⃣ Clone Repository

```bash
git clone https://github.com/yourusername/rbac-workflow-system.git
cd rbac-workflow-system
```

### 2️⃣ Install Dependencies

```bash
composer install
```

### 3️⃣ Setup Environment

Copy `.env` file and configure database:

```ini
database.default.hostname = localhost
database.default.database = your_database
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

### 4️⃣ Import Database

Use the provided `queries.sql` file.
Import into MySQL using **phpMyAdmin** or CLI:

```bash
mysql -u root -p your_database < queries.sql
```

### 5️⃣ Run Project

#### Using WAMP:

1. Place the project inside `C:/wamp64/www/` (or your WAMP `www` folder).
2. Start WAMP and ensure Apache & MySQL services are running.
3. Access the project in browser:

```
http://localhost/rbac_workflow/public
```

#### Using Built-in PHP Server:

```bash
php spark serve
```

---

✅ **Notes:**

* Make sure PHP version ≥ 8.0.
* Ensure `writable/` folder has proper permissions.
* Adjust `.env` DB credentials based on your setup.
