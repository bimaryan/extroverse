<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login/");
    exit();
}

$role = $_SESSION["role"];
$username = $_SESSION["username"];
$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../db.php"; // Ganti dengan path yang sesuai ke file koneksi database Anda.

    $user_id = $_SESSION["user_id"];
    $username = $_POST["username"];
    $email = $_POST["email"];

    // Validasi dan lakukan update profil ke database di sini.
    // Pastikan untuk memvalidasi input, misalnya, memeriksa apakah tanggal lahir sesuai format atau nomor telepon valid.
    if (empty($username)) {
        header("Location: ../profil/?error=empty_fields");
        exit();
    }

    // Menerima unggahan gambar profil
    $profile_image = $_FILES['profile_image']['name'];
    $profile_image_tmp = $_FILES['profile_image']['tmp_name'];

    // Pindahkan gambar ke folder penyimpanan (misalnya, folder "uploads")
    $target_dir = "img/";
    $target_file = $target_dir . basename($profile_image);

    // Periksa apakah file gambar berhasil diunggah
    if (!empty($profile_image)) {
        if (move_uploaded_file($profile_image_tmp, $target_file)) {
            // File berhasil diunggah, maka Anda bisa menyimpan nama file ini di database
            $update_query = "UPDATE users SET username = ?, email = ?, profile_image = ? WHERE user_id = ?";
            $stmt = mysqli_prepare($koneksi, $update_query);
            mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $profile_image, $user_id);

            if (mysqli_stmt_execute($stmt)) {
                // Pembaruan berhasil dilakukan.
                header("Location: ../profil/?success=true");
                exit();
            } else {
                // Pembaruan gagal, Anda bisa menangani kesalahan sesuai kebutuhan.
                header("Location: ../profil/?error=update_failed");
                exit();
            }

            mysqli_stmt_close($stmt);
        } else {
            // File gagal diunggah, Anda bisa menangani kesalahan sesuai kebutuhan.
            header("Location: ../profil/?error=upload_failed");
            exit();
        }
    } else {
        // File gambar kosong, lanjutkan dengan pembaruan data profil tanpa mengubah gambar
        $update_query = "UPDATE users SET username = ?, email = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($koneksi, $update_query);
        mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            // Pembaruan berhasil dilakukan.
            header("Location: ../profil/?success=true");
            exit();
        } else {
            // Pembaruan gagal, Anda bisa menangani kesalahan sesuai kebutuhan.
            header("Location: ../profil/?error=update_failed");
            exit();
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($koneksi);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Ticketin Dong</title>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-3">
        <div class="flex items-center justify-between">
            <!-- Bagian Logo dan Nama Aplikasi -->
            <div class="flex items-center space-x-4">
                <img src="../img/extroverse.png" alt="Logo Aplikasi" class="h-8 w-8" style="width: 123px; height: 100%;">
            </div>

            <!-- Bagian Pencarian -->
            <div class="w-full m-4">
                <div class="relative">
                    <input type="text" class="w-full border rounded-md pl-8 pr-4 py-2 focus:outline-none focus:border-blue-500" placeholder="Cari event...">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>

            <!-- Bagian Profil Pengguna -->
            <div class="flex items-center space-x-4 relative">
                <button id="profileButton" class="relative bg-transparent border border-transparent rounded-full cursor-pointer focus:outline-none">
                    <i class="bi bi-person-circle text-3xl"></i>
                </button>
                <div id="profilePopup" class="hidden absolute right-0 top-5 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-md z-10">
                    <ul class="py-2 px-4">
                        <li><a href="../profil/">Akun</a></li>
                        <li><a href="../event/">Buat Events</a></li>
                        <li><a href="#">Riwayat Pembelian</a></li>
                        <li><a href="../auth/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Konten Profil Pengguna -->
        <div class="bg-white p-6 rounded-lg shadow-lg mt-4">
            <h2 class="text-2xl font-semibold mb-4 text-center">Profile Data</h2>
            <hr />
            <form class="mt-4" action="" method="POST">
                <!-- <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Current Profile Picture</label>
                    <img src="../profil/img/ <?php echo $target_file; ?>" alt="Profile Picture" class="w-32 h-32 rounded-full">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="profile_image">Profile Picture</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="profile_image" type="file" name="profile_image">
                </div> -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email:</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" id="email" name="email" value="<?php echo $email; ?>" readonly><br>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" name="username" value="<?php echo $username; ?>" readonly>
                </div>
                <!-- <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="dob">Date of Birth</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="dob" type="date" name="dob" value="<?php echo $dob; ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="gender">Gender</label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="gender" name="gender">
                        <option value="male" <?php echo (isset($gender) && $gender === 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo (isset($gender) && $gender === 'female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="job">Job</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="job" type="text" name="job" value="<?php echo $_SESSION['job']; ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">Phone Number</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="phone" type="tel" name="phone" value="<?php echo $_SESSION['phone']; ?>">
                </div> -->
                <!-- Tambahkan bidang lainnya sesuai kebutuhan -->
                <!-- <div class="mb-4">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-full" type="submit">Submit</button>
                </div> -->
            </form>
        </div>
    </div>

    <script>
        const profileButton = document.getElementById("profileButton");
        const profilePopup = document.getElementById("profilePopup");

        profileButton.addEventListener("click", () => {
            profilePopup.classList.toggle("hidden");
        });

        // Sembunyikan pop-up saat mengklik di luar pop-up
        window.addEventListener("click", (e) => {
            if (!profileButton.contains(e.target) && !profilePopup.contains(e.target)) {
                profilePopup.classList.add("hidden");
            }
        });
    </script>
</body>

</html>