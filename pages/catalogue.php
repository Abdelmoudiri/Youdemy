<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Catalogue de cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .course-card {
            animation: fadeInUp 0.6s ease-out;
            transition: all 0.3s ease;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .course-image {
            transition: transform 0.3s ease;
        }
        .course-card:hover .course-image {
            transform: scale(1.05);
        }
        .course-tag {
            transition: background-color 0.3s ease;
        }
        .course-card:hover .course-tag {
            background-color: #3B82F6;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="VisiteurDash.php" class="text-3xl font-bold text-blue-600">Youdemy</a>
            <div class="flex items-center">
                <a href="VisiteurDash.php" class="text-gray-800 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Accueil</a>
                <a href="#" class="text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Catalogue</a>
                <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition duration-300 ml-4">Créer un compte</a>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-8">
        <h1 class="text-4xl font-bold mb-8 text-center">Catalogue de cours</h1>

        <div class="mb-8">
            <form id="search-form" class="flex flex-col md:flex-row gap-4">
                <input type="text" id="search-input" placeholder="Rechercher un cours" class="flex-grow px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                <select id="category-filter" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Toutes les catégories</option>
                    <option value="Développement Web">Développement Web</option>
                    <option value="Marketing Digital">Marketing Digital</option>
                    <option value="Intelligence Artificielle">Intelligence Artificielle</option>
                    <option value="Design">Design</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-300">Rechercher</button>
            </form>
        </div>

        <div id="courses-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Les cartes de cours seront générées dynamiquement ici -->
        </div>

        <div id="pagination" class="mt-8 flex justify-center space-x-2">
            <!-- Les boutons de pagination seront générés dynamiquement ici -->
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap justify-between">
                <div class="w-full md:w-1/4 mb-6 md:mb-0">
                    <h3 class="text-xl font-semibold mb-2">Youdemy</h3>
                    <p class="text-gray-400">Plateforme d'apprentissage en ligne pour tous.</p>
                </div>
                <div class="w-full md:w-1/4 mb-6 md:mb-0">
                    <h4 class="text-lg font-semibold mb-2">Liens rapides</h4>
                    <ul>
                        <li><a href="index.html" class="text-gray-400 hover:text-white">Accueil</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Catalogue</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Créer un compte</a></li>
                    </ul>
                </div>
                <div class="w-full md:w-1/4 mb-6 md:mb-0">
                    <h4 class="text-lg font-semibold mb-2">Nous contacter</h4>
                    <p class="text-gray-400">Email : contact@youdemy.com</p>
                    <p class="text-gray-400">Téléphone : +33 1 23 45 67 89</p>
                </div>
                <div class="w-full md:w-1/4">
                    <h4 class="text-lg font-semibold mb-2">Suivez-nous</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8 text-center">
                <p class="text-gray-400">&copy; 2025 Youdemy. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        // Données des cours (normalement, ces données viendraient d'une API ou d'une base de données)
        const courses = [
            { id: 1, title: "Introduction au développement web", description: "Apprenez les bases du HTML, CSS et JavaScript.", category: "Développement Web", image: "https://via.placeholder.com/400x200", rating: 4.8 },
            { id: 2, title: "Marketing digital avancé", description: "Maîtrisez les stratégies de marketing en ligne.", category: "Marketing Digital", image: "https://via.placeholder.com/400x200", rating: 4.9 },
            { id: 3, title: "Intelligence artificielle pour débutants", description: "Découvrez les concepts fondamentaux de l'IA.", category: "Intelligence Artificielle", image: "https://via.placeholder.com/400x200", rating: 4.7 },
            { id: 4, title: "Design UX/UI moderne", description: "Créez des interfaces utilisateur attrayantes et fonctionnelles.", category: "Design", image: "https://via.placeholder.com/400x200", rating: 4.6 },
            { id: 5, title: "Développement d'applications mobiles", description: "Apprenez à créer des apps pour iOS et Android.", category: "Développement Web", image: "https://via.placeholder.com/400x200", rating: 4.8 },
            { id: 6, title: "SEO et référencement naturel", description: "Optimisez votre site pour les moteurs de recherche.", category: "Marketing Digital", image: "https://via.placeholder.com/400x200", rating: 4.7 },
            { id: 7, title: "Machine Learning avec Python", description: "Maîtrisez les algorithmes de machine learning.", category: "Intelligence Artificielle", image: "https://via.placeholder.com/400x200", rating: 4.9 },
            { id: 8, title: "Photographie numérique avancée", description: "Perfectionnez vos compétences en photographie.", category: "Design", image: "https://via.placeholder.com/400x200", rating: 4.5 },
            { id: 9, title: "Cybersécurité pour les entreprises", description: "Protégez votre entreprise contre les cyberattaques.", category: "Développement Web", image: "https://via.placeholder.com/400x200", rating: 4.8 },
        ];

        const coursesPerPage = 6;
        let currentPage = 1;

        function displayCourses(coursesToShow) {
            const container = document.getElementById('courses-container');
            container.innerHTML = '';

            coursesToShow.forEach(course => {
                const courseCard = `
                    <div class="course-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="relative overflow-hidden">
                            <img src="${course.image}" alt="${course.title}" class="course-image w-full h-48 object-cover">
                            <div class="absolute top-0 left-0 mt-4 ml-4">
                                <span class="course-tag bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">${course.category}</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-bold text-xl mb-2">${course.title}</h3>
                            <p class="text-gray-600 mb-4">${course.description}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-600 font-semibold">${course.rating} ★★★★☆</span>
                                <a href="#" class="text-blue-600 font-semibold hover:underline">En savoir plus</a>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += courseCard;
            });
        }

        function updatePagination(totalPages) {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                const button = document.createElement('button');
                button.textContent = i;
                button.classList.add('px-4', 'py-2', 'rounded', 'bg-blue-600', 'text-white', 'hover:bg-blue-700');
                if (i === currentPage) {
                    button.classList.add('bg-blue-800');
                }
                button.addEventListener('click', () => {
                    currentPage = i;
                    const filteredCourses = filterCourses();
                    const paginatedCourses = paginateCourses(filteredCourses);
                    displayCourses(paginatedCourses);
                    updatePagination(Math.ceil(filteredCourses.length / coursesPerPage));
                });
                paginationContainer.appendChild(button);
            }
        }

        function paginateCourses(coursesToPaginate) {
            const startIndex = (currentPage - 1) * coursesPerPage;
            const endIndex = startIndex + coursesPerPage;
            return coursesToPaginate.slice(startIndex, endIndex);
        }

        function filterCourses() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const categoryFilter = document.getElementById('category-filter').value;

            return courses.filter(course => {
                const matchesSearch = course.title.toLowerCase().includes(searchTerm) || course.description.toLowerCase().includes(searchTerm);
                const matchesCategory = categoryFilter === '' || course.category === categoryFilter;
                return matchesSearch && matchesCategory;
            });
        }

        document.getElementById('search-form').addEventListener('submit', (e) => {
            e.preventDefault();
            currentPage = 1;
            const filteredCourses = filterCourses();
            const paginatedCourses = paginateCourses(filteredCourses);
            displayCourses(paginatedCourses);
            updatePagination(Math.ceil(filteredCourses.length / coursesPerPage));
        });

        // Initial display
        const initialFilteredCourses = filterCourses();
        const initialPaginatedCourses = paginateCourses(initialFilteredCourses);
        displayCourses(initialPaginatedCourses);
        updatePagination(Math.ceil(initialFilteredCourses.length / coursesPerPage));
    </script>
</body>
</html>