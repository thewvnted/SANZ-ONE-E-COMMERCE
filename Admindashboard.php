<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Add JavaScript for logo click -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('.navbar-brand').addEventListener('click', function () {
                window.location.href = 'Admindashboard.php'; // Redirect to the original page on logo click
            });
        });
    </script>

</head>

<body>

    <!-- Top bar -->
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
                    <ul class="navbar-nav me-auto mb-3 mb-lg-0">
                    </ul>
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
            <div class="col-12 d-flex justify-content-start align-items-center">
                <!-- Add some spacing between the welcome message, login/logout button, and other buttons -->
                <div style="margin-right: 180px;">

                    <?php
                    error_reporting(E_ALL);
                    ini_set('display_errors', 1);

                    // Start or resume the session
                    session_start();

                    // Function to get the admin's username from the database
                    function getUsername($con, $id)
                    {
                        $sql = "SELECT username FROM admin_table WHERE id = ?";
                        $stmt = mysqli_prepare($con, $sql);

                        mysqli_stmt_bind_param($stmt, "s", $id);
                        mysqli_stmt_execute($stmt);

                        $result = mysqli_stmt_get_result($stmt);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            return $row["username"];
                        } else {
                            return "";
                        }
                    }

                    // Establish a database connection (replace with your database credentials)
                    $con = mysqli_connect('localhost', 'root', '', 'Sanzone Commerce');

                    // Check if login form is submitted
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
                        // Your existing login code...

                        // After successful login, redirect to the dashboard
                        header("Location: Admindashboard.php");
                        exit();
                    }
                    $welcomeMessage = '';
                    $loginButton = '<a href="adminlogin.php" class="btn btn-outline-light" style="margin-left: 10px;">Login</a>';

                    if (isset($_SESSION['id']) && $_SESSION['username'] !== "") {
                        // If logged in, get admin's username and update welcome message with it
                        $id = $_SESSION['id'];
                        $usernameFromDB = getUsername($con, $id);

                        if ($usernameFromDB != "") {
                            $welcomeMessage = 'Welcome, ' . $usernameFromDB . '!';
                            // Provide logout button
                            $loginButton = '<form method="post" action=""><button type="submit" name="logout" class="btn btn-outline-light" style="margin-left: 5px;">Logout</button></form>';
                        }
                    }

                    // Display the welcome message
                    echo '<span class="navbar-text" style="font-size: 1.1rem; color: #ffffff;">' . $welcomeMessage . '</span>';
                    echo $loginButton;

                    // Check if logout button is clicked
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
                        // Destroy the session
                        session_destroy();

                        // Redirect to Admindashboard.php or any other desired page after logout
                        header("Location: Admindashboard.php");
                        exit();
                    }

                    // Close the database connection
                    mysqli_close($con);
                    ?>

                </div>

                <!-- Additional buttons -->
                <?php
                if (isset($_SESSION['id']) && $_SESSION['username'] !== "") {
                    ?>
                    <form method="post" action="Admindashboard.php?insertproducts">
                        <button type="submit" class="btn btn-light mx-2">Insert Products</button>
                    </form>
                    <form method="post" action="Admindashboard.php?viewproducts">
                        <button type="submit" class="btn btn-light mx-2">View Products</button>
                    </form>
                    <form method="post" action="Admindashboard.php?insertcategories">
                        <button type="submit" class="btn btn-light mx-2">Insert Categories</button>
                    </form>
                    <form method="post" action="Admindashboard.php?viewcategories">
                        <button type="submit" class="btn btn-light mx-2">View Categories</button>
                    </form>
                    <form method="post" action="Admindashboard.php?insertbrands">
                        <button type="submit" class="btn btn-light mx-2">Insert Brands</button>
                    </form>
                    <form method="post" action="Admindashboard.php?viewbrands">
                        <button type="submit" class="btn btn-light mx-2">View Brands</button>
                    </form>
                    <form method="post" action="Admindashboard.php?allorders">
                        <button type="submit" class="btn btn-light mx-2">All Orders</button>
                    </form>
                    <form method="post" action="Admindashboard.php?allpayments">
                        <button type="submit" class="btn btn-light mx-2">All Payments</button>
                    </form>
                    <form method="post" action="Admindashboard.php?userlist">
                        <button type="submit" class="btn btn-light mx-2">User List</button>
                    </form>
                <?php
                }
                ?>
                <!-- Additional buttons -->
            </div>
        </div>
    </div>

    <!-- Fourth child -->
    <div class="container mt-3">
        <?php
        if (isset($_GET['insertproducts'])) {
            include('insertproducts.php');
        } elseif (isset($_GET['viewproducts'])) {
            include('viewproducts.php');
        } elseif (isset($_GET['insertcategories'])) {
            include('insertcategories.php');
        } elseif (isset($_GET['viewcategories'])) {
            include('viewcategories.php');
        } elseif (isset($_GET['insertbrands'])) {
            include('insertbrands.php');
        } elseif (isset($_GET['viewbrands'])) {
            include('viewbrands.php');
        } elseif (isset($_GET['allorders'])) {
            include('allorders.php');
        } elseif (isset($_GET['allpayments'])) {
            include('allpayments.php');
        } elseif (isset($_GET['userlist'])) {
            include('userlist.php');
        }
        ?>
    </div>

    <!-- All Rights Reserved bar -->
    <div class="container-fluid" style="background-color: #8ead94; position: fixed; bottom: 0; width: 100%;">
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-black"
                    style="font-family: 'Times New Roman'; font-size: 14px; margin-bottom: 10px;">All Rights Reserved
                    © Baraka Kahindi 2023 </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN6b1fFMVpFgmiEJufqN2MW02x4UPqMZ"
        crossorigin="anonymous"></script>
</body>

</html>
