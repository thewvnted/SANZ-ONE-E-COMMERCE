<?php
// Check if a session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('connect.php'); // Assuming connect.php contains your database connection code

// Fetch all payments from the Payments table
$query = "SELECT id, amount, date, order_number FROM Payments";
$result = mysqli_query($con, $query);

// Check if the query was successful
if (!$result) {
    die('Error: ' . mysqli_error($con));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Payments</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Add your custom styles if needed -->
</head>

<body>

<div class="container mt-3">
    <h1>All Payments</h1>

    <?php
    if (mysqli_num_rows($result) > 0) {
        // Display payments if there are any
        echo '<table class="table table-bordered">';
        echo '<thead>';
        echo '<tr>';
        echo '<th scope="col">Payment ID</th>';
        echo '<th scope="col">Amount</th>';
        echo '<th scope="col">Date</th>';
        echo '<th scope="col">Order Number</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['amount'] . '</td>';
            echo '<td>' . $row['date'] . '</td>';
            echo '<td>' . $row['order_number'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        // Display a message if there are no payments
        echo '<p>No payments found.</p>';
    }

    // Close the database connection
    mysqli_close($con);
    ?>

    <!-- Bootstrap JS scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN6b1fFMVpFgmiEJufqN2MW02x4UPqMZ" crossorigin="anonymous"></script>
    <!-- Add your custom scripts if needed -->

</body>

</html>
