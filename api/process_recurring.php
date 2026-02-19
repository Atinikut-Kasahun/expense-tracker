<?php
include '../db/config.php';

function process_recurring($user_id, $pdo)
{
    try {
        $today = date('Y-m-d');
        // Find pending recurring transactions
        $stmt = $pdo->prepare("SELECT * FROM recurring_transactions 
                               WHERE user_id = :user_id 
                               AND (next_date <= :today OR next_date IS NULL)");
        $stmt->execute(['user_id' => $user_id, 'today' => $today]);
        $recurring = $stmt->fetchAll();

        foreach ($recurring as $item) {
            // Insert into expenses
            $ins = $pdo->prepare("INSERT INTO expenses (user_id, title, amount, type, category, date) 
                                  VALUES (:uid, :title, :amount, :type, :cat, :date)");
            $ins->execute([
                'uid' => $user_id,
                'title' => $item['title'] . ' (Recurring)',
                'amount' => $item['amount'],
                'type' => $item['type'],
                'cat' => $item['category'],
                'date' => $today
            ]);

            // Update next_date (assuming monthly for now)
            $next = date('Y-m-d', strtotime('+1 month', strtotime($item['next_date'] ?? $today)));
            $upd = $pdo->prepare("UPDATE recurring_transactions 
                                  SET last_processed = :today, next_date = :next 
                                  WHERE id = :id");
            $upd->execute(['today' => $today, 'next' => $next, 'id' => $item['id']]);
        }
        return true;
    } catch (Exception $e) {
        return false;
    }
}
