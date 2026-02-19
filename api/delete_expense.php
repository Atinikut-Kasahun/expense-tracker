<?php
include '../db/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
        echo json_encode(['error' => 'CSRF token mismatch']);
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['error' => 'Invalid expense ID.']);
        exit;
    }

    try {
        $sql = "DELETE FROM expenses WHERE id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'user_id' => $user_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Expense not found or unauthorized.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to delete expense: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}