<?php
// Environment Configuration
define('DEVELOPMENT_MODE', true); // Set to false in production

// Error Reporting
if (DEVELOPMENT_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Application Settings
define('APP_NAME', 'HelpHub');
define('APP_URL', 'http://localhost/helphub'); // Change this in production
define('APP_TIMEZONE', 'Europe/Paris');

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'pdf']);

// Session Settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

// Set timezone
date_default_timezone_set(APP_TIMEZONE);
?> 