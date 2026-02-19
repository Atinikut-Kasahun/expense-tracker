<?php
include 'db/config.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS recurring_transactions (
        id SERIAL PRIMARY KEY,
        user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
        title VARCHAR(100) NOT NULL,
        amount DECIMAL(12, 2) NOT NULL,
        type VARCHAR(10) NOT NULL,
        category VARCHAR(50),
        interval_type VARCHAR(20) DEFAULT 'monthly',
        last_processed DATE,
        next_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Recurring transactions table checked/created successfully.";
} catch (Exception $e) {
    echo "Error creating recurring table: " . $e->getMessage();
}
