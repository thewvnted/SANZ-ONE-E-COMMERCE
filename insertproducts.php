<?php
include 'connect.php';

// Initialize variables
$message = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert_products'])) {
    // Check for file upload errors
    if ($_FILES["product_image"]["error"] !== UPLOAD_ERR_OK) {
        $message = "File upload error: " . htmlspecialchars($_FILES["product_image"]["error"]);
    } else {
        // Define the target directory where uploaded files will be stored
        $targetDirectory = "/opt/lampp/htdocs/Sanzon E-Commerce/Uploads/";

        // Construct the target file path
        $fileName = basename($_FILES["product_image"]["name"]);
        $targetFile = $targetDirectory . $fileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
            // Retrieve other form data
            $productName = $_POST['product_name'];
            $productPrice = $_POST['product_price'];
            $brand = $_POST['brand'];
            $category = $_POST['category'];
            $productDescription = $_POST['description'];

            // Prepare and execute the SQL insert statement
            $sql = "INSERT INTO products_table (product_name, product_price, product_image, brand_name, category_name, description) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);

            if (!$stmt) {
                $message = "Error in preparing statement: " . mysqli_error($con);
            } else {
                mysqli_stmt_bind_param($stmt, "ssssss", $productName, $productPrice, $fileName, $brand, $category, $productDescription);

                if (mysqli_stmt_execute($stmt)) {
                    $message = "Product inserted successfully.";

                    // Redirect to the Admin Dashboard page with the query parameter
                    echo "<script>alert('$message')</script>";
                    echo "<script>window.open('Admindashboard.php?insertproducts', '_self')</script>";
                    exit();
                } else {
                    $message = "Error: " . htmlspecialchars(mysqli_error($con));
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}

// Display the message if any
if (!empty($message)) {
    echo "<div>" . htmlspecialchars($message) . "</div>";
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add your head content here -->
</head>
<body>

<form action="" method="post" enctype="multipart/form-data" class="mb-4 w-50 m-auto">

    <!-- Product Name -->
    <div class="form-outline mb-4">
        <label for="product_name" class="form-label">Product Name</label>
        <input type="text" name="product_name" id="product_name" class="form-control"
               placeholder="Enter product name" autocomplete="off" required="required">
    </div>

    <!-- Product Price -->
    <div class="form-outline mb-4">
        <label for="product_price" class="form-label">Product Price</label>
        <input type="text" name="product_price" id="product_price" class="form-control"
               placeholder="Enter product price" autocomplete="off" required="required">
    </div>

    <!-- Product Image File -->
    <div class="form-outline mb-4">
        <label for="product_image" class="form-label">Product Image</label>
        <input type="file" name="product_image" id="product_image" class="form-control" accept="image/*" required="required">
    </div>

    <!-- Brand Dropdown -->
    <div class="form-outline mb-4">
        <label for="brand" class="form-label">Select Brand</label>
        <select name="brand" id="brand" class="form-select" required="required">
            <option value="" disabled selected>Select a brand</option>
            <option value="PlayStation">PlayStation</option>
            <option value="Switch">Switch</option>
            <option value="XBOX">XBOX</option>
        </select>
    </div>

    <!-- Category Dropdown -->
    <div class="form-outline mb-4">
        <label for="category" class="form-label">Select Category</label>
        <select name="category" id="category" class="form-select" required="required">
            <option value="" disabled selected>Select a category</option>
            <option value="Console">Console</option>
            <option value="Gaming Pads">Gaming Pads</option>
            <option value="Games">Games</option>
            <option value="Gift Cards">Gift Cards</option>
            <option value="Accessories">Accessories</option>
        </select>
    </div>

    <!-- Description -->
    <div class="form-outline mb-4">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control"
              placeholder="Enter description" autocomplete="off" required="required"></textarea>
    </div>

    <!-- Insert Product Button -->
    <div class="form-outline mb-4">
        <input type="submit" class="btn btn-primary" name="insert_products" value="Insert Product">
    </div>
</form>

</body>
</html>
