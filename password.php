<?php
include('connect.php');
require 'vendor/autoload.php';

require 'phpmailer/PHPMailer.php';
require 'phpmailer/Exception.php';
require 'phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (isset($_POST['send'])) {
    $email = $_POST['user_email'];
    $sql = "SELECT * FROM `user_table` WHERE user_email = '$email'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $verification_code = $row['verification_code']; // Extracting verification code

        // Create a new PHPMailer instance with debugging
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 2; // Enable verbose debug output

        try {
            // Recipients
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'munga90210@gmail.com';
            $mail->Password = 'lhbtrbfeeamxjezl';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('munga90210@gmail.com', 'Sanzone Group Foundation');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Verification Code';
            $mail->Body = 'Your verification code is: ' . $verification_code;

            // Uncomment the following line to send the email
            $mail->send();

            // Redirect to the userlogin page
            header("Location: userlogin.php");
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Error sending email.')</script>";
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        // Email not found in the database
        echo "<script>alert('Email not found.')</script>";
    }
} elseif (isset($_POST['back'])) {
    // Redirect to the userlogin page
    header("Location: userlogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            max-width: 400px;
            background-color: #fff;
            padding: 32px 24px;
            font-size: 14px;
            font-family: inherit;
            color: #212121;
            display: flex;
            flex-direction: column;
            gap: 20px;
            box-sizing: border-box;
            border-radius: 10px;
            box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.084), 0px 2px 3px rgba(0, 0, 0, 0.168);
        }

        .form-container button:active {
            scale: 0.95;
        }

        .form-container .logo-container {
            text-align: center;
            font-weight: 600;
            font-size: 18px;
        }

        .form-container .form {
            display: flex;
            flex-direction: column;
        }

        .form-container .form-group {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .form-container .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-container .form-group input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 6px;
            font-family: inherit;
            border: 1px solid #ccc;
        }

        .form-container .form-group input::placeholder {
            opacity: 0.5;
        }

        .form-container .form-group input:focus {
            outline: none;
            border-color: #1778f2;
        }

        .form-container .form-submit-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: inherit;
            color: #fff;
            background-color: #212121;
            border: none;
            width: 100%;
            padding: 12px 16px;
            font-size: inherit;
            gap: 8px;
            margin: 12px 0;
            cursor: pointer;
            border-radius: 6px;
            box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.084), 0px 2px 3px rgba(0, 0, 0, 0.168);
        }

        .form-container .form-submit-btn:hover {
            background-color: #313131;
        }

        .form-container .link {
            color: #1778f2;
            text-decoration: none;
        }

        .form-container .signup-link {
            align-self: center;
            font-weight: 500;
        }

        .form-container .signup-link .link {
            font-weight: 400;
        }

        .form-container .link:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <div class="form-container">
        <div class="logo-container">
            Forgot Password
        </div>

        <form class="form" method="post">
            <div class="form-group">
                <label for="user_email">Email</label>
                <input type="text" id="user_email" name="user_email" placeholder="Enter your email" required="">
            </div>

            <button class="form-submit-btn" type="submit" name="send">Send</button>
            <button class="form-submit-btn" type="submit" name="back">Back</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
