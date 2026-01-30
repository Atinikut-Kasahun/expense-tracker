<?php
include 'config.php';

$sql = "
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

pg_query($conn, $sql);

// 2. Create Expenses Table
$sql_expenses = "
CREATE TABLE IF NOT EXISTS expenses (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    title VARCHAR(255) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    type VARCHAR(10) DEFAULT 'expense' NOT NULL,
    category VARCHAR(50),
    date DATE DEFAULT CURRENT_DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user
        FOREIGN KEY(user_id) 
        REFERENCES users(id)
        ON DELETE CASCADE
);
";

pg_query($conn, $sql_expenses);

// 3. SECURE FIX: If table existed without 'type', add it now
$check_col = "SELECT column_name FROM information_schema.columns WHERE table_name='expenses' AND column_name='type'";
$check_res = pg_query($conn, $check_col);

if (pg_num_rows($check_res) == 0) {
    echo "Adding missing 'type' column...<br>";
    $alter_sql = "ALTER TABLE expenses ADD COLUMN type VARCHAR(10) DEFAULT 'expense' NOT NULL CHECK (type IN ('income', 'expense'))";
    pg_query($conn, $alter_sql);
}

echo "Database setup completed successfully!";
?>