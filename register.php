<?php
include "db/config.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = "CSRF token mismatch.";
    } else {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($name) || empty($email) || empty($password)) {
            $error = "All fields are required.";
        } else {
            try {
                $passHash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, email, password_hash) VALUES (:name, :email, :pass)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['name' => $name, 'email' => $email, 'pass' => $passHash]);
                $success = "Registration successful! You can now <a href='login.php' class='font-bold underline'>login</a>.";
            } catch (PDOException $e) {
                $error = "Registration failed. Email might already be taken.";
            }
        }
    }
}

include "partials/header.php";
?>

<div class="max-w-md mx-auto py-12">
    <div
        class="bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Create Account</h2>
            <p class="text-slate-500 dark:text-slate-400">Join Xpense and master your finances</p>
        </div>

        <?php if ($error): ?>
            <div
                class="bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 p-4 rounded-2xl mb-6 text-sm flex items-center gap-3">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div
                class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 p-4 rounded-2xl mb-6 text-sm flex items-center gap-3">
                <i class="bi bi-check-circle-fill"></i>
                <div><?php echo $success; ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <div>
                <label
                    class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Full
                    Name</label>
                <input type="text" name="name" required placeholder="John Doe"
                    class="w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>
            <div>
                <label
                    class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Email
                    Address</label>
                <input type="email" name="email" required placeholder="john@example.com"
                    class="w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>
            <div>
                <label
                    class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-secondary text-white font-bold py-4 rounded-2xl transition shadow-lg shadow-indigo-200 dark:shadow-none active:scale-95">
                Sign Up
            </button>
        </form>

        <p class="text-center mt-8 text-slate-500 dark:text-slate-400 text-sm">
            Already have an account? <a href="login.php" class="text-primary font-bold hover:underline">Login here</a>
        </p>
    </div>
</div>

<?php include "partials/footer.php"; ?>