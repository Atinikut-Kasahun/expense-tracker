<?php
$host = "localhost";
$port = "5432"; // Default PostgreSQL port
$user = "postgres";
$pass = "your_new_password"; // <--- IMPORTANT: REPLACE THIS with your actual postgres password!
$db = "expense_tracker";

// Connect to PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

if (!$conn) {
    die("PostgreSQL Connection Failed: " . pg_last_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
