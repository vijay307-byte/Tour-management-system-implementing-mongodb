<?php
session_start();

require 'vendor/autoload.php';

// MongoDB connection
$client = new MongoDB\Client("mongodb://127.0.0.1:27017");
$db = $client->tour_db;

$tours = $db->tours;

// Fetch all tours
$tourList = $tours->find();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tours</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .card img {
            height: 220px;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">

            <a class="navbar-brand fw-bold">Tour Management System</a>

            <div>

                <a class="me-3 text-decoration-none text-white" href="index.php">Home</a>
                <a class="me-3 text-decoration-none text-white" href="tours.php">Tours</a>
                <a class="me-3 text-decoration-none text-white" href="mybookings.php">My Bookings</a>


                <?php if(isset($_SESSION['user'])): ?>

                <span class="text-white me-2">
                    <?php echo $_SESSION['user']; ?>
                </span>

                <?php if($_SESSION['role'] == 'admin'): ?>
                <a href="admin.php" class="btn btn-warning btn-sm me-2">Dashboard</a>
                <?php endif; ?>

                <a class="btn btn-danger btn-sm" href="logout.php">Logout</a>

                <?php else: ?>

                <a class="btn btn-light btn-sm" href="login.php">Login</a>

                <?php endif; ?>

            </div>

        </div>
    </nav>

    <!-- CONTENT -->
    <div class="container mt-5">

        <h2 class="text-center mb-4 fw-bold">Available Tours</h2>

        <div class="row">

          <?php $hasData = false; ?>
          <?php foreach($tourList as $t): $hasData = true; ?>
            <div class="col-md-4 mb-4">

                <div class="card shadow-sm h-100">

                    <!-- IMAGE -->
                    <img src="images/<?php echo $t['image']; ?>" class="card-img-top" alt="Tour">

                    <div class="card-body d-flex flex-column">

                        <!-- TITLE -->
                        <h5 class="card-title"><?php echo $t['name']; ?></h5>

                        <!-- DESCRIPTION -->
                        <p class="card-text">
                            Explore this destination with our curated package.
                        </p>

                        <!-- PRICE -->
                        <p class="fw-bold text-success">
                            ₹<?php echo number_format($t['price']); ?> per person
                        </p>

                        <!-- BUTTON -->
                        <a href="booktour.php?tour=<?php echo urlencode($t['name']); ?>&price=<?php echo $t['price']; ?>"
                            class="btn btn-success mt-auto w-100">
                            Book Tour
                        </a>

                    </div>

                </div>

            </div>

            <?php endforeach; ?>

            <?php if(!$hasData): ?>

            <p class="text-center">No tours available. Please check later.</p>

            <?php endif; ?>

        </div>

    </div>

</body>

</html>