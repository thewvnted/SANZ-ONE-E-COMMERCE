<?php
// Include your database connection file
include 'connect.php';

// Function to fetch products from the database
function getProducts($conn)
{
    $sql = "SELECT * FROM products_table";
    $result = mysqli_query($conn, $sql);

    $products = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }

    return $products;
}

// Function to generate product card HTML
function generateProductCard($product)
{
    ?>
             <div class="col-md-3 mb-4 me-4">
            <div class="card" style="width: 19rem; margin-bottom: 20px; margin-right: 20px; padding: 5px;">
                    <img src="./images/<?php echo $product['product_image']; ?>" class="card-img-top" alt="..." style="height: 250px; object-fit: contain;">
                    <div class="card-body">
                        <p class="card-text"><?php echo $product['product_name']; ?></p>
                        <p class="card-text"><?php echo 'Price: Ksh.' . $product['product_price']; ?></p>
                        <button class="btn btn-primary" onclick="addToCart(this)">Add to Cart</button>
                        <a href="viewmore.php?product=<?php echo urlencode($product['product_name']); ?>" class="btn btn-secondary">View More</a>
                    </div>
                </div>
            </div>
            <?php
        }

// Fetch products from the database
$products = getProducts($conn);

// Generate product cards
foreach ($products as $product) {
    generateProductCard($product);
}

// Close the database connection
mysqli_close($conn);
?>

