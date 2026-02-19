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

try {
    // 1. Monthly Spending Breakdown (Last 6 Months)
    $monthly_sql = "SELECT 
                        TO_CHAR(date, 'Mon YYYY') as month,
                        SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense,
                        SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                        MIN(date) as sort_date
                    FROM expenses 
                    WHERE user_id = :user_id 
                    GROUP BY month 
                    ORDER BY sort_date DESC 
                    LIMIT 6";
    $stmt = $pdo->prepare($monthly_sql);
    $stmt->execute(['user_id' => $user_id]);
    $monthly_data = array_reverse($stmt->fetchAll());

    // 2. Category Percentage (Current Month)
    $cat_sql = "SELECT 
                    category, 
                    SUM(amount) as total_amount
                FROM expenses 
                WHERE user_id = :user_id 
                  AND type = 'expense'
                  AND date >= DATE_TRUNC('month', CURRENT_DATE)
                GROUP BY category 
                ORDER BY total_amount DESC";
    $stmt = $pdo->prepare($cat_sql);
    $stmt->execute(['user_id' => $user_id]);
    $category_data = $stmt->fetchAll();

    // 3. Top Expense Category
    $top_cat = $category_data[0]['category'] ?? 'None';

    // 4. Monthly Summary
    $summary_sql = "SELECT 
                        SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as monthly_income,
                        SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as monthly_expense
                    FROM expenses 
                    WHERE user_id = :user_id 
                      AND date >= DATE_TRUNC('month', CURRENT_DATE)";
    $stmt = $pdo->prepare($summary_sql);
    $stmt->execute(['user_id' => $user_id]);
    $summary = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'monthly_trends' => $monthly_data,
        'category_breakdown' => $category_data,
        'top_category' => $top_cat,
        'current_month' => [
            'income' => (float) ($summary['monthly_income'] ?? 0),
            'expense' => (float) ($summary['monthly_expense'] ?? 0)
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
