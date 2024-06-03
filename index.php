<?php
    include 'connect.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanzone Gaming</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Add this script for dynamic search suggestions and form submission -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('#searchInput');
            const searchSuggestions = document.querySelector('#searchSuggestions');
            const searchForm = document.querySelector('#searchForm');

            // Function to update search suggestions
            function updateSuggestions(query) {
                // Fetch the list of products that start with the entered query
                fetch(`getproducts.php?startsWith=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(products => {
                        const suggestionsHTML = products.map(product => `<li>${product.product_name}</li>`).join('');
                        searchSuggestions.innerHTML = suggestionsHTML;
                    })
                    .catch(error => {
                        console.error('Error fetching products:', error);
                    });
            }

            // Function to handle form submission
            function handleFormSubmission() {
                const searchQuery = searchInput.value.trim();

                // Redirect to viewproducts.php with the search query as a parameter
                window.location.href = `viewproducts.php?search=${encodeURIComponent(searchQuery)}`;

                // Clear the search input value 
                searchInput.value = '';

                // Hide search suggestions
                searchSuggestions.style.display = 'none';
            }

            // Event listeners
            searchInput.addEventListener('input', function () {
                const query = this.value.trim();
                updateSuggestions(query);

                // Show/hide search suggestions
                searchSuggestions.style.display = query.length > 0 ? 'block' : 'none';
            });

            searchInput.addEventListener('focus', function () {
                // Clear search suggestions when the search input is focused
                searchSuggestions.style.display = 'none';
            });

            searchSuggestions.addEventListener('click', function (event) {
                // Handle click on a suggestion
                if (event.target.tagName === 'LI') {
                    searchInput.value = event.target.textContent;
                    handleFormSubmission();
                }
            });

            searchForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent default form submission
                handleFormSubmission();
            });
        });
    </script>
</head>

<body>
    <?php
    session_start();

    function getusername($conn, $username) {
        $sql = "SELECT user_name FROM user_table WHERE user_name = ?";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["user_name"];
        } else {
            return "";
        }
    }



    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        // ... (your existing login logic)
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
        session_destroy();
        header("Location: index.php");
        exit();
    }

    $welcomeMessage = 'Welcome, Guest!&nbsp';
    $loginButton = '<a href="login.php" class="btn btn-outline-light" style="margin-left: 5px;">Login</a>';

    if (isset($_SESSION['user_id'])) {
        $username = $_SESSION['user_name'];
        $usernameFromDB = getusername($con, $username);

        if ($usernameFromDB != "") {
            $welcomeMessage = 'Welcome, ' . $usernameFromDB . '!';
            $loginButton = '<form method="post" action=""><button type="submit" name="logout" class="btn btn-outline-light" style="margin-left: 5px;">Logout</button></form>';
        }
    }
    ?>

    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #689b72;">
            <div class="container-fluid">
                <img src="./images/lygo.png" alt="" class="navbar-brand" style="max-width: 100px;">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="viewproducts.php" style="font-size: 1.1rem; color: #ffffff;">All Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="signup.php" style="font-size: 1.1rem; color: #ffffff;">SignUp</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="contact.php" style="font-size: 1.1rem; color: #ffffff;">Contact</a>
                        </li>
                        <a class="nav-link" href="addToCart.php" style="font-size: 1.1rem; color: #ffffff;">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                        </a>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Omitted the total price section -->
                        <?php else: ?>
                            <li class="nav-item">
                                <!-- Omitted the total price calculation and display -->
                            </li>
                        <?php endif; ?>
                    </ul>

                    <form class="d-flex ms-auto" id="searchForm">
                        <input class="form-control me-2" type="search" id="searchInput" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                        <!-- Suggestions container -->
                        <ul id="searchSuggestions"></ul>
                    </form>

                    <!-- ... (existing code) ... -->

                </div>
            </div>
        </nav>
    </div>

    <div class="container-fluid" style="background-color: #8ead94;">
        <div class="row">
            <div class="col-6">
                <p class="mt-2"><?php echo $welcomeMessage . $loginButton; ?>
                    <div class="dropdown" style="display: inline-block; margin-left: 10px;">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="sidebarDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 0.8em; padding: 10px 20px;">Menu</button>
                        <div class="dropdown-menu" aria-labelledby="sidebarDropdown">
                            <style>
                                .dropdown { display: inline-block; margin-left: 10px; }
                                .dropdown-toggle { background: #75F; color: #FFF; padding: 20px 40px; border-radius: 3px; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5); transition: background 0.5s, color 0.5s, transform 0.5s; position: relative; cursor: pointer; }
                                .dropdown-menu { position: absolute; top: 100%; left: 0; background-color: #19222a; border: none; border-radius: 0; padding: 0; width: 18em; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5); }
                                .dropdown-menu a { color: rgba(255, 255, 255, 0.9); font-size: 0.75em; padding: 1.05em 1em; display: block; white-space: nowrap; }
                                .dropdown-menu a:hover { background-color: rgba(0, 0, 0, 0.9); transition: all 0.6s ease; }
                            </style>
                            <aside class="sidebar">
                                <header>Menu</header>
                                <nav class="sidebar-nav">
                                    <ul>
                                        <li><a href="#"><i class="ion-bag"></i> <span>Brands:</span></a>
                                            <ul class="nav-flyout">
                                                <li><a href="viewproducts.php?brand=PlayStation"><i class="ion-ios-color-filter-outline"></i>PlayStation</a></li>
                                                <li><a href="viewproducts.php?brand=Switch"><i class="ion-ios-clock-outline"></i>Switch</a></li>
                                                <li><a href="viewproducts.php?brand=XBOX"><i class="ion-android-star-outline"></i>XBOX</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#"><i class="ion-bag"></i> <span>Categories:</span></a>
                                            <ul class="nav-flyout">
                                                <li><a href="viewproducts.php?category=Console"><i class="ion-ios-alarm-outline"></i>Console</a></li>
                                                <li><a href="viewproducts.php?category=Gaming Pads"><i class="ion-ios-camera-outline"></i>Gaming Pads</a></li>
                                                <li><a href="viewproducts.php?category=Games"><i class="ion-ios-chatboxes-outline"></i>Games</a></li>
                                                <li><a href="viewproducts.php?category=Gift Cards"><i class="ion-ios-chatboxes-outline"></i>Gift Cards</a></li>
                                                <li><a href="viewproducts.php?category=Accessories"><i class="ion-ios-chatboxes-outline"></i>Accessories</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </aside>
                        </div>
                    </div>
                </p>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-2 text-center" style="background-color: #f8fef9;">
        <div class="row">
            <div class="col-12">
                <h1 class="text-black bold" style="font-family: 'Times New Roman', Times, serif;">Elevate your gaming Experience!</h1>
            </div>
        </div>
    </div>

    <?php


    function getProducts($con, $searchQuery = '') {
        $sql = "SELECT * FROM products_table";
        
        // Modify the SQL query to include search filtering
        if (!empty($searchQuery)) {
            $sql .= " WHERE product_name LIKE '%$searchQuery%'";
        }

        $result = mysqli_query($con, $sql);

        $products = array();

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
        }

        return $products;
    }

    function generateProductCard($product) {
    ?>
        <div class="col-md-3 mb-4">
            <div class="container d-flex justify-content-center">
                <div class="card" style="max-width: 20rem; margin: 10 10px 20px 10px; padding: 8px;">
                    <img src="./images/<?php echo $product['product_image']; ?>" class="card-img-top" alt="..." style="height: 150px; object-fit: contain;">
                    <div class="card-body">
                        <p class="card-text"><?php echo $product['product_name']; ?></p>
                        <p class="card-text"><?php echo 'Price: Ksh.' . $product['product_price']; ?></p>
                        <button class="btn btn-primary" onclick="addToCart(this)">Add to Cart</button>
                        <a href="viewmore.php?product=<?php echo urlencode($product['product_name']); ?>" class="btn btn-secondary">View More</a>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    if ($con) {
        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
        $products = getProducts($con, $searchQuery);
    } else {
        $products = array();
    }
    ?>

    <div class="container">
        <div class="row mb-3">
            <?php
            foreach ($products as $product) {
                generateProductCard($product);
            }
            ?>
        </div>
    </div>

    <style>
    /* Style for the footer bar */
    .footer-bar {
        background-color: #8ead94;
        position: fixed;
        bottom: 0;
        width: 100%;
        display: none; /* Initially hide the footer bar */
        transition: display 0.5s ease; /* Add a transition effect */
        padding: 5px 0; /* Adjust the padding */
        max-height: 50px; /* Set maximum height */
        overflow: hidden; /* Hide overflow content */
    }

    /* Style for the footer text */
    .footer-text {
        color: #000;
        font-family: 'Times New Roman';
        font-size: 14px;
        text-align: center;
        padding: 0;
        margin: 0;
        position: absolute;
        right: 10px; /* Position to the far right */
        bottom: 0;
        font-weight: bold; /* Make the text very bold */
    }

    /* Style for the partner logos */
    .partner-logos {
        display: flex;
        justify-content: center;
        align-items: center; /* Align items vertically */
        margin-bottom: 5px; /* Adjust as needed */
    }

    .partner-logo {
        max-width: 80px; /* Adjust as needed */
        height: auto;
        margin: 0 10px; /* Adjust as needed */
    }
</style>

<!-- Footer -->
<div class="container-fluid footer-bar" id="footerBar">
    <div class="row">
        <div class="col-12">
            <div class="partner-logos">
                <img src="/Sanzon E-Commerce/images/Visa Logo.png" alt="Visa Logo" class="partner-logo">
                <img src="/Sanzon E-Commerce/images/Mastercard.png" alt="Mastercard Logo" class="partner-logo">
                <img src="/Sanzon E-Commerce/images/Paypal.png" alt="PayPal Logo" class="partner-logo">
            </div>
            <p class="footer-text">All Rights Reserved © Baraka Kahindi 2023</p>
        </div>
    </div>
</div>





<script>
    window.addEventListener('scroll', function () {
        var footerBar = document.getElementById('footerBar');
        var scrollPosition = window.scrollY;

        // Calculate the threshold for showing the footer
        var threshold = document.body.scrollHeight - window.innerHeight * 1.2;

        // If scroll position is past the threshold, show the footer, else hide it
        if (scrollPosition >= threshold) {
            footerBar.style.display = 'block';
        } else {
            footerBar.style.display = 'none';
        }
    });
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Add JavaScript for logo click -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Wait for the navbar-brand element to be available
            const navbarBrand = document.querySelector('.navbar-brand');

            if (navbarBrand) {
                navbarBrand.addEventListener('click', function () {
                    window.location.href = 'index.php'; // Redirect to the original page on logo click
                });
            }
        });
    </script>

    <!-- Add this script for handling Add to Cart button click -->
    <script>
        function addToCart(button) {
            // Get the product details from the card
            const card = button.closest('.card');
            const productName = card.querySelector('.card-text').textContent;
            const productPriceString = card.querySelector('.card-text:nth-child(2)').textContent;
            const productPrice = parseFloat(productPriceString.replace('Price: Ksh.', '').trim());

            // Make an asynchronous request to the server to add the product to the cart
            fetch('addToCart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    productName: productName,
                    productPrice: productPrice,
                }),
            })
            .then(response => response.json())
            .then(data => {
                // Update the cart icon and total price on the page
                updateCartUI(data);

                // Check if the resetCart flag is set
                if (data.resetCart) {
                    // Redirect to the original form of the page
                    window.location.href = 'index.php';
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
            });
        }

        // Function to update the cart icon and total price on the page
        function updateCartUI(cartData) {
            const cartIcon = document.querySelector('.fa-shopping-cart');
            const cartCountElement = document.querySelector('.fa-shopping-cart + .nav-link');
            const totalPriceElement = document.querySelector('.nav-item:has(.fa-shopping-cart) + .nav-item a');

            // Update cart icon count
            const cartCount = cartData.cartCount;
            cartIcon.textContent = ` Cart ${cartCount}`;

            // Update total price
            const totalPrice = cartData.totalPrice.toFixed(2); // Format total price to two decimal places
            totalPriceElement.textContent = `Total Price: Ksh. ${totalPrice}`;
        }
    </script>
</body>
</html>
