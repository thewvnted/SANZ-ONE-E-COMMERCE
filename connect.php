<?php
$con = mysqli_connect('localhost', 'root', '', 'Sanzone Commerce');

if ($con) {
    // Connection successful
    // echo "Success";
} else {
    // Connection failed
    echo "Connection failed: " . mysqli_connect_error();
}
?>
