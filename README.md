# GastroNova Restaurant Management System — NIBM DSE 25.3

## Technology
HTML, CSS, JavaScript, jQuery, PHP, MySQL. No Bootstrap/Tailwind/WordPress.

## Setup in XAMPP
1. Copy this `restaurant_management_system` folder into `C:\xampp\htdocs\`.
2. Start Apache and MySQL.
3. Open phpMyAdmin.
4. Import `database.sql`.
5. Visit `http://localhost/restaurant_management_system/`.
6. Login: `manager@gastronova.com` / `password`.

## Requirements covered
- Responsive professional UI
- Navigation menu
- More than 3 pages
- JavaScript validation / dynamic calculation
- jQuery events/effects
- Search and filtering
- AJAX CRUD/data operations
- PHP server-side validation
- Form processing, sessions, error handling
- MySQL CREATE/READ/UPDATE/DELETE/SEARCH/SORT
- Business rule: >Rs.5,000 = 10% discount; >Rs.10,000 = 15%
- Dashboard statistics
- CSV export
- Dark mode
- Password hashing/verification

## Important viva point
The SQL contains a bcrypt password hash. Explain that passwords are never stored as plain text. PHP `password_verify()` checks the entered password against the stored hash.

## Three-member division
Member 1: Database + PHP/API + business logic.
Member 2: Frontend HTML/CSS + responsive UI + dashboard.
Member 3: JavaScript/jQuery + AJAX + testing + CSV/dark mode.
All members should understand the complete flow before viva.
