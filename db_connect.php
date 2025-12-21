<?php
// Database connection details
// Load database configuration
if (file_exists(__DIR__ . '/db_config.php')) {
    require_once __DIR__ . '/db_config.php';
} else {
    // Fallback or error if config is missing (for production safety)
    $servername = getenv('DB_SERVER') ?: "localhost";
    $username = getenv('DB_USERNAME') ?: "root";
    $password = getenv('DB_PASSWORD') ?: "";
    $dbname = getenv('DB_NAME') ?: "database";
    $port = getenv('DB_PORT') ?: 3306;
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
