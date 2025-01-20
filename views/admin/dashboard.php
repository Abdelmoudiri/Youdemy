<?php
session_start();
include_once "../../classes/Admin.php";
include_once "../../classes/Tag.php";

$admin = new Admin("", "", "", "", "", "");
$tag = new Tag("");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Croisé</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 h-screen bg-white fixed left-0 top-0 shadow-lg">
            <div class="flex items-center justify-center h-16 border-b">
                <h1 class="text-xl font-bold">Croisé Admin</h1>
            </div>
            <nav class="mt-6">
                <div class="px-4 py-2">
                    <ul class="space-y-2">
                        <li>
                            <a href="#dashboard" data-section="dashboard" class="nav-link w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-home mr-3"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="#courses" data-section="courses" class="nav-link w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-book mr-3"></i>
                                <span>Courses</span>
                            </a>
                        </li>
                        <li>
                            <a href="#categories" data-section="categories" class="nav-link w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-folder mr-3"></i>
                                <span>Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="#tags" data-section="tags" class="nav-link w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-tags mr-3"></i>
                                <span>Tags</span>
                            </a>
                        </li>
                        <li>
                            <a href="#teachers" data-section="teachers" class="nav-link w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-chalkboard-teacher mr-3"></i>
                                <span>Teachers</span>
                            </a>
                        </li>
                        <li>
                            <a href="#students" data-section="students" class="nav-link w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-user-graduate mr-3"></i>
                                <span>Students</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1 p-8">
            <!-- Dashboard Section -->
            <section id="dashboard" class="section">
                <h2 class="text-2xl font-semibold mb-6">Dashboard Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Statistics Cards -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm font-medium">Total Courses</h3>
                        <p class="text-2xl font-bold"><?php echo $admin->getNumCourses()['num']; ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm font-medium">Total Teachers</h3>
                        <p class="text-2xl font-bold"><?php echo $admin->getNumTeachers()['num']; ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm font-medium">Total Students</h3>
                        <p class="text-2xl font-bold"><?php echo $admin->getNumStudents()['num']; ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm font-medium">Categories</h3>
                        <p class="text-2xl font-bold"><?php echo $admin->getNumCategories()['num']; ?></p>
                    </div>
                </div>

                <!-- Available Courses -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Available Courses</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($admin->showCourses() as $course): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['titre']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($course['prenom'] . ' ' . $course['nom']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= $course['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($course['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                            <?= ucfirst(htmlspecialchars($course['status'] ?? 'pending')) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php if ($course['status'] === 'pending'): ?>
                                        <button onclick="approveCourse(<?= $course['id_course'] ?>)" 
                                                class="text-green-600 hover:text-green-900 mr-3">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="refuseCourse(<?= $course['id_course'] ?>)" 
                                                class="text-red-600 hover:text-red-900 mr-3">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                        <button onclick="deleteCourse(<?= $course['id_course'] ?>)" 
                                                class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Categories Section -->
            <section id="categories" class="section hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Gestion des catégories</h2>
                        <button onclick="openModal('categoryModal')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Nouvelle catégorie
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($admin->showCategories() as $category): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($category['nom_categorie']) ?></td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($category['description']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="editCategory(<?= $category['id_categorie'] ?>, '<?= addslashes($category['nom_categorie']) ?>', '<?= addslashes($category['description']) ?>')" 
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="confirmDelete('category', <?= $category['id_categorie'] ?>)" 
                                                class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Tags Section -->
            <section id="tags" class="section hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Gestion des tags</h2>
                        <button onclick="openModal('tagModal')" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-2"></i>Nouveau tag
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($admin->showTags() as $tag): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($tag['nom_tag']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="editTag(<?= $tag['id_tag'] ?>, '<?= addslashes($tag['nom_tag']) ?>')" 
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="confirmDelete('tag', <?= $tag['id_tag'] ?>)" 
                                                class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Teachers Section -->
            <section id="teachers" class="section hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Teachers Management</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courses</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($admin->showTeachers() as $teacher): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($teacher['prenom'] . ' ' . $teacher['nom']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($teacher['email']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $teacher['course_count'] ?? '0' ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= ($teacher['status'] ?? 'pending') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= ucfirst(htmlspecialchars($teacher['status'] ?? 'pending')) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php if (($teacher['status'] ?? 'pending') === 'blocked'): ?>
                                        <button onclick="activateTeacher(<?= $teacher['id_user'] ?>)" 
                                                class="text-green-600 hover:text-green-900 mr-3">
                                            <i class="fas fa-check"></i> Activate
                                        </button>
                                        <?php else: ?>
                                        <button onclick="blockTeacher(<?= $teacher['id_user'] ?>)" 
                                                class="text-yellow-600 hover:text-yellow-900 mr-3">
                                            <i class="fas fa-ban"></i> Block
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Students Section -->
            <section id="students" class="section hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Students Management</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled Courses</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($admin->showStudents() as $student): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($student['prenom'] . ' ' . $student['nom']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($student['email']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $student['enrolled_courses'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= $student['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= ucfirst($student['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php if ($student['status'] === 'blocked'): ?>
                                        <button onclick="activateStudent(<?= $student['id_user'] ?>)" 
                                                class="text-green-600 hover:text-green-900 mr-3">
                                            <i class="fas fa-check"></i> Activate
                                        </button>
                                        <?php else: ?>
                                        <button onclick="blockStudent(<?= $student['id_user'] ?>)" 
                                                class="text-yellow-600 hover:text-yellow-900 mr-3">
                                            <i class="fas fa-ban"></i> Block
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Category Modal -->
    <div id="categoryModal" class="modal">
        <div class="bg-white rounded-lg p-6 w-96">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold" id="categoryModalTitle">Nouvelle catégorie</h2>
                <button onclick="closeModal('categoryModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="categoryForm" action="../../includes/category.inc.php" method="post">
                <input type="hidden" name="category_id" id="category_id">
                <div class="mb-4">
                    <label for="category_name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="category_name" id="category_name" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="category_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="category_description" id="category_description" rows="3" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('categoryModal')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tag Modal -->
    <div id="tagModal" class="modal">
        <div class="bg-white rounded-lg p-6 w-96">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold" id="tagModalTitle">Nouveau tag</h2>
                <button onclick="closeModal('tagModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="tagForm" action="../../includes/tag.inc.php" method="post">
                <input type="hidden" name="tag_id" id="tag_id">
                <div class="mb-4">
                    <label for="tag_name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="tag_name" id="tag_name" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('tagModal')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Function to show section
            function showSection(sectionId) {
                // Hide all sections
                document.querySelectorAll('.section').forEach(section => {
                    section.classList.add('hidden');
                });
                
                // Show selected section
                const targetSection = document.getElementById(sectionId);
                if (targetSection) {
                    targetSection.classList.remove('hidden');
                }
                
                // Update active state in navigation
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('bg-gray-100');
                    if (link.getAttribute('href') === '#' + sectionId) {
                        link.classList.add('bg-gray-100');
                    }
                });
            }

            // Handle navigation clicks
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const sectionId = this.getAttribute('data-section');
                    showSection(sectionId);
                    window.location.hash = sectionId;
                });
            });

            // Handle initial load and browser back/forward
            function handleHashChange() {
                const hash = window.location.hash.slice(1) || 'dashboard';
                showSection(hash);
            }

            window.addEventListener('hashchange', handleHashChange);
            handleHashChange(); // Handle initial load
        });

        // Course actions
        function approveCourse(courseId) {
            if (confirm('Are you sure you want to approve this course?')) {
                window.location.href = `../../includes/course.inc.php?action=approve&id=${courseId}`;
            }
        }

        function refuseCourse(courseId) {
            if (confirm('Are you sure you want to refuse this course?')) {
                window.location.href = `../../includes/course.inc.php?action=refuse&id=${courseId}`;
            }
        }

        function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
                window.location.href = `../../includes/course.inc.php?action=delete&id=${courseId}`;
            }
        }

        // Teacher actions
        function activateTeacher(teacherId) {
            if (confirm('Are you sure you want to activate this teacher?')) {
                window.location.href = `../../includes/user.inc.php?action=activate&id=${teacherId}&role=teacher`;
            }
        }

        function blockTeacher(teacherId) {
            if (confirm('Are you sure you want to block this teacher?')) {
                window.location.href = `../../includes/user.inc.php?action=block&id=${teacherId}&role=teacher`;
            }
        }

        // Student actions
        function activateStudent(studentId) {
            if (confirm('Are you sure you want to activate this student?')) {
                window.location.href = `../../includes/user.inc.php?action=activate&id=${studentId}&role=student`;
            }
        }

        function blockStudent(studentId) {
            if (confirm('Are you sure you want to block this student?')) {
                window.location.href = `../../includes/user.inc.php?action=block&id=${studentId}&role=student`;
            }
        }
    </script>
</body>
</html>