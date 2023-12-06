<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $token = $_POST["token"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    // Validate email and token (you can add more validation)
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/^[a-f0-9]{64}$/', $token)) {
        require_once "http://localhost/extroverse/db.php"; // Adjust the path based on your file structure

        // Check if the email and token combination exists in the password_resets table
        $tokenSql = "SELECT * FROM password_resets WHERE email='$email' AND token='$token'";
        $result = mysqli_query($koneksi, $tokenSql);

        if (mysqli_num_rows($result) == 1) {
            // The email and token are valid
            // Validate the new password
            if (strlen($newPassword) < 8) {
                echo "New password should be at least 8 characters long.";
            } elseif ($newPassword !== $confirmPassword) {
                echo "Passwords do not match.";
            } else {
                // Update the user's password in the users table
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePasswordSql = "UPDATE users SET password='$hashedPassword' WHERE email='$email'";
                if (mysqli_query($koneksi, $updatePasswordSql)) {
                    // Password reset successful
                    // You can also delete the used token from the password_resets table
                    echo "Password reset successful. Redirect the user to the login page.";
                    // Optionally, you can also delete the used token
                    $deleteTokenSql = "DELETE FROM password_resets WHERE email='$email' AND token='$token'";
                    mysqli_query($koneksi, $deleteTokenSql);
                } else {
                    echo "Error updating password: " . mysqli_error($koneksi);
                }
            }
        } else {
            echo "Invalid email or token.";
        }
    } else {
        echo "Invalid email or token format.";
    }
}
?>
