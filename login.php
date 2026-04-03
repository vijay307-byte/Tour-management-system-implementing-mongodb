<?php
session_start();

require 'vendor/autoload.php';

// MongoDB connection
$client = new MongoDB\Client("mongodb://127.0.0.1:27017");
$db = $client->tour_db;
$users = $db->users;

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_POST['email'];
    $password = $_POST['password'];

    // MongoDB query
    $user = $users->findOne([
        'email' => $email,
        'password' => $password
    ]);

    if($user){

        $_SESSION['user'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];

        // Role-based redirect
        if($user['role'] == 'admin'){
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit();

    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

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

                    <h3 class="text-center mb-4">Login</h3>

                    <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <div class="text-center mt-3">
<a href="signup.php">Create new account</a>
</div>

</body>

</html>