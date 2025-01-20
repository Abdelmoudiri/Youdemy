<?php
    session_start();
    require_once '../../classes/CourseManager.php';

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

    $courseManager = new CourseManager();
    $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 12;
    $offset = ($page - 1) * $limit;

    if ($categoryId) {
        $courses = $courseManager->getCoursesByCategory($categoryId, 'Approuvé', $limit, $offset);
        $totalCourses = $courseManager->countCoursesByCategory($categoryId, 'Approuvé');
    } else {
        $courses = $courseManager->getCourses('Approuvé', $limit, $offset);
        $totalCourses = $courseManager->countCourses('Approuvé');
    }

    $totalPages = ceil($totalCourses / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cours - YouDemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                        <a href="#" class="text-gray-700 hover:text-blue-600 transition">Accueil</a>
                        <a href="categories.php" class="text-gray-700 hover:text-blue-600 transition">Catégories</a>
                        <a href="courses.php" class="text-blue-600 font-medium">Cours</a>
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
                <a href="#" class="block py-3 px-4 text-gray-700 hover:bg-gray-50">Accueil</a>
                <a href="categories.php" class="block py-3 px-4 text-gray-700 hover:bg-gray-50">Catégories</a>
                <a href="courses.php" class="block py-3 px-4 text-blue-600 font-medium">Cours</a>
                <a href="contact.php" class="block py-3 px-4 text-gray-700 hover:bg-gray-50">Contact</a>
                <div class="px-4 py-3 space-y-2">
                    <a href="../auth/login.php" class="block w-full px-4 py-2 text-center text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Connexion</a>
                    <a href="../auth/register.php" class="block w-full px-4 py-2 text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">Inscription</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="px-5 py-20">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold mb-8">Nos Cours</h1>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php if ($courses): ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                            <img src="../../assets/img/courses/<?php echo htmlspecialchars($course['couverture']); ?>" 
                                 alt="<?php echo htmlspecialchars($course['titre']); ?>"
                                 class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="font-bold text-xl mb-2"><?php echo htmlspecialchars($course['titre']); ?></h3>
                                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($course['description'], 0, 100)) . '...'; ?></p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <img src="../../assets/img/users/<?php echo htmlspecialchars($course['photo']); ?>" 
                                             alt="<?php echo htmlspecialchars($course['prenom'] . ' ' . $course['nom']); ?>"
                                             class="w-8 h-8 rounded-full">
                                        <span class="text-sm text-gray-600">
                                            <?php echo htmlspecialchars($course['prenom'] . ' ' . $course['nom']); ?>
                                        </span>
                                    </div>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        <?php echo htmlspecialchars($course['niveau']); ?>
                                    </span>
                                </div>
                                <a href="course-details.php?id=<?php echo $course['id_course']; ?>" 
                                   class="mt-4 block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Voir le cours
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="col-span-full text-center text-gray-600">Aucun cours disponible pour le moment.</p>
                <?php endif; ?>
            </div>

            <?php if ($totalPages > 1): ?>
            <div class="mt-8 flex justify-center space-x-4">
                <?php if ($page > 1): ?>
                    <a href="?<?php echo $categoryId ? "category=$categoryId&" : ""; ?>page=<?php echo $page - 1; ?>" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Précédent
                    </a>
                <?php endif; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?<?php echo $categoryId ? "category=$categoryId&" : ""; ?>page=<?php echo $page + 1; ?>" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Suivant
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>

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