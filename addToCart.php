<?php
session_start();
include('connect.php');

// Check if the session cart array exists and is not empty
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the removeItem flag is set
    if (isset($_POST['removeItem'])) {
        $productNameToRemove = $_POST['removeItem'];

        // Check if the product to remove is in the cart
        if (isset($_SESSION['cart'][$productNameToRemove])) {
            // Decrease the quantity or remove the item if quantity is 1
            if ($_SESSION['cart'][$productNameToRemove]['quantity'] > 1) {
                $_SESSION['cart'][$productNameToRemove]['quantity']--;
            } else {
                unset($_SESSION['cart'][$productNameToRemove]);
            }

            // Calculate and update the total price
            $totalPrice = array_sum(array_column($_SESSION['cart'], 'price'));

            // Redirect back to addToCart.php
            header("Location: addToCart.php");
            exit(); // Ensure that no further output is sent
        }
    }

    // Check if the checkout flag is set
    if (isset($_POST['checkout'])) {
        // Generate a random 4-digit order number
        $orderNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Get the current date
        $orderDate = date('Y-m-d');

        // Insert order details into the Orders table
        foreach ($_SESSION['cart'] as $productName => $item) {
            $orderName = $item['name'];
            $status = 'pending';
            
            $insertOrderQuery = "INSERT INTO Orders (order_number, order_name, order_date, order_status) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $insertOrderQuery);
            mysqli_stmt_bind_param($stmt, 'ssss', $orderNumber, $orderName, $orderDate, $status);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }


       // Redirect to checkout.php with cart data stored in session
$_SESSION['cartData'] = $_SESSION['cart'];
header("Location: checkout.php");
exit();

    }

    // Get the product details from the JSON request body
    $requestData = json_decode(file_get_contents('php://input'), true);
    $productName = isset($requestData['productName']) ? $requestData['productName'] : '';
    $productPrice = isset($requestData['productPrice']) ? $requestData['productPrice'] : 0;

    // Fetch the product details from the database
    $query = "SELECT product_price FROM products_table WHERE product_name = '$productName'";
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if (!$result) {
        die('Error: ' . mysqli_error($con));
    }

    $productData = mysqli_fetch_assoc($result);

    // Check if product data was retrieved
    if (!$productData) {
        die("Product data not found for product: $productName");
    }

    // Add the product to the cart or increase quantity if already in the cart
    if (isset($_SESSION['cart'][$productName])) {
        $_SESSION['cart'][$productName]['quantity']++;
    } else {
        $_SESSION['cart'][$productName] = array(
            'name' => $productName,
            'price' => $productData['product_price'],
            'quantity' => 1,
        );
    }

    // Calculate and update the total price
    $totalPrice = array_sum(array_column($_SESSION['cart'], 'price'));

    // Return the updated cart data
    echo json_encode(array(
        'success' => true,
        'message' => 'ITEM ADDED TO CART',
        'cartCount' => count($_SESSION['cart']),
        'totalPrice' => $totalPrice,
        'resetCart' => false, // Set resetCart flag to false
    ));

    // Close the database connection
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Add your custom styles if needed -->
</head>
<body>

<div class="container mt-3">
    <?php
    $totalPrice = 0; // Initialize total price here

    if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    ?>
        <h1>Shopping Cart</h1>
        <?php foreach ($_SESSION['cart'] as $productName => $item): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= $item['name'] ?></h5>
                    <p class="card-text">Quantity: <?= $item['quantity'] ?></p>
                    <p class="card-text">Price: Ksh. <?= number_format($item['price'], 2) ?></p>
                    <!-- Add a remove button for each item -->
                    <form method="post" action="addToCart.php">
                        <input type="hidden" name="removeItem" value="<?= $productName ?>">
                        <button type="submit" class="btn btn-danger">Remove</button>
                    </form>
                </div>
            </div>
            <?php
            $totalPrice += $item['price'] * $item['quantity']; // Update total price in the loop
        endforeach;
        ?>
        <p>Total Price: Ksh. <?= number_format($totalPrice, 2) ?></p>
        <!-- Add Proceed to Checkout button -->
        <form action="addToCart.php" method="post">
            <input type="hidden" name="checkout" value="1">
            <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
        </form>
    <?php
    } else {
        // Display custom message with image in the center of the page
        echo '<div class="text-center mt-5">';
        echo '<img src="./images/17-sad(emotions).jpg" alt="Sad Emotions" style="max-width: 100%; height: auto;">';
        echo '<p class="mt-3" style="font-size: 24px; font-weight: bold;">YOUR SHOPPING CART IS EMPTY!</p>';
        echo '</div>';
    }
    ?>

    <!-- Add your additional HTML content or styling here -->
</div>

<!-- Bootstrap JS scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN6b1fFMVpFgmiEJufqN2MW02x4UPqMZ" crossorigin="anonymous"></script>
<!-- Add your custom scripts if needed -->

</body>
</html>
