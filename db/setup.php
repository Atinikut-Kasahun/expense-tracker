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

$columns_to_check = [
    'type' => "ALTER TABLE expenses ADD COLUMN type VARCHAR(10) DEFAULT 'expense' NOT NULL",
    'category' => "ALTER TABLE expenses ADD COLUMN category VARCHAR(50)",
    'date' => "ALTER TABLE expenses ADD COLUMN date DATE DEFAULT CURRENT_DATE"
];

foreach ($columns_to_check as $col => $alter_sql) {
    $check_col = "SELECT column_name FROM information_schema.columns WHERE table_name='expenses' AND column_name='$col'";
    $check_res = pg_query($conn, $check_col);
    if (pg_num_rows($check_res) == 0) {
        echo "Adding missing '$col' column...<br>";
        pg_query($conn, $alter_sql);
    }
}


$check_constraint = "ALTER TABLE expenses DROP CONSTRAINT IF EXISTS expenses_type_check; ALTER TABLE expenses ADD CONSTRAINT expenses_type_check CHECK (type IN ('income', 'expense'))";
pg_query($conn, $check_constraint);

echo "Database setup completed successfully!";
?>
