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

    if (!isset($_GET['id'])) {
        header('Location: courses.php');
        exit;
    }

    $courseId = $_GET['id'];
    $etudiant = new Student('','','','','','','', $_SESSION['id_user']);
    $tg = new Tag('');

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

    // Récupérer les détails du cours
    $course = $etudiant->getCourseDetails($courseId);
    if (!$course) {
        header('Location: courses.php');
        exit;
    }

    // Créer une instance de DocumentCourse avec les détails du cours
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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['titre']); ?> - YouDemy</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <header>
        <?php include_once '../../includes/navbar_student.php'; ?>
    </header>

    <main class="pb-10 pt-24 px-5">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="relative h-64">
                    <img src="../../uploads/<?php echo htmlspecialchars($course['couverture']); ?>" 
                         alt="<?php echo htmlspecialchars($course['titre']); ?>"
                         class="w-full h-full object-cover">
                    <div class="absolute top-0 right-0 mt-4 mr-4">
                        <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-sm">
                            <?php echo htmlspecialchars($course['niveau']); ?>
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        <?php echo htmlspecialchars($course['titre']); ?>
                    </h1>

                    <div class="flex items-center mb-6">
                        <img src="../../uploads/<?php echo htmlspecialchars($course['photo']); ?>" 
                             alt="<?php echo htmlspecialchars($course['prenom'] . ' ' . $course['nom']); ?>"
                             class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <p class="font-medium text-gray-900">
                                <?php echo htmlspecialchars($course['prenom'] . ' ' . $course['nom']); ?>
                            </p>
                            <p class="text-sm text-gray-500">Enseignant</p>
                        </div>
                    </div>

                    <div class="prose max-w-none mb-6">
                        <h2 class="text-xl font-semibold mb-2">Description</h2>
                        <p class="text-gray-600">
                            <?php echo nl2br(htmlspecialchars($course['description'])); ?>
                        </p>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-2">Catégorie</h2>
                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full">
                            <?php echo htmlspecialchars($course['nom_categorie']); ?>
                        </span>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-2">Tags</h2>
                        <div class="flex flex-wrap gap-2">
                            <?php 
                            $tags = $tg->showCourseTags($courseId);
                            if (!empty($tags)) {
                                foreach ($tags as $tag) {
                                    echo '<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">' . 
                                         htmlspecialchars($tag['nom_tag']) . 
                                         '</span>';
                                }
                            } else {
                                echo '<p class="text-gray-500">Aucun tag associé à ce cours</p>';
                            }
                            ?>
                        </div>
                    </div>

                    <?php if ($etudiant->isEnrolled($_SESSION['id_user'], $courseId)) { ?>
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                            Vous êtes déjà inscrit à ce cours
                        </div>
                        <a href="view_course.php?id=<?php echo $courseId; ?>" 
                           class="inline-block w-full text-center bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                            Accéder au cours
                        </a>
                    <?php } else { ?>
                        <form action="enroll_course.php" method="POST" class="mt-6">
                            <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
                            <button type="submit" name="enroll" 
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                                S'inscrire au cours
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>

    <?php include '../../includes/footer.php'; ?>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
