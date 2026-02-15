</div>
</main>
<footer class="border-t border-slate-200 dark:border-slate-800 py-8 mt-12">
    <div
        class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4 text-slate-500 dark:text-slate-400 text-sm">
        <p>&copy;
            <?php echo date('Y'); ?> Xpense Tracker. All rights reserved.
        </p>
        <div class="flex gap-6">
            <a href="#" class="hover:text-primary transition">Privacy Policy</a>
            <a href="#" class="hover:text-primary transition">Terms of Service</a>
        </div>
    </div>
</footer>

<script>
    // Dark Mode Logic
    const darkModeToggle = document.getElementById('darkModeToggle');
    const theme = localStorage.getItem('theme');

    if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    }

    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        });
    }
</script>
</body>

</html>