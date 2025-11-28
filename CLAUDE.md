# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is the codebase for **Eikaiwa.fm** and **Nihongo.fm**, Japanese English-language podcasting and educational platforms that were originally designed in 2004. The project consists of:

1. **Main website** (root directory) - Custom-built podcasting and newsletter platform
2. **CubeCart store** (`/store` and `/store-CubeCart-6.0.12`) - E-commerce shopping cart software (version 6.0.12)
3. **Admin interface** (`/admin`) - Backend management for podcasts, users, and newsletters

## Key Technologies

- **Backend:** Procedural PHP (with some object-oriented classes)
- **Database:** MySQL accessed via `ez_sql.php` library
- **Frontend:** HTML, CSS, JavaScript
- **Shopping Cart:** CubeCart 6.0.12 (open source e-commerce)
- **Audio Streaming:** iCastCenter and Centova Cast

## Architecture and Structure

### Main Website (`/index.php`)

The main entry point acts as a simple **router**:
- Uses `$_GET['page']` parameter to determine which HTML template to include
- Browser sniffing determines which CSS file to load (Safari, Firefox, IE, Opera)
- Handles newsletter signup form submissions
- All HTML templates are in `/html` directory and included via PHP

### Database Layer

- **Library:** `ez_sql.php` (version 1.26) - custom MySQL abstraction layer
- **Connection details:** Hardcoded in `/php/ez_sql.php` (lines 17-20)
- **Access pattern:** Global `$db` object used throughout
- **Security note:** Uses deprecated `mysql_*` functions and MD5 password hashing

### Admin System (`/admin`)

Object-oriented classes for management:
- **`podcast_class.php`:** `podCast` class for managing podcast RSS feed items
  - Methods: `newItem()`, `updateItem()`, `getItems()`
  - Interacts with `podcast_item` and `podcast_channel` database tables
- **`user_admin_class.php`:** `userAdmin` class for user management
  - Methods: `addUser()`, `updateUser()`, `getUser()`, `userCount()`
  - Uses MD5 for password hashing (deprecated)

### CubeCart Store (`/store`)

- Separate e-commerce application
- Entry point: `/store/ini.inc.php` (configuration) and `/store/index.php`
- Version: 6.1.1
- Uses MVC-style architecture with classes, controllers, and modules
- Writable directories: `/cache`, `/images`, `/files`, `/backup`

### Template System

Simple include-based templating:
1. `index.php` sets variables based on `$_GET['page']`
2. Includes appropriate HTML template from `/html` directory
3. Templates can access PHP variables directly using `<? print $variable; ?>`

### Directory Layout

- `/html/` - HTML templates (home.html, about.html, faq.html, etc.)
- `/php/` - Utility classes (ez_sql.php, email_class.php, authenticate.php)
- `/admin/` - Administrative interface (podcast creator, user admin, newsletter)
- `/css/` - Browser-specific stylesheets
- `/javascript/` - Client-side JavaScript
- `/img/` - Images
- `/audio/` - Audio files
- `/store/` - CubeCart e-commerce platform
- `/cgi-bin/` - CGI PHP binaries (php52.cgi, php56.cgi, php71.cgi)

## Database Tables (Referenced in Code)

- `newsletter_mailing_list` - Email subscribers with region
- `podcast_item` - Podcast episode data
- `podcast_channel` - Podcast channel configuration
- `users` - User accounts with MD5 hashed passwords

## Common Development Patterns

### Database Queries

```php
global $db;
$result = $db->query("SELECT * FROM table WHERE id = 1");
$row = $db->get_row("SELECT * FROM table WHERE id = 1");
$var = $db->get_var("SELECT column FROM table WHERE id = 1");
$results = $db->get_results("SELECT * FROM table", ARRAY_A);
```

### Adding PHP Pages

1. Create HTML template in `/html` directory
2. Add conditional in `index.php` to handle new page parameter
3. Set `$include` variable to new template filename
4. Optionally set `$ad_page` for sidebar ads

### Security Considerations

This is legacy code with several security vulnerabilities:
- **SQL Injection:** No prepared statements, raw query construction
- **XSS:** Limited input sanitization
- **Password hashing:** Uses MD5 instead of modern algorithms
- **Deprecated functions:** `mysql_*` functions instead of MySQLi/PDO

When modifying code, be aware these issues exist but match the existing patterns unless explicitly modernizing security.

## Constants Defined

In `index.php`:
- `HTML_DIR`, `IMG_DIR`, `CSS_DIR`, `JS_DIR`, `PHP_DIR`
- `INDEX_FILE`, `PODCAST_LINK`, `STORE_URL`
- `FREE_PLAYER_URL`, `PREMIUM_PLAYER_URL`

In `store/ini.inc.php`:
- `CC_VERSION`, `CC_ROOT_DIR`, `CC_CACHE_DIR`, `CC_FILES_DIR`
- `CC_CLASSES_DIR`, `CC_INCLUDES_DIR`, `CC_LANGUAGE_DIR`

## Browser Compatibility

The site uses extensive browser sniffing to serve different CSS files and adjust layout variables for:
- Safari
- Firefox/Gecko browsers
- Opera
- Internet Explorer (default fallback)

This affects styling variables like `$cbox_height`, `$player_height`, `$signup_padding`, etc.
