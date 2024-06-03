<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Add your custom styles if needed -->
</head>

<body>

    <div class="container mt-5">
        <?php
        // Display custom message with image in the center of the page
        echo '<div class="text-center">';
        echo '<img src="./images/Happy face.jpeg" alt="Happy Face" style="max-width: 50%; height: auto;">'; // Adjusted size
        echo '<p class="mt-3" style="font-size: 24px; font-weight: bold;">YOUR PAYMENT HAS BEEN CONFIRMED!</p>';
        echo '</div>';
        ?>
    </div>

    <div class="container mt-3 text-center">
        <!-- Continue Shopping button linking back to index.php -->
        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
    </div>

    <div class="container mt-3 text-center">
        <!-- Continue Shopping button linking back to index.php -->
        <a href="receipt.php" class="btn btn-primary">Receipt</a>
    </div>

    <!-- Bootstrap JS scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN6b1fFMVpFgmiEJufqN2MW02x4UPqMZ"
        crossorigin="anonymous"></script>
    <!-- Add your custom scripts if needed -->

</body>

</html>
