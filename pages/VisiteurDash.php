<?php
session_start();

include_once('../../classes/Article.php');
include_once('../../classes/Categorie.php');
include_once('../../classes/User.php');
include_once('../../classes/database.php');
$user_name = $_SESSION['user_name'];
if( $_SESSION['role'] ==='admin'){
    header("Location: AdminDash.php");
}
if( $_SESSION['role'] ==='auteur'){
    header("Location: AuteurDash.php");
}
var_dump($_SESSION);
$articles = [];
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$keyword_filter = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$author_filter = isset($_GET['author']) ? $_GET['author'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3; 
$offset = ($page - 1) * $limit;

$query = "SELECT a.id_article, a.titre, a.content, a.datePublication, a.image, c.nom AS categorie, u.firstname AS auteur 
          FROM Article a 
          JOIN Categorie c ON a.id_categorie = c.id_categorie 
          JOIN User u ON a.id_auteur = u.id_user 
          WHERE a.etat = 'Accepter'";

if ($category_filter) {
    $query .= " AND c.nom = :category";
}
if ($keyword_filter) {
    $query .= " AND (a.titre LIKE :keyword OR a.content LIKE :keyword)";
}
if ($author_filter) {
    $query .= " AND u.firstname LIKE :author";
}

$query .= " ORDER BY a.datePublication DESC LIMIT :limit OFFSET :offset";

$stmt = $conn->prepare($query);

if ($category_filter) {
    $stmt->bindParam(':category', $category_filter, PDO::PARAM_STR);
}
if ($keyword_filter) {
    $keyword_param = "%" . $keyword_filter . "%";
    $stmt->bindParam(':keyword', $keyword_param, PDO::PARAM_STR);
}
if ($author_filter) {
    $author_param = "%" . $author_filter . "%";
    $stmt->bindParam(':author', $author_param, PDO::PARAM_STR);
}

$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

if ($stmt && $stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $articles[] = $row;
    }
} else {
    echo "Aucun article trouvé.";
}

$categories = [];
$category_query = "SELECT DISTINCT nom FROM Categorie";
$category_stmt = $conn->query($category_query);
if ($category_stmt && $category_stmt->rowCount() > 0) {
    while ($category = $category_stmt->fetch(PDO::FETCH_ASSOC)) {
        $categories[] = $category['nom'];
    }
}

$total_query = "SELECT COUNT(*) 
                FROM Article a 
                JOIN Categorie c ON a.id_categorie = c.id_categorie 
                JOIN User u ON a.id_auteur = u.id_user 
                WHERE a.etat = 'Accepter'";

if ($category_filter) {
    $total_query .= " AND c.nom = :category";
}
if ($keyword_filter) {
    $total_query .= " AND (a.titre LIKE :keyword OR a.content LIKE :keyword)";
}
if ($author_filter) {
    $total_query .= " AND u.firstname LIKE :author";
}

$total_stmt = $conn->prepare($total_query);

if ($category_filter) {
    $total_stmt->bindParam(':category', $category_filter, PDO::PARAM_STR);
}
if ($keyword_filter) {
    $total_stmt->bindParam(':keyword', $keyword_param, PDO::PARAM_STR);
}
if ($author_filter) {
    $total_stmt->bindParam(':author', $author_param, PDO::PARAM_STR);
}

$total_stmt->execute();
$total_articles = $total_stmt->fetchColumn();
$total_pages = ceil($total_articles / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Articles</title>
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-gray-800 p-4 text-white flex justify-between items-center">
        <h1 class="text-xl font-bold">Article BLOG</h1>
        <p class="text-sm">Bienvenue, <?= htmlspecialchars($user_name) ?> !</p>
        <a href="logout.php" class="text-white bg-red-600 px-4 py-2 rounded hover:bg-red-700">Logout</a>
    </header>

    <!-- Main Content -->
    <section class="py-6 sm:py-12 dark:bg-gray-100 dark:text-gray-800">
        <div class="container p-6 mx-auto space-y-8">
            <div class="space-y-2 text-center">
                <h2 class="text-3xl font-bold">ARTICLES</h2>
                <p class="font-serif text-sm dark:text-gray-600">BEST BLOG EVER</p>
            </div>

            <!-- Formulaire de recherche avancée -->
            <div class="text-center mb-6">
                <form method="GET" action="" class="flex justify-center space-x-4">
                    <div>
                        <label for="keyword" class="text-lg font-medium text-gray-700">Recherche par mot-clé :</label>
                        <input type="text" name="keyword" id="keyword" value="<?= htmlspecialchars($keyword_filter) ?>" class="ml-4 p-2 border rounded" placeholder="Rechercher des articles...">
                    </div>

                    <div>
                        <label for="author" class="text-lg font-medium text-gray-700">Recherche par auteur :</label>
                        <input type="text" name="author" id="author" value="<?= htmlspecialchars($author_filter) ?>" class="ml-4 p-2 border rounded" placeholder="Rechercher un auteur...">
                    </div>

                    <button type="submit" class="ml-4 p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Rechercher</button>
                </form>
            </div>

            <!-- Filtre par catégorie -->
            <div class="text-center mb-6">
                <form method="GET" action="" class="flex justify-center space-x-4">
                    <label for="category" class="text-lg font-medium text-gray-700">Trier par catégorie :</label>
                    <select name="category" id="category" class="ml-4 p-2 border rounded">
                        <option value="">-- Sélectionner une catégorie --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category) ?>" <?= $category === $category_filter ? 'selected' : '' ?>><?= htmlspecialchars($category) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="ml-4 p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Trier</button>
                </form>
            </div>

            <!-- Liste des articles -->
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <?php
                foreach ($articles as $article): ?>
                <article class="relative bg-white shadow-md rounded-md overflow-hidden">
                    <img class="object-cover w-full h-52" src="<?= htmlspecialchars($article['image']) ?: 'https://source.unsplash.com/200x200/?fashion' ?>" alt="Image de l'article">
                    <div class="p-4">
                        <p class="text-sm text-gray-500"><?= date("F j, Y", strtotime($article['datePublication'])) ?></p>
                        <h1 class="text-gray-900 font-semibold text-xl mt-2 mb-3"><?= htmlspecialchars($article['titre']) ?></h1>
                        <p class="text-gray-700 text-md"><?= htmlspecialchars($article['content']) ?></p>
                    </div>
                    <div class="absolute top-4 right-4 bg-white bg-opacity-80 py-1 px-3 rounded-md text-xs">
                        <p class="text-gray-700"><?= htmlspecialchars($article['categorie']) ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center space-x-2 mt-6">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&keyword=<?= urlencode($keyword_filter) ?>&author=<?= urlencode($author_filter) ?>&category=<?= urlencode($category_filter) ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Précédent</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>&keyword=<?= urlencode($keyword_filter) ?>&author=<?= urlencode($author_filter) ?>&category=<?= urlencode($category_filter) ?>" class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 <?= $i === $page ? 'bg-blue-500 text-white' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>&keyword=<?= urlencode($keyword_filter) ?>&author=<?= urlencode($author_filter) ?>&category=<?= urlencode($category_filter) ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Suivant</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center p-4">
        <p>&copy; 2025 Article BLOG. Tous droits réservés.</p>
    </footer>

</body>
</html>