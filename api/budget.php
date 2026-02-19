<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

include '../db/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$month = date('Y-m');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->prepare("SELECT amount FROM budgets WHERE user_id = :user_id AND month = :month");
        $stmt->execute(['user_id' => $user_id, 'month' => $month]);
        $budget = $stmt->fetch();

        echo json_encode([
            'success' => true,
            'budget' => (float) ($budget['amount'] ?? 0)
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
        echo json_encode(['success' => false, 'error' => 'CSRF token mismatch']);
        exit;
    }

    $amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);

    if ($amount < 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid amount']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO budgets (user_id, amount, month) 
                               VALUES (:user_id, :amount, :month) 
                               ON CONFLICT (user_id, month) 
                               DO UPDATE SET amount = EXCLUDED.amount");
        $stmt->execute([
            'user_id' => $user_id,
            'amount' => $amount,
            'month' => $month
        ]);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
