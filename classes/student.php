<?php

    require_once __DIR__ . '/User.php';

    class Student extends User {


        public function __construct($nom,$prenom,$telephone,$email,$password,$role,$status,$photo) {
            parent::__construct( $nom, $prenom, $telephone, $email, $password, $role, $status, $photo);
        }

        // SUBSCRIBE TO A COURSE
        public function subscribeToCourse($student,$course){
            try{
                $sql = 'INSERT INTO enrollments(id_course,id_student)
                        VALUES (:course,:student)';
                $query = $this->database->prepare($sql);
                $query->bindParam(':student', $student, PDO::PARAM_INT);
                $query->bindParam(':course', $course, PDO::PARAM_INT);
                if($query->execute()){
                    return true;
                }else{
                    return false;
                }
            }catch(PDOException $e){
                throw new Exception('Erreur lors de l\'Inscription à ce cours : ' .$e->getMessage());
            }
        }
        
        
        // GET SUBSCRIBED COURSES
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
        public function getTotalStudents() {
            try {
                $stmt = $this->connect()->query("SELECT COUNT(*) as total FROM users WHERE role = 'student'");
                $result = $stmt->fetch();
                return $result['total'];
            } catch(PDOException $e) {
                return 0;
            }
        }

        // Get all students with their enrolled courses count
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
                
                $stmt = $this->connect()->query($query);
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
        public function enrollCourse($courseId) {
            try {
                $query = "INSERT INTO enrollments (id_student, id_course, enrollment_date) VALUES (?, ?, NOW())";
                $stmt = $this->connect()->prepare($query);
                return $stmt->execute([$this->id, $courseId]);
            } catch(PDOException $e) {
                return false;
            }
        }
    }

?>