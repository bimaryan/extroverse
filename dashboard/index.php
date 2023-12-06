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


    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="http://localhost/extroverse/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../img/extroverse.png" class="h-8" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Extroverse</span>
            </a>
            <div class="flex items-center gap-3 md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <a href="http://localhost/extroverse/search">
                    <div class="flex md:order-2">
                        <div class="relative hidden md:block">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only">Search icon</span>
                            </div>
                            <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
                        </div>
                    </div>
                </a>
                <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full" src="/docs/images/people/profile-picture-3.jpg" alt="user photo">
                </button>
                <!-- Dropdown menu -->
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white"><?php echo $username; ?></span>
                        <span class="block text-sm  text-gray-500 truncate dark:text-gray-400"><?php echo $email; ?></span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Account</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Settings</a>
                        </li>
                        <!-- <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Earnings</a>
                        </li> -->
                        <li>
                            <a href="http://localhost/extroverse/auth/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</a>
                        </li>
                    </ul>
                </div>
                <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-user" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <div class="relative md:hidden mb-2">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
                        </div>
                    </li>
                    <li>
                        <a href="./" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="../event/buat_acara/" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Buat Acara</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Riwayat Pembelian</a>
                    </li>
                    <!-- <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Pricing</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Contact</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="https://flowbite.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Flowbite</span>
            </a>
            <div class="flex md:order-2">
                <button type="button" data-collapse-toggle="navbar-search" aria-controls="navbar-search" aria-expanded="false" class="md:hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 me-1">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
                <div class="relative hidden md:block">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Search icon</span>
                    </div>
                    <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
                </div>
                <button data-collapse-toggle="navbar-search" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-search" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-search">
                <div class="relative mt-3 md:hidden">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
                </div>
                <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <a href="#" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">About</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Services</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav> -->
    <div class="container mx-auto p-3">

        <!-- <div class="flex items-center justify-between">
        <a href="/" class="flex items-center space-x-4">
            <img src="../img/extroverse.png" alt="Logo Aplikasi" class="h-8 w-8" style="width: 123px; height: 100%;">
        </a>

        <div class="w-full m-4">
            <div class="relative">
                <a href="../search"><input type="text" class="w-full border rounded-md pl-8 pr-4 py-2 focus:outline-none focus:border-blue-500" placeholder="Cari event..."></a>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="bi bi-search"></i>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-4 relative">
            <button id="profileButton" class="relative bg-transparent border border-transparent rounded-full cursor-pointer focus:outline-none">
                <i class="bi bi-person-circle text-3xl"></i>
            </button>
            <div id="profilePopup" class="hidden absolute right-0 top-5 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-md z-10">
                <ul class="py-2 px-4">
                    <li><a href="../profil/">Akun</a></li>
                    <li><a href="../event/buat_acara/">Buat Events</a></li>
                    <li><a href="#">Riwayat Pembelian</a></li>
                    <li><a href="../auth/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div> -->

        <?php if ($role === 'pengguna') :
            require_once "../db.php";
        ?>
            <div class="">
                <div class="mb-4 mt-4">
                    <div class="flex overflow-x-auto swiper-wrapper">
                        <?php
                        $query_terbaru = "SELECT * FROM events ORDER BY tanggal DESC LIMIT 8";
                        $result_terbaru = mysqli_query($koneksi, $query_terbaru);
                        while ($row_terbaru = mysqli_fetch_assoc($result_terbaru)) :
                        ?>
                            <div class="swiper-slide mr-4" style='width: 459px; height: auto; margin-right: 15px;'> <!-- Adjust the margin as needed -->
                                <a href="../event/event_detail/?event_id=<?php echo $row_terbaru['event_id']; ?>">
                                    <div class="relative overflow-hidden">
                                        <div style="width: 1226px; height: 239px; justify-content: flex-end; align-items: center; display: inline-flex;">
                                            <div style="width: 1956px; align-self: stretch; position: relative;">
                                                <img src="../event/buat_acara/<?php echo $row_terbaru['cover_foto']; ?>" style="width: 459px; height: 239px; left: 0px; top: 0px; position: absolute; border: 1px solid #111111;" />
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