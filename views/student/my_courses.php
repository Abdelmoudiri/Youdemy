<?php

    session_start();

    require_once '../../config/db.php';
    require_once '../../classes/course.php';
    require_once '../../classes/tag.php';
    require_once '../../classes/student.php';
    require_once '../../classes/teacher.php';
    require_once '../../classes/Categorie.php';
    require_once '../../classes/DocumentCourse.php';
    require_once '../../classes/User.php';

    $cours = new DocumentCourse('', '', '', '', 'pdf', 0, '', '');
    $tg = new Tag('');
    $etudiant = new Student('','','','','','','','');

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

    $etd = $_SESSION['id_user'];
    $courses = $etudiant->subscribedCourses($etd);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy</title>
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
                    <a href="categories.php"><li class="cursor-pointer duration-300 hover:text-blue-600 hover:font-medium hover:border-b-2 hover:border-blue-600 hover:pb-3">Catégories</li></a>
                    <a href="courses.php"><li class="cursor-pointer duration-300 hover:text-blue-600 hover:font-medium hover:border-b-2 hover:border-blue-600 hover:pb-3">Cours</li></a>
                    <a href="contact.php"><li class="cursor-pointer duration-300 hover:text-blue-600 hover:font-medium hover:border-b-2 hover:border-blue-600 hover:pb-3">Contact</li></a>
                    <a href="#"><li class="active cursor-pointer duration-300">Mes Cours</li></a>
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
                <a href="categories.php" class="block py-2 px-4 text-sm hover:bg-blue-600 hover:text-white">Catégories</a>
                <a href="courses.php" class="block py-2 px-4 text-sm hover:bg-blue-600 hover:text-white">Cours</a>
                <a href="contact.php" class="block py-2 px-4 text-sm hover:bg-blue-600 hover:text-white">Contact</a>
                <a href="#" class="block py-2 px-4 text-sm hover:bg-blue-600 hover:text-white">Mes Cours</a>
                <form method="POST">
                    <button name="disconnect" class="block py-2 px-4 text-sm hover:bg-blue-600 hover:text-white">Déconnexion</button>
                </form>
            </div>
        </nav>
    </header>

    
    <main class="pb-10 pt-24 px-5 ">
        <section class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Mes Cours</h1>
            <p class="text-gray-600 text-lg">Voici les cours auxquels vous êtes actuellement inscrit.</p>
        </section>

        <!-- Cours Inscrits -->
        <section class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php 
            if (is_array($courses)) {
                foreach ($courses as $course) {
                    $cour = new DocumentCourse(
                        $course['titre'] ?? '',
                        $course['description'] ?? '',
                        $course['couverture'] ?? '',
                        $course['contenu'] ?? '',
                        $course['format_document'] ?? 'pdf',
                        intval($course['taille'] ?? 0),
                        $course['statut_cours'] ?? '',
                        $course['niveau'] ?? ''
                    );

                    $teacher = new Teacher(
                        0,
                        $course['nom'],
                        $course['prenom'],
                        '',
                        '',
                        '',
                        '',
                        '',
                        $course['photo']
                    );

                    $ctg = new Categorie(
                        $course['nom_categorie'],
                        ''
                    );
            ?>
            <div class="course-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="relative">
                    <img src="../../uploads/<?php echo htmlspecialchars($cour->getCouverture()); ?>" alt="Course" class="w-full h-48 object-cover">
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-2">
                        <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded-full text-xs"><?php echo htmlspecialchars($ctg->getName()); ?></span>
                        <span class="ml-2 text-gray-500 text-sm">•</span>
                        <span class="ml-2 text-gray-500 text-sm"><?php echo htmlspecialchars($cour->getNiveau()); ?></span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 py-2">
                        <?php 
                        $tags = $tg->showCourseTags($course['id_course']);
                        foreach ($tags as $tag) {
                            $tg->setNom($tag['nom_tag']) ?>
                            <span class="text-white bg-blue-500 px-2 text-xs rounded-full"># <?php echo htmlspecialchars($tg->getNom()) ?></span>
                        <?php } ?>
                    </div>
                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($cour->getTitre()); ?></h3>
                    <p class="text-gray-600 mb-4 line-clamp-2"><?php echo htmlspecialchars($cour->getDescription()); ?></p>
                    <div class="flex items-center mb-4">
                        <img src="../../uploads/<?php echo htmlspecialchars($teacher->getPhoto()); ?>" alt="Instructor" class="w-8 h-8 rounded-full">
                        <span class="ml-2 text-gray-600"><?php echo htmlspecialchars($teacher->getPrenom() . ' ' . $teacher->getNom()); ?></span>
                    </div>
                    <div class="flex items-center justify-center">
                        <a href="details.php?id=<?php echo $course['id_course'] ?>" class="w-full">
                            <button type="button" class="font-medium text-white bg-blue-600 w-full py-1 rounded-md hover:bg-blue-800">Voir le Cours</button>
                        </a>
                    </div>
                </div>
            </div>  

            <?php
                }
            }
            ?>
        </section>
    </main>

    <?php include '../../includes/footer.php'; ?>

    <script src="../../assets/js/main.js"></script>
</body>
</html>