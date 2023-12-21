<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: http://localhost/extroverse/auth/login");
    exit();
}
// Pastikan Anda sudah membuat koneksi ke database
require_once "../db.php";

$user_id = $_SESSION['user_id'];
$role = $_SESSION["role"];
$username = $_SESSION["username"];
$email = $_SESSION['email'];

$query_logo = mysqli_query($koneksi, "SELECT profile_image FROM users WHERE user_id = '$user_id'");

$data_user = mysqli_fetch_assoc($query_logo);

// Retrieve event details from the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $nama_acara = $_POST["nama_acara"];
    $deskripsi = $_POST["deskripsi"];
    $tanggal = $_POST["tanggal"];
    $harga = $_POST["harga"];
    $jumlah_tiket = $_POST["jumlah_tiket"];
    $lokasi = $_POST["lokasi"];
    $jumlah_tiket_terjual = 0; // Jumlah tiket terjual awalnya 0
    $tiket_type = $_POST["tiket_type"];

    $user_id = $_SESSION["user_id"];

    // Proses unggahan cover foto
    $target_directory = "../img/"; // Direktori tempat Anda ingin menyimpan file
    $target_file = $target_directory . basename($_FILES["cover_foto"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file gambar atau bukan
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["cover_foto"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }

    // Cek jika file sudah ada
    if (file_exists($target_file)) {
        echo "Maaf, file tersebut sudah ada.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["cover_foto"]["size"] > 5000000) { // Batas ukuran file dalam byte (contoh: 5MB)
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Izinkan hanya format file gambar tertentu
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Maaf, hanya file JPG, JPEG, PNG, dan GIF yang diizinkan.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Maaf, file Anda tidak dapat diunggah.";
    } else {
        if (move_uploaded_file($_FILES["cover_foto"]["tmp_name"], $target_file)) {
            // File gambar berhasil diunggah, Anda dapat menyimpan nama file di database
            // Insert data ke tabel events
            $query = "INSERT INTO events (user_id, nama_acara, deskripsi, tanggal, harga, jumlah_tiket, lokasi, jumlah_tiket_terjual, cover_foto, tiket_type)
VALUES ('$user_id', '$nama_acara', '$deskripsi', '$tanggal', $harga, $jumlah_tiket, '$lokasi', $jumlah_tiket_terjual, '$target_file', '$tiket_type')";

            if (mysqli_query($koneksi, $query)) {
                // Event berhasil ditambahkan, Anda dapat mengarahkan pengguna ke halaman lain atau memberikan pesan sukses
                header("Location: ./"); // Ganti dengan halaman tujuan setelah event berhasil dibuat
                exit();
            } else {
                // Terjadi kesalahan, Anda dapat menampilkan pesan kesalahan atau mengarahkan pengguna kembali ke halaman buat acara
                echo "Terjadi kesalahan saat membuat acara: " . mysqli_error($koneksi);
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }

    // Tutup koneksi ke database jika sudah tidak digunakan
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
    <title>Extroverse - Penjual</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .event-form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        .submit-button {
            background-color: #007bff;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="./" class="flex ms-2 md:me-24">
                        <img src="https://6abf-180-241-241-47.ngrok-free.app/extroverse/logo/extroverse.png" class="h-8 me-3" alt="Extroverse" />
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Extroverse</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-full" src="<?php echo $data_user['profile_image'] ?>" alt="user photo">
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900 dark:text-white" role="none">
                                    <?php echo $username; ?>
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                    <?php echo $email; ?>
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Akun</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Pengaturan</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Daftar Distributor</a>
                                </li>
                                <li>
                                    <a href="http://localhost/extroverse/auth/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <span class="ms-3"><i class="bi bi-house-door"></i> Dashboard</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('addCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-plus-square"></i> Tambah Acara</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('messageCard')">

                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-chat-dots"></i> Pesan</span>
                        <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('customerCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-people"></i> Pelanggan</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('eventCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-calendar4-event"></i> Acara</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('dataCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-graph-up"></i> Rekapan Data</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">

                        <span class="flex-1 ms-3 whitespace-nowrap">Sign Up</span>
                    </a>
                </li> -->
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="rounded-lg mt-14">
            <div style="display: none;" id="addCard" class="bg-white rounded-lg shadow p-6 mt-4">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Tambahkan Acara</h2>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="cover_foto">Cover Foto Event</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cover_foto" type="file" name="cover_foto" accept="image/*" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_acara">Nama Acara</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama_acara" type="text" name="nama_acara" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="deskripsi">Deskripsi</label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deskripsi" name="deskripsi" style="resize: none;" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal">Tanggal</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tanggal" type="date" name="tanggal" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="harga">Harga</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="harga" type="number" name="harga" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tiket_type">Tiket Type</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tiket_type" name="tiket_type" required>
                            <option value="Presale 1">Presale 1</option>
                            <option value="Presale 2">Presale 2</option>
                            <option value="Presale 3">Presale 3</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="jumlah_tiket">Jumlah Tiket</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="jumlah_tiket" type="number" name="jumlah_tiket" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="lokasi">Lokasi</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="lokasi" type="text" name="lokasi" required>
                    </div>
                    <div class="mb-4">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-full" type="submit">Buat Acara</button>
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

            <div id="eventCard" class="bg-white rounded-lg shadow p-6 mt-4 mb-4">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">List Daftar Acara</h2>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="border px-6 py-3">
                                    <div class="text-center">
                                        Cover Foto
                                    </div>
                                </th>
                                <th scope="col" class="border px-6 py-3">
                                    <div class="text-center">
                                        Nama Acara
                                    </div>
                                </th>
                                <th scope="col" class="border px-6 py-3">
                                    <div class="text-center">
                                        Deskripsi
                                    </div>
                                </th>
                                <th scope="col" class="border px-6 py-3">
                                    <div class="text-center">
                                        Tanggal
                                    </div>
                                </th>
                                <th scope="col" class="border px-6 py-3">
                                    <div class="text-center">
                                        Harga
                                    </div>
                                </th>
                                <th scope="col" class="border px-6 py-3">
                                    <div class="text-center">
                                        Jumlah Tiket
                                    </div>
                                </th>
                                <th scope="col" class="border px-6 py-3">
                                    <div class="text-center">
                                        Lokasi
                                    </div>
                                </th>
                                <th scope="col" class="border px-6 py-3">
                                    <div class="text-center">
                                        Tiket Terjual
                                    </div>
                                </th>
                                <th scope="col" class="border px-6 py-3">
                                    <div class="text-center">
                                        Tiket Type
                                    </div>
                                </th>
                                <th scope="col" class="border px-6 py-3">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_event = mysqli_query($koneksi, "SELECT * FROM events WHERE user_id = '$user_id' ");
                            $events = mysqli_fetch_all($query_event, MYSQLI_ASSOC);
                            // Check if $events is set and is an array or object
                            if (isset($events) && (is_array($events) || is_object($events))) {
                                foreach ($events as $event) {
                                    echo "<tr scope='col' class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>";
                                    echo "<td class='border'><img style='display: flex; justify-content: center; align-items: center; width: 50px; height: 50px; margin: auto' src='http://localhost/extroverse/img/{$event['cover_foto']}' alt='Cover Event' class='rounded-lg'></td>";
                                    echo "<td class='border text-center' style=''>{$event['nama_acara']}</td>";
                                    echo "<td class='border text-center' style=''>{$event['deskripsi']}</td>";
                                    echo "<td class='border text-center' style=''>{$event['tanggal']}</td>";
                                    echo "<td class='border text-center' style=''>{$event['harga']}</td>";
                                    echo "<td class='border text-center' style=''>{$event['jumlah_tiket']}</td>";
                                    echo "<td class='border text-center' style=''>{$event['lokasi']}</td>";
                                    echo "<td class='border text-center' style=''>{$event['jumlah_tiket_terjual']}</td>";
                                    echo "<td class='border text-center' style=''>{$event['tiket_type']}</td>";
                                    echo "<td class='border text-center' style=''><a data-modal-target='default-modal' data-modal-toggle='default-modal' class='bg-blue-500 hover:bg-blue-700 text-white font-semibold py-1 px-2 rounded-full' onclick='openEditModal(<?php echo $event_details('event_id');)'>Edit</a></td>";
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
                <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <?php
                    // Check if the event_id is set and is a valid positive integer
                    if (isset($_GET['event_id']) && is_numeric($_GET['event_id']) && $_GET['event_id'] > 0) {
                        $event_id = $_GET['event_id'];

                        // Assuming you have a database connection established
                        require_once "../db.php";

                        // Fetch event details from the database
                        $query_event_details = "SELECT * FROM events WHERE event_id = $event_id";
                        $result_event_details = mysqli_query($koneksi, $query_event_details);

                        // Check for errors in the query
                        if (!$result_event_details) {
                            die('Error in query: ' . mysqli_error($koneksi));
                        }

                        // Check if the event was found
                        if (mysqli_num_rows($result_event_details) > 0) {
                            $event_details = mysqli_fetch_assoc($result_event_details);
                            // Now $event_details contains the details of the event
                        } else {
                            echo "Event not found.";
                        }

                        // Close the database connection
                        mysqli_close($koneksi);
                    } else {
                        echo "Invalid event ID.";
                    }
                    ?>
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Edit Event
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-4 md:p-5 space-y-4">
                                <form method="POST" action="edit_event.php" enctype="multipart/form-data">
                                    <input type="hidden" id="event-id" name="event_id">
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="cover_foto">Cover Foto Event</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cover_foto" type="file" name="cover_foto" accept="image/*" required>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_acara">Nama Acara</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama_acara" type="text" name="nama_acara" required value="<?php echo $event_details['nama_acara']; ?>">
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="deskripsi">Deskripsi</label>
                                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deskripsi" name="deskripsi" style="resize: none;" required></textarea>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal">Tanggal</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tanggal" type="date" name="tanggal" required>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="harga">Harga</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="harga" type="number" name="harga" required>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tiket_type">Tiket Type</label>
                                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tiket_type" name="tiket_type" required>
                                            <option value="Presale 1">Presale 1</option>
                                            <option value="Presale 2">Presale 2</option>
                                            <option value="Presale 3">Presale 3</option>
                                        </select>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="jumlah_tiket">Jumlah Tiket</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="jumlah_tiket" type="number" name="jumlah_tiket" required>
                                    </div>
                                    <div class="">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="lokasi">Lokasi</label>
                                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="lokasi" type="text" name="lokasi" required>
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="flex items-center border-t border-gray-200 rounded-b dark:border-gray-600">
                                        <button data-modal-hide="default-modal" class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">Simpan</button>
                                        <button data-modal-hide="default-modal" type="button" class="mt-4 ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Batal</button>
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

        </div>
    </div>
    </div>

    <script>
        function openEditModal(eventId) {
            // Set the event ID in the hidden input field
            document.getElementById('event-id').value = eventId;

            // Use JavaScript to trigger the modal to show
            document.querySelector('[data-modal-target="default-modal"]').click();
        }
    </script>


    <script>
        document.getElementById('cover_photo').addEventListener('change', function() {
            const fileInput = this;
            const maxSizeMB = 5;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;

            if (fileInput.files.length > 0) {
                const fileSizeBytes = fileInput.files[0].size;

                if (fileSizeBytes > maxSizeBytes) {
                    alert('File size exceeds the maximum allowed size (5MB). Please choose a smaller file.');
                    fileInput.value = ''; // Clear the file input
                }
            }
        });
    </script>
    <script>
        // Function to hide all cards
        function hideAllCards() {
            var cards = ['eventCard', 'addCard', 'messageCard', 'customerCard', 'dataCard'];
            cards.forEach(function(cardId) {
                var card = document.getElementById(cardId);
                if (card) {
                    card.style.display = 'none';
                }
            });
        }

        // Function to show specific card
        function showCard(cardId) {
            hideAllCards();
            var card = document.getElementById(cardId);
            if (card) {
                card.style.display = 'block';
            }
        }

        // Use these functions in your onclick attributes
        // For example: onclick="showCard('eventCard')"
    </script>

</body>

</html>