<?php
// Error handling function
function handleError($errno, $errstr, $errfile, $errline) {
    $error = [
        'type' => $errno,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline,
        'time' => date('Y-m-d H:i:s')
    ];
    
    // Log error
    error_log(json_encode($error));
    
    // Show user-friendly error based on environment
    if (DEVELOPMENT_MODE) {
        // Show detailed error in development
        echo "<div class='alert alert-danger'>";
        echo "<strong>Error:</strong> " . htmlspecialchars($errstr) . "<br>";
        echo "File: " . htmlspecialchars($errfile) . " (Line: " . htmlspecialchars($errline) . ")";
        echo "</div>";
    } else {
        // Show generic error in production
        echo "<div class='alert alert-danger'>";
        echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        echo "</div>";
    }
    
    return true;
}

// Set error handler
set_error_handler('handleError');

// Function to set flash messages
function setFlashMessage($message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is manager
function isManager() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'manager';
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to format date
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

// Function to format currency
function formatCurrency($amount) {
    return number_format($amount, 2, ',', ' ') . ' €';
}

// Function to generate random string
function generateRandomString($length = 10) {
    return bin2hex(random_bytes($length));
}

// Function to check if request is AJAX
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

// Function to get current URL
function getCurrentUrl() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . 
           "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

// Function to get base URL
function getBaseUrl() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . 
           "://$_SERVER[HTTP_HOST]";
}

// Function to check if string contains HTML
function containsHtml($string) {
    return $string !== strip_tags($string);
}

// Function to truncate text
function truncateText($text, $length = 100, $append = '...') {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . $append;
    }
    return $text;
}

// Function to get file extension
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

// Function to validate file upload
function validateFileUpload($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'], $maxSize = 5242880) {
    $errors = [];
    
    // Check if file was uploaded
    if (!isset($file['error']) || is_array($file['error'])) {
        $errors[] = "Paramètres de fichier invalides.";
        return $errors;
    }
    
    // Check for upload errors
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $errors[] = "Le fichier est trop grand.";
            break;
        case UPLOAD_ERR_PARTIAL:
            $errors[] = "Le téléchargement du fichier n'a pas été terminé.";
            break;
        case UPLOAD_ERR_NO_FILE:
            $errors[] = "Aucun fichier n'a été téléchargé.";
            break;
        default:
            $errors[] = "Une erreur inconnue s'est produite.";
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        $errors[] = "Le fichier est trop grand. Taille maximale: " . ($maxSize / 1024 / 1024) . "MB";
    }
    
    // Check file type
    $ext = getFileExtension($file['name']);
    if (!in_array($ext, $allowedTypes)) {
        $errors[] = "Type de fichier non autorisé. Types autorisés: " . implode(', ', $allowedTypes);
    }
    
    return $errors;
}
?> 