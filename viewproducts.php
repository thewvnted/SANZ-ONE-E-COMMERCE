<?php
include('connect.php');

// Check if a specific brand is selected
if (isset($_GET['brand'])) {
    $selectedBrand = $_GET['brand'];
    // Fetch the list of products for the selected brand
    $sql = "SELECT * FROM products_table WHERE brand_name = '$selectedBrand'";
} elseif (isset($_GET['category'])) {
    $selectedCategory = $_GET['category'];
    // Fetch the list of products for the selected category
    $sql = "SELECT * FROM products_table WHERE category_name = '$selectedCategory'";
} elseif (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    // Fetch the list of products based on the search query
    $sql = "SELECT * FROM products_table WHERE product_name LIKE '%$searchQuery%'";
} else {
    // If no specific brand, category, or search query is selected, fetch all products
    $sql = "SELECT * FROM products_table";
}

$result = mysqli_query($con, $sql);
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>

    <!-- Product List Table -->
    <div class="container mt-3">
      

        <?php
        if (mysqli_num_rows($result) > 0) {
        ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Brand Name</th>
                        <th>Category Name</th>
                        <th>Product Image</th>
                        <th>Description</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['product_id'] . '</td>';
                        echo '<td>' . $row['product_name'] . '</td>';
                        echo '<td>' . $row['product_price'] . '</td>';
                        echo '<td>' . $row['brand_name'] . '</td>';
                        echo '<td>' . $row['category_name'] . '</td>';
                        // Display the product image
                        echo '<td><img src="/Sanzon E-Commerce/images/' . $row['product_image'] . '" alt="Product Image" style="max-width: 100px; max-height: 100px;"></td>';
                        // Add more columns as needed
                        echo '<td>' . $row['description'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <?php
} else {
    // Display custom message with image in the center of the page
    echo '<div class="text-center" style="font-size: 24px; font-weight: bold; margin-top: 100px;">';
    echo '<img src="/Sanzon E-Commerce/images/17-sad(emotions).jpg" alt="Sad Emotions" style="max-width: 100%; height: auto;">';
    echo '<p>SORRY, THE PRODUCT YOU ARE LOOKING FOR IS NOT AVAILABLE!</p>';
    echo '</div>';
}
?>

    </div>

    <!-- Bootstrap JS scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN6b1fFMVpFgmiEJufqN2MW02x4UPqMZ" crossorigin="anonymous"></script>
</body>
</html>
