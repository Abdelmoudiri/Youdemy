<?php
    session_start();
    require_once '../../classes/Categorie.php';

    if (isset($_SESSION["role"])) {
        if ($_SESSION['role'] === 'Admin') {
            header("Location: ../admin/dashboard.php");
        } else if ($_SESSION['role'] === 'Enseignant') {
            header("Location: ../teacher/dashboard.php");
        } else {
            header("Location: ../student/dashboard.php");
        }
        exit();
    }

    $category = new Categorie();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories - YouDemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header>
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
    </header>

    <main class="px-5 py-20">
        <section class="md:grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php
            $categories = $category->getCoursesPerCategory('Approuvé');
            

            if (is_array($categories)) {
                foreach ($categories as $cat) {
                    if ($cat['total_approved_courses'] > 0) {
            ?>
                        <div class="category-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 mb-5">
                            <div class="relative h-48 bg-black">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <h3 class="text-2xl font-bold text-white"><?php echo htmlspecialchars($cat['nom_categorie']); ?></h3>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-gray-600"><?php echo htmlspecialchars($cat['description_categorie']); ?></p>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-sm text-gray-500"><?php echo $cat['total_approved_courses']; ?> cours disponibles</span>
                                    <a href="courses.php?category=<?php echo $cat['id_categorie']; ?>" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        Voir les cours
                                    </a>
                                </div>
                            </div>
                        </div>
            <?php
                      
                    }
                }
            }
            ?>
        </section>
    </main>
</body>
</html>