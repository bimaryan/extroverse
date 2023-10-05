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
    <title>Ticketin Dong</title>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-semibold mb-4">Daftar Acara</h2>
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
        <br/>
        <p><a href="buat_acara.php" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-full">Buat Acara Baru</a></p>
    </div>
</body>

</html>