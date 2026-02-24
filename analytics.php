<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "partials/header.php";
?>

<div class="space-y-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Financial Analytics</h1>
            <p class="text-slate-500 dark:text-slate-400">Deep dive into your spending patterns and trends</p>
        </div>
        <div class="flex gap-4">
            <button onclick="window.print()"
                class="glass px-6 py-3 rounded-2xl font-bold flex items-center gap-2 hover:bg-white/10 transition">
                <i class="bi bi-printer"></i> Print Report
            </button>
            <a href="dashboard.php"
                class="bg-primary text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-200 dark:shadow-none hover:bg-secondary transition flex items-center gap-2">
                <i class="bi bi-house"></i> Dashboard
            </a>
        </div>
    </header>

    <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="glass p-8 rounded-[2rem] border-l-4 border-primary">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Top Category</p>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white" id="topCategory">---</h2>
        </div>
        <div class="glass p-8 rounded-[2rem] border-l-4 border-emerald-500">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Monthly Income</p>
            <h2 class="text-2xl font-black text-emerald-600 dark:text-emerald-400" id="monIncome">$0.00</h2>
        </div>
        <div class="glass p-8 rounded-[2rem] border-l-4 border-rose-500">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Monthly Expense</p>
            <h2 class="text-2xl font-black text-rose-600 dark:text-rose-400" id="monExpense">$0.00</h2>
        </div>
        <div class="glass p-8 rounded-[2rem] border-l-4 border-indigo-400">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Savings Rate</p>
            <h2 class="text-2xl font-black text-indigo-600 dark:text-indigo-400" id="saveRate">0%</h2>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 glass p-10 rounded-[2.5rem]">
            <h3 class="text-xl font-bold mb-8 flex items-center gap-2">
                <i class="bi bi-bar-chart-fill text-primary"></i> 6-Month Spending Trend
            </h3>
            <div class="h-[400px]">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
        <div class="glass p-10 rounded-[2.5rem]">
            <h3 class="text-xl font-bold mb-8 flex items-center gap-2">
                <i class="bi bi-pie-chart-fill text-primary"></i> Category Breakdown
            </h3>
            <div class="h-[350px] flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
            <div id="categoryList" class="mt-8 space-y-4">
                <!-- Dynamic List -->
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    async function initAnalytics() {
        try {
            const res = await fetch('api/get_analytics.php');
            const data = await res.json();

            if (!data.success) throw new Error(data.error);

            // Update Stats
            document.getElementById('topCategory').innerText = data.top_category;
            document.getElementById('monIncome').innerText = '$' + data.current_month.income.toLocaleString();
            document.getElementById('monExpense').innerText = '$' + data.current_month.expense.toLocaleString();

            const savings = data.current_month.income > 0
                ? Math.round(((data.current_month.income - data.current_month.expense) / data.current_month.income) * 100)
                : 0;
            document.getElementById('saveRate').innerText = (savings > 0 ? savings : 0) + '%';

            renderTrendChart(data.monthly_trends);
            renderCategoryCharts(data.category_breakdown);

        } catch (err) {
            console.error(err);
        }
    }

    function renderTrendChart(trends) {
        const ctx = document.getElementById('trendChart').getContext('2d');
        const isDark = document.documentElement.classList.contains('dark');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: trends.map(t => t.month),
                datasets: [
                    {
                        label: 'Income',
                        data: trends.map(t => t.total_income),
                        backgroundColor: '#10b981',
                        borderRadius: 8,
                    },
                    {
                        label: 'Expense',
                        data: trends.map(t => t.total_expense),
                        backgroundColor: '#4361ee',
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: isDark ? '#94a3b8' : '#64748b', font: { weight: 'bold' } }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: isDark ? '#94a3b8' : '#64748b' }
                    },
                    y: {
                        grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' },
                        ticks: { color: isDark ? '#94a3b8' : '#64748b' }
                    }
                }
            }
        });
    }

    function renderCategoryCharts(categories) {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        const isDark = document.documentElement.classList.contains('dark');
        const colors = ['#4361ee', '#3f37c9', '#4895ef', '#4cc9f0', '#10b981', '#f59e0b', '#ef4444'];

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: categories.map(c => c.category),
                datasets: [{
                    data: categories.map(c => c.total_amount),
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 20
                }]
            },
            options: {
                cutout: '75%',
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Legend List
        const list = document.getElementById('categoryList');
        const total = categories.reduce((acc, curr) => acc + parseFloat(curr.total_amount), 0);

        list.innerHTML = categories.slice(0, 5).map((c, i) => `
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full" style="background: ${colors[i % colors.length]}"></div>
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 capitalize">${c.category}</span>
                </div>
                <div class="text-right">
                    <div class="text-sm font-black text-slate-900 dark:text-white">$${parseFloat(c.total_amount).toLocaleString()}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">${Math.round((c.total_amount / total) * 100)}%</div>
                </div>
            </div>
        `).join('');
    }

    document.addEventListener('DOMContentLoaded', initAnalytics);
</script>

<?php include "partials/footer.php"; ?>