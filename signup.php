<?php
include('connect.php');

if (isset($_POST['SignUp'])) {
    $username = $_POST['username'];
    $f_name = $_POST['firstname'];
    $l_name = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Generate a 4-digit verification code
    $verification_code = rand(1000, 9999);

    $hash_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

    $stmt = mysqli_prepare($con, "INSERT INTO `user_table` (user_name, user_firstname, user_lastname, user_email, user_password, verification_code) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssss", $username, $f_name, $l_name, $email, $hash_password, $verification_code);

    $execution_result = mysqli_stmt_execute($stmt);

    if ($execution_result) {
        echo "<script>alert('Successful SignUp!')</script>";
        echo "<script>window.open('login.php', '_self')</script>";
    } else {
        echo "<script>alert('Failed')</script>";
        echo "<script>window.open('signup.php', '_self')</script>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
    body {
        height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 350px;
        padding: 20px;
        border-radius: 20px;
        position: relative;
        background-color: #1a1a1a;
        color: #fff;
        border: 1px solid #333;
    }

    .title {
        font-size: 28px;
        font-weight: 600;
        letter-spacing: -1px;
        position: relative;
        display: flex;
        align-items: center;
        padding-left: 30px;
        color: #00bfff;
    }

    .title::before {
        width: 18px;
        height: 18px;
    }

    .title::after {
        width: 18px;
        height: 18px;
        animation: pulse 1s linear infinite;
    }

    .title::before,
    .title::after {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        border-radius: 50%;
        left: 0px;
        background-color: #00bfff;
    }

    .message,
    .signin {
        font-size: 14.5px;
        color: rgba(255, 255, 255, 0.7);
    }

    .signin {
        text-align: center;
    }

    .signin a:hover {
        text-decoration: underline royalblue;
    }

    .signin a {
        color: #00bfff;
    }

    .flex {
        display: flex;
        width: 100%;
        gap: 6px;
    }

    .form label {
        position: relative;
    }

    .form label .input {
        background-color: #333;
        color: #fff;
        width: 100%;
        padding: 20px 05px 05px 10px;
        outline: 0;
        border: 1px solid rgba(105, 105, 105, 0.397);
        border-radius: 10px;
    }

    .form label .input + span {
        color: rgba(255, 255, 255, 0.5);
        position: absolute;
        left: 10px;
        top: 0px;
        font-size: 0.9em;
        cursor: text;
        transition: 0.3s ease;
    }

    .form label .input:placeholder-shown + span {
        top: 12.5px;
        font-size: 0.9em;
    }

    .form label .input:focus + span,
    .form label .input:valid + span {
        color: #00bfff;
        top: 0px;
        font-size: 0.7em;
        font-weight: 600;
    }

    .input {
        font-size: medium;
    }

    .submit {
        border: none;
        outline: none;
        padding: 10px;
        border-radius: 10px;
        color: #fff;
        font-size: 16px;
        transform: .3s ease;
        background-color: #00bfff;
    }

    .submit:hover {
        background-color: #00bfff96;
    }

    @keyframes pulse {
        from {
            transform: scale(0.9);
            opacity: 1;
        }

        to {
            transform: scale(1.8);
            opacity: 0;
        }
    }
</style>
</head>
<body>
    <form class="form" method="post" action="">
        <p class="title">Sign Up</p>
        <p class="message">Signup now and get full access to our app.</p>
        
        <label>
            <input required="" placeholder="" type="text" class="input" name="username">
            <span>Username</span>
        </label>

        <div class="flex">
            <label>
                <input required="" placeholder="" type="text" class="input" name="firstname">
                <span>Firstname</span>
            </label>

            <label>
                <input required="" placeholder="" type="text" class="input" name="lastname">
                <span>Lastname</span>
            </label>
        </div>

        <label>
            <input required="" placeholder="" type="email" class="input" name="email">
            <span>Email</span>
        </label>

        <label>
            <input required="" placeholder="" type="password" class="input" name="password">
            <span>Password</span>
        </label>
        <label>
            <input class="input" type="password" name="confirmpassword" id="confirmpassword" placeholder="" required="required">
            <span>Confirm password</span>
        </label>
        <button class="submit" name="SignUp">Submit</button>
        <p class="login">Already have an account? <a href="login.php">LogIn</a></p>
    </form>
</body>
</html>
