<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View More</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<?php
// Get the product name from the query string
$productName = urldecode($_GET['product']);

// Function to fetch all product names from the database
function getAllProducts()
{
    include('connect.php'); // Include your database connection file

    $sql = "SELECT product_name FROM products_table";
    $result = mysqli_query($con, $sql);

    $products = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row['product_name'];
    }

    mysqli_close($con);

    return $products;
}

// Check if the product name is valid
if (in_array($productName, getAllProducts())) {
    // Fetch additional details for the product from the database
    $productDetails = getProductDetails($productName);

    // Display the product details
    echo '<div class="container mt-3">';
    echo '<h2>More Details</h2>';
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Product Name</th>';
    echo '<th>Description</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    echo '<tr>';
    echo '<td>' . $productDetails['product_name'] . '</td>';
    echo '<td>' . $productDetails['description'] . '</td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    // Handle invalid product name (redirect or display an error)
    echo '<div class="container">';
    echo '<p>Invalid product.</p>';
    echo '</div>';
}

// Function to fetch product details from the database
function getProductDetails($productName)
{
    include('connect.php'); // Include your database connection file

    $sql = "SELECT * FROM products_table WHERE product_name = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $productName);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($con);
            return $row;
        }
    }

    return null;
}
?>

<!-- Bootstrap JS scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN6b1fFMVpFgmiEJufqN2MW02x4UPqMZ" crossorigin="anonymous"></script>
</body>
</html>
