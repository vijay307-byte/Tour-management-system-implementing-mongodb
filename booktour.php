<?php
$conn = new mysqli("localhost", "root", "", "tour_db");

if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}

// Get tour from URL
$tour = $_GET['tour'] ?? '';
$price = $_GET['price'] ?? 0;

// Handle form submit
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $tour = $_POST['tour'];
    $people = $_POST['people'];
    $date = $_POST['date'];
    $total = $_POST['total'];

    $conn->query("INSERT INTO bookings 
    (name, email, phone, tour, people, date, total) 
    VALUES 
    ('$name','$email','$phone','$tour','$people','$date','$total')");

    $success = true;
    
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Book Tour</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .card {
            border-radius: 12px;
            border: none;
        }
    </style>

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow p-4">

                    <h3 class="text-center mb-4">Book Tour</h3>

                    <?php if(isset($success)): ?>
                    <div class="alert alert-success">
                        Booking Successful!
                    </div>
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

                        <input type="hidden" name="total" id="hiddenTotal">
                        <input type="hidden" id="price" value="<?php echo $price; ?>">

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
        const hiddenTotal = document.getElementById("hiddenTotal");

        function calculate() {
            let people = parseInt(peopleInput.value) || 0;
            let total = price * people;
            if (total > 0) {
                totalField.value = "₹" + total.toLocaleString("en-IN");
                hiddenTotal.value = total;
            } else {
                totalField.value = "";
            }
        }
        peopleInput.addEventListener("input", calculate);
    </script>



</body>

</html>