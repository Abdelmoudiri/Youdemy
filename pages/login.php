<?php
include_once __DIR__ . '/../classes/User.php';

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
    $user = new User();
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

    if ($role === 'student') {
        if ($user->register($studentData)) {
            echo "<script>alert('Inscription réussie !');</script>";
        } else {
            echo "<script>alert('Erreur lors de l\'inscription.');</script>";
        }
    }
    if ($role === 'teacher') {
        if ($user->register($teacherData)) {
            echo "<script>alert('Inscription réussie !');</script>";
        } else {
            echo "<script>alert('Erreur lors de l\'inscription.');</script>";
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['passwordIN'];

    $user = new User();

    $res = $user->login($email, $password);
    if ($res) {
        echo "<script>alert('login réussie !');</script>";
    }else{
        echo "<script>alert('Essayer a nouveau !!');</script>";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-light-gray {
            background-color: #F8F9F9;
        }

        .bg-navbar {
            background-color: #e8e8e5;
        }

        .bg-action-button {
            background-color: #c8a876;
        }

        .text-dark-blue {
            color: #2C3E50;
        }

        .text-secondary {
            color: #7F8C8D;
        }

        .text-footer {
            color: #2C3E50;
        }

        .border-dark-blue {
            border-color: #2C3E50;
        }

        .hover-bg-dark-beige:hover {
            background-color: #c09858;
        }

        .hover-text-dark-beige:hover {
            color: #c09858;
        }
    </style>
</head>

<body>
    <?php
    include_once "header.php"
    ?>
    <section class="bg-light-gray min-h-screen flex items-center justify-center p-4 pt-24">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-lg w-full">
            <div id="signup" class="p-6 hidden">
                <h1 class="text-3xl font-bold text-center text-dark-blue mb-6">Create Account</h1>
                <form method="post" action="login.php" enctype="multipart/form-data">
                    <div class="mb-4 relative">
                        <label for="role" class="block text-dark-blue font-medium">Role</label>
                        <select name="role" id="role" required
                            class="w-full border border-dark-blue focus:ring focus:ring-blue-200 focus:outline-none py-2 px-4 rounded">
                            <option value="student">Etudiant</option>
                            <option value="teacher">Enseignant</option>
                        </select>

                    </div>

                    <div class="mb-4 relative">
                        <label for="fName" class="block text-dark-blue font-medium">First Name</label>
                        <input type="text" name="fName" id="fName" placeholder="First Name" required
                            class="w-full border border-dark-blue focus:ring focus:ring-blue-200 focus:outline-none py-2 px-4 rounded">
                    </div>
                    <div class="mb-4 relative">
                        <label for="lName" class="block text-dark-blue font-medium">Last Name</label>
                        <input type="text" name="lName" id="lName" placeholder="Last Name" required
                            class="w-full border border-dark-blue focus:ring focus:ring-blue-200 focus:outline-none py-2 px-4 rounded">
                    </div>
                    <div class="mb-4 relative">
                        <label for="email" class="block text-dark-blue font-medium">Email</label>
                        <input type="email" name="email" id="email" placeholder="Email" required
                            class="w-full border border-dark-blue focus:ring focus:ring-blue-200 focus:outline-none py-2 px-4 rounded">
                    </div>

                    <div class="mb-6 relative">
                        <label for="password" class="block text-dark-blue font-medium">Password</label>
                        <input type="password" name="password" id="password" placeholder="Password" required
                            class="w-full border border-dark-blue focus:ring focus:ring-blue-200 focus:outline-none py-2 px-4 rounded">
                    </div>
                    <div class="mb-4 relative">
                        <label for="photo" class="block text-dark-blue font-medium">Profile Photo</label>
                        <input type="file" name="image"
                            class="w-full border border-dark-blue focus:ring focus:ring-blue-200 focus:outline-none py-2 px-4 rounded">
                    </div>


                    <input type="submit" value="Sign Up" onclick="validateForm()" name="signUp"
                        class="w-full bg-action-button text-white py-2 rounded-md font-medium hover:bg-dark-beige transition duration-300">
                </form>

                <p class="text-center my-6 text-secondary">Already have an account?
                    <button id="signInButton"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg active:scale-95 transition-transform duration-300 ease-in-out">
                        Sign In
                    </button>
                </p>
            </div>
            <div id="signIn" class="p-6 ">
                <h1 class="text-3xl font-bold text-center text-dark-blue mb-6">Welcome Back</h1>
                <form method="post" action="login.php">
                    <div class="mb-4 relative">
                        <label for="emailLogin" class="block text-dark-blue font-medium">Email</label>
                        <input type="email" name="email" id="emailLogin" placeholder="Email" required
                            class="w-full border border-dark-blue focus:ring focus:ring-blue-200 focus:outline-none py-2 px-4 rounded">
                    </div>
                    <div class="mb-6 relative">
                        <label for="passwordLogin" class="block text-dark-blue font-medium">Password</label>
                        <input type="password" name="passwordIN" id="passwordLogin" placeholder="Password" required
                            class="w-full border border-dark-blue focus:ring focus:ring-blue-200 focus:outline-none py-2 px-4 rounded">
                    </div>
                    <p class="text-right text-blue-500 text-sm mb-4">
                        <a href="#" class="hover:underline">Forgot Password?</a>
                    </p>

                    <input type="submit" value="Sign In" name="signIn"
                        class="w-full bg-action-button text-white py-2 rounded-md font-medium hover:bg-dark-beige transition duration-300">
                </form>
                <p class="text-center my-6 text-secondary">Don't have an account?
                    <button id="signUpButton"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 hover:shadow-lg active:scale-95 transition-transform duration-300 ease-in-out">
                        Sign Up
                    </button>
                </p>
            </div>
        </div>
    </section>
    <script>
        const signUpButton = document.getElementById('signUpButton');
        const signInButton = document.getElementById('signInButton');
        const signInForm = document.getElementById('signIn');
        const signUpForm = document.getElementById('signup');

        signUpButton.addEventListener('click', function() {
            signInForm.style.display = "none";
            signUpForm.style.display = "block";
        })

        signInButton.addEventListener('click', function() {
            signInForm.style.display = "block";
            signUpForm.style.display = "none";
        })
    </script>

    <?php
    include_once "footer.php"
    ?>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="../src/js/script.js"></script>
</body>

</html>