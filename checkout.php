<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the session cart array exists and is not empty
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Check if the payment form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmPayment'])) {
    // Assume you have a function to handle payment confirmation and order status update
    confirmPayment();

    // Unset the cart session after successful payment
    unset($_SESSION['cart']);
    // Perform the redirection after unsetting the cart session
    header("Location: paymentconfirmed.php");
    exit();
}

// If the payment form is not submitted, continue rendering the checkout page
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        /* Add your CSS styles here */
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Lato', sans-serif;
            background: #f5f5f5;
        }

        .container {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .items-list {
            margin-bottom: 20px;
        }

        .price {
            color: #4488dd;
            margin-bottom: 20px;
            font-size: 1.2em;
        }

        .card__container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #555;
        }

        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .button {
            margin-top: 20px;
        }

        button {
            background: #22b877;
            border: none;
            border-radius: 10px;
            color: #fff;
            cursor: pointer;
            font-size: 19px;
            line-height: 2em;
            padding: 10px 20px;
            transition: background 0.2s ease;
        }

        button:hover {
            background: #22a877;
            color: #eee;
            transition: background 0.2s ease;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Checkout</h1>
        <?php
        if (!empty($_SESSION['cart'])) {
            // Display the cart items on the checkout page
            $totalPrice = 0;
            echo '<div class="items-list">';
            foreach ($_SESSION['cart'] as $productName => $item) {
                echo '<p>' . $item['name'] . ' x' . $item['quantity'] . ' - Ksh. ' . number_format($item['price'], 2) . '</p>';
                $totalPrice += $item['price'] * $item['quantity'];
            }
            echo '</div>';
            echo '<p class="price">Total Price: Ksh. ' . number_format($totalPrice, 2) . '</p>';
        } else {
            echo '<p>Your cart is empty. Add items before proceeding to checkout.</p>';
        }
        ?>
        <div class="card__container">
            <!-- Include the payment confirmation form here -->
            <form method="post" action="checkout.php">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" name="fullName" required>

                <label for="cardNumber">Card Number</label>
                <input type="text" id="cardNumber" name="cardNumber" required>

                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" required>

                <label for="expiryDate">Expiry Date</label>
                <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YYYY" required>

                <div class="button">
                    <button type="submit" name="confirmPayment"><i class="ion-locked"></i> Confirm and Pay</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<?php
// Function to handle payment confirmation and order status update
function confirmPayment()
{
    // Assume you have a database connection
    include('connect.php');

    // Get the total price from the session
    $totalPrice = calculateTotalPrice($_SESSION['cart']); // Assuming you have a function to calculate the total price

    // Get the existing order number from the Orders table
    $orderNumber = getOrderNumber($con);

    // Insert payment details into the Payments table
    $amount = $totalPrice;
    $date = date('Y-m-d H:i:s');

    $paymentQuery = "INSERT INTO Payments (amount, date, order_number) VALUES ('$amount', '$date', '$orderNumber')";
    $paymentResult = mysqli_query($con, $paymentQuery);

    if (!$paymentResult) {
        die('Error: ' . mysqli_error($con));
    }

    // Update order status to CONFIRMED in the Orders table
    $updateOrderQuery = "UPDATE Orders SET order_status = 'CONFIRMED' WHERE order_number = '$orderNumber' AND order_status = 'PENDING'";
    $updateOrderResult = mysqli_query($con, $updateOrderQuery);

    if (!$updateOrderResult) {
        die('Error updating order status: ' . mysqli_error($con));
    }

    // Close the database connection
    mysqli_close($con);

    // Redirect to payment confirmed page
    header("Location: paymentconfirmed.php");
    exit();
}

// Function to get the order number from the Orders table
function getOrderNumber($con)
{
    // Retrieve the order number based on your logic
    // For simplicity, I'm assuming you have a specific condition; modify this as per your actual data structure
    $query = "SELECT order_number FROM Orders WHERE order_status = 'PENDING' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die('Error getting order number: ' . mysqli_error($con));
    }

    // If an order number exists, return it; otherwise, handle accordingly
    if ($row = mysqli_fetch_assoc($result)) {
        $orderNumber = $row['order_number'];
    } else {
        die('Error: Order number not found');
    }

    return $orderNumber;
}

// Function to calculate the total price of items in the cart
function calculateTotalPrice($cart)
{
    $totalPrice = 0;
    foreach ($cart as $productName => $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }
    return $totalPrice;
}
?>
