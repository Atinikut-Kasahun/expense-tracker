<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
include '../db/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
        echo json_encode(['success' => false, 'error' => 'CSRF token mismatch']);
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title'] ?? '');
    $amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);
    $type = $_POST['type'] ?? 'expense';
    $category = trim($_POST['category'] ?? 'General');
    $date = $_POST['date'] ?? date('Y-m-d');

    // Basic Validation
    if (empty($title)) {
        echo json_encode(['success' => false, 'error' => 'Title is required']);
        exit;
    }
    if (!$amount || $amount <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid amount']);
        exit;
    }

    try {
        $sql = "INSERT INTO expenses (user_id, title, amount, type, category, date) VALUES ($1, $2, $3, $4, $5, $6)";
        $result = pg_query_params($conn, $sql, array($user_id, $title, $amount, $type, $category, $date));

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception(pg_last_error($conn));
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}