<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login/");
    exit();
}

$role = $_SESSION["role"];
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
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

        <?php if ($role === 'pengguna') :
            require_once "../db.php";
        ?>
            <div class="">
                <div class="mb-4 mt-4">
                    <div class="flex overflow-x-auto swiper-wrapper">
                        <?php
                        $query_terbaru = "SELECT * FROM events ORDER BY tanggal DESC LIMIT 10";
                        $result_terbaru = mysqli_query($koneksi, $query_terbaru);
                        while ($row_terbaru = mysqli_fetch_assoc($result_terbaru)) :
                        ?>
                            <div class="swiper-slide mr-4" style='width: 459px; height: auto;'>
                                <a href="../event/event_detail/?event_id=<?php echo $row_terbaru['event_id']; ?>">
                                    <div class="relative overflow-hidden">
                                        <div style="width: 1226px; height: 239px; justify-content: flex-end; align-items: center; display: inline-flex">
                                            <div style="width: 1956px; align-self: stretch; position: relative">
                                                <img src="../event/buat_acara/<?php echo $row_terbaru['cover_foto']; ?>" style="width: 459px; height: 239px; left: 0px; top: 0px; position: absolute object-cover" />
                                            </div>
                                        </div>
                                        <!-- <div class="w-full overflow-hidden mb-5">
                                            <img src="../event/buat_acara/<?php echo $row_terbaru['cover_foto']; ?>" alt="Cover Event" class="object-cover">
                                        </div> -->
                                    </div>
                                </a>
                            </div>
                        <?php
                        endwhile;
                        ?>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="text-xl font-semibold mb-2">Event Terpopuler</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 mb-4">
                        <?php
                        $query_top = "SELECT * FROM events ORDER BY jumlah_tiket_terjual DESC";
                        $result_top = mysqli_query($koneksi, $query_top);
                        $total_events = mysqli_num_rows($result_top);
                        $events_per_page = 2; // Jumlah event per halaman
                        $total_pages = ceil($total_events / $events_per_page);

                        if (isset($_GET['page'])) {
                            $current_page = $_GET['page'];
                        } else {
                            $current_page = 1;
                        }

                        $offset = ($current_page - 1) * $events_per_page;
                        $query_top_page = "SELECT * FROM events ORDER BY jumlah_tiket_terjual DESC LIMIT $events_per_page OFFSET $offset";
                        $result_top_page = mysqli_query($koneksi, $query_top_page);

                        while ($row_top = mysqli_fetch_assoc($result_top_page)) :
                        ?>
                            <div class="w-full bg-white border rounded-lg p-4">
                                <a href="../event/event_detail/?event_id=<?php echo $row_top['event_id']; ?>">
                                    <div class="relative">
                                        <div class="w-full h-64 overflow-hidden mb-5">
                                            <img src="../event/buat_acara/<?php echo $row_top['cover_foto']; ?>" alt="Cover Event" class="w-full h-full object-cover rounded-lg">
                                        </div>
                                        <p class="mb-2 absolute top-2 right-2 bg-white p-2 rounded font-semibold text-lg"><?php echo $row_top['tanggal']; ?></p>
                                    </div>
                                    <h4 class="text-lg font-semibold mb-2"><?php echo $row_top['nama_acara']; ?></h4>
                                    <p class="text-sm mb-2 text-gray-500 mt-6 mb-4"><?php echo nl2br($row_top['lokasi']); ?></p>
                                    <p class="text-sm mb-2 text-gray-500">Tiket Terjual: <?php echo $row_top['jumlah_tiket_terjual']; ?></p>
                                    <hr />
                                    <div class="flex justify-between items-center mt-5 text-gray-500">
                                        <p class="mb-2">Start From</p>
                                        <p class="mb-2 text-yellow-500 font-semibold">Rp <?php echo number_format($row_top['harga'], 2); ?></p>
                                    </div>
                                    <!-- Tambahkan detail lainnya sesuai kebutuhan -->
                                </a>
                            </div>
                        <?php
                        endwhile;
                        ?>
                    </div>
                    <div class="text-center">
                        <?php
                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo '<a class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-700" href="?page=' . $i . '">' . $i . '</a>';
                        }
                        ?>
                    </div>
                </div>

                <!-- <div>
                    <h3 class="text-xl font-semibold mb-2">Event Terdekat</h3>
                    <div class="flex overflow-x-auto">
                        <?php
                        $query_terdekat = "SELECT * FROM events WHERE tanggal >= CURDATE() ORDER BY tanggal LIMIT 5";
                        $result_terdekat = mysqli_query($koneksi, $query_terdekat);
                        while ($row_terdekat = mysqli_fetch_assoc($result_terdekat)) :
                        ?>
                            <div class="w-full bg-white border rounded-lg p-4 mr-4">
                                <h4 class="text-lg font-semibold mb-2"><?php echo $row_terdekat['nama_acara']; ?></h4>
                                <p class="text-sm mb-2">Tanggal: <?php echo $row_terdekat['tanggal']; ?></p>
                            </div>
                        <?php
                        endwhile;
                        ?>
                    </div>
                </div> -->
            </div>
        <?php endif; ?>
        <?php if ($role === 'admin');
        require "../db.php";
        ?>
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
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