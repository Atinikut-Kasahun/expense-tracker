<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

include '../db/config.php'; // This now provides $pdo

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

// Filters
$category = $_GET['category'] ?? null;
$type = $_GET['type'] ?? null;
$search = $_GET['search'] ?? null;
$page = max(1, (int) ($_GET['page'] ?? 1));
$limit = (int) ($_GET['limit'] ?? 10);
$offset = ($page - 1) * $limit;

try {
    // Totals Query (Always for the full data unless specific date range requested later)
    $totals_sql = "SELECT 
        SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
        SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
        FROM expenses WHERE user_id = :user_id";
    $stmt = $pdo->prepare($totals_sql);
    $stmt->execute(['user_id' => $user_id]);
    $totals = $stmt->fetch();

    $income = (float) ($totals['total_income'] ?? 0);
    $expense = (float) ($totals['total_expense'] ?? 0);
    $balance = $income - $expense;

    // Filtered List Query
    $conditions = ["user_id = :user_id"];
    $params = ['user_id' => $user_id];

    if ($category) {
        $conditions[] = "category = :category";
        $params['category'] = $category;
    }
    if ($type) {
        $conditions[] = "type = :type";
        $params['type'] = $type;
    }
    if ($search) {
        $conditions[] = "title ILIKE :search";
        $params['search'] = "%$search%";
    }

    $where_clause = implode(" AND ", $conditions);

    // Count for pagination
    $count_sql = "SELECT COUNT(*) FROM expenses WHERE $where_clause";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($params);
    $total_items = (int) $count_stmt->fetchColumn();
    $total_pages = ceil($total_items / $limit);

    // Fetch data
    $list_sql = "SELECT * FROM expenses WHERE $where_clause ORDER BY date DESC, id DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($list_sql);
    $stmt->bindValue(':user_id', $user_id);
    if ($category)
        $stmt->bindValue(':category', $category);
    if ($type)
        $stmt->bindValue(':type', $type);
    if ($search)
        $stmt->bindValue(':search', "%$search%");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $expenses = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'summary' => [
            'income' => $income,
            'expense' => $expense,
            'balance' => $balance
        ],
        'expenses' => $expenses,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total_items' => $total_items,
            'total_pages' => $total_pages
        ]
    ]);
} catch (Exception $e) {
    send_error('Server Error: ' . $e->getMessage(), 500);
}