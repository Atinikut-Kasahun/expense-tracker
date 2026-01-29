<?php
$host = "localhost";
$port = "5432"; // Default PostgreSQL port
$user = "postgres"; // Change 'root' to 'postgres'
$pass = "your_new_password"; // The password you set in the query tool
$db = "expense_tracker";

// Connect to PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

if (!$conn) {
    die("PostgreSQL Connection Failed: " . pg_last_error());
}

session_start();
?>