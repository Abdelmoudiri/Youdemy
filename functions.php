<?php

function login()
{
    if (isset($_POST['signIn'])) {
        $email = $_POST['email'];
        $password = $_POST['passwordIN'];

        $res = User::login($email, $password);
        if ($res) {
            echo "<script>alert('login réussie !');</script>";
            $_SESSION['nom_complet'] = $res['name'] . " " . $res['lastname'];
            if($res['role']==='admin'){header('location: pages/AdminDash.php');}

        }else{
            echo "<script>alert('Essayer a nouveau !!');</script>";
    
        }
    }
}

function register()
{
    if (isset($_POST['signUp'])) {
        $role = $_POST['role'];
        $firstName = $_POST['fName'];
        $lastName = $_POST['lName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        // $filename = $_FILES['image']['name'];
        // $tempname = $_FILES['image']['tmp_name'];
        // $newfilename = uniqid() . "-" . $filename;
        // move_uploaded_file($tempname, '../uploads/' . $newfilename);
        
        $studentData = [
            'nom' => $firstName,
            'prenom' => $lastName,
            'email' => $email,
            'mot_de_passe' => $password,
            'role' => 'student',
            'status' => 'active'
        ];
    
        $teacherData = [
            'nom' => $firstName,
            'prenom' => $lastName,
            'email' => $email,
            'mot_de_passe' => $password,
            'role' => 'teacher'
        ];
        $vis=new Visiteur();
    
        if ($role === 'student') {
            if ($vis->register($studentData)) {
                echo "<script>alert('Inscription réussie !');</script>";
            } else {
                echo "<script>alert('Erreur lors de l\'inscription.');</script>";
            }
        }
        if ($role === 'teacher') {
            if ($vis->register($teacherData)) {
                echo "<script>alert('Inscription réussie !');</script>";
            } else {
                echo "<script>alert('Erreur lors de l\'inscription.');</script>";
            }
        }
    }
}
?>