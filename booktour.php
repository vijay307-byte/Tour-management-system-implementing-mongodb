<?php
session_start();
require 'mongo_connect.php';

// 🔒 Must be logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get logged-in user email
$email = $_SESSION['email'];

// Get tour from URL
$tour = $_GET['tour'] ?? '';
$price = $_GET['price'] ?? 0;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $tour = $_POST['tour'];
    $people = (int)$_POST['people'];
    $date = $_POST['date'];
    $price = (int)$_POST['price'];

    // 🔥 Server-side total calculation (DO NOT trust frontend)
    $total = $people * $price;

    // 🚀 Insert booking (linked to logged-in user)
    $insert = $db->bookings->insertOne([
        'name' => $name,
        'email' => $email, // integrity link
        'phone' => $phone,
        'tour' => $tour,
        'people' => $people,
        'date' => $date,
        'total' => $total,
        'status' => 'Pending',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    // Redirect to mybookings with success flag
    header("Location: mybookings.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow p-4">
                <h3 class="text-center mb-4">Book Tour</h3>

                <form method="POST">

                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" value="<?php echo $email; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Selected Tour</label>
                        <input type="text" name="tour" class="form-control" value="<?php echo $tour; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Travel Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Number of People</label>
                        <input type="number" name="people" id="people" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label>Total Price</label>
                        <input type="text" id="totalPrice" class="form-control" readonly>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" name="price" id="price" value="<?php echo $price; ?>">

                    <button class="btn btn-success w-100">
                        Confirm Booking
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

<script>
const price = parseInt(document.getElementById("price").value) || 0;
const peopleInput = document.getElementById("people");
const totalField = document.getElementById("totalPrice");

function calculate() {
    let people = parseInt(peopleInput.value) || 0;
    let total = price * people;

    if (total > 0) {
        totalField.value = "₹" + total.toLocaleString("en-IN");
    } else {
        totalField.value = "";
    }
}

peopleInput.addEventListener("input", calculate);
</script>

</body>
</html>