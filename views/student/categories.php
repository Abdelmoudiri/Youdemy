<?php
    session_start();

    require_once "../../config/db.php";
    require_once "../../classes/student.php";
    require_once "../../classes/Categorie.php";
    require_once "../../classes/course.php";

    $category = new Categorie();
    $categories = $category->getAllCategories();

    if ($_SESSION['role'] !== 'Etudiant') {
        if ($_SESSION['role'] === 'Admin') {
            header("Location: ../admin/dashboard.php");
        } else if ($_SESSION['role'] === 'Enseignant') {
            header("Location: ../teacher/dashboard.php");
        } else {
            session_unset();
            session_destroy();
            header("Location: ../guest");
            exit;
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['disconnect'])) {
            session_unset();
            session_destroy();
            header("Location: ../guest");
            exit;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories - YouDemy</title>
    <link rel="icon" href="../../assets/img/logo.png">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header>
        <!-- Navbar Section -->
        <nav class="px-5 py-3 flex items-center justify-between gap-5 shadow-md bg-white bg-opacity-90 shadow-lg fixed w-full z-50">
            <a href="../student/" class="flex items-center gap-1">
                <img class="w-14" src="../../assets/img/logo.png" alt="Logo de Youdemy Plateforme">
                <h1 class="text-2xl font-semibold">You<span class="text-blue-800">Demy</span></h1>
            </a>
            <div class="hidden lg:flex items-center justify-between gap-20">
                <ul class="flex items-center gap-10 text-md">
                    <a href="../student/"><li class="cursor-pointer duration-300 hover:text-blue-600 hover:font-medium hover:border-b-2 hover:border-blue-600 hover:pb-3">Accueil</li></a>
                    <a href="#"><li class="active cursor-pointer duration-300">Catégories</li></a>
                    <a href="courses.php"><li class="cursor-pointer duration-300 hover:text-blue-600 hover:font-medium hover:border-b-2 hover:border-blue-600 hover:pb-3">Cours</li></a>
                    <a href="contact.php"><li class="cursor-pointer duration-300 hover:text-blue-600 hover:font-medium hover:border-b-2 hover:border-blue-600 hover:pb-3">Contact</li></a>
                    <a href="my_courses.php"><li class="cursor-pointer duration-300 hover:text-blue-600 hover:font-medium hover:border-b-2 hover:border-blue-600 hover:pb-3">Mes Cours</li></a>
                </ul>
                <form method="POST" class="flex gap-3">
                    <button name="disconnect" class="rounded-sm py-1 px-5 border border-blue-500 text-md text-white bg-blue-600 duration-500 hover:bg-blue-900 hover:border-blue-900">Déconnexion</button>
                </form>
            </div>
            <div class="lg:hidden flex items-center">
                <button class="mobile-menu-button">
                    <i class="fas fa-bars text-blue-600 text-2xl"></i>
                </button>
            </div>
            <div class="bg-white mobile-menu hidden lg:hidden absolute left-0 top-[70px] flex-1 w-full">
                <a href="../student/" class="block py-2 px-4 text-sm hover:bg-blue-600 hover:text-white">Accueil</a>
                <a href="#" class="block py-2 px-4 text-sm hover:bg-blue-600 hover:text-white">Catégories</a>
                <a href="courses.php" class="block py-2 px-4 text-sm hover:bg-blue-600 hover:text-white">Cours</a>
                <a href="contact.php" class="block py-2 px-4 text-sm hover:bg-blue-600 hover:text-white">Contact</a>
                <a href="my_courses.php" class="block py-2 px-4 text-sm hover:bg-blue-600 hover:text-white">Mes Cours</a>
                <form method="POST">
                    <button name="disconnect" class="block py-2 px-4 text-sm hover:bg-blue-600 hover:text-white">Déconnexion</button>
                </form>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="category h-[85vh] pt-24 flex justify-center items-center text-white text-center">
            <div class="flex flex-col items-center gap-5">
                <h1 class="font-bold text-5xl">Explorez nos Catégories</h1>
                <p class="text-xl font-extralight">Découvrez une large gamme de sujets pour enrichir vos connaissances</p>
            </div>
        </section>
    </header>

    <main class="px-5 py-10">
        <section class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Catégories</h1>
            <p class="text-gray-600 text-lg">Découvrez nos différentes catégories de cours.</p>
        </section>

        <section class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php 
                if (empty($categories)) {
                    echo '<div class="col-span-full text-center py-10">
                            <p class="text-gray-600 text-lg">Aucune catégorie n\'est disponible pour le moment.</p>
                          </div>';
                } else {
                    foreach ($categories as $cat) {
            ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">
                            <?php echo htmlspecialchars($cat['nom_categorie']); ?>
                        </h3>
                        <p class="text-gray-600 mb-4">
                            <?php echo htmlspecialchars($cat['description'] ?? 'Aucune description disponible'); ?>
                        </p>
                        <a href="courses.php?category=<?php echo urlencode($cat['nom_categorie']); ?>" 
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            Voir les cours
                        </a>
                    </div>
                </div>
            <?php 
                    }
                }
            ?>
        </section>
    </main>

    <?php include_once '../../includes/footer.php'; ?>


    <script src="../../assets/js/main.js"></script>
</body>
</html>