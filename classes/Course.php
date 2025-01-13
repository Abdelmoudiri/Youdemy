youdemy/
│
├── index.php                     # Point d'entrée de l'application
├── config.php                    # Configuration de la base de données et constantes
│
├── classes/                      # Dossier pour les classes PHP
│   ├── Database.php              # Gestion de la connexion à la base de données
│   ├── User.php                  # Classe pour gérer les utilisateurs
│   ├── Course.php                # Classe pour gérer les cours
│   ├── Category.php              # Classe pour gérer les catégories
│   ├── Tag.php                   # Classe pour gérer les tags
│   └── Enrollment.php            # Classe pour gérer les inscriptions
│
├── pages/                        # Dossier pour les pages du site
│   ├── home.php                  # Page d'accueil
│   ├── course_list.php           # Liste des cours
│   ├── course_detail.php         # Détails d'un cours
│   ├── login.php                 # Page de connexion
│   ├── register.php              # Page d'inscription
│   ├── student_dashboard.php     # Tableau de bord étudiant
│   ├── teacher_dashboard.php     # Tableau de bord enseignant
│   └── admin_dashboard.php       # Tableau de bord administrateur
│
├── assets/                       # Dossier pour les fichiers statiques (CSS, JS, images)
│   ├── css/
│   │   └── style.css             # Fichier CSS principal
│   ├── js/
│   │   └── script.js             # Fichier JavaScript principal
│   └── images/                   # Images du site
│
├── database/                     # Scripts SQL pour la base de données
│   ├── create_tables.sql         # Script pour créer les tables
│   └── sample_data.sql           # Données de test
│
└── README.md                     # Documentation du projet