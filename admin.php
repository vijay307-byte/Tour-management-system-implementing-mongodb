<?php
session_start();

require 'vendor/autoload.php';

// Access control
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// MongoDB connection
$client = new MongoDB\Client("mongodb://127.0.0.1:27017");
$db = $client->tour_db;

$bookings = $db->bookings;
$tours = $db->tours;

// ================= ACTIONS =================

// Approve / Reject booking
if(isset($_GET['action'])){
    $id = $_GET['id'];

    if($_GET['action'] == "approve"){
        $bookings->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => ['status' => 'Approved']]
        );
    }

    if($_GET['action'] == "reject"){
        $bookings->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => ['status' => 'Rejected']]
        );
    }
}

// Add Tour
if(isset($_POST['addTour'])){

    $name = $_POST['tour_name'];
    $price = (int)$_POST['tour_price'];

    $imageName = $_FILES['tour_image']['name'];
    $tempName = $_FILES['tour_image']['tmp_name'];

    $uploadPath = "images/" . $imageName;
    move_uploaded_file($tempName, $uploadPath);

    $tours->insertOne([
        'name' => $name,
        'price' => $price,
        'image' => $imageName
    ]);
}

// Delete Tour
if(isset($_GET['deleteTour'])){
    $id = $_GET['deleteTour'];

    $tours->deleteOne([
        '_id' => new MongoDB\BSON\ObjectId($id)
    ]);
}

// Fetch data
$bookingList = $bookings->find();
$tourList = $tours->find();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        img.preview {
            width: 60px;
            height: 40px;
            object-fit: cover;
        }
    </style>

</head>

<body class="bg-light">

    <div class="container mt-4">

        <!-- TOP BAR -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Admin Dashboard</h2>

            <div>
                <a href="index.php" class="btn btn-primary btn-sm">Home</a>
                <a href="tours.php" class="btn btn-secondary btn-sm">View Tours</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>

        <!-- BOOKINGS -->
        <h4>Bookings</h4>

        <table class="table table-bordered bg-white">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Tour</th>
                <th>People</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php foreach($bookingList as $row): ?>
            <tr>
                <td><?php echo $row['_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['tour']; ?></td>
                <td><?php echo $row['people']; ?></td>
                <td>₹<?php echo $row['total']; ?></td>
                <td><?php echo $row['status']; ?></td>
          <td>

            <?php if($row['status'] == 'Pending'): ?>

                <a class="btn btn-success btn-sm" href="?action=approve&id=<?php echo $row['_id']; ?>">
                    Approve
                </a>

                <a class="btn btn-danger btn-sm" href="?action=reject&id=<?php echo $row['_id']; ?>">
                    Reject
                </a>

            <?php else: ?>

                <span class="text-muted">Completed</span>

            <?php endif; ?>

            </td>
            </tr>
            <?php endforeach; ?>

        </table>

        <!-- ADD TOUR -->
        <h4 class="mt-5">Add Tour</h4>

        <form method="POST" enctype="multipart/form-data" class="row g-2">

            <div class="col-md-4">
                <input type="text" name="tour_name" class="form-control" placeholder="Tour Name" required>
            </div>

            <div class="col-md-3">
                <input type="number" name="tour_price" class="form-control" placeholder="Price" required>
            </div>

            <div class="col-md-3">
                <input type="file" name="tour_image" class="form-control" required>
            </div>

            <div class="col-md-2">
                <button name="addTour" class="btn btn-dark w-100">Add</button>
            </div>

        </form>

        <!-- TOUR LIST -->
        <h4 class="mt-4">Manage Tours</h4>

        <table class="table table-bordered bg-white">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>

            <?php foreach($tourList as $t): ?>
            <tr>
                <td><?php echo $t['_id']; ?></td>
                <td><?php echo $t['name']; ?></td>
                <td>₹<?php echo $t['price']; ?></td>
                <td>
                    <img src="images/<?php echo $t['image']; ?>" class="preview">
                </td>
                <td>
                    <a class="btn btn-danger btn-sm" href="?deleteTour=<?php echo $t['_id']; ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>

    </div>

</body>

</html>