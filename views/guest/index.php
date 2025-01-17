<?php
    session_start();
    if (isset($_SESSION["role"])){
        if($_SESSION['role'] === 'Admin'){
            header("Location: ../admin/dashboard.php");
        }else if($_SESSION['role'] === 'Enseignant'){
            header("Location: ../teacher/dashboard.php");
        }else{
            header("Location: ../student/");
        }
        exit;
    }
?>
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouDemy - Plateforme d'apprentissage en ligne</title>
    <link rel="icon" href="../../assets/img/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white fixed w-full z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="#" class="flex items-center space-x-2">
                        <img class="w-10 h-10" src="../../assets/img/logo.png" alt="YouDemy Logo">
                        <span class="text-2xl font-bold">You<span class="text-blue-600">Demy</span></span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-blue-600 font-medium">Accueil</a>
                    <a href="categories.php" class="text-gray-700 hover:text-blue-600 transition">Catégories</a>
                    <a href="courses.php" class="text-gray-700 hover:text-blue-600 transition">Cours</a>
                    <a href="contact.php" class="text-gray-700 hover:text-blue-600 transition">Contact</a>
                    <div class="flex items-center space-x-4">
                        <a href="../auth/login.php" class="px-4 py-2 text-gray-700 hover:text-blue-600 transition">Connexion</a>
                        <a href="../auth/register.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Inscription</a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="mobile-menu-button p-2 rounded-md text-gray-600 hover:text-blue-600 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu hidden md:hidden bg-white border-t">
            <a href="#" class="block py-3 px-4 text-blue-600 font-medium">Accueil</a>
            <a href="categories.php" class="block py-3 px-4 text-gray-700 hover:bg-gray-50">Catégories</a>
            <a href="courses.php" class="block py-3 px-4 text-gray-700 hover:bg-gray-50">Cours</a>
            <a href="contact.php" class="block py-3 px-4 text-gray-700 hover:bg-gray-50">Contact</a>
            <div class="px-4 py-3 space-y-2">
                <a href="../auth/login.php" class="block w-full px-4 py-2 text-center text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Connexion</a>
                <a href="../auth/register.php" class="block w-full px-4 py-2 text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">Inscription</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-600 to-blue-800 min-h-screen flex items-center">
        <div class="absolute inset-0">
            <img src="../../assets/img/hero-pattern.svg" class="w-full h-full object-cover opacity-10">
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Apprenez Sans Limite</h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-10">Découvrez des milliers de cours en ligne dispensés par des experts passionnés</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="courses.php" class="px-8 py-4 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition transform hover:scale-105">
                    Découvrir les cours
                </a>
                <a href="../auth/register.php" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-medium hover:bg-white hover:text-blue-600 transition transform hover:scale-105">
                    Commencer gratuitement
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Pourquoi choisir YouDemy ?</h2>
                <p class="text-xl text-gray-600">Une plateforme conçue pour votre réussite</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-white rounded-xl shadow-xl hover:shadow-2xl transition duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-graduation-cap text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Apprentissage flexible</h3>
                    <p class="text-gray-600">Apprenez à votre rythme, où que vous soyez et quand vous voulez.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-xl hover:shadow-2xl transition duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-chalkboard-teacher text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Experts qualifiés</h3>
                    <p class="text-gray-600">Des instructeurs expérimentés et passionnés pour vous guider.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-xl hover:shadow-2xl transition duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-certificate text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Certificats reconnus</h3>
                    <p class="text-gray-600">Obtenez des certificats validant vos compétences acquises.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Courses Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Cours populaires</h2>
                <p class="text-xl text-gray-600">Découvrez nos cours les plus appréciés</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Course cards will be dynamically populated -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                    <img src="../../assets/img/course1.jpg" alt="Course" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full">Développement Web</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Introduction au HTML/CSS</h3>
                        <p class="text-gray-600 mb-4">Apprenez les bases du développement web moderne.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-blue-600 font-semibold">Gratuit</span>
                            <a href="courses.php" class="text-blue-600 hover:text-blue-800">En savoir plus →</a>
                        </div>
                    </div>
                </div>
                <!-- Add more course cards as needed -->
            </div>
            <div class="text-center mt-12">
                <a href="courses.php" class="inline-block px-8 py-4 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition transform hover:scale-105">
                    Voir tous les cours
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Ce que disent nos étudiants</h2>
                <p class="text-xl text-gray-600">Découvrez les expériences de nos apprenants</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <img src="../../assets/img/user1.jpg" alt="Student" class="w-12 h-12 rounded-full">
                        <div class="ml-4">
                            <h4 class="font-semibold">Sarah M.</h4>
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">"Une expérience d'apprentissage incroyable ! Les cours sont bien structurés et les instructeurs sont très compétents."</p>
                </div>
                <!-- Add more testimonials as needed -->
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-blue-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Prêt à commencer votre voyage d'apprentissage ?</h2>
            <p class="text-xl text-blue-100 mb-10">Rejoignez des milliers d'apprenants qui transforment leur vie grâce à YouDemy</p>
            <a href="../auth/register.php" class="inline-block px-8 py-4 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition transform hover:scale-105">
                Commencer maintenant
            </a>
        </div>
    </section>

    <?php include_once '../../includes/footer.php'; ?>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>