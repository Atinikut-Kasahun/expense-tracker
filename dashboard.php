<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'] ?? 'User';

require_once 'api/process_recurring.php';
process_recurring($_SESSION['user_id'], $pdo);

include "partials/header.php";
?>

<div class="space-y-10">
    <!-- Premium SaaS Dashboard Header -->
    <header class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span
                    class="px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest border border-indigo-500/20">Active
                    Workspace</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1">
                    <i class="bi bi-clock-history"></i> Last synced: <span id="lastSyncTime">Just now</span>
                </span>
            </div>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Financial Overview</h1>
            <p class="text-slate-500 dark:text-slate-400">Welcome back, manager. Here's your real-time cash flow status.
            </p>
        </div>
        <div class="flex gap-3">
            <button onclick="toggleModal('setBudgetModal')"
                class="glass px-5 py-3 rounded-2xl font-bold text-sm flex items-center gap-2 hover:bg-white/10 transition">
                <i class="bi bi-gear-wide-connected"></i> Configure
            </button>
            <button onclick="toggleModal('addExpenseModal')"
                class="bg-primary hover:bg-secondary text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all active:scale-95 flex items-center gap-2 text-sm">
                <i class="bi bi-plus-lg"></i> New Transaction
            </button>
        </div>
    </header>

    <!-- Main Hierarchy: Net Balance Hero -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 relative group">
            <div
                class="absolute -inset-1 bg-gradient-to-r from-primary to-purple-600 rounded-[2.5rem] blur opacity-10 group-hover:opacity-20 transition duration-1000">
            </div>
            <div
                class="relative glass p-10 rounded-[2.5rem] border border-white/20 shadow-2xl overflow-hidden min-h-[280px] flex flex-col justify-center">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total Net Balance
                        </p>
                        <h2 class="text-6xl md:text-7xl font-black text-slate-900 dark:text-white tracking-tighter"
                            id="currentBalance">$0.00</h2>
                    </div>
                    <div class="bg-indigo-500/10 p-4 rounded-3xl text-primary border border-primary/10">
                        <i class="bi bi-wallet2 text-3xl"></i>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-6">
                    <div
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-bold border border-emerald-500/20">
                        <i class="bi bi-arrow-up-right"></i> +12.5% <span
                            class="text-[10px] opacity-60 font-medium font-sans">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div class="glass p-6 rounded-3xl border-l-4 border-emerald-500 shadow-sm relative overflow-hidden group">
                <div class="flex justify-between items-center relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Income</p>
                        <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400" id="totalIncome">$0.00
                        </h3>
                    </div>
                    <div class="bg-emerald-500/10 p-2.5 rounded-2xl text-emerald-600">
                        <i class="bi bi-graph-up-arrow text-lg"></i>
                    </div>
                </div>
                <div
                    class="absolute right-0 bottom-0 opacity-[0.03] scale-150 rotate-12 transition group-hover:scale-[1.7] duration-700">
                    <i class="bi bi-graph-up-arrow text-8xl text-emerald-600"></i>
                </div>
            </div>

            <div class="glass p-6 rounded-3xl border-l-4 border-rose-500 shadow-sm relative overflow-hidden group">
                <div class="flex justify-between items-center relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Expenses
                        </p>
                        <h3 class="text-2xl font-black text-rose-600 dark:text-rose-400" id="totalExpense">$0.00</h3>
                    </div>
                    <div class="bg-rose-500/10 p-2.5 rounded-2xl text-rose-600">
                        <i class="bi bi-graph-down-arrow text-lg"></i>
                    </div>
                </div>
                <div
                    class="absolute right-0 bottom-0 opacity-[0.03] scale-150 -rotate-12 transition group-hover:scale-[1.7] duration-700">
                    <i class="bi bi-graph-down-arrow text-8xl text-rose-600"></i>
                </div>
            </div>

            <div class="glass p-6 rounded-3xl border-l-4 border-purple-500 shadow-sm transition-all group cursor-pointer"
                onclick="toggleModal('setBudgetModal')">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Monthly Budget</p>
                    <span class="text-[10px] font-black text-purple-600 bg-purple-500/10 px-2 py-0.5 rounded"
                        id="budgetPercent">0%</span>
                </div>
                <div class="flex items-baseline justify-between mb-3">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white" id="budgetAmount">$0.00</h3>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                    <div id="budgetProgress" class="bg-purple-500 h-full transition-all duration-1000"
                        style="width: 0%"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-8">
        <!-- Cash Flow Trend -->
        <div class="glass p-8 rounded-[2.5rem] shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6 flex items-center justify-between">
                <span class="flex items-center gap-2"><i class="bi bi-activity text-indigo-500"></i> Cash Flow Trend</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Global Analytics</span>
            </h3>
            <div class="h-[350px]">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Transactions Section -->
        <div class="glass rounded-[2.5rem] overflow-hidden shadow-sm">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Transaction History</h3>
                    <p class="text-sm text-slate-500">Precision tracking for your financial footprint</p>
                </div>
                <div class="flex gap-4">
                    <button onclick="exportToCSV()" class="glass px-6 py-3 rounded-2xl font-bold flex items-center gap-2 hover:bg-white/10 transition active:scale-95 text-sm">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <button onclick="toggleModal('addExpenseModal')" class="bg-primary hover:bg-secondary text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all active:scale-95 flex items-center gap-2 text-sm">
                        <i class="bi bi-plus-lg"></i> Add New
                    </button>
                </div>
            </div>

            <div class="px-8 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/10 flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px] relative">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="searchInput" placeholder="Search transactions..." class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:ring-2 focus:ring-primary outline-none transition text-sm">
                </div>
                <select id="typeFilter" class="px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:ring-2 focus:ring-primary outline-none text-sm cursor-pointer dark:text-white">
                    <option value="">All Types</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
                <input type="text" id="categoryFilter" placeholder="Category" class="px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:ring-2 focus:ring-primary outline-none text-sm w-32 dark:text-white">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 dark:bg-slate-800/30">
                        <tr>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Date</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Description</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Category</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Amount</th>
                            <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="expenseTableBody" class="divide-y divide-slate-50 dark:divide-slate-800">
                        <!-- Content via JS -->
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/30 dark:bg-slate-800/10">
                <p class="text-xs font-medium text-slate-500" id="paginationInfo">Showing 0 of 0</p>
                <div class="flex gap-2">
                    <button id="prevPage" class="p-2 rounded-lg border border-slate-200 dark:border-slate-800 hover:bg-white dark:hover:bg-slate-900 transition disabled:opacity-30 disabled:cursor-not-allowed">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button id="nextPage" class="p-2 rounded-lg border border-slate-200 dark:border-slate-800 hover:bg-white dark:hover:bg-slate-900 transition disabled:opacity-30 disabled:cursor-not-allowed">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Category Breakdown -->
        <div class="glass p-10 rounded-[2.5rem] shadow-sm">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-10 flex items-center gap-2">
                <i class="bi bi-pie-chart-fill text-indigo-500"></i> Allocation Analysis
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="h-[300px] flex justify-center relative">
                    <canvas id="categoryChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-[10px] font-black text-slate-400 uppercase">Total Focus</span>
                        <span class="text-2xl font-black text-slate-900 dark:text-white" id="totalFocusAmount">$0</span>
                    </div>
                </div>
                <div id="categoryLegend" class="space-y-4">
                    <!-- Dynamic legend -->
                </div>
            </div>
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

<!-- Set Budget Modal -->
<div id="setBudgetModal"
    class="hidden fixed inset-0 z-[60] flex items-end md:items-center justify-center bg-slate-900/40 backdrop-blur-sm p-0 md:p-4">
    <div
        class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-t-[2.5rem] md:rounded-[2.5rem] shadow-2xl p-10 animate-in slide-in-from-bottom duration-300">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Set Budget</h2>
            <button onclick="toggleModal('setBudgetModal')"
                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <form id="setBudgetForm" class="space-y-6">
            <div
                class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-2xl text-xs text-indigo-600 dark:text-indigo-400 font-medium mb-4">
                Setting a budget helps you keep track of your monthly spending.
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Monthly
                    Limit</label>
                <div class="relative">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                    <input type="number" step="0.01" name="amount" id="budgetInput" required placeholder="0.00"
                        class="w-full pl-10 pr-5 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-primary outline-none transition dark:text-white font-bold">
                </div>
            </div>
            <button type="submit"
                class="w-full bg-primary hover:bg-secondary text-white font-bold py-4 rounded-2xl transition shadow-xl shadow-indigo-100 dark:shadow-none active:scale-95">
                Update Budget
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let categoryChart, trendChart;
    let currentPage = 1;
    let totalPages = 1;

    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-8 right-8 px-6 py-3 rounded-2xl text-white font-bold shadow-2xl z-[100] animate-in slide-in-from-bottom-4 duration-300 ${type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'}`;
        toast.innerHTML = `<div class="flex items-center gap-3"><i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i>${message}</div>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('animate-out', 'fade-out', 'slide-out-to-bottom-4');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function showSkeletons() {
        const tbody = document.getElementById('expenseTableBody');
        tbody.innerHTML = Array(5).fill(0).map(() => `
            <tr class="animate-pulse">
                <td class="px-8 py-6"><div class="h-3 bg-slate-100 dark:bg-slate-800 rounded w-20"></div></td>
                <td class="px-8 py-6">
                    <div class="h-4 bg-slate-100 dark:bg-slate-800 rounded w-40 mb-2"></div>
                    <div class="h-2 bg-slate-50 dark:bg-slate-800/50 rounded w-24"></div>
                </td>
                <td class="px-8 py-6"><div class="h-6 bg-slate-100 dark:bg-slate-800 rounded-full w-20"></div></td>
                <td class="px-8 py-6 text-right"><div class="h-4 bg-slate-100 dark:bg-slate-800 rounded w-16 ml-auto"></div></td>
                <td class="px-8 py-6 text-center"><div class="h-10 bg-slate-100 dark:bg-slate-800 rounded-xl w-10 mx-auto"></div></td>
            </tr>
        `).join('');
    }

    async function loadDashboardData() {
        const tbody = document.getElementById('expenseTableBody');
        showSkeletons();

        const search = document.getElementById('searchInput').value;
        const type = document.getElementById('typeFilter').value;
        const category = document.getElementById('categoryFilter').value;

        const url = `api/get_expenses.php?page=${currentPage}&search=${encodeURIComponent(search)}&type=${type}&category=${encodeURIComponent(category)}`;

        try {
            const response = await fetch(url);
            const data = await response.json();

            document.getElementById('totalIncome').innerText = '$' + data.summary.income.toLocaleString(undefined, { minimumFractionDigits: 2 });
            document.getElementById('totalExpense').innerText = '$' + data.summary.expense.toLocaleString(undefined, { minimumFractionDigits: 2 });
            document.getElementById('currentBalance').innerText = '$' + data.summary.balance.toLocaleString(undefined, { minimumFractionDigits: 2 });

            totalPages = data.pagination.total_pages;
            document.getElementById('paginationInfo').innerText = `Page ${data.pagination.page} of ${data.pagination.total_pages || 1} (${data.pagination.total_items} items)`;
            document.getElementById('prevPage').disabled = currentPage <= 1;
            document.getElementById('nextPage').disabled = currentPage >= totalPages;

            document.getElementById('lastSyncTime').innerText = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            if (data.expenses.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <div class="max-w-xs mx-auto">
                                <div class="bg-indigo-50 dark:bg-indigo-900/20 w-20 h-20 rounded-3xl flex items-center justify-center text-indigo-500 mx-auto mb-6">
                                    <i class="bi bi-plus-square-dotted text-4xl"></i>
                                </div>
                                <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Transactions Yet</h4>
                                <p class="text-sm text-slate-500 mb-8 leading-relaxed">Your financial journey starts here. Add your first income or expense to see insights.</p>
                                <button onclick="toggleModal('addExpenseModal')" class="bg-primary text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-200 dark:shadow-none hover:bg-secondary transition-all">
                                    Log First Transaction
                                </button>
                            </div>
                        </td>
                    </tr>`;
            } else {
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
                            ${item.type === 'income' ? '+' : '-'}$${Math.abs(parseFloat(item.amount)).toFixed(2).toLocaleString()}
                        </td>
                        <td class="px-8 py-6 text-center">
                            <button onclick="deleteExpense(${item.id})" class="text-slate-300 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition p-2.5 rounded-xl">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            }

            renderCharts(data);
            updateBudgetProgress(data.summary.expense);
        } catch (err) {
            tbody.innerHTML = `<tr><td colspan="5" class="p-12 text-center text-rose-500 bg-rose-50/50">Failed to sync with cloud. Check connecton.</td></tr>`;
        }
    }

    async function fetchBudget() {
        try {
            const res = await fetch('api/budget.php');
            const data = await res.json();
            if (data.success) {
                const budget = data.budget || 0;
                document.getElementById('budgetAmount').innerText = '$' + budget.toLocaleString(undefined, { minimumFractionDigits: 2 });
                document.getElementById('budgetInput').value = budget;
                return budget;
            }
        } catch (err) { console.error('Budget fetch failed'); }
        return 0;
    }

    async function updateBudgetProgress(currentExpense) {
        const budget = await fetchBudget();
        const percent = budget > 0 ? Math.min(100, Math.round((currentExpense / budget) * 100)) : 0;
        const progressBar = document.getElementById('budgetProgress');
        const percentText = document.getElementById('budgetPercent');

        progressBar.style.width = percent + '%';
        percentText.innerText = percent + '%';

        if (percent >= 100) {
            progressBar.className = 'bg-rose-500 h-full transition-all duration-1000';
            showToast("You've reached your monthly budget!", 'error');
        } else if (percent >= 80) {
            progressBar.className = 'bg-amber-500 h-full transition-all duration-1000';
        } else {
            progressBar.className = 'bg-purple-500 h-full transition-all duration-1000';
        }
    }

    document.getElementById('setBudgetForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        try {
            const res = await fetch('api/budget.php', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            const result = await res.json();
            if (result.success) {
                showToast("Budget updated successfully!");
                toggleModal('setBudgetModal');
                loadDashboardData();
            } else {
                showToast(result.error, 'error');
            }
        } catch (err) { showToast("Failed to update budget", 'error'); }
    });

    // Filter Listeners
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => { currentPage = 1; loadDashboardData(); }, 500);
    });

    document.getElementById('typeFilter').addEventListener('change', () => { currentPage = 1; loadDashboardData(); });
    document.getElementById('categoryFilter').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => { currentPage = 1; loadDashboardData(); }, 500);
    });

    document.getElementById('prevPage').addEventListener('click', () => { if (currentPage > 1) { currentPage--; loadDashboardData(); } });
    document.getElementById('nextPage').addEventListener('click', () => { if (currentPage < totalPages) { currentPage++; loadDashboardData(); } });

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
        const colors = ['#4361ee', '#10b981', '#f59e0b', '#ef4444', '#7209b7', '#4cc9f0'];

        const cats = {};
        const expensesOnly = data.expenses.filter(e => e.type === 'expense');
        expensesOnly.forEach(e => {
            cats[e.category] = (cats[e.category] || 0) + Math.abs(parseFloat(e.amount));
        });

        const catEntries = Object.entries(cats).sort((a, b) => b[1] - a[1]);
        const totalExp = expensesOnly.reduce((acc, curr) => acc + Math.abs(parseFloat(curr.amount)), 0);
        document.getElementById('totalFocusAmount').innerText = '$' + totalExp.toLocaleString(undefined, { maximumFractionDigits: 0 });

        // Update Custom Legend
        const legend = document.getElementById('categoryLegend');
        if (catEntries.length === 0) {
            legend.innerHTML = `<p class="text-sm text-slate-400 italic">Add expenses to see breakdown</p>`;
        } else {
            legend.innerHTML = catEntries.slice(0, 4).map(([cat, val], i) => `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full" style="background: ${colors[i % colors.length]}"></div>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 capitalize">${cat || 'General'}</span>
                    </div>
                    <span class="text-xs font-black text-slate-900 dark:text-white">${Math.round((val / totalExp) * 100)}%</span>
                </div>
            `).join('');
        }

        const ctxPie = document.getElementById('categoryChart').getContext('2d');
        if (categoryChart) categoryChart.destroy();
        categoryChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: catEntries.map(c => c[0]),
                datasets: [{
                    data: catEntries.map(c => c[1]),
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                cutout: '85%',
                radius: '90%',
                plugins: { legend: { display: false } }
            }
        });

        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        if (trendChart) trendChart.destroy();
        trendChart = new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: data.expenses.slice(0, 15).reverse().map(e => {
                    const d = new Date(e.date);
                    return d.toLocaleDateString([], { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Transaction Flow',
                    data: data.expenses.slice(0, 15).reverse().map(e => e.type === 'income' ? parseFloat(e.amount) : -parseFloat(e.amount)),
                    borderColor: '#4361ee',
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#4361ee',
                    pointBorderColor: isDark ? '#0f172a' : '#ffffff',
                    pointBorderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: (ctx) => {
                        const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(67, 97, 238, 0.15)');
                        gradient.addColorStop(1, 'rgba(67, 97, 238, 0)');
                        return gradient;
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                         backgroundColor: isDark ? '#1e293b' : '#ffffff',
                         titleColor: isDark ? '#f1f5f9' : '#0f172a',
                         bodyColor: isDark ? '#94a3b8' : '#64748b',
                         borderColor: isDark ? '#334155' : '#e2e8f0',
                         borderWidth: 1,
                         padding: 12,
                         displayColors: false,
                         callbacks: {
                             label: (ctx) => '$' + ctx.parsed.y.toLocaleString()
                         }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: textColor, font: { size: 10 } } },
                    y: { 
                        grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)', drawBorder: false }, 
                        ticks: { color: textColor, font: { size: 10 }, callback: (v) => '$' + v } 
                    }
                }
            }
        });
    }

    async function exportToCSV() {
        try {
            const res = await fetch('api/get_expenses.php?limit=1000');
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            const headers = ['Date', 'Title', 'Category', 'Type', 'Amount'];
            const rows = data.expenses.map(e => [
                e.date,
                e.title,
                e.category || 'General',
                e.type,
                e.amount
            ]);

            let csvContent = "data:text/csv;charset=utf-8,"
                + headers.join(",") + "\n"
                + rows.map(r => r.join(",")).join("\n");

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `expenses_export_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast("Export successful!");
        } catch (err) {
            showToast("Export failed: " + err.message, 'error');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadDashboardData();
        fetchBudget();
    });
</script>

<?php include "partials/footer.php"; ?>