<?php
if (!isset($_SESSION)) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un étudiant
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Etudiant') {
    header('Location: ../../index.php');
    exit;
}
?>

<nav class="bg-white shadow-lg fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="../../index.php" class="text-2xl font-bold text-blue-600">
                    YouDemy
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex space-x-8">
                <a href="../student/index.php" 
                   class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-900">
                    Accueil
                </a>
                <a href="../student/courses.php" 
                   class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-900">
                    Tous les cours
                </a>
                <a href="../student/my_courses.php" 
                   class="inline-flex items-center px-1 pt-1 text-gray-500 hover:text-gray-900">
                    Mes cours
                </a>
            </div>

            <!-- User Menu -->
            <div class="flex items-center">
                <div class="ml-3 relative group">
                    <div class="flex items-center cursor-pointer">
                        <img class="h-8 w-8 rounded-full object-cover" 
                             src="../../uploads/<?php echo htmlspecialchars($_SESSION['photo'] ?? 'user.png'); ?>" 
                             alt="Photo de profil">
                        <span class="ml-2 text-gray-700">
                            <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?>
                        </span>
                    </div>
                    <!-- Dropdown Menu -->
                    <div class="hidden group-hover:block absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                        <div class="py-1">
                            <a href="../student/profile.php" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Mon profil
                            </a>
                            <a href="../../logout.php" 
                               class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
