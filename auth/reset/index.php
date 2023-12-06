<?php
session_start();

// Include PHPMailer autoloader
require 'C:\xampp\htdocs\extroverse\vendor\phpmailer\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\extroverse\vendor\phpmailer\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\extroverse\vendor\phpmailer\phpmailer\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Validate email (you can add more validation)
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        require_once 'C:\xampp\htdocs\extroverse\db.php'; // Adjust the path based on your file structure

        // Check if the email exists in your user database
        $checkEmailSql = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($koneksi, $checkEmailSql);

        if (mysqli_num_rows($result) == 1) {
            // Email exists, generate a unique token
            $token = bin2hex(random_bytes(32));

            // Store the token in the password_resets table
            $storeTokenSql = "INSERT INTO password_resets (email, token) VALUES ('$email', '$token')";
            if (mysqli_query($koneksi, $storeTokenSql)) {
                // Send an email with the reset link
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host       = 'gmail.com';  // Specify your SMTP server
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'bimagaminh@gmail.com';  // SMTP username
                    $mail->Password   = '@Nabati2301';  // SMTP password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;

                    // Recipients
                    $mail->setFrom('bimagaminh@gmail.com', 'Bima Ryan Alfarizi');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset';
                    $mail->Body    = "Click the following link to reset your password: <a href='http://localhost/auth/reset/confirm/?email=$email&token=$token'>Reset Password</a>";

                    $mail->send();
                    echo 'Password reset link sent to your email.';
                } catch (Exception $e) {
                    echo "Error sending email: {$mail->ErrorInfo}";
                }
            } else {
                echo "Error storing reset token: " . mysqli_error($koneksi);
            }
        } else {
            echo "Email not found.";
        }
    } else {
        echo "Invalid email format.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>

<body>
    <form method="POST" action="">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>
</body>

</html>