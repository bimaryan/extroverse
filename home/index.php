<?php
session_start();

// if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "pengguna") {
//     header("Location: ../auth/login/");
//     exit();
// }

// $role = $_SESSION["role"];
// $username = $_SESSION["username"];
// $email = $_SESSION['email'];
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Unbounded">
    <title>Extroverse - Home</title>
</head>

<body class="bg-gray-300">
    <?php
    include "../components/navbar.php";
    ?>
    <div class="container mx-auto p-3">
        <div class="">
            <div class="mb-4 mt-4">
                <div class="flex overflow-x-auto swiper-wrapper">
                    <?php
                    require_once '../db.php';
                    $query_terbaru = "SELECT * FROM events ORDER BY tanggal DESC LIMIT 8";
                    $result_terbaru = mysqli_query($koneksi, $query_terbaru);
                    while ($row_terbaru = mysqli_fetch_assoc($result_terbaru)) :
                    ?>
                        <div class="swiper-slide mr-4" style='width: 459px; height: auto; margin-right: 15px;'> <!-- Adjust the margin as needed -->
                            <a href="../event/event_detail/?event_id=<?php echo $row_terbaru['event_id']; ?>">
                                <div class="relative overflow-hidden">
                                    <div style="width: 1226px; height: 239px; justify-content: flex-end; align-items: center; display: inline-flex;">
                                        <div style="width: 1956px; align-self: stretch; position: relative;">
                                            <img src="<?php echo $row_terbaru['cover_foto']; ?>" style="width: 459px; height: 239px; left: 0px; top: 0px; position: absolute;" />
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
                                        <img src="<?php echo $row_top['cover_foto']; ?>" alt="Cover Event" class="w-full h-full rounded-lg">
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
    <script>
        function hideAllCards() {
            var cards = ['eventCard', 'addCard', 'messageCard', 'customerCard', 'dataCard', 'profile', 'dashboard'];
            cards.forEach(function(cardId) {
                var card = document.getElementById(cardId);
                if (card) {
                    card.style.display = 'none';
                }
            });
        }

        function showCard(cardId) {
            hideAllCards();
            var card = document.getElementById(cardId);
            if (card) {
                card.style.display = 'block';
            }
        }
    </script>
</body>

</html>