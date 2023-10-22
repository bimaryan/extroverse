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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Register</title>
    <style>
        .login-HP {
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 85vh;
        }

        .login-HP .overlap {
            position: relative;
            width: 379px;
            height: 420px;
            background-color: #ffffff;
            border-radius: 7px;
            border: 1px solid;
            border-color: #000000;
            /* box-shadow: 8px 8px 5px #00000040; */
        }

        .login-HP .text-wrapper {
            position: absolute;
            height: 35px;
            top: 9px;
            left: 156px;
            font-family: "Aoboshi One", Helvetica;
            font-weight: 400;
            color: #000000;
            font-size: 24px;
            text-align: center;
            letter-spacing: 0;
            line-height: normal;
        }

        .login-HP .don-t-have-yesplis {
            position: absolute;
            height: 15px;
            top: 50px;
            left: 74px;
            font-family: "Archivo", Helvetica;
            font-weight: 400;
            color: transparent;
            font-size: 14px;
            text-align: center;
            letter-spacing: 0;
            line-height: normal;
            white-space: nowrap;
        }

        .login-HP .span {
            color: #919191;
        }

        .login-HP .text-wrapper-2 {
            color: #9d00e7;
            text-decoration: none;
        }

        .login-HP .email {
            position: absolute;
            width: 311px;
            height: 61px;
            top: 109px;
            left: 34px;
        }

        .login-HP .username {
            position: absolute;
            width: 311px;
            height: 61px;
            top: 184px;
            left: 34px;
        }

        .login-HP .text-wrapper-3 {
            height: 15px;
            top: 0;
            font-family: "Archivo", Helvetica;
            font-weight: 700;
            color: #000000;
            font-size: 14px;
            text-align: center;
            letter-spacing: 0;
            line-height: normal;
            white-space: nowrap;
            position: absolute;
            left: 0;
        }

        .login-HP .img {
            width: 309px;
            height: 41px;
            top: 20px;
            position: absolute;
            left: 0;
        }

        .login-HP .password {
            position: absolute;
            width: 311px;
            height: 61px;
            top: 259px;
            left: 34px;
        }

        .login-HP .submit {
            position: absolute;
            width: 311px;
            height: 41px;
            top: 350px;
            left: 34px;
        }

        .login-HP .overlap-group {
            position: relative;
            width: 309px;
            height: 41px;
            background-color: #0038ff;
            border-radius: 5px;
            background-size: 100% 100%;
        }

        .login-HP .submit-2 {
            font-family: "Archivo", Helvetica;
            font-weight: 700;
            color: #ffffff;
            font-size: 14px;
            text-align: center;
        }

        .navbar {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo {
            height: 100%;
            width: 80px;
        }
    </style>
</head>

<body>
    <nav class="navbar bg-body-tertiary shadow">
        <div class="container-fluid d-flex justify-content-center align-items-center">
            <a class="navbar-brand" href="/">
                <img src="../../img/extroverse.png" alt="Logo" class="d-inline-block align-text-top logo">
            </a>
        </div>
    </nav>
    <div class="login-HP">
        <div class="div">
            <div class="card">
                <form method="POST">
                    <div class="overlap">
                        <div class="text-wrapper">Register</div>
                        <p class="don-t-have-yesplis">
                            <span class="span">Already have extroverse account ? </span>
                            <a href="../login/" class="text-wrapper-2" style="color: #9D00E7">Login</a>
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
                            <button class="overlap-group" type="submit" style="background-color: #48006A">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>