<?php
include '../db/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['error' => 'Invalid expense ID.']);
        exit;
    }

    $sql = "DELETE FROM expenses WHERE id = $1 AND user_id = $2";
    $result = pg_query_params($conn, $sql, array($id, $user_id));

    if ($result) {
        if (pg_affected_rows($result) > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Expense not found or unauthorized.']);
        }
    } else {
        echo json_encode(['error' => 'Failed to delete expense: ' . pg_last_error($conn)]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}