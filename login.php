<?php
include "db/config.php";

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
        $sql = "SELECT id, username, password_hash FROM users WHERE email = $1";
        $result = pg_query_params($conn, $sql, array($email));

        if ($result && pg_num_rows($result) > 0) {
            $user = pg_fetch_assoc($result);
            if (password_verify($password, $user['password_hash'])) {
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

include "partials/header.php";
?>

<div class="max-w-md mx-auto py-12">
    <div
        class="bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Welcome Back</h2>
            <p class="text-slate-500 dark:text-slate-400">Log in to manage your precision tracking</p>
        </div>

        <?php if ($error): ?>
            <div
                class="bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 p-4 rounded-2xl mb-6 text-sm flex items-center gap-3">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <div>
                <label
                    class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Email
                    Address</label>
                <input type="email" name="email" required placeholder="name@example.com"
                    class="w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>
            <div>
                <label
                    class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-secondary text-white font-bold py-4 rounded-2xl transition shadow-lg shadow-indigo-200 dark:shadow-none active:scale-95 flex items-center justify-center gap-2">
                Sign In <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <p class="text-center mt-8 text-slate-500 dark:text-slate-400 text-sm">
            Don't have an account? <a href="register.php" class="text-primary font-bold hover:underline">Create one</a>
        </p>
    </div>
</div>

<?php include "partials/footer.php"; ?>