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
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
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
                        <div class="text-wrapper">Login</div>
                        <p class="don-t-have-yesplis">
                            <span class="span">Don’t have extroverse account ? </span>
                            <a href="../register/" class="text-wrapper-2">Sign Up</a>
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
                            <button class="overlap-group" type="submit">
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