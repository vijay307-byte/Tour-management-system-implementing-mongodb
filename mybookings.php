<?php
session_start();

// Must be logged in
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "tour_db");

if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}

// Get logged-in user's email
$email = $_SESSION['email'];

// Fetch bookings
$result = $conn->query("SELECT * FROM bookings WHERE email='$email' ORDER BY id DESC");


// Handle cancel booking
if(isset($_GET['cancel'])){
    $id = $_GET['cancel'];

    // Only cancel user's own booking
    $conn->query("UPDATE bookings 
                  SET status='Cancelled' 
                  WHERE id=$id AND email='$email'");
}
?>



<!DOCTYPE html>
<html>

<head>
    <title>My Bookings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }
    </style>

</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">

            <a class="navbar-brand fw-bold">Tour Management System</a>

            <div>

                <a class="text-white me-3 text-decoration-none" href="index.php">Home</a>
                <a class="text-white me-3 text-decoration-none" href="tours.php">Tours</a>

                <?php if($_SESSION['role'] == 'admin'): ?>
                <a class="btn btn-warning btn-sm me-2" href="admin.php">Dashboard</a>
                <?php endif; ?>
                <a class="btn btn-light btn-sm me-2" href="mybookings.php">My Bookings</a>
                <a class="btn btn-danger btn-sm" href="logout.php">Logout</a>

            </div>

        </div>
    </nav>

    <!-- CONTENT -->
    <div class="container mt-5">

        <h3 class="text-center mb-4 fw-bold">My Bookings</h3>

        <!-- SUCCESS MESSAGE -->
        <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success text-center">
            Booking placed successfully!
        </div>
        <?php endif; ?>

        <table class="table table-bordered bg-white">

            <tr>
                <th>ID</th>
                <th>Tour</th>
                <th>Date</th>
                <th>People</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            

            <?php if($result->num_rows > 0): ?>

            <?php while($row = $result->fetch_assoc()): ?>

            <tr>

                <td><?php echo $row['id']; ?></td>

                <td><?php echo $row['tour']; ?></td>

                <td><?php echo $row['date']; ?></td>

                <td><?php echo $row['people']; ?></td>

                <td>₹<?php echo number_format($row['total']); ?></td>

                <td>
                    <span class="badge 
                            <?php 
                                if($row['status'] == 'Approved') echo 'bg-success';
                                elseif($row['status'] == 'Rejected') echo 'bg-danger';
                                elseif($row['status'] == 'Cancelled') echo 'bg-secondary';
                                else echo 'bg-warning text-dark';
                            ?>">
                        <?php echo $row['status']; ?>
                    </span>
                </td>


                <td>
                    <?php if($row['status'] == 'Pending'): ?>

                    <a href="?cancel=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Cancel this booking?')">
                        Cancel
                    </a>

                    <?php else: ?>

                    <span class="text-muted">-</span>

                    <?php endif; ?>
                </td>
            </tr>

            <?php endwhile; ?>

            <?php else: ?>

            <tr>
                <td colspan="6" class="text-center">No bookings found</td>
            </tr>

            <?php endif; ?>

        </table>

    </div>

</body>

</html>