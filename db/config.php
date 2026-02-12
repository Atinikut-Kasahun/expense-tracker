<?php
$host = "localhost";
$port = "5432"; 
$user = "postgres";
$db = "expense_tracker";


$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

if (!$conn) {
    die("PostgreSQL Connection Failed: " . pg_last_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
