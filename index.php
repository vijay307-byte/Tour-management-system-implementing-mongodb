<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Minimal PHP Site</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <nav class="navbar navbar-dark bg-primary text-white border-bottom">
        <div class="container">

            <a class="navbar-brand fw-bold">Tour Management System</a>

            <div>
                <a class="me-3 text-decoration-none text-white" href="index.php">Home</a>
                <a class="me-3 text-decoration-none text-white" href="tours.php">Tours</a>
                <a class="me-3 text-decoration-none text-white" href="mybookings.php">My Bookings</a>


                <?php session_start(); ?>

                <?php if(isset($_SESSION['user'])): ?>

                <span class="me-3 text-white">
                    Welcome, <?php echo $_SESSION['user']; ?>
                </span>

                <a class="text-decoration-none text-white" href="logout.php">
                    Logout
                </a>

                <?php else: ?>

                <a class="text-decoration-none text-white" href="login.php">
                    Login
                </a>

                <?php endif; ?>

            </div>

        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-6 mt-5">
                <h1>Welcome to our Tour Management System</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, voluptate.</p>
                <a href="tours.php" class="btn btn-primary">Explore Tours</a>
            </div>

            <div class="col-md-6 mt-5">
                <img src="images/alice-EraqPwT9OSc-unsplash.jpg" alt="Tour Image" class="img-fluid rounded">
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-4">
                <h3>Easy Booking</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, voluptate.</p>
            </div>
            <div class="col-md-4">
                <h3>Best Destinations</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, voluptate.</p>
            </div>
            <div class="col-md-4">
                <h3>24/7 Support</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas, voluptate.</p>
            </div>
        </div>

</body>

</html>