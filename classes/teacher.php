<?php

    require_once __DIR__ . '/User.php';

    class Teacher extends User {

        public function __construct($id,$nom,$prenom,$telephone,$email,$password,$role,$status,$photo) {
            parent::__construct( $nom, $prenom, $telephone, $email, $password, $role, $status, $photo);

        }

        // DISPLAY COURSES
        public function displayCourses($id_teacher){
            try {
                $query = "SELECT 
                            courses.id_course,
                            courses.titre,
                            courses.description,
                            courses.contenu,
                            courses.video,
                            courses.couverture,
                            courses.niveau,
                            courses.date_publication,
                            courses.statut_cours,
                            categories.nom_categorie
                        FROM courses
                        JOIN categories ON courses.id_categorie = categories.id_categorie
                        WHERE courses.id_teacher = :id_teacher
                        ORDER BY courses.date_publication DESC";
                $stmt = $this->database->prepare($query);
                $stmt->bindParam(':id_teacher', $id_teacher, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $results;
            } catch (PDOException $e) {
                throw new Exception("Erreur lors de la Récupération des Cours : " . $e->getMessage());
            }
        }

        // COUNT MY COURSES
        public function countCourses($id_teacher,$status){
            try {
                $query = "SELECT COUNT(*) AS course_count 
                          FROM courses 
                          WHERE id_teacher = :id_teacher AND statut_cours = :status";
                $stmt = $this->database->prepare($query);
                $stmt->bindParam(':id_teacher', $id_teacher, PDO::PARAM_INT);
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['course_count'];
            } catch (PDOException $e) {
                throw new Exception("Erreur lors de la Récupération du Nombre de Cours: " . $e->getMessage());
            }
        }

        // COUNT ENROLLED STUDENTS IN MY COURSES
        public function countEnrolledStudents($id_teacher){
            try {
                $query = "SELECT COUNT(DISTINCT enrollments.id_student) AS total_students
                        FROM enrollments
                        JOIN courses ON enrollments.id_course = courses.id_course
                        WHERE courses.id_teacher = :id_teacher";
                $stmt = $this->database->prepare($query);
                $stmt->bindParam(':id_teacher', $id_teacher, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['total_students'];
            } catch (PDOException $e) {
                throw new Exception("Erreur lors de la Récupération du Nombre des Etudiants: " . $e->getMessage());
            }
        }

        // COUNT COURSES PER CATEGORY
        public function countCoursesPerCategory($id_teacher){
            try {
                $query = "SELECT categories.nom_categorie, COUNT(courses.id_course) AS course_count
                        FROM courses
                        JOIN categories ON courses.id_categorie = categories.id_categorie
                        WHERE courses.id_teacher = :id_teacher
                        GROUP BY categories.nom_categorie";
                $stmt = $this->database->prepare($query);
                $stmt->bindParam(':id_teacher', $id_teacher, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $results;
            } catch (PDOException $e) {
                throw new Exception("Erreur lors de la Récupération du Nombre des Cours Par catégorie: " . $e->getMessage());
            }
        }

        // LAST 3 COURSES
        public function lastCourses($id_teacher){
            try {
                $query = "SELECT titre, couverture, statut_cours, date_publication
                        FROM courses
                        WHERE id_teacher = :id_teacher
                        ORDER BY date_publication DESC
                        LIMIT 3";
                $stmt = $this->database->prepare($query);
                $stmt->bindParam(':id_teacher', $id_teacher, PDO::PARAM_INT);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $results;
            } catch (PDOException $e) {
                throw new Exception("Erreur lors de la Récupération des Derniers Cours : " . $e->getMessage());
            }
        }

        // Get total number of teachers
        public function getTotalTeachers() {
            try {
                $stmt = $this->connect()->query("SELECT COUNT(*) as total FROM users WHERE role = 'teacher'");
                $result = $stmt->fetch();
                return $result['total'];
            } catch(PDOException $e) {
                return 0;
            }
        }

        // Get all teachers with their course counts
        public function getAllTeachers() {
            try {
                $query = "SELECT u.id_user as id, 
                                u.nom as name, 
                                u.prenom as firstname,
                                u.email,
                                u.status as active,
                                COUNT(c.id_course) as courses_count
                         FROM users u
                         LEFT JOIN courses c ON u.id_user = c.id_teacher
                         WHERE u.role = 'teacher'
                         GROUP BY u.id_user";
                
                $stmt = $this->connect()->query($query);
                $teachers = $stmt->fetchAll();
                
                // Format the data
                foreach ($teachers as &$teacher) {
                    $teacher['name'] = $teacher['name'] . ' ' . $teacher['firstname'];
                    $teacher['active'] = $teacher['active'] === 'active';
                    unset($teacher['firstname']);
                }
                
                return $teachers;
            } catch(PDOException $e) {
                return [];
            }
        }

    }

?>