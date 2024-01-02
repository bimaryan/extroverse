<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "penjual") {
    header("Location: http://localhost/extroverse/auth/login");
    exit();
}

require_once "../db.php";

$user_id = $_SESSION['user_id'];
$role = $_SESSION["role"];
$username = $_SESSION["username"];
$email = $_SESSION['email'];

$query_logo = mysqli_query($koneksi, "SELECT profile_image FROM users WHERE user_id = '$user_id'");

$data_user = mysqli_fetch_assoc($query_logo);

// Fungsi untuk mendapatkan total uang masuk dari database
function getTotalIncome($koneksi, $user_id) {
    $query_income = mysqli_query($koneksi, "SELECT SUM(total_uang_masuk) AS total_income FROM events WHERE user_id = '$user_id'");
    $data_income = mysqli_fetch_assoc($query_income);
    return $data_income['total_income'];
}

// Fungsi untuk mendapatkan total jumlah tiket terjual dari database
function getTotalTicketsSold($koneksi, $user_id) {
    $query_tickets = mysqli_query($koneksi, "SELECT SUM(jumlah_tiket_terjual) AS total_tickets_sold FROM events WHERE user_id = '$user_id'");
    $data_tickets = mysqli_fetch_assoc($query_tickets);
    return $data_tickets['total_tickets_sold'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user inputs
    $nama_acara = mysqli_real_escape_string($koneksi, $_POST["nama_acara"]);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST["deskripsi"]);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST["tanggal"]);
    $harga = intval($_POST["harga"]);
    $jumlah_tiket = intval($_POST["jumlah_tiket"]);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST["lokasi"]);
    $jumlah_tiket_terjual = 0;
    $tiket_type = mysqli_real_escape_string($koneksi, $_POST["tiket_type"]);
    $order_id = rand();

    $user_id = $_SESSION["user_id"];

    // File upload handling
    $target_directory = "../img/";
    $target_file = $target_directory . basename($_FILES["cover_foto"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if file is a valid image
    $check = getimagesize($_FILES["cover_foto"]["tmp_name"]);
    if ($check === false) {
        $uploadOk = 0;
        echo "Maaf, file tersebut bukan gambar.";
    }

    // Check file size
    if ($_FILES["cover_foto"]["size"] > 5000000) {
        $uploadOk = 0;
        echo "Maaf, ukuran file terlalu besar.";
    }

    // Allow only certain file formats
    $allowed_formats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_formats)) {
        $uploadOk = 0;
        echo "Maaf, hanya file JPG, JPEG, PNG, dan GIF yang diizinkan.";
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Maaf, file Anda tidak dapat diunggah.";
    } else {
        // Move the file to the target directory
        if (move_uploaded_file($_FILES["cover_foto"]["tmp_name"], $target_file)) {
            $query = "INSERT INTO events (user_id, nama_acara, deskripsi, tanggal, harga, jumlah_tiket, lokasi, jumlah_tiket_terjual, cover_foto, tiket_type, order_id)
                VALUES ('$user_id', '$nama_acara', '$deskripsi', '$tanggal', $harga, $jumlah_tiket, '$lokasi', $jumlah_tiket_terjual, '$target_file', '$tiket_type', '$order_id')";

            if (mysqli_query($koneksi, $query)) {
                header("Location: ../../midtrans/examples/snap/checkout-process-simple-version.php?order_id=$order_id");
                exit();
            } else {
                echo "Terjadi kesalahan saat membuat acara: " . mysqli_error($koneksi);
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }
    
    $totalIncome = getTotalIncome($koneksi, $user_id);
    $totalTicketsSold = getTotalTicketsSold($koneksi, $user_id);

    mysqli_close($koneksi);
}
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
    <title>Extroverse - Penjual</title>
</head>

<body style="background: #CECECE;">
    <nav
        class="fixed top-0 z-50 w-full h-20 bg-purple-900 shadow border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <a href="./" class="flex ms-2 md:me-24">
                        <img src="http://localhost/extroverse/logo/pt.png" class="h-8 me-3" alt="Extroverse"
                            style="width: 50px; height: max-content;" />
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white"
                            style="color: white;">Extroverse</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button"
                                class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-full" src="<?php echo $data_user['profile_image'] ?>"
                                    alt="user photo">
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                            id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900 dark:text-white" role="none">
                                    <?php echo $username; ?>
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                    <?php echo $email; ?>
                                </p>
                            </div>
                            <hr class="bg-gray-200 border-1 dark:bg-gray-700" />
                            <ul class="py-1" role="none">
                                <li>
                                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer"
                                        role="menuitem" onclick="showCard('profile')">Account</a>
                                </li>
                                <li>
                                    <a href="../daftar_distributor/"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Register Distributor</a>
                                </li>
                                <li>
                                    <a href="http://localhost/extroverse/auth/logout.php"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Sign out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
        aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('dashboard')">
                        <span class="ms-3"><i class="bi bi-house-door"></i> Dashboard</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('addCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-plus-square"></i> Tambah
                            Acara</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('messageCard')">

                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-chat-dots"></i> Pesan</span>
                        <span
                            class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('customerCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-people"></i> Pelanggan</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('eventCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-calendar4-event"></i> Acara</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('dataCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-graph-up"></i> Rekapan Data</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <div class="p-4 sm:ml-64">
        <div class="rounded-lg mt-14">
            <div class="bg-white rounded-lg shadow p-6 mt-4" id="dashboard">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Dashboard</h2>
                <div class="text-center grid grid-cols-2 gap-4">
                    <div class="p-4 rounded bg-purple-900 font-bold dark:bg-gray-800" style="color: white;">
                        <p>Total Uang Masuk: <?php echo $totalIncome; ?></p>
                    </div>
                    <div class="p-4 rounded bg-purple-900 font-bold dark:bg-gray-800" style="color: white;">
                        <p>Jumlah Tiket Terjual: <?php echo $totalTicketsSold; ?></p>
                    </div>
                </div>


            </div>
            <div style="display: none;" id="addCard" class="bg-white rounded-lg shadow p-6 mt-4">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Tambahkan Acara</h2>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="cover_foto">Cover Foto
                            Event</label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="cover_foto" type="file" name="cover_foto" accept="image/*" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_acara">Nama Acara</label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="nama_acara" type="text" name="nama_acara" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="deskripsi">Deskripsi</label>
                        <textarea
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="deskripsi" name="deskripsi" style="resize: none;" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal">Tanggal</label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="tanggal" type="date" name="tanggal" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="harga">Harga</label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="harga" type="number" name="harga" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tiket_type">Tiket Type</label>
                        <select
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="tiket_type" name="tiket_type" required>
                            <option value="Presale 1">Presale 1</option>
                            <option value="Presale 2">Presale 2</option>
                            <option value="Presale 3">Presale 3</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="jumlah_tiket">Jumlah
                            Tiket</label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="jumlah_tiket" type="number" name="jumlah_tiket" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="lokasi">Lokasi</label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="lokasi" type="text" name="lokasi" required>
                    </div>
                    <div class="mb-4">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-full"
                            type="submit">Buat Acara</button>
                    </div>
                </form>
            </div>
            <div style="display: none;" id="messageCard" class="bg-white rounded-lg shadow p-6 mt-4 mb-4">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Pesan</h2>
                COMING SOON
            </div>
            <div style="display: none;" id="customerCard" class="bg-white rounded-lg shadow p-6 mt-4 mb-4">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Pelanggan</h2>
                COMING SOON
            </div>
            <div style="display: none;" id="eventCard" class="bg-white rounded-lg shadow p-6 mt-4 mb-4">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">List Daftar Acara</h2>
                <div class="relative overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="text-center">
                                        Cover Foto
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="text-center">
                                        Nama Acara
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="text-center">
                                        Deskripsi
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="text-center">
                                        Tanggal
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="text-center">
                                        Harga
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="text-center">
                                        Jumlah Tiket
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="text-center">
                                        Lokasi
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="text-center">
                                        Tiket Terjual
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="text-center">
                                        Tiket Type
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_event = mysqli_query($koneksi, "SELECT * FROM events WHERE user_id = '$user_id' ");
                            $events = mysqli_fetch_all($query_event, MYSQLI_ASSOC);
                            if (isset($events) && (is_array($events) || is_object($events))) {
                                foreach ($events as $event) {
                                    echo "<tr scope='col' class=''>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap'><img style='display: flex; justify-content: center; align-items: center; width: 50px; height: 50px; margin: auto' src='http://localhost/extroverse/img/{$event['cover_foto']}' alt='Cover Event' class='rounded-lg'></td>";
                                    echo "<td class='text-center px-6 py-4 whitespace-nowrap' style=''>{$event['nama_acara']}</td>";
                                    echo "<td class='text-center px-6 py-4 whitespace-nowrap' style=''>{$event['deskripsi']}</td>";
                                    echo "<td class='text-center px-6 py-4 whitespace-nowrap' style=''>{$event['tanggal']}</td>";
                                    echo "<td class='text-center px-6 py-4 whitespace-nowrap' style=''>{$event['harga']}</td>";
                                    echo "<td class='text-center px-6 py-4 whitespace-nowrap' style=''>{$event['jumlah_tiket']}</td>";
                                    echo "<td class='text-center px-6 py-4 whitespace-nowrap' style=''>{$event['lokasi']}</td>";
                                    echo "<td class='text-center px-6 py-4 whitespace-nowrap' style=''>{$event['jumlah_tiket_terjual']}</td>";
                                    echo "<td class='text-center px-6 py-4 whitespace-nowrap' style=''>{$event['tiket_type']}</td>";
                                    echo "<td class='text-center px-6 py-4 whitespace-nowrap' style=''><a data-modal-target='default-modal' data-modal-toggle='default-modal' class='text-blue-500 font-semibold py-1 px-2 rounded-full'>Edit</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='11' style='padding: 10px;'>No events available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Main modal -->
                <div id="default-modal" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Edit Event
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="default-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-4 md:p-5 space-y-4">
                                <form method="POST" action="edit_event.php" enctype="multipart/form-data">
                                    <input type="hidden" id="event-id" name="event_id">
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="cover_foto">Cover
                                            Foto Event</label>
                                        <input
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            id="cover_foto" type="file" name="cover_foto" accept="image/*" required>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_acara">Nama
                                            Acara</label>
                                        <input
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            id="nama_acara" type="text" name="nama_acara" required>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2"
                                            for="deskripsi">Deskripsi</label>
                                        <textarea
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            id="deskripsi" name="deskripsi" style="resize: none;" required></textarea>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2"
                                            for="tanggal">Tanggal</label>
                                        <input
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            id="tanggal" type="date" name="tanggal" required>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2"
                                            for="harga">Harga</label>
                                        <input
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            id="harga" type="number" name="harga" required>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tiket_type">Tiket
                                            Type</label>
                                        <select
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            id="tiket_type" name="tiket_type" required>
                                            <option value="Presale 1">Presale 1</option>
                                            <option value="Presale 2">Presale 2</option>
                                            <option value="Presale 3">Presale 3</option>
                                        </select>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2"
                                            for="jumlah_tiket">Jumlah Tiket</label>
                                        <input
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            id="jumlah_tiket" type="number" name="jumlah_tiket" required>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2"
                                            for="lokasi">Lokasi</label>
                                        <input
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            id="lokasi" type="text" name="lokasi" required>
                                    </div>
                                    <!-- Modal footer -->
                                    <div
                                        class="flex items-center border-t border-gray-200 rounded-b dark:border-gray-600">
                                        <button data-modal-hide="default-modal"
                                            class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            type="submit">Simpan</button>
                                        <button data-modal-hide="default-modal" type="button"
                                            class="mt-4 ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: none;" id="dataCard" class="bg-white rounded-lg shadow p-6 mt-4 mb-4">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Rekapan Data</h2>
                COMING SOON
            </div>
            <div class="bg-white rounded-lg shadow p-6 mt-4" style="display: none;" id="profile">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Profil Penjual</h2>
                <div class="flex items-center">
                    <img class="w-16 h-16 rounded-full" src="<?php echo $data_user['profile_image'] ?>"
                        alt="user photo">
                    <div class="ms-4">
                        <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo $username; ?></p>
                        <p class="text-sm text-gray-500 dark:text-gray-300"><?php echo $email; ?></p>
                        <p class="text-sm text-gray-500 dark:text-gray-300">Role: <?php echo $role; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.getElementById('cover_photo').addEventListener('change', function() {
        const fileInput = this;
        const maxSizeMB = 5;
        const maxSizeBytes = maxSizeMB * 1024 * 1024;

        if (fileInput.files.length > 0) {
            const fileSizeBytes = fileInput.files[0].size;

            if (fileSizeBytes > maxSizeBytes) {
                alert('File size exceeds the maximum allowed size (5MB). Please choose a smaller file.');
                fileInput.value = '';
            }
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