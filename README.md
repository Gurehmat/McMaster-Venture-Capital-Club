# McMaster Venture Capital Club — Website

> Dark, premium multi-page club site built with **Vanilla HTML/CSS/JS · PHP · MySQL · Bootstrap 5**.
>
> Developed by Aleesha Abdullah, Caden Chan, Gurehmat Chahal & Matthew Kolesnik for COMPSCI 1XD3.

---

## Stack

| Layer     | Tech                                      |
|-----------|-------------------------------------------|
| Frontend  | HTML5, CSS3 (custom), Vanilla JS ES2020+  |
| UI Grid   | Bootstrap 5 (layout only — all branding overridden) |
| Backend   | PHP 8+ with PDO                           |
| Database  | MySQL 8 / MariaDB 10.6+                   |
| Fonts     | Google Fonts — Playfair Display + Inter   |

---

## Project Structure

```
/index.php              ← Main single-scroll public page
/events.php             ← All events (filterable)
/admin/
  login.php             ← Admin login (session-based)
  dashboard.php         ← Session-gated panel (Members | Events | Executives | Partners)
  logout.php
/api/
  register.php          ← Member sign-up (JSON)
  check_email.php       ← Live email duplicate check (AJAX)
  get_events.php        ← All events (JSON)
  add_event.php         ← Admin: add event + image upload
  delete_event.php      ← Admin: delete event
  get_executives.php    ← Executive team (JSON)
  get_partners.php      ← Partners + associates (JSON)
  add_partner.php       ← Admin: add partner
  delete_partner.php    ← Admin: delete partner
  add_executive.php     ← Admin: add executive
  delete_executive.php  ← Admin: delete executive
/db/
  config.php            ← PDO connection helper (edit credentials here)
  schema.sql            ← CREATE TABLE + seed data
  migrate_20260405.sql  ← Adds `events.location` and `executives.bio` to existing DBs
/assets/
  css/style.css         ← Full custom stylesheet (dark theme, gold accents)
  js/main.js            ← AJAX rendering, join form, email check
  uploads/              ← Runtime event image uploads
/tools/
  gen_hash.php          ← bcrypt hash generator (delete in production)
```

---

## Quick Start

### 1. Import the Database

```sql
SOURCE /path/to/db/schema.sql;
```

If you already created the database before these updates, also run:

```sql
SOURCE /path/to/db/migrate_20260405.sql;
```

### 2. Configure DB Credentials

Set environment variables when possible:

- `MVCC_DB_HOST`
- `MVCC_DB_PORT`
- `MVCC_DB_NAME`
- `MVCC_DB_USER`
- `MVCC_DB_PASS`

If you are running locally without environment variables, `db/config.php` falls back to the XAMPP defaults.

### 3. Fix the Admin Password

The seed SQL contains a **placeholder** hash. Generate a real bcrypt hash:

```bash
php tools/gen_hash.php yourSecurePassword
```

Copy the hash and run:

```sql
UPDATE admins SET password_hash = '<hash>' WHERE username = 'admin';
```

Delete `tools/gen_hash.php` before deploying to production.

### 4. Uploads Directory Permissions (Linux)

```bash
chmod 775 assets/uploads/
chown www-data:www-data assets/uploads/
```

### 5. Run

- **XAMPP / WAMP**: drop the folder in `htdocs/`
- **PHP built-in (dev)**: `php -S localhost:8080`

---

## Admin Panel

| URL                    | Notes                                              |
|------------------------|----------------------------------------------------|
| `/admin/login.php`     | Requires a real password hash in the `admins` table |
| `/admin/dashboard.php` | Tabs: Members · Events · Executives · Partners      |

---

## Theme

| CSS Variable    | Hex       |
|-----------------|-----------|
| `--red`         | `#7a0000` |
| `--brown`       | `#845a12` |
| `--gold`        | `#c29e61` |
| `--pastel-gold` | `#e7cb9b` |
| `--dark-grey`   | `#323131` |
| `--black`       | `#1a1a1a` |

---

## Security

- All SQL uses **PDO prepared statements**.
- Admin routes guard with `$_SESSION['admin_id']`; redirect to login if unset.
- Admin write endpoints require a CSRF token.
- Passwords stored with `password_hash(…, PASSWORD_DEFAULT)` (bcrypt).
- Session cookies use `HttpOnly` and `SameSite=Lax`.
- `db/` blocked by `.htaccess` (`Require all denied`).
- `assets/uploads/` `.htaccess` disables PHP execution inside the upload dir.
