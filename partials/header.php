<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xpense | Smart Finance Tracker</title>
    <meta name="csrf-token" content="<?php echo generate_csrf_token(); ?>">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4361ee',
                        secondary: '#3f37c9',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        :root {
            --bg-gradient: linear-gradient(-45deg, #4361ee, #3a0ca3, #7209b7, #4cc9f0);
        }

        @keyframes gradient-bg {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animated-bg {
            background: var(--bg-gradient);
            background-size: 400% 400%;
            animation: gradient-bg 15s ease infinite;
        }

        .glass {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dark .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
    </style>
    <script>
        // Theme Persistence
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body
    class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300 animated-bg">
    <nav class="sticky top-0 z-50 w-full glass border-b border-slate-200 dark:border-slate-800 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2 group">
                <div class="bg-primary p-2 rounded-lg text-white group-hover:scale-110 transition">
                    <i class="bi bi-intersect"></i>
                </div>
                <span class="text-xl font-bold tracking-tight">Xpense</span>
            </a>

            <div class="flex items-center gap-6">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="analytics.php" class="text-sm font-medium hover:text-primary transition">Analytics</a>
                    <a href="dashboard.php" class="text-sm font-medium hover:text-primary transition">Dashboard</a>
                    <a href="logout.php"
                        class="bg-slate-100 hover:bg-rose-50 hover:text-rose-600 dark:bg-slate-800 dark:hover:bg-rose-900/30 px-4 py-2 rounded-full text-sm font-semibold transition">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="text-sm font-medium hover:text-primary transition">Login</a>
                    <a href="register.php"
                        class="bg-primary hover:bg-secondary text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 transition active:scale-95">
                        Get Started
                    </a>
                <?php endif; ?>

                <button id="darkModeToggle"
                    class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i class="bi bi-moon-stars dark:hidden"></i>
                    <i class="bi bi-sun hidden dark:block"></i>
                </button>

                <script>
                    const toggle = document.getElementById('darkModeToggle');
                    toggle.addEventListener('click', () => {
                        const isDark = document.documentElement.classList.toggle('dark');
                        localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    });
                </script>
            </div>
        </div>
    </nav>
    <main class="min-h-[calc(100vh-160px)]">
        <div class="max-w-7xl mx-auto px-6 py-8">