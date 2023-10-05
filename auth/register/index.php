<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"]; // Add email field
    $password = $_POST["password"];

    // Validasi data
    if (empty($username) || empty($email) || empty($password)) {
        echo "Isi semua field.";
    } elseif (strlen($username) < 5) {
        echo "Username minimal 5 karakter.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email tidak valid.";
    } elseif (strlen($password) < 8) {
        echo "Password minimal 8 karakter.";
    } else {
        // Koneksi ke database (gunakan file koneksi Anda)
        require_once "../../db.php";

        // Sanitasi data
        $username = mysqli_real_escape_string($koneksi, $username);
        $email = mysqli_real_escape_string($koneksi, $email);

        // Hash password sebelum menyimpannya di database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'pengguna')";

        if (mysqli_query($koneksi, $sql)) {
            $_SESSION["email"] = $email;
            header('location: ../login/');
            exit();
        } else {
            echo "Terjadi kesalahan dalam registrasi: " . mysqli_error($koneksi);
        }

        mysqli_close($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Register</title>
</head>

<body>
    <div class="navbar">
        <div class="overlap-2">
            <div class="div-wrapper">
                <div class="text-wrapper-4">Extro</div>
            </div>
            <div class="text-wrapper-5">verse</div>
        </div>
    </div>
    <div class="login-HP">
        <div class="div">
            <div class="card">
                <form method="POST">
                    <div class="overlap">
                        <div class="text-wrapper">Register</div>
                        <p class="don-t-have-yesplis">
                            <span class="span">Already have yesplis account ? </span>
                            <a href="../login/" class="text-wrapper-2">Login</a>
                        </p>
                        <div class="email">
                            <label for="email" class="text-wrapper-3">Email Address:</label>
                            <input class="img form-control" type="email" id="email" name="email" required />
                        </div>

                        <div class="username">
                            <label for="username" class="text-wrapper-3">Username:</label>
                            <input class="img form-control" type="text" id="username" name="username" required />
                        </div>

                        <div class="password">
                            <label for="password" class="text-wrapper-3">Password:</label>
                            <input class="img form-control" type="password" id="password" name="password" required />
                        </div>
                        <div class="submit">
                            <button class="overlap-group" type="submit">
                                <div class="submit-2">Register</div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <h2>Register</h2>
    <form method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Register">
    </form> -->
</body>

</html>