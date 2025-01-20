<?php

session_start();

require_once "../../classes/teacher.php";
require_once "../../classes/Categorie.php";
require_once "../../classes/DocumentCourse.php";
require_once "../../classes/Tag.php";


$enseignant = new Teacher(
    (int)$_SESSION['id_user'],
    $_SESSION['nom'],
    $_SESSION['prenom'],
    '',
    $_SESSION['email'],
    '',
    $_SESSION['role'],
    'Actif',
    $_SESSION['photo']
);

$new_cour = new DocumentCourse('', '', '', '', '', 0, 'En Attente', 'Facile');

if ($_SESSION['role'] !== 'Enseignant') {
    if ($_SESSION['role'] === 'Admin') {
        header("Location: ../admin/dashboard.php");
    } else if ($_SESSION['role'] === 'Etudiant') {
        header("Location: ../student/");
    } else {
        header("Location: ../guest");
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['disconnect'])) {
        session_unset();
        session_destroy();
        header("Location: ../guest");
        exit;
    }
    if (isset($_POST["delete"])) {
        $course = $_POST['course'];
        $delete = $new_cour->deleteCourse($course);
        if ($delete) {
            echo '<script>alert("Cours Supprimé avec Succés !")</script>';
        } else {
            echo '<script>alert("Cours Non Supprimé !")</script>';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Cours</title>
    <script src="https://kit.fontawesome.com/7e51403c1f.js" crossorigin="anonymous"></script>
    <link href="../../assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="../../assets/css/tailwind.css" rel="stylesheet">
</head>

<body>
    <header class="px-5 py-3 bg-white shadow-md fixed w-full z-10">
        <nav class="flex items-center justify-between gap-5 z-10">
            <div class="flex items-center gap-6">
                <div id="burger-menu">
                    <i class="fa-solid fa-bars text-2xl cursor-pointer md:hidden"></i>
                </div>
                <a href="dashboard.php">
                    <img src="../../assets/images/logo.png" alt="logo" class="w-40">
                </a>
            </div>
            <div id="links" class="fixed md:static left-[-500px] top-0 bottom-0 duration-500 bg-white md:bg-transparent shadow-lg md:shadow-none w-[250px] md:w-auto z-[1000]">
                <ul class="flex flex-col md:flex-row gap-5 p-5 md:p-0">
                    <li>
                        <a href="dashboard.php" class="text-gray-600 hover:text-purple-600 duration-300">
                            <i class="fa-solid fa-home"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    <li>
                        <a href="courses.php" class="text-purple-600 hover:text-purple-600 duration-300">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <span>Cours</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile.php" class="text-gray-600 hover:text-purple-600 duration-300">
                            <i class="fa-solid fa-user"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" class="md:hidden">
                            <button type="submit" name="disconnect" class="text-gray-600 hover:text-purple-600 duration-300">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="flex items-center gap-5">
                <form method="POST" class="hidden md:block">
                    <button type="submit" name="disconnect" class="text-gray-600 hover:text-purple-600 duration-300">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </nav>
    </header>

    <main class="bg-gray-100 pt-24 pb-12 px-5">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['success_message']; ?></span>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['error_message']; ?></span>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- HEAD -->
        <section class="flex flex-wrap items-center justify-between gap-5">
            <h1 class="text-3xl text-gray-800 font-bold">Mes Cours</h1>
            <div class="w-full max-w-lg">
                <form class="sm:flex sm:items-center">
                    <input class="inline w-full rounded-md border border-gray-300 bg-white py-2 pl-3 pr-3 leading-5 placeholder-gray-500 focus:border-indigo-500 focus:placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm" placeholder="Rechercher un Cours .." type="search">
                    <button type="submit" class="mt-3 inline-flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    </button>
                </form>
            </div>
            <button type="button" id="btn_ajouter_cour" class="bg-blue-600 text-md text-white py-1 px-4 rounded-md duration-300 hover:px-5 hover:bg-blue-800">Ajouter un Cours</button>
        </section>
        <!-- Modal d'ajout de cours -->
        <div id="addCourseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="bg-white p-8 rounded-lg w-[500px] mx-auto mt-20">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Ajouter un nouveau cours</h2>
                    <button onclick="closeAddCourseModal()" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form action="dashboard.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre du cours</label>
                        <input type="text" name="titre" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" required rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image de couverture</label>
                        <input type="file" name="couverture" accept="image/*" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="text-sm text-gray-500 mt-1">Format accepté : JPG, PNG, GIF (Max: 5MB)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Document du cours (PDF)</label>
                        <input type="file" name="pdf_file" accept=".pdf" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="text-sm text-gray-500 mt-1">Format accepté : PDF uniquement (Max: 5MB)</p>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="closeAddCourseModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" name="add_course"
                                class="px-4 py-2 text-white bg-purple-600 rounded-md hover:bg-purple-700 transition-colors">
                            Ajouter le cours
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Courses -->
        <section class="z-[0] grid md:grid-cols-2 lg:grid-cols-3 gap-5 mt-10">
            <?php
            $courses = $enseignant->displayCourses($enseignant->getId());
            if ($courses) {
                foreach ($courses as $course) {
                    $tags = $new_cour->getCourseTags($course['id_cours']);
            ?>
                    <div class="bg-white shadow-md hover:shadow-lg rounded-md">
                        <div class="relative">
                            <img src="../../uploads/<?php echo $course['couverture'] ?>" alt="course-cover" class="w-full h-48 object-cover rounded-t-md">
                            <div class="absolute top-0 right-0 p-3 flex gap-3">
                                <form method="POST">
                                    <input type="hidden" name="course" value="<?php echo $course['id_cours'] ?>">
                                    <button type="submit" name="delete" class="text-red-600 hover:text-red-800 duration-300">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-200">
                                        <img src="../../uploads/<?php echo $_SESSION['photo'] ?>" alt="profile" class="w-full h-full rounded-full object-cover">
                                    </div>
                                    <div>
                                        <h3 class="text-sm text-gray-800 font-medium"><?php echo $_SESSION['nom'] . " " . $_SESSION['prenom'] ?></h3>
                                        <p class="text-xs text-gray-600">Enseignant</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600"><?php echo $course['date_creation'] ?></span>
                                </div>
                            </div>
                            <div class="mt-5">
                                <h2 class="text-xl text-gray-800 font-bold"><?php echo $course['titre'] ?></h2>
                                <p class="text-gray-600 mt-3"><?php echo $course['description'] ?></p>
                            </div>
                            <div class="mt-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-tag text-purple-600"></i>
                                        <span class="text-sm text-gray-600">Tags:</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <?php
                                        if ($tags) {
                                            foreach ($tags as $tag) {
                                        ?>
                                                <span class="text-sm text-gray-600"><?php echo $tag['nom_tag'] ?></span>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-circle text-<?php echo $course['statut'] === 'En Attente' ? 'yellow' : ($course['statut'] === 'Approuvé' ? 'green' : 'red') ?>-600"></i>
                                            <span class="text-sm text-gray-600"><?php echo $course['statut'] ?></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-circle text-<?php echo $course['niveau'] === 'Facile' ? 'green' : ($course['niveau'] === 'Moyen' ? 'yellow' : 'red') ?>-600"></i>
                                            <span class="text-sm text-gray-600"><?php echo $course['niveau'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </section>
    </main>

    <script>
        let list = document.querySelector('#links');
        const menu = document.querySelector('#burger-menu');

        menu.addEventListener('click', function() {
            list.classList.toggle('left-0');
            list.classList.toggle('left-[-500px]');
        });

        // Fonctions pour le modal
        function openAddCourseModal() {
            document.getElementById('addCourseModal').classList.remove('hidden');
        }

        function closeAddCourseModal() {
            document.getElementById('addCourseModal').classList.add('hidden');
        }

        // Gestionnaire de clic pour le bouton d'ajout
        document.getElementById("btn_ajouter_cour").addEventListener("click", function() {
            openAddCourseModal();
        });

        // Fermer le modal si on clique en dehors
        document.getElementById('addCourseModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddCourseModal();
            }
        });
    </script>

</body>

</html>