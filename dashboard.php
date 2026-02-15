<?php
include "db/config.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'] ?? 'User';

include "partials/header.php";
?>

<div class="space-y-8">
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
            class="stat-card glass p-8 rounded-[2rem] border-l-4 border-indigo-500 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Net Balance</p>
                    <h2 class="text-4xl font-extrabold text-slate-900 dark:text-white" id="currentBalance">$0.00</h2>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-2xl text-indigo-600 dark:text-indigo-400">
                    <i class="bi bi-wallet2 text-2xl"></i>
                </div>
            </div>
        </div>
        <div
            class="stat-card glass p-8 rounded-[2rem] border-l-4 border-emerald-500 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Income</p>
                    <h2 class="text-4xl font-extrabold text-emerald-600 dark:text-emerald-400" id="totalIncome">$0.00
                    </h2>
                </div>
                <div
                    class="bg-emerald-50 dark:bg-emerald-900/30 p-4 rounded-2xl text-emerald-600 dark:text-emerald-400">
                    <i class="bi bi-graph-up-arrow text-2xl"></i>
                </div>
            </div>
        </div>
        <div
            class="stat-card glass p-8 rounded-[2rem] border-l-4 border-rose-500 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Expenses</p>
                    <h2 class="text-4xl font-extrabold text-rose-600 dark:text-rose-400" id="totalExpense">$0.00</h2>
                </div>
                <div class="bg-rose-50 dark:bg-rose-900/30 p-4 rounded-2xl text-rose-600 dark:text-rose-400">
                    <i class="bi bi-graph-down-arrow text-2xl"></i>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="glass p-8 rounded-[2.5rem] shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <i class="bi bi-pie-chart-fill text-indigo-500"></i> Expenses by Category
            </h3>
            <div class="h-[300px] flex justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        <div class="glass p-8 rounded-[2.5rem] shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <i class="bi bi-activity text-indigo-500"></i> Cash Flow Trend
            </h3>
            <div class="h-[300px]">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </section>

    <section class="glass rounded-[2.5rem] overflow-hidden shadow-sm">
        <div
            class="p-8 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Transaction History</h3>
                <p class="text-sm text-slate-500">Manage your recent income and expenses</p>
            </div>
            <button onclick="toggleModal('addExpenseModal')"
                class="bg-primary hover:bg-secondary text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all active:scale-95 flex items-center gap-2">
                <i class="bi bi-plus-lg"></i> Add New
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 dark:bg-slate-800/30">
                    <tr>
                        <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em]">Date</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                            Description</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em]">Category
                        </th>
                        <th
                            class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right">
                            Amount</th>
                        <th
                            class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] text-center">
                            Action</th>
                    </tr>
                </thead>
                <tbody id="expenseTableBody" class="divide-y divide-slate-50 dark:divide-slate-800">
                    <!-- Skeletons will show here -->
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- Add Expense Modal -->
<div id="addExpenseModal"
    class="hidden fixed inset-0 z-[60] flex items-end md:items-center justify-center bg-slate-900/40 backdrop-blur-sm p-0 md:p-4">
    <div
        class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] shadow-2xl p-10 animate-in slide-in-from-bottom duration-300">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">New Transaction</h2>
            <button onclick="toggleModal('addExpenseModal')"
                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <form id="addExpenseForm" class="space-y-6">
            <div>
                <label
                    class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Description</label>
                <input type="text" name="title" required placeholder="e.g. Starbucks Coffee"
                    class="w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-indigo-500 outline-none transition dark:text-white">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Amount</label>
                    <input type="number" step="0.01" name="amount" required placeholder="0.00"
                        class="w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-indigo-500 outline-none transition dark:text-white">
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Type</label>
                    <select name="type"
                        class="w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-indigo-500 outline-none transition dark:text-white appearance-none">
                        <option value="expense">Expense</option>
                        <option value="income">Income</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Category</label>
                    <input type="text" name="category" placeholder="Food, Rent, etc."
                        class="w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-indigo-500 outline-none transition dark:text-white">
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Date</label>
                    <input type="date" name="date" value="<?= date('Y-m-d') ?>"
                        class="w-full px-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-indigo-500 outline-none transition dark:text-white">
                </div>
            </div>

            <div class="pt-6 flex flex-col md:flex-row gap-4">
                <button type="submit"
                    class="w-full bg-primary hover:bg-secondary text-white font-bold py-4 rounded-2xl transition shadow-xl shadow-indigo-100 dark:shadow-none active:scale-95">
                    Save Transaction
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let categoryChart, trendChart;

    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function showSkeletons() {
        const tbody = document.getElementById('expenseTableBody');
        tbody.innerHTML = Array(3).fill(0).map(() => `
            <tr class="animate-pulse">
                <td class="px-8 py-6"><div class="h-4 bg-slate-100 dark:bg-slate-800 rounded w-24"></div></td>
                <td class="px-8 py-6"><div class="h-4 bg-slate-100 dark:bg-slate-800 rounded w-48"></div></td>
                <td class="px-8 py-6"><div class="h-6 bg-slate-100 dark:bg-slate-800 rounded-full w-20"></div></td>
                <td class="px-8 py-6 text-right"><div class="h-4 bg-slate-100 dark:bg-slate-800 rounded w-16 ml-auto"></div></td>
                <td class="px-8 py-6 text-center"><div class="h-8 bg-slate-100 dark:bg-slate-800 rounded-lg w-8 mx-auto"></div></td>
            </tr>
        `).join('');
    }

    async function loadDashboardData() {
        const tbody = document.getElementById('expenseTableBody');
        showSkeletons();

        try {
            const response = await fetch('api/get_expenses.php');
            const data = await response.json();

            document.getElementById('totalIncome').innerText = '$' + data.summary.income.toLocaleString(undefined, { minimumFractionDigits: 2 });
            document.getElementById('totalExpense').innerText = '$' + data.summary.expense.toLocaleString(undefined, { minimumFractionDigits: 2 });
            document.getElementById('currentBalance').innerText = '$' + data.summary.balance.toLocaleString(undefined, { minimumFractionDigits: 2 });

            if (data.expenses.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="py-20 text-center"><div class="text-slate-400 space-y-3"><i class="bi bi-inbox text-5xl opacity-20"></i><p>No transactions found yet.</p></div></td></tr>`;
                return;
            }

            tbody.innerHTML = data.expenses.map(item => `
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition group">
                    <td class="px-8 py-6 text-sm text-slate-500 dark:text-slate-400">${item.date}</td>
                    <td class="px-8 py-6 font-bold text-slate-900 dark:text-white capitalize">${item.title}</td>
                    <td class="px-8 py-6">
                        <span class="px-4 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-[10px] font-extrabold uppercase tracking-widest border border-indigo-100 dark:border-indigo-900/50">
                            ${item.category || 'General'}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right font-extrabold ${item.type === 'income' ? 'text-emerald-600' : 'text-rose-600'}">
                        ${item.type === 'income' ? '+' : '-'}$${Math.abs(item.amount).toFixed(2).toLocaleString()}
                    </td>
                    <td class="px-8 py-6 text-center">
                        <button onclick="deleteExpense(${item.id})" class="text-slate-300 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition p-2.5 rounded-xl">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </td>
                </tr>
            `).join('');

            renderCharts(data);
        } catch (err) {
            tbody.innerHTML = `<tr><td colspan="5" class="p-12 text-center text-rose-500 bg-rose-50/50">Failed to sync with cloud. Check connecton.</td></tr>`;
        }
    }

    async function deleteExpense(id) {
        if (!confirm("Remove this transaction?")) return;

        try {
            const formData = new FormData();
            formData.append('id', id);
            const res = await fetch('api/delete_expense.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await res.json();

            if (result.success) {
                showToast("Transaction removed successfully");
                loadDashboardData();
            } else {
                showToast(result.error, 'error');
            }
        } catch (err) {
            showToast("Failed to delete transaction", 'error');
        }
    }

    document.getElementById('addExpenseForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = `<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span> Saving...`;

        try {
            const formData = new FormData(this);
            const response = await fetch('api/add_expense.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();

            if (result.success) {
                toggleModal('addExpenseModal');
                this.reset();
                showToast("Transaction saved!");
                loadDashboardData();
            } else {
                showToast(result.error, 'error');
            }
        } catch (err) {
            showToast("Connection error", 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = "Save Transaction";
        }
    });

    function renderCharts(data) {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#94a3b8' : '#64748b';

        const cats = {};
        data.expenses.filter(e => e.type === 'expense').forEach(e => {
            cats[e.category] = (cats[e.category] || 0) + parseFloat(e.amount);
        });

        const ctxPie = document.getElementById('categoryChart').getContext('2d');
        if (categoryChart) categoryChart.destroy();
        categoryChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: Object.keys(cats),
                datasets: [{
                    data: Object.values(cats),
                    backgroundColor: ['#4361ee', '#10b981', '#f59e0b', '#ef4444', '#64748b'],
                    borderWidth: 0,
                    hoverOffset: 20
                }]
            },
            options: {
                cutout: '80%',
                radius: '90%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: textColor, font: { weight: 'bold', size: 11 } } }
                }
            }
        });

        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        if (trendChart) trendChart.destroy();
        trendChart = new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: data.expenses.slice(0, 10).reverse().map(e => e.date),
                datasets: [{
                    data: data.expenses.slice(0, 10).reverse().map(e => e.amount),
                    borderColor: '#4361ee',
                    borderWidth: 4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: (ctx) => {
                        const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, 'rgba(67, 97, 238, 0.2)');
                        gradient.addColorStop(1, 'rgba(67, 97, 238, 0)');
                        return gradient;
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: textColor } },
                    y: { grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' }, ticks: { color: textColor } }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', loadDashboardData);
</script>

<?php include "partials/footer.php"; ?>