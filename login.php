<?php
// Start by including config (which starts session)
// Since header.php also might be included later, we need to be careful about session_start
// Config.php has session_start() at the end.
include "db/config.php";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Prepare SQL to prevent injection
        $sql = "SELECT id, username, password_hash FROM users WHERE email = $1";
        $result = pg_query_params($conn, $sql, array($email));

        if ($result && pg_num_rows($result) > 0) {
            $user = pg_fetch_assoc($result);
            // Verify Password
            if (password_verify($password, $user['password_hash'])) {
                // Login Success
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Expense Tracker</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="auth-page">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="glass-card text-center">
                    <div class="mb-4">
                        <i class="bi bi-wallet2 text-primary display-4"></i>
                    </div>
                    <h3 class="auth-title">Welcome Back</h3>
                    <p class="auth-subtitle">Login to manage your expenses</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <div>
                                <?php echo $error; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-floating mb-3 text-start">
                            <input type="email" class="form-control" id="emailInput" name="email"
                                placeholder="name@example.com" required>
                            <label for="emailInput">Email address</label>
                        </div>
                        <div class="form-floating mb-4 text-start">
                            <input type="password" class="form-control" id="passwordInput" name="password"
                                placeholder="Password" required>
                            <label for="passwordInput">Password</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            Sign In <i class="bi bi-arrow-right-short"></i>
                        </button>

                        <div class="text-muted">
                            Don't have an account? <a href="register.php">Create one</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>