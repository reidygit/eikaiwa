# GEMINI.md

## Project Overview

This project is the codebase for the Eikaiwa.fm and Nihongo.fm websites, which appear to be podcasting and educational platforms. The codebase is written in a procedural style of PHP, with some object-oriented programming for specific functionalities like podcast creation and user administration. The site uses a simple, custom-built templating system. The core functionalities include a podcast RSS feed generator, a newsletter signup, and a user management system.

The project appears to have been originally created in 2004 and has been updated over the years. It uses older web technologies and practices, such as browser sniffing to serve different CSS files and `md5` for password hashing.

## Key Technologies

*   **Backend:** PHP
*   **Database:** A MySQL database is used, accessed via the `ez_sql.php` library.
*   **Frontend:** HTML, CSS, and JavaScript.
*   **Shopping Cart:** The `Eikaiwa.FM Structure 2016 Details.txt` file mentions `CubeCart` as the shopping cart software.
*   **Audio Streaming:** The `Eikaiwa.FM Structure 2016 Details.txt` file mentions `iCastCenter` and `Centova Cast` for audio streaming.

## Project Structure

*   `/index.php`: The main entry point for the website. It acts as a router, including different HTML templates based on the `page` query parameter.
*   `/admin/`: Contains the administrative interface for managing the website, including user administration and podcast creation.
*   `/html/`: Contains the HTML templates that are included by `index.php`.
*   `/css/`: Contains the CSS files for the website.
*   `/php/`: Contains PHP libraries, such as the `ez_sql.php` database abstraction layer.
*   `/podcast.xml`: The generated RSS feed for the podcast.
*   `/Eikaiwa.FM Structure 2016 Details.txt`: A file containing high-level details about the project's history and technology stack.

## Building and Running

This is a classic PHP web application. To run this project, you would need a web server (like Apache or Nginx) with PHP and a MySQL database.

1.  **Web Server:** Configure a web server to serve files from the project's root directory.
2.  **PHP:** Ensure that a PHP interpreter is installed and configured with the web server.
3.  **Database:**
    *   Set up a MySQL database.
    *   The database connection details are likely stored in the `php/ez_sql.php` file or a similar configuration file. You will need to update these details to match your database server.
    *   The database schema is not included in the repository, so you would need to create it based on the queries in the PHP files.
4.  **Access the Website:** Once the web server is running, you can access the website by navigating to the appropriate URL in your web browser.

## Development Conventions

*   **Coding Style:** The PHP code is written in a procedural style. There are no strict coding standards enforced.
*   **Database:** All database interactions are done through the `ez_sql.php` library.
*   **Templating:** A simple templating system is used, where `index.php` includes HTML files from the `/html` directory.
*   **Security:** The application uses `md5` for password hashing, which is outdated and insecure. It is recommended to upgrade to a more modern and secure password hashing algorithm like `password_hash()`.
