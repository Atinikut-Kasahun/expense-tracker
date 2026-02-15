<?php
include 'db/config.php';
include 'partials/header.php';
?>

<section class="py-20 flex flex-col items-center text-center">
    <div class="max-w-3xl">
        <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 bg-gradient-to-r from-primary to-indigo-400 bg-clip-text text-transparent">
            Smart Spending. <br>Total Control.
        </h1>
        <p class="text-xl text-slate-500 dark:text-slate-400 mb-10 leading-relaxed">
            The modern expense tracker designed for precision, speed, and elegance. Manage your wealth without the complexity.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="register.php" class="bg-primary hover:bg-secondary text-white px-8 py-4 rounded-2xl text-lg font-bold shadow-xl shadow-indigo-200 dark:shadow-none transition-all hover:scale-105 active:scale-95">
                Start Tracking Now
            </a>
            <a href="login.php" class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 px-8 py-4 rounded-2xl text-lg font-bold transition-all">
                Already a member?
            </a>
        </div>
    </div>
</section>

<section class="py-20 grid grid-cols-1 md:grid-cols-3 gap-8" id="features">
    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 hover:shadow-2xl hover:shadow-indigo-500/10 transition duration-500">
        <div class="bg-indigo-50 dark:bg-indigo-900/30 w-14 h-14 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-6 text-2xl">
            <i class="bi bi-graph-up-arrow"></i>
        </div>
        <h3 class="text-xl font-bold mb-3">Smart Insights</h3>
        <p class="text-slate-500 dark:text-slate-400 leading-relaxed">Visualize your spending habits with intuitive charts and real-time data analysis.</p>
    </div>
    
    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 hover:shadow-2xl hover:shadow-indigo-500/10 transition duration-500">
        <div class="bg-emerald-50 dark:bg-emerald-900/30 w-14 h-14 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-6 text-2xl">
            <i class="bi bi-lightning-charge"></i>
        </div>
        <h3 class="text-xl font-bold mb-3">Quick Entry</h3>
        <p class="text-slate-500 dark:text-slate-400 leading-relaxed">Log your transactions in seconds. Our minimal interface keeps you focused.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 hover:shadow-2xl hover:shadow-indigo-500/10 transition duration-500">
        <div class="bg-rose-50 dark:bg-rose-900/30 w-14 h-14 rounded-2xl flex items-center justify-center text-rose-600 dark:text-rose-400 mb-6 text-2xl">
            <i class="bi bi-shield-check"></i>
        </div>
        <h3 class="text-xl font-bold mb-3">Safe & Secure</h3>
        <p class="text-slate-500 dark:text-slate-400 leading-relaxed">Your financial data is private and encrypted. We take security seriously.</p>
    </div>
</section>

<?php include 'partials/footer.php'; ?>