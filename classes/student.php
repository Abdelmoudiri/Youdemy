<?php

    require_once __DIR__ . '/User.php';
    require_once __DIR__ . '/../config/db.php';

    class Student extends User {
        protected $database;
        protected $id_user;

        public function __construct($nom,$prenom,$telephone,$email,$password,$role,$status,$photo) {
            parent::__construct($nom, $prenom, $telephone, $email, $password, $role, $status, $photo);
            $this->database = Database::getInstance()->getConnection();
        }

        // SUBSCRIBE TO A COURSE
        /**
         * Subscribe to a course
         *
         * @param int $student_id
         * @param int $course_id
         * @return bool
         */
        public function subscribeToCourse($student_id, $course_id){
            try{
                $sql = 'INSERT INTO enrollments(id_course, id_student)
                        VALUES (:course, :student)';
                $query = $this->database->prepare($sql);
                $query->bindParam(':student', $student_id, PDO::PARAM_INT);
                $query->bindParam(':course', $course_id, PDO::PARAM_INT);
                return $query->execute();
            }catch(PDOException $e){
                error_log("Erreur d'inscription au cours : " . $e->getMessage());
                return false;
            }
        }
        
        // GET SUBSCRIBED COURSES
        /**
         * Get subscribed courses
         *
         * @param int $student
         * @return array
         */
        public function subscribedCourses($student) {
            try {
                $sql = 'SELECT 
                            Co.id_course,
                            Co.titre,
                            Co.description,
                            Co.couverture,
                            Co.niveau,
                            Co.contenu,
                            Co.video,
                            Co.statut_cours,
                            Ca.nom_categorie,
                            U.prenom,
                            U.nom,
                            U.photo
                        FROM enrollments E
                        INNER JOIN courses Co ON E.id_course = Co.id_course
                        INNER JOIN categories Ca ON Co.id_categorie = Ca.id_categorie
                        INNER JOIN users U ON Co.id_teacher = U.id_user
                        WHERE E.id_student = :student';
                
                $query = $this->database->prepare($sql);
                $query->bindParam(':student', $student, PDO::PARAM_INT);
                $query->execute();
                
                $courses = $query->fetchAll(PDO::FETCH_ASSOC);
                return $courses;
            } catch (PDOException $e) {
                throw new Exception('Erreur lors de la récupération des cours inscrits : ' . $e->getMessage());
            }
        }

        // Get total number of students
        /**
         * Get total number of students
         *
         * @return int
         */
        public function getTotalStudents() {
            try {
                $stmt = $this->database->query("SELECT COUNT(*) as total FROM users WHERE role = 'student'");
                $result = $stmt->fetch();
                return $result['total'];
            } catch(PDOException $e) {
                return 0;
            }
        }

        // Get all students with their enrolled courses count
        /**
         * Get all students with their enrolled courses count
         *
         * @return array
         */
        public function getAllStudents() {
            try {
                $query = "SELECT u.id_user as id, 
                                u.nom as name, 
                                u.prenom as firstname,
                                u.email,
                                u.created_at as join_date,
                                COUNT(e.id_course) as enrolled_courses
                         FROM users u
                         LEFT JOIN enrollments e ON u.id_user = e.id_student
                         WHERE u.role = 'student'
                         GROUP BY u.id_user";
                
                $stmt = $this->database->query($query);
                $students = $stmt->fetchAll();
                
                // Format the data
                foreach ($students as &$student) {
                    $student['name'] = $student['name'] . ' ' . $student['firstname'];
                    unset($student['firstname']);
                }
                
                return $students;
            } catch(PDOException $e) {
                return [];
            }
        }

        // Enroll student in a course
        /**
         * Enroll student in a course
         *
         * @param int $courseId
         * @return bool
         */
        public function enrollCourse($courseId) {
            try {
                $query = "INSERT INTO enrollments (id_student, id_course, enrollment_date) VALUES (:student, :course, NOW())";
                $stmt = $this->database->prepare($query);
                $stmt->bindParam(':student', $this->id_user, PDO::PARAM_INT);
                $stmt->bindParam(':course', $courseId, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(PDOException $e) {
                error_log("Erreur lors de l'inscription au cours : " . $e->getMessage());
                return false;
            }
        }

        // Get course details
        /**
         * Get course details
         *
         * @param int $courseId
         * @return array|null
         */
        public function getCourseDetails($courseId) {
            try {
                $sql = "SELECT c.*, u.nom, u.prenom, u.photo, cat.nom_categorie 
                        FROM courses c 
                        JOIN users u ON c.id_user = u.id_user 
                        LEFT JOIN categories cat ON c.id_categorie = cat.id_categorie 
                        WHERE c.id_course = :courseId";
                $stmt = $this->database->prepare($sql);
                $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                error_log("Erreur lors de la récupération des détails du cours : " . $e->getMessage());
                return null;
            }
        }

        // Check if student is enrolled in a course
        /**
         * Check if student is enrolled in a course
         *
         * @param int $studentId
         * @param int $courseId
         * @return bool
         */
        public function isEnrolled($studentId, $courseId) {
            try {
                $sql = "SELECT COUNT(*) FROM inscription WHERE id_student = :studentId AND id_course = :courseId";
                $stmt = $this->database->prepare($sql);
                $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
                $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchColumn() > 0;
            } catch(PDOException $e) {
                error_log("Erreur lors de la vérification de l'inscription : " . $e->getMessage());
                return false;
            }
        }
    }

?>