<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanzone Gaming</title>
    <!-- bootstrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<?php
// Start or resume the session
session_start();

// Assuming you have a section that calculates the cart item count
$cartItemCount = 0; // Initialize with a default value

// Check if the cart array exists in the session, if not, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to get the user's username from the database
function getusername($conn, $username)
{
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

// Establish a database connection (replace with your database credentials)
$con = mysqli_connect('localhost', 'root', '', 'Sanzone Commerce');

// Check if login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['user-name'];
    $password = $_POST['user_password'];

    $sql = "SELECT * FROM `user_table` WHERE user_name = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            if (password_verify($password, $row['user_password'])) {
                // Successful login
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_name'] = $row['user_name'];
                header("Location: index.php");
                exit();
            } else {
                // Incorrect password
                echo "<script>alert('Incorrect password')</script>";
            }
        } else {
            // User not found
            echo "<script>alert('User not found. Username: $username')</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        // Statement preparation failed
        echo "<script>alert('Error preparing statement: " . mysqli_error($con) . "')</script>";
    }
}

// Check if logout button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();

    // Redirect to index.php
    header("Location: index.php");
    exit();
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // If logged in, get user's username and update welcome message with it
    $username = $_SESSION['user_name'];
    $usernameFromDB = getusername($con, $username);

    if ($usernameFromDB != "") {
        $welcomeMessage = 'Welcome, ' . $usernameFromDB . '!';
        // Provide logout button
        $loginButton = '<form method="post" action=""><button type="submit" name="logout" class="btn btn-outline-light" style="margin-left: 5px;">Logout</button></form>';
    }
} else {
    // User is not logged in
    $welcomeMessage = 'Welcome, Guest!&nbsp';
    $loginButton = '<a href="login.php" class="btn btn-outline-light" style="margin-left: 5px;">Login</a>';
}

// Calculate and display the number of items in the cart
$numItemsInCart = count($_SESSION['cart']);
$cartButton = '<a class="nav-link" href="#" style="font-size: 1.1rem; color: #ffffff;">';
$cartButton .= '<i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart (<span id="cartItemCount">' . $numItemsInCart . '</span>)</a>';
?>




    <!-- top bar -->
    <div class="container-fluid p-0">
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #689b72;">
        <div class="container-fluid">
            <img src="./images/lygo.png" alt="" class="navbar-brand" style="max-width: 100px;">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.1rem; color: #ffffff;">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php" style="font-size: 1.1rem; color: #ffffff;">SignUp</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.1rem; color: #ffffff;">Contact</a>
                    </li>
                    <li class="nav-item">
    <?php echo '<a class="nav-link" href="#" style="font-size: 1.1rem; color: #ffffff;">';
    echo '<i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart (' . $numItemsInCart . ')</a>'; ?>
</li>

                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.1rem; color: #ffffff;">Total Price:</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
</div>


    <!-- welcome bar -->
    <div class="container-fluid" style="background-color: #8ead94;">
        <div class="row">
            <div class="col-6">
                <p class="mt-2"><?php echo $welcomeMessage; ?><?php echo $loginButton; ?></p>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-2 text-center" style="background-color: #f8fef9;">
        <div class="row">
            <div class="col-12">
                <h1 class="text-black"
                    style="font-family: 'Times New Roman', Times, serif;">Elevate your gaming Experience</h1>
            </div>
        </div>
    </div>


<!-- New right column as a side navbar for brands and categories just below the Elave your game text-->

<!-- fourth child -->
<div class="row">
<div class="col-md-10">

<!-- products -->
<div class="container">
    <div class="row mb-3">
        <div class="col-md-4 mb-3">
            <div class="card" style="width: 19rem;">
                <img src="./images/PS5 Console.jpeg" class="card-img-top" alt="..." style="height: 250px; object-fit: contain;">
                <div class="card-body">
                    <p class="card-text">PlayStation 5 Standard 825GB.</p>
                    <button class="btn btn-primary" onclick="addToCart(this)">Add to Cart</button>
                    <a href="#" class="btn btn-secondary">View More</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card" style="width: 19rem;">
                <img src="./images/PS4 EAFC24.jpeg" class="card-img-top" alt="..." style="height: 250px; object-fit: contain;">
                <div class="card-body">
                    <p class="card-text">PlayStation 4 EAFC24.</p>
                    <button class="btn btn-primary" onclick="addToCart(this)">Add to Cart</button>
                    <a href="#" class="btn btn-secondary">View More</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card" style="width: 19rem;">
                <img src="./images/Nintendo Mario Game.jpeg" class="card-img-top" alt="..." style="height: 250px; object-fit: contain;">
                <div class="card-body">
                    <p class="card-text">Mario Kart Nintendo.</p>
                    <button class="btn btn-primary" onclick="addToCart(this)">Add to Cart</button>
                    <a href="#" class="btn btn-secondary">View More</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4 mb-3">
            <div class="card" style="width: 19rem;">
                <img src="./images/PlayStation Plus Gift card.jpeg" class="card-img-top" alt="..." style="height: 250px; object-fit: contain;">
                <div class="card-body">
                    <p class="card-text">PlayStation Plus Gift card.</p>
                    <button class="btn btn-primary" onclick="addToCart(this)">Add to Cart</button>
                    <a href="#" class="btn btn-secondary">View More</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card" style="width: 19rem;">
                <img src="./images/Nintendo Switch.jpeg" class="card-img-top" alt="..." style="height: 250px; object-fit: contain;">
                <div class="card-body">
                    <p class="card-text">Nintendo Switch console.</p>
                    <button class="btn btn-primary" onclick="addToCart(this)">Add to Cart</button>
                    <a href="#" class="btn btn-secondary">View More</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card" style="width: 19rem;">
                <img src="./images/PS5 EAFC24.jpeg" class="card-img-top" alt="..." style="height: 250px; object-fit: contain;">
                <div class="card-body">
                    <p class="card-text">PlayStation 5 EAFC24.</p>
                    <button class="btn btn-primary" onclick="addToCart(this)">Add to Cart</button>
                    <a href="#" class="btn btn-secondary">View More</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4 mb-3">
            <div class="card" style="width: 19rem;">
                <img src="./images/XBOX EAFC24.jpeg" class="card-img-top" alt="..." style="height: 250px; object-fit: contain;">
                <div class="card-body">
                    <p class="card-text">XBOX EAFC24.</p>
                    <button class="btn btn-primary" onclick="addToCart(this)">Add to Cart</button>
                    <a href="#" class="btn btn-secondary">View More</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card" style="width: 19rem;">
                <img src="./images/XBOX Console.jpeg" class="card-img-top" alt="..." style="height: 250px; object-fit: contain;">
                <div class="card-body">
                    <p class="card-text">XBOX Series X 1TB.</p>
                    <button class="btn btn-primary" onclick="addToCart(this)">Add to Cart</button>
                    <a href="#" class="btn btn-secondary">View More</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card" style="width: 19rem;">
                <img src="./images/PS4 Cosole.jpeg" class="card-img-top" alt="..." style="height: 250px; object-fit: contain;">
                <div class="card-body">
                    <p class="card-text">PlayStation 4 1TB.</p>
                    <button class="btn btn-primary" onclick="addToCart(this)">Add to Cart</button>
                    <a href="#" class="btn btn-secondary">View More</a>
                </div>
            </div>
        </div>
    </div>
</div>


     <!-- JavaScript to Update Cart Count -->
    <script>
        function addToCart(button) {
            var cartItemCountElement = document.getElementById('cartItemCount');
            var newCartCount = parseInt(cartItemCountElement.innerText) + 1;
            cartItemCountElement.innerText = newCartCount;
            button.disabled = true;

            // Send an AJAX request to update the cart in the session
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Handle the response if needed
                }
            };
            xhr.send("add_to_cart=true&product_id=" + productId);
        }
    </script>


<div class="col-md-2">
<!--sidenav -->

<style>
body {
    font-family: "Source Sans Pro", sans-serif;
    font-size: 100%;
    overflow-y: scroll;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
    background-color: #fefefe;
}

.app {
    max-height: 100vh;
    overflow: hidden;
}

.sidebar {
    position: absolute;
    width: 18em;
    padding: 0.5em 0;
    height: calc(100% - 0%);
    top: 26%;
    bottom: 50%;
    right: 0;
    overflow: hidden;
    background-color: #19222a;
    -webkit-transform: translateZ(0);
    visibility: visible;
    -webkit-backface-visibility: hidden;
}

.sidebar header {
    background-color: #09f;
    width: 100%;
    display: block;
    padding: 0.75em 1em;
    margin-bottom: 3em; /* Add margin below the header */
    margin-bottom: 3em; /* Add margin between Brand and Categories */
}

.sidebar-nav {
    position: fixed;
    background-color: #19222a;
    height: 100%;
    font-weight: 400;
    font-size: 1.2em;
    overflow: auto;
    padding-bottom: 6em;
    z-index: 9;
    overflow: hidden;
    -webkit-overflow-scrolling: touch;
}

.sidebar-nav ul {
    list-style: none;
    display: block;
    padding: 0;
    margin: 0;
}

.sidebar-nav ul li {
    margin-left: 0;
    padding-left: 0;
    display: inline-block;
    width: 100%;
}

.sidebar-nav ul li a {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.75em;
    padding: 1.05em 1em;
    position: relative;
    display: block;
}

.sidebar-nav ul li a:hover {
    background-color: rgba(0, 0, 0, 0.9);
    transition: all 0.6s ease;
}

.sidebar-nav ul li i {
    font-size: 1.8em;
    padding-right: 0.5em;
    width: 9em;
    display: inline;
    vertical-align: middle;
}

.sidebar-nav ul li a:after {
    content: '\f125';
    font-family: ionicons;
    font-size: 0.5em;
    width: 10px;
    color: #fff;
    position: absolute;
    right: 0.75em;
    top: 45%;
}

.sidebar-nav .nav-flyout {
    position: absolute;
    background-color: #080D11;
    z-index: 9;
    left: 2.5em;
    top: 0;
    height: 100vh;
    transform: translateX(100%);
    transition: all 0.5s ease;
}

.sidebar-nav .nav-flyout a:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

.sidebar-nav ul li:hover .nav-flyout {
    transform: translateX(0);
    transition: all 0.5s ease;
}
     /* Add some margin or padding between Brand and Categories */
     .sidebar-nav ul li:nth-child(2) {
            margin-bottom: 0em; /* Add margin between Brand and Categories */
        }
    </style>
</head>



<div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <section class="app">
                    <aside class="sidebar">
                        <header>
                            Menu
                        </header>
                        <nav class="sidebar-nav">
                            <ul>
                                <li>
                                    <a href="#"><i class="ion-bag"></i> <span>Brand</span></a>
                                    <ul class="nav-flyout">
                                        <li>
                                            <a href="#"><i class="ion-ios-color-filter-outline"></i>PlayStation</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="ion-ios-clock-outline"></i>Switch</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="ion-android-star-outline"></i>XBOX</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><i class="ion-bag"></i> <span>Categories</span></a>
                                    <ul class="nav-flyout">
                                        <li>
                                            <a href="#"><i class="ion-ios-alarm-outline"></i>Console</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="ion-ios-camera-outline"></i>Gaming Pads</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="ion-ios-chatboxes-outline"></i>Games</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </aside>
                </section>
            </div>
        </div>
    </div>
    <!-- AllRights Reserved bar -->
    <div class="container-fluid" style="background-color: #8ead94; position: fixed; bottom: 0; width: 100%;">
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-black" style="font-family: 'Times New Roman'; font-size: 14px; margin-bottom: 10px;">All Rights Reserved © Baraka Kahindi 2023 </p>
            </div>
        </div>
    </div>
    <!-- bootstrap js link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>
