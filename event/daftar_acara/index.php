<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login/index.php");
    exit();
}

// Pastikan Anda sudah membuat koneksi ke database
require_once "../../db.php";

// Ambil data acara dari database
$query = "SELECT * FROM events";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Gagal mengambil data acara: " . mysqli_error($koneksi));
}
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
    <div class="container mx-auto p-8">
        <div class="flex items-center justify-between mb-8">
            <!-- Bagian Logo dan Nama Aplikasi -->
            <div class="flex items-center space-x-4">
                <img src="logo.png" alt="Logo Aplikasi" class="h-8 w-8">
            </div>

            <!-- Bagian Pencarian -->
            <div class="w-60">
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

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-3xl text-center font-semibold mb-4">Daftar Acara</h2>
            <table class="w-full border-collapse border bg-white shadow-md">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Nama Acara</th>
                        <th class="border px-4 py-2">Deskripsi</th>
                        <th class="border px-4 py-2">Tanggal</th>
                        <th class="border px-4 py-2">Harga</th>
                        <th class="border px-4 py-2">Jumlah Tiket</th>
                        <th class="border px-4 py-2">Type Tiket</th>
                        <th class="border px-4 py-2">Lokasi</th>
                        <th class="border px-4 py-2">Jumlah Tiket Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td class='border px-4 py-2'>" . $row['nama_acara'] . "</td>";
                        echo "<td class='border px-4 py-2'>" . $row['deskripsi'] . "</td>";
                        echo "<td class='border px-4 py-2'>" . $row['tanggal'] . "</td>";
                        echo "<td class='border px-4 py-2'>" . $row['harga'] . "</td>";
                        echo "<td class='border px-4 py-2'>" . $row['jumlah_tiket'] . "</td>";
                        echo "<td class='border px-4 py-2'>" . $row['tiket_type'] . "</td>";
                        echo "<td class='border px-4 py-2'>" . $row['lokasi'] . "</td>";
                        echo "<td class='border px-4 py-2'>" . $row['jumlah_tiket_terjual'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <br />
        <p><a href="../buat_acara/" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-full">Buat Acara Baru</a></p>
    </div>
</body>

</html>