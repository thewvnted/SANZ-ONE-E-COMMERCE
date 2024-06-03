<?php
require_once('/opt/lampp/htdocs/Sanzon E-Commerce/tcpdf/tcpdf.php');
session_start();

// Check if the session cart data exists
if (!isset($_SESSION['cartData']) || empty($_SESSION['cartData'])) {
    // Redirect back to checkout page or any other appropriate action
    header("Location: checkout.php");
    exit();
}

// Create a new PDF instance
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sanzone E-Commerce');
$pdf->SetTitle('Receipt');
$pdf->SetSubject('Order Details');
$pdf->SetKeywords('Receipt, Order, PDF');

// Set default header data
$pdf->SetHeaderData('/opt/lampp/htdocs/Sanzon E-Commerce/images/logo.png', PDF_HEADER_LOGO_WIDTH, 'Receipt ', '001', PDF_HEADER_STRING);

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add a page
$pdf->AddPage();

// Content
$html = '<h1>Receipt</h1>';

// Get order details from session cart
if (!empty($_SESSION['cartData'])) {
    $html .= '<h2>Order Details</h2>';
    $html .= '<table border="1">';
    $html .= '<tr><th>Product Name</th><th>Quantity</th><th>Price</th></tr>';
    foreach ($_SESSION['cartData'] as $productName => $item) {
        $html .= '<tr>';
        $html .= '<td>' . $item['name'] . '</td>';
        $html .= '<td>' . $item['quantity'] . '</td>';
        $html .= '<td>Ksh. ' . number_format($item['price'], 2) . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';
} else {
    $html .= '<p>No order details available.</p>';
}

// Calculate total amount paid from session cart
$totalPrice = 0;
foreach ($_SESSION['cartData'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

$html .= '<p><strong>Total Amount Paid (from session cart):</strong> Ksh. ' . number_format($totalPrice, 2) . '</p>';
$html .= '<p><strong>Date of Payment:</strong> ' . date('Y-m-d H:i:s') . '</p>';

// Fetch all orders from the Orders table
include('connect.php'); // Assuming connect.php contains your database connection code
$query = "SELECT * FROM Orders";
$result = mysqli_query($con, $query);

// Check if the query was successful
if (!$result) {
    die('Error: ' . mysqli_error($con));
}

// Add orders fetched from the database
$html .= '<h2>All Orders</h2>';
$html .= '<table border="1">';
$html .= '<tr><th>Order ID</th><th>Order Number</th><th>Order Name</th><th>Order Date</th><th>Order Status</th></tr>';
while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>';
    $html .= '<td>' . $row['order_id'] . '</td>';
    $html .= '<td>' . $row['order_number'] . '</td>';
    $html .= '<td>' . $row['order_name'] . '</td>';
    $html .= '<td>' . $row['order_date'] . '</td>';
    $html .= '<td>' . $row['order_status'] . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';

// Close the database connection
mysqli_close($con);

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('receipt.pdf', 'D');
?>
