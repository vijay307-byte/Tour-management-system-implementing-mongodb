<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://127.0.0.1:27017");
$db = $client->tour_db;
$users = $db->users;

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $existingUser = $users->findOne([
        'email' => $email
    ]);

    if($existingUser){
        $error = "Email already registered";
    } else {

        // Insert user
        $users->insertOne([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'user'
        ]);

        $success = "Account created! You can login now.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Signup</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 12px;
        }
    </style>

</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">

                <div class="card shadow p-4">

                    <h3 class="text-center mb-4">Create Account</h3>

                    <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-success w-100">
                            Sign Up
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        <a href="login.php">Already have an account? Login</a>
                    </div>

                </div>

            </div>
        </div>
    </div>

</body>

</html>