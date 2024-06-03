<?php
include('connect.php');

// Initialize variables
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert_categories'])) {
    // Corrected the name attribute to match the form
    $category_name = $_POST['categories_name'];

    // Check if the Category title is not empty
    if (!empty($category_name)) {
        // Insert the category into the database using prepared statements
        $stmt = mysqli_prepare($con, "INSERT INTO `Categories` (category_name) VALUES (?)");

        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "s", $category_name);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $message = "Category inserted successfully!";

            // Redirect to the Admin Dashboard page with the query parameter
            echo "<script>alert('$message')</script>";
            echo "<script>window.open('Admindashboard.php?insertcategories', '_self')</script>";
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
    <title>Insert Categories</title>
</head>

<body>

    <!-- Display the form -->
    <form action="" method="post" class="mb-2">
        <div class="form-outline mb-4">
            <!-- Corrected the name attribute to match the PHP code -->
            <label for="categories_name" class="form-label">Insert Categories</label>
            <input type="text" name="categories_name" id="categories_name" class="form-control" placeholder="Enter categories name" autocomplete="off" required="required">
        </div>
        
        <button type="submit" class="btn btn-info" name="insert_categories">Insert Categories</button>
    </form>

</body>

</html>
