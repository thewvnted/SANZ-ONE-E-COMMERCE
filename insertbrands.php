<?php
include('connect.php');

// Initialize variables
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert_brands'])) {
    $brand_name = $_POST['brand_name'];

    // Check if the brand title is not empty
    if (!empty($brand_name)) {
        // Insert the brand into the database using prepared statements
        $stmt = mysqli_prepare($con, "INSERT INTO `Brands` (brand_name) VALUES (?)");

        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "s", $brand_name);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $message = "Brand inserted successfully!";

            // Redirect to the Admin Dashboard page with the query parameter
            echo "<script>alert('$message')</script>";
            echo "<script>window.open('Admindashboard.php?insertbrands', '_self')</script>";
            exit();
        } else {
            $message = "Error: " . mysqli_error($con);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $message = "Fields cannot be empty!";
    }
}

// Display the message if any
if (!empty($message)) {
    echo "<div>$message</div>";
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Brands</title>
</head>

<body>

    <!-- Display the form -->
    <form action="" method="post" class="mb-2">
        <div class="form-outline mb-4">
            <label for="brand_name" class="form-label">Insert Brands</label>
            <input type="text" name="brand_name" id="brand_name" class="form-control"
                   placeholder="Enter brand name" autocomplete="off" required="required">
        </div>
        
        <button type="submit" class="btn btn-info" name="insert_brands">Insert Brands</button>
    </form>

</body>

</html>
