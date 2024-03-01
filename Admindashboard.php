<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>

    <!-- Top bar -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #689b72;">
            <div class="container-fluid">
                <img src="./images/lygo.png" alt="" class="navbar-brand" style="max-width: 100px;">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-3 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#" style="font-size: 1.1rem; color: #ffffff;"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                    <?php
                    // Start or resume the session
                    session_start();

                    // Check if user is logged in
                    if (isset($_SESSION['admin_id'])) {
                        // If logged in, show welcome message and logout button
                        echo '<span class="navbar-text" style="font-size: 1.1rem; color: #ffffff;">';
                        echo 'Welcome, ' . $_SESSION['admin_username'] . '! <a href="logout.php" class="btn btn-light mx-2">Logout</a>';
                        echo '</span>';
                    } else {
                        // If not logged in, show login button
                        echo '<span class="navbar-text" style="font-size: 1.1rem; color: #ffffff;">';
                        echo 'Welcome, Guest! <button class="btn btn-light mx-2">Login</button>';
                        echo '</span>';
                    }
                    ?>
                </div>
            </div>
        </nav>
    </div>

    <!-- Manage details bar -->
    <div class="container-fluid mt-2 text-center" style="background-color: #f8fef9;">
        <div class="row">
            <div class="col-12">
                <h1 class="text-black" style="font-family: 'Times New Roman', Times, serif;">MANAGE INFORMATION</h1>
            </div>
        </div>
    </div>

    <!-- Admin actions bar -->
    <div class="container-fluid mt-3 text-center" style="background-color: #689b72; padding: 15px;">
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            <form method="post" action="Admindashboard.php?insertproducts">
                <button type="submit" class="btn btn-light mx-2">Insert Product</button>
            </form>
            <form method="post" action="viewproduct.php">
                <button type="submit" class="btn btn-light mx-2">View Product</button>
            </form>
            <form method="post" action="Admindashboard.php?insertcategories">
                <button type="submit" class="btn btn-light mx-2">Insert Categories</button>
            </form>
            <form method="post" action="viewcategories.php">
                <button type="submit" class="btn btn-light mx-2">View Categories</button>
            </form>
            <form method="post" action="Admindashboard.php?insertbrands">
                <button type="submit" class="btn btn-light mx-2">Insert Brands</button>
            </form>
            <form method="post" action="viewbrands.php">
                <button type="submit" class="btn btn-light mx-2">View Brands</button>
            </form>
            <form method="post" action="allorders.php">
                <button type="submit" class="btn btn-light mx-2">All Orders</button>
            </form>
            <form method="post" action="allpayments.php">
                <button type="submit" class="btn btn-light mx-2">All Payments</button>
            </form>
            <form method="post" action="userlist.php">
                <button type="submit" class="btn btn-light mx-2">User List</button>
            </form>
            <!-- Additional buttons -->
 
        </div>
    </div>
</div>


    <!-- Fourth child -->
    <div class="container mt-3">
        <?php
        if(isset($_GET['insertproducts'])){
            include('insertproducts.php');
        }
        ?>
    </div>

    <!-- AllRights Reserved bar -->
    <div class="container-fluid" style="background-color: #8ead94; position: fixed; bottom: 0; width: 100%;">
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-black" style="font-family: 'Times New Roman'; font-size: 14px; margin-bottom: 10px;">All Rights Reserved © Baraka Kahindi 2023 </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN6b1fFMVpFgmiEJufqN2MW02x4UPqMZ" crossorigin="anonymous"></script>
</body>
</html>
