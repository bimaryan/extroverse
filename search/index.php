<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <title>Extroverse</title>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-3">
        <div class="flex items-center justify-between">
            <!-- Bagian Logo dan Nama Aplikasi -->
            <a href="/" class="flex items-center space-x-4">
                <img src="../img/extroverse.png" alt="Logo Aplikasi" class="h-8 w-8" style="width: 123px; height: 100%;">
            </a>

            <!-- Bagian Pencarian -->
            <div class="w-full m-4">
                <div class="relative">
                    <form action="" method="GET">
                        <input type="text" class="w-full border rounded-md pl-8 pr-4 py-2 focus:outline-none focus:border-blue-500" id="search" name="Mencari" placeholder="Cari event..." required>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="bi bi-search"></i>
                        </div>
                    </form>
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
    </div>

    <div class="container mx-auto p-3">
        <div class="mb-4">
            <?php
            session_start();

            if (!isset($_SESSION["user_id"])) {
                header("Location: ../auth/login/");
                exit();
            }

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