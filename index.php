<?php
require_once __DIR__ . '/includes/bootstrap.php';
include 'partials/header.php';
?>

<section class="py-24 flex flex-col items-center text-center">
    <div class="max-w-4xl px-6">
        <div
            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-widest mb-8 animate-bounce">
            <span class="relative flex h-2 w-2">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
            </span>
            ✨ Built with PHP + PostgreSQL + Tailwind
        </div>

        <h1
            class="text-6xl md:text-8xl font-extrabold tracking-tight mb-8 bg-gradient-to-r from-primary via-indigo-400 to-purple-500 bg-clip-text text-transparent leading-tight">
            Wealth Management <br>Redefined.
        </h1>

        <p class="text-xl md:text-2xl text-slate-500 dark:text-slate-400 mb-12 max-w-2xl mx-auto leading-relaxed">
            The modern financial operating system for high-performers. Precision tracking, intelligent insights, and
            elegant design.
        </p>

        <div class="flex flex-col sm:flex-row gap-6 justify-center mb-20">
            <a href="register.php"
                class="bg-primary hover:bg-secondary text-white px-10 py-5 rounded-2xl text-xl font-bold shadow-2xl shadow-indigo-500/20 transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                Get Started Free <i class="bi bi-arrow-right"></i>
            </a>
            <a href="login.php"
                class="glass px-10 py-5 rounded-2xl text-xl font-bold transition-all hover:bg-white/10 flex items-center gap-2">
                Sign In
            </a>
        </div>

        <!-- Animated Stats Counter Preview -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-8 mb-20">
            <div class="text-center p-6 glass rounded-3xl">
                <div class="text-3xl font-black text-primary mb-1">$12,450</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Saved This Month</div>
            </div>
            <div class="text-center p-6 glass rounded-3xl">
                <div class="text-3xl font-black text-emerald-500 mb-1">1,240+</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Transactions Sync'd</div>
            </div>
            <div class="text-center p-6 glass rounded-3xl hidden md:block">
                <div class="text-3xl font-black text-purple-500 mb-1">99.9%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Accuracy Rate</div>
            </div>
        </div>

        <!-- Dashboard Preview -->
        <div class="relative group max-w-5xl mx-auto">
            <div
                class="absolute -inset-1 bg-gradient-to-r from-primary to-purple-600 rounded-[2.5rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
            </div>
            <div class="relative glass rounded-[2.5rem] overflow-hidden border border-white/10 shadow-2xl">
                <img src="assets/dashboard_mockup_preview.png" alt="Dashboard Preview"
                    class="w-full h-auto transform transition duration-500 group-hover:scale-[1.02]">
            </div>
        </div>
    </div>
</section>

<section class="py-20 grid grid-cols-1 md:grid-cols-3 gap-8" id="features">
    <div
        class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 hover:shadow-2xl hover:shadow-indigo-500/10 transition duration-500">
        <div
            class="bg-indigo-50 dark:bg-indigo-900/30 w-14 h-14 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-6 text-2xl">
            <i class="bi bi-graph-up-arrow"></i>
        </div>
        <h3 class="text-xl font-bold mb-3">Smart Insights</h3>
        <p class="text-slate-500 dark:text-slate-400 leading-relaxed">Visualize your spending habits with intuitive
            charts and real-time data analysis.</p>
    </div>

    <div
        class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 hover:shadow-2xl hover:shadow-indigo-500/10 transition duration-500">
        <div
            class="bg-emerald-50 dark:bg-emerald-900/30 w-14 h-14 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-6 text-2xl">
            <i class="bi bi-lightning-charge"></i>
        </div>
        <h3 class="text-xl font-bold mb-3">Quick Entry</h3>
        <p class="text-slate-500 dark:text-slate-400 leading-relaxed">Log your transactions in seconds. Our minimal
            interface keeps you focused.</p>
    </div>

    <div
        class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 hover:shadow-2xl hover:shadow-indigo-500/10 transition duration-500">
        <div
            class="bg-rose-50 dark:bg-rose-900/30 w-14 h-14 rounded-2xl flex items-center justify-center text-rose-600 dark:text-rose-400 mb-6 text-2xl">
            <i class="bi bi-shield-check"></i>
        </div>
        <h3 class="text-xl font-bold mb-3">Safe & Secure</h3>
        <p class="text-slate-500 dark:text-slate-400 leading-relaxed">Your financial data is private and encrypted. We
            take security seriously.</p>
    </div>
</section>

<?php include 'partials/footer.php'; ?>