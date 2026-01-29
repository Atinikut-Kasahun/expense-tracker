<?php
include "db/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // FIX: Use pg_query() instead of $conn->query()
    // Also ensuring column names match the ones we created (username vs name)
    $sql = "INSERT INTO users (username, email, password_hash) VALUES ('$name', '$email', '$pass')";
    $result = pg_query($conn, $sql);

    if ($result) {
        header("Location: login.php");
        exit(); // Always exit after a header redirect
    } else {
        echo "Error: " . pg_last_error($conn);
    }
}
include "partials/header.php";
?>