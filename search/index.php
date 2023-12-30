<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login/");
    exit();
}
$role = $_SESSION["role"];
$username = $_SESSION["username"];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://kit.fontawesome.com/f74deb4653.js" crossorigin="anonymous"></script>
    <title>Extroverse</title>
</head>

<body class="bg-gray-100">
    <?php
    include "../components/navbar.php";
    ?>

    <div class="container mx-auto p-3">
        <div class="mt-2 mb-4">
            <a href="http://localhost/extroverse/" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"><i class="bi bi-arrow-left-circle"></i> Back</a>
        </div>
        <div class="mb-4">
            <?php

            require_once "../db.php";
            // Logika Pencarian
            if (isset($_GET['Mencari'])) {
                $Mencari = mysqli_real_escape_string($koneksi, $_GET['Mencari']);
                $search_query = "SELECT * FROM events WHERE nama_acara LIKE '%$Mencari%' OR deskripsi LIKE '%$Mencari%'";
                $result = mysqli_query($koneksi, $search_query);

                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">';

                        while ($row = mysqli_fetch_assoc($result)) {
                            // Tampilkan hasil pencarian di sini
                            echo '<div class="w-full bg-white border rounded-lg p-4">';
                            echo '<a href="../event/event_detail/?event_id=' . $row['event_id'] . '">';
                            echo '<div class="relative">';
                            echo '<div class="w-full h-64 overflow-hidden mb-5">';
                            echo '<img src="../event/buat_acara/' . $row['cover_foto'] . '" alt="Cover Event" class="w-full h-full object-cover rounded-lg">';
                            echo '</div>';
                            echo '<p class="mb-2 absolute top-2 right-2 bg-white p-2 rounded font-semibold text-lg">' . $row['tanggal'] . '</p>';
                            echo '</div>';
                            echo '<h4 class="text-lg font-semibold mb-2">' . $row['nama_acara'] . '</h4>';
                            echo '<p class="text-sm mb-2 text-gray-500 mt-6 mb-4">' . nl2br($row['lokasi']) . '</p>';
                            echo '<p class="text-sm mb-2 text-gray-500">Tiket Terjual: ' . $row['jumlah_tiket_terjual'] . '</p>';
                            echo '<hr />';
                            echo '<div class="flex justify-between items-center mt-5 text-gray-500">';
                            echo '<p class="mb-2">Start From</p>';
                            echo '<p class="mb-2 text-yellow-500 font-semibold">Rp ' . number_format($row['harga'], 2) . '</p>';
                            echo '</div>';
                            echo '</a>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<p class="text-lg font-semibold text-red-500">Tidak ditemukan hasil pencarian untuk: ' . $Mencari . '</p>';
                    }
                } else {
                    echo 'Parameter query tidak di temukan';
                }
            }
            ?>
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