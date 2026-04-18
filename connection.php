<?php
$host = "127.0.0.1"; // or "localhost"
$port = "3306";      // default MySQL port (confirm in Workbench)
$dbname = "hotel"; 
$username = "root";  // or your MySQL Workbench user
$password = "Ab@123.#"; // your MySQL Workbench password

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Database connected successfully"; // for testing
} catch(PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
