<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login/index.php");
    exit();
}

// Pastikan Anda sudah membuat koneksi ke database
require_once "../../db.php";

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

    // Proses unggahan cover foto
    $target_directory = "uploads/"; // Direktori tempat Anda ingin menyimpan file
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
            $query = "INSERT INTO events (nama_acara, deskripsi, tanggal, harga, jumlah_tiket, lokasi, jumlah_tiket_terjual, cover_foto, tiket_type) 
              VALUES ('$nama_acara', '$deskripsi', '$tanggal', $harga, $jumlah_tiket, '$lokasi', $jumlah_tiket_terjual, '$target_file', '$tiket_type')";

            if (mysqli_query($koneksi, $query)) {
                // Event berhasil ditambahkan, Anda dapat mengarahkan pengguna ke halaman lain atau memberikan pesan sukses
                header("Location: ../daftar_acara/"); // Ganti dengan halaman tujuan setelah event berhasil dibuat
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
                <img src="../../img/extroverse.png" alt="Logo Aplikasi" class="h-8 w-8" style="width: 123px; height: 100%;">
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

        <div class="bg-white rounded-lg shadow p-6 mt-4">
            <h3 class="text-3xl text-center font-semibold mb-4">Buat Acara Baru</h3>
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