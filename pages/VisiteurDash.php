<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Plateforme de cours en ligne</title>
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
    <?php
    include_once "header.php"
    ?>
    <main>
        <section class="bg-blue-600 text-white py-20">
            <div class="container mx-auto px-6 text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">Apprenez sans limites avec Youdemy</h1>
                <p class="text-xl mb-8">Découvrez des milliers de cours en ligne dispensés par des experts.</p>
                <a href="login.php" class="bg-white text-blue-600 px-6 py-3 rounded-full text-lg font-semibold hover:bg-gray-100 transition duration-300">Commencer maintenant</a>
            </div>
        </section>

        <section class="py-16">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Nos cours les plus populaires</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Carte de cours 1 -->
                <div class="course-card bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="relative overflow-hidden">
                        <img src="https://via.placeholder.com/400x200" alt="Cours 1" class="course-image w-full h-48 object-cover">
                        <div class="absolute top-0 left-0 mt-4 ml-4">
                            <span class="course-tag bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Développement Web</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Introduction au développement web</h3>
                        <p class="text-gray-600 mb-4">Apprenez les bases du HTML, CSS et JavaScript pour créer vos propres sites web.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-semibold">4.8 ★★★★☆</span>
                            <a href="#" class="text-blue-600 font-semibold hover:underline">En savoir plus</a>
                        </div>
                    </div>
                </div>
                <!-- Carte de cours 2 -->
                <div class="course-card bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="relative overflow-hidden">
                        <img src="https://via.placeholder.com/400x200" alt="Cours 2" class="course-image w-full h-48 object-cover">
                        <div class="absolute top-0 left-0 mt-4 ml-4">
                            <span class="course-tag bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Marketing Digital</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Marketing digital avancé</h3>
                        <p class="text-gray-600 mb-4">Maîtrisez les stratégies de marketing en ligne pour développer votre entreprise.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-semibold">4.9 ★★★★★</span>
                            <a href="#" class="text-blue-600 font-semibold hover:underline">En savoir plus</a>
                        </div>
                    </div>
                </div>
                <!-- Carte de cours 3 -->
                <div class="course-card bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="relative overflow-hidden">
                        <img src="https://via.placeholder.com/400x200" alt="Cours 3" class="course-image w-full h-48 object-cover">
                        <div class="absolute top-0 left-0 mt-4 ml-4">
                            <span class="course-tag bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">Intelligence Artificielle</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Intelligence artificielle pour débutants</h3>
                        <p class="text-gray-600 mb-4">Découvrez les concepts fondamentaux de l'IA et ses applications pratiques.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-semibold">4.7 ★★★★☆</span>
                            <a href="#" class="text-blue-600 font-semibold hover:underline">En savoir plus</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <section class="bg-gray-200 py-16">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center mb-8">Pourquoi choisir Youdemy ?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="text-xl font-semibold mb-2">Contenu de qualité</h3>
                        <p class="text-gray-700">Des cours créés par des experts de l'industrie.</p>
                    </div>
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <h3 class="text-xl font-semibold mb-2">Apprentissage flexible</h3>
                        <p class="text-gray-700">Apprenez à votre rythme, où que vous soyez.</p>
                    </div>
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <h3 class="text-xl font-semibold mb-2">Certificats reconnus</h3>
                        <p class="text-gray-700">Obtenez des certificats pour valoriser vos compétences.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-3xl font-bold mb-8">Prêt à commencer votre voyage d'apprentissage ?</h2>
                <a href="login.php" class="bg-blue-600 text-white px-6 py-3 rounded-full text-lg font-semibold hover:bg-blue-700 transition duration-300">Créer un compte gratuit</a>
            </div>
        </section>
    </main>

    <?php
    include_once "footer.php"
    ?>
    
</body>
</html>