<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validasi data
    if (empty($email) || empty($password)) {
        echo "Isi semua field.";
    } else {
        // Koneksi ke database (gunakan file koneksi Anda)
        require_once "../../db.php";

        // Sanitasi data
        $email = mysqli_real_escape_string($koneksi, $email);

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($koneksi, $sql);

        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                if (password_verify($password, $row['password'])) {
                    // Login berhasil
                    $_SESSION["user_id"] = $row["user_id"];
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["role"] = $row["role"];
                    $_SESSION['email'] = $email;

                    header("Location: ../../dashboard/");
                    exit();
                } else {
                    echo "Kata sandi salah.";
                }
            } else {
                echo "Email tidak ditemukan.";
            }
        } else {
            echo "Terjadi kesalahan dalam pengolahan permintaan.";
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
    <!-- <link href="styles.css" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
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
            height: 355px;
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
            color: #9D00E7;
            text-decoration: none;
        }

        .login-HP .email {
            position: absolute;
            width: 311px;
            height: 61px;
            top: 109px;
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
            top: 184px;
            left: 34px;
        }

        .login-HP .submit {
            position: absolute;
            width: 311px;
            height: 41px;
            top: 279px;
            left: 34px;
        }

        .login-HP .overlap-group {
            position: relative;
            width: 309px;
            height: 41px;
            background-color: #ffffff;
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
                        <div class="text-wrapper">Login</div>
                        <p class="don-t-have-yesplis">
                            <span class="span">Don’t have extroverse account ? </span>
                            <a href="../register/" class="text-wrapper-2" style="color: #9D00E7">Sign Up</a>
                        </p>
                        <div class="email">
                            <label for="email" class="text-wrapper-3">Email Address:</label>
                            <input class="img form-control" type="email" id="email" name="email" required />
                        </div>
                        <div class="password">
                            <label for="password" class="text-wrapper-3">Password:</label>
                            <input class="img form-control" type="password" id="password" name="password" required />
                        </div>
                        <div class="submit">
                            <button class="overlap-group" type="submit" style="background-color: #48006A">
                                <div class="submit-2">Login</div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>