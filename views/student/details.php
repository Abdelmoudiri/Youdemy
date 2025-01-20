<?php
session_start();

require_once '../../config/db.php';
require_once '../../classes/student.php';
require_once '../../classes/tag.php';

// Vérification de la connexion et du rôle
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Etudiant') {
    $_SESSION['error'] = "Accès non autorisé";
    header('Location: ../../index.php');
    exit;
}

// Vérification de l'ID du cours
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID du cours invalide";
    header('Location: courses.php');
    exit;
}

$courseId = intval($_GET['id']);
$etudiant = new Student('','','','','','','', $_SESSION['id_user']);
$tg = new Tag('');

// Récupération des détails du cours
$course = $etudiant->getCourseDetails($courseId);

if (!$course) {
    $_SESSION['error'] = "Ce cours n'existe pas ou n'est pas accessible.";
    header('Location: courses.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['titre']); ?> - YouDemy</title>
    <link href="../../assets/css/style.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php 
    if (file_exists('../../includes/navbar_student.php')) {
        include '../../includes/navbar_student.php';
    } else {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                Erreur: Navigation non trouvée
              </div>';
    }
    ?>

    <main class="container mx-auto px-4 py-8 mt-20">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php 
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php 
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="relative h-96">
                <img src="../../uploads/<?php echo htmlspecialchars($course['couverture']); ?>" 
                     alt="<?php echo htmlspecialchars($course['titre']); ?>"
                     class="w-full h-full object-cover">
                <div class="absolute top-4 right-4">
                    <span class="px-4 py-2 bg-blue-600 text-white rounded-full text-sm">
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

                <?php if ($etudiant->isEnrolled($_SESSION['id_user'], $courseId)): ?>
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                        Vous êtes déjà inscrit à ce cours
                    </div>
                    <a href="view_course.php?id=<?php echo $courseId; ?>" 
                       class="inline-block w-full text-center bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                        Accéder au cours
                    </a>
                <?php else: ?>
                    <form action="enroll_course.php" method="POST" class="mt-6">
                        <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                            S'inscrire au cours
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php 
    if (file_exists('../../includes/footer.php')) {
        include '../../includes/footer.php';
    }
    ?>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
