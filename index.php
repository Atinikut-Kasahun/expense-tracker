<?php
include 'db/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker | Take Control of Your Finances</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <a href="index.php" class="logo">
                    <i class="fas fa-wallet"></i> Xpense
                </a>
                <div class="nav-links">
                    <a href="#features">Features</a>
                    <a href="login.php" class="btn btn-outline">Log In</a>
                    <a href="register.php" class="btn btn-primary">Get Started</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Smart Spending. <br><span>Total Control.</span></h1>
                <p>The modern expense tracker designed for precision, speed, and elegance. Manage your wealth without the complexity.</p>
                <div class="hero-actions">
                    <a href="register.php" class="btn btn-primary">Start Tracking Now</a>
                    <a href="login.php" class="btn btn-outline">Already a member?</a>
                </div>
            </div>
        </section>

        <section class="features" id="features">
            <div class="container">
                <div class="section-title">
                    <h2>Everything you need.</h2>
                    <p>Designed to help you understand where your money goes.</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Smart Insights</h3>
                        <p>Visualize your spending habits with intuitive charts and real-time data analysis.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3>Quick Entry</h3>
                        <p>Log your transactions in seconds. Our minimal interface keeps you focused.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <h3>Safe & Secure</h3>
                        <p>Your financial data is private and encrypted. We take security seriously.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Xpense Tracker. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
