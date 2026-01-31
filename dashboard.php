<?php
ob_start();
include "db/config.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackerPro | Premium Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }
    </style>
</head>

<body class="text-slate-800 antialiased">

    <nav class="sticky top-0 z-40 w-full glass border-b border-slate-200 px-6 py-3 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="bg-indigo-600 p-2 rounded-lg text-white">
                <i class="bi bi-intersect"></i>
            </div>
            <span class="text-xl font-bold tracking-tight">Tracker<span class="text-indigo-600">Pro</span></span>
        </div>

        <div class="flex items-center gap-4">
            <div class="hidden md:block text-right">
                <p class="text-xs text-slate-500 font-medium leading-none">Welcome back,</p>
                <p class="text-sm font-bold text-slate-900"><?= htmlspecialchars($username) ?></p>
            </div>
            <a href="logout.php"
                class="flex items-center gap-2 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 px-4 py-2 rounded-full text-sm font-semibold transition">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 space-y-8">
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="stat-card glass p-6 rounded-3xl border-l-4 border-indigo-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Net Balance</p>
                        <h2 class="text-3xl font-extrabold mt-1" id="currentBalance">$0.00</h2>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-2xl text-indigo-600"><i class="bi bi-wallet2 fs-4"></i></div>
                </div>
            </div>
            <div class="stat-card glass p-6 rounded-3xl border-l-4 border-emerald-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Income</p>
                        <h2 class="text-3xl font-extrabold mt-1 text-emerald-600" id="totalIncome">$0.00</h2>
                    </div>
                    <div class="bg-emerald-100 p-3 rounded-2xl text-emerald-600"><i
                            class="bi bi-graph-up-arrow fs-4"></i></div>
                </div>
            </div>
            <div class="stat-card glass p-6 rounded-3xl border-l-4 border-rose-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Expenses</p>
                        <h2 class="text-3xl font-extrabold mt-1 text-rose-600" id="totalExpense">$0.00</h2>
                    </div>
                    <div class="bg-rose-100 p-3 rounded-2xl text-rose-600"><i class="bi bi-graph-down-arrow fs-4"></i>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="glass p-6 rounded-3xl">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-pie-chart-fill text-indigo-500"></i> Expenses by Category
                </h3>
                <div class="h-[250px] flex justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            <div class="glass p-6 rounded-3xl">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-activity text-indigo-500"></i> Cash Flow Trend
                </h3>
                <div class="h-[250px]">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </section>

        <section class="glass rounded-3xl overflow-hidden">
            <div class="p-6 border-b border-slate-200 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold">Transaction History</h3>
                    <button onclick="toggleModal('addExpenseModal')"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-200 transition active:scale-95">
                        <i class="bi bi-plus-lg me-2"></i> Add New
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Date
                            </th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                Description</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                Category</th>
                            <th
                                class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">
                                Amount</th>
                            <th
                                class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody id="expenseTableBody" class="divide-y divide-slate-100"></tbody>
                </table>
            </div>
        </section>
    </main>

    <div id="addExpenseModal"
        class="hidden fixed inset-0 z-50 flex items-end md:items-center justify-center bg-slate-900/60 backdrop-blur-sm p-0 md:p-4">
        <div
            class="bg-white w-full max-w-lg rounded-t-[2rem] md:rounded-[2rem] shadow-2xl p-8 animate-in slide-in-from-bottom duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-slate-800">New Transaction</h2>
                <button onclick="toggleModal('addExpenseModal')" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="addExpenseForm" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Description</label>
                    <input type="text" name="title" required placeholder="e.g. Starbucks Coffee"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Amount</label>
                        <input type="number" step="0.01" name="amount" required placeholder="0.00"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Type</label>
                        <select name="type"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition bg-white">
                            <option value="expense">Expense</option>
                            <option value="income">Income</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Category</label>
                        <input type="text" name="category" placeholder="Food, Rent, etc."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Date</label>
                        <input type="date" name="date" value="<?= date('Y-m-d') ?>"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                </div>

                <div class="pt-4 flex flex-col md:flex-row gap-3">
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl transition shadow-lg shadow-indigo-200 active:scale-95">
                        Save Transaction
                    </button>
                    <button type="button" onclick="toggleModal('addExpenseModal')"
                        class="w-full md:w-auto px-8 py-4 text-slate-400 font-bold">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let categoryChart, trendChart;

        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        async function loadDashboardData() {
            const tbody = document.getElementById('expenseTableBody');
            try {
                const response = await fetch('api/get_expenses.php');
                const data = await response.json();

                document.getElementById('totalIncome').innerText = '$' + data.summary.income.toLocaleString();
                document.getElementById('totalExpense').innerText = '$' + data.summary.expense.toLocaleString();
                document.getElementById('currentBalance').innerText = '$' + data.summary.balance.toLocaleString();

                tbody.innerHTML = data.expenses.map(item => `
                    <tr class="hover:bg-slate-50 transition group">
                        <td class="px-6 py-4 text-sm text-slate-500">${item.date}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">${item.title}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[11px] font-extrabold uppercase">${item.category || 'General'}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-extrabold ${item.type === 'income' ? 'text-emerald-600' : 'text-rose-600'}">
                            ${item.type === 'income' ? '+' : '-'}$${Math.abs(item.amount).toFixed(2)}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="deleteExpense(${item.id})" class="text-slate-300 hover:text-rose-600 transition p-2">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');

                renderCharts(data);
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center p-12 text-slate-400">Unable to load transactions.</td></tr>`;
            }
        }

        // Form Submission Handler
        document.getElementById('addExpenseForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = "Saving...";

            try {
                const response = await fetch('api/add_expense.php', {
                    method: 'POST',
                    body: new FormData(this)
                });
                const result = await response.json();

                if (result.success) {
                    toggleModal('addExpenseModal');
                    this.reset();
                    loadDashboardData();
                } else {
                    alert("Error: " + result.error);
                }
            } catch (err) {
                alert("Connection error. Try again.");
            } finally {
                btn.disabled = false;
                btn.innerText = "Save Transaction";
            }
        });

        function renderCharts(data) {
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
                        backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#64748b'],
                        borderWidth: 0
                    }]
                },
                options: { cutout: '80%', plugins: { legend: { position: 'bottom' } } }
            });

            const ctxTrend = document.getElementById('trendChart').getContext('2d');
            if (trendChart) trendChart.destroy();
            trendChart = new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: data.expenses.slice(0, 7).reverse().map(e => e.date),
                    datasets: [{
                        label: 'Transaction Amount',
                        data: data.expenses.slice(0, 7).reverse().map(e => e.amount),
                        borderColor: '#6366f1',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(99, 102, 241, 0.1)'
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        }

        document.addEventListener('DOMContentLoaded', loadDashboardData);
    </script>
</body>

</html>