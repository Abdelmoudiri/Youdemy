<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Youdemy</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100">
  <div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white">
      <div class="p-4">
        <h1 class="text-2xl font-bold">Youdemy</h1>
        <p class="text-sm text-gray-400">Admin Dashboard</p>
      </div>
      <nav class="mt-6">
        <a href="#" class="block py-2 px-4 text-sm hover:bg-gray-700">Tableau de bord</a>
        <a href="#" class="block py-2 px-4 text-sm hover:bg-gray-700">Utilisateurs</a>
        <a href="#" class="block py-2 px-4 text-sm hover:bg-gray-700">Cours</a>
        <a href="#" class="block py-2 px-4 text-sm hover:bg-gray-700">Statistiques</a>
        <a href="#" class="block py-2 px-4 text-sm hover:bg-gray-700">Paramètres</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Header -->
      <header class="bg-white shadow-sm p-4">
        <div class="flex justify-between items-center">
          <h2 class="text-xl font-semibold">Tableau de bord</h2>
          <div class="flex items-center space-x-4">
            <input type="text" placeholder="Rechercher..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500">
            <button class="p-2 text-gray-600 hover:text-gray-900">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-3 3H9a3 3 0 01-3-3v-1m6 0H9"></path>
              </svg>
            </button>
            <div x-data="{ open: false }" class="relative">
              <button @click="open = !open" class="flex items-center space-x-2">
                <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Admin">
                <span class="text-sm">Admin</span>
              </button>
              <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Paramètres</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Déconnexion</a>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Section -->
      <main class="flex-1 p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-sm font-medium text-gray-500">Utilisateurs</h3>
            <p class="mt-2 text-3xl font-bold">1,234</p>
            <p class="text-sm text-gray-400">+5.2% ce mois-ci</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-sm font-medium text-gray-500">Cours</h3>
            <p class="mt-2 text-3xl font-bold">567</p>
            <p class="text-sm text-gray-400">+12.3% ce mois-ci</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-sm font-medium text-gray-500">Revenus</h3>
            <p class="mt-2 text-3xl font-bold">$12,345</p>
            <p class="text-sm text-gray-400">+8.7% ce mois-ci</p>
          </div>
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-sm font-medium text-gray-500">Engagement</h3>
            <p class="mt-2 text-3xl font-bold">78%</p>
            <p class="text-sm text-gray-400">+3.4% ce mois-ci</p>
          </div>
        </div>

        <!-- Charts -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold">Activité des utilisateurs</h3>
            <canvas id="userActivityChart" class="mt-4"></canvas>
          </div>
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold">Revenus mensuels</h3>
            <canvas id="revenueChart" class="mt-4"></canvas>
          </div>
        </div>

        <!-- Recent Users Table -->
        <div class="mt-8 bg-white rounded-lg shadow-sm">
          <div class="p-6">
            <h3 class="text-lg font-semibold">Utilisateurs récents</h3>
            <div class="mt-4 overflow-x-auto">
              <table class="min-w-full">
                <thead>
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'inscription</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <td class="px-6 py-4">John Doe</td>
                    <td class="px-6 py-4">john@example.com</td>
                    <td class="px-6 py-4">Étudiant</td>
                    <td class="px-6 py-4">2023-10-01</td>
                  </tr>
                  <tr>
                    <td class="px-6 py-4">Jane Smith</td>
                    <td class="px-6 py-4">jane@example.com</td>
                    <td class="px-6 py-4">Enseignant</td>
                    <td class="px-6 py-4">2023-09-25</td>
                  </tr>
                  <!-- Add more rows as needed -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Chart.js Scripts -->
  <script>
    // User Activity Chart
    const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
    new Chart(userActivityCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Utilisateurs actifs',
          data: [65, 59, 80, 81, 56, 72],
          borderColor: '#3b82f6',
          fill: false,
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Revenus',
          data: [1200, 1900, 3000, 2500, 2000, 3000],
          backgroundColor: '#10b981',
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>
</body>
</html>