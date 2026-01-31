<?php
include "db/config.php";
include "partials/header.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Secure Password Hash
        $passHash = password_hash($password, PASSWORD_DEFAULT);

        // SECURE SQL: Use pg_query_params to prevent SQL Injection
        // $1, $2, $3 are placeholders for the values in the array
        $sql = "INSERT INTO users (username, email, password_hash) VALUES ($1, $2, $3)";
        $result = pg_query_params($conn, $sql, array($name, $email, $passHash));

        if ($result) {
            $success = "Registration successful! You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Registration failed. Email might already be taken.";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h2 class="text-center mb-4">Create Account</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="John Doe">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="john@example.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="********">
                </div>

                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
            </form>
            <div class="mt-3 text-center">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</div>

<!-- End container from header.php -->
</div>
</body>

</html>