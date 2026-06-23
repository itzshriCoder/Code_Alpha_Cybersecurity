<?php
$host = "localhost"; // database location
$user = getenv("DB_USER"); // database username stored in environment variable
$pass = getenv("DB_PASS"); // database password stored in environment variable
$db_name = "reciphp_demo"; // database name

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
    
    // Set PDO to throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set encoding to utf8
    $pdo->exec("set names utf8");
} catch(PDOException $e) {
    // If connection fails, display error message
    die("Connection failed: " . $e->getMessage());
}
?>
