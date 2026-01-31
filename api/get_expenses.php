<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

include '../db/config.php';

header('Content-Type: application/json');

function send_error($message, $code = 400)
{
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    send_error('Unauthorized', 401);
}

$user_id = $_SESSION['user_id'];

try {
    // Get totals
    $totals_sql = "SELECT 
        SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
        SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
        FROM expenses WHERE user_id = $1";
    $totals_result = pg_query_params($conn, $totals_sql, array($user_id));

    if (!$totals_result) {
        send_error('Database Error (Totals): ' . pg_last_error($conn), 500);
    }

    $totals = pg_fetch_assoc($totals_result);

    $income = (float) ($totals['total_income'] ?? 0);
    $expense = (float) ($totals['total_expense'] ?? 0);
    $balance = $income - $expense;

    // Get latest expenses
    $list_sql = "SELECT * FROM expenses WHERE user_id = $1 ORDER BY date DESC, id DESC LIMIT 20";
    $list_result = pg_query_params($conn, $list_sql, array($user_id));

    if (!$list_result) {
        send_error('Database Error (List): ' . pg_last_error($conn), 500);
    }

    $expenses = pg_fetch_all($list_result) ?: [];

    echo json_encode([
        'success' => true,
        'summary' => [
            'income' => $income,
            'expense' => $expense,
            'balance' => $balance
        ],
        'expenses' => $expenses
    ]);
} catch (Exception $e) {
    send_error('Server Error: ' . $e->getMessage(), 500);
}