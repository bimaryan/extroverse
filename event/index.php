<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan Anda sudah membuat koneksi ke database
    require_once "../db.php";

    // Ambil data dari formulir
    $nama_acara = $_POST["nama_acara"];
    $deskripsi = $_POST["deskripsi"];
    $tanggal = $_POST["tanggal"];
    $harga = $_POST["harga"];
    $jumlah_tiket = $_POST["jumlah_tiket"];
    $lokasi = $_POST["lokasi"];
    $jumlah_tiket_terjual = 0; // Jumlah tiket terjual awalnya 0

    // Validasi dan sanitasi data jika diperlukan (sesuai kebutuhan)

    // Insert data ke tabel events
    $query = "INSERT INTO events (nama_acara, deskripsi, tanggal, harga, jumlah_tiket, lokasi, jumlah_tiket_terjual) 
              VALUES ('$nama_acara', '$deskripsi', '$tanggal', $harga, $jumlah_tiket, '$lokasi', $jumlah_tiket_terjual)";

    if (mysqli_query($koneksi, $query)) {
        // Event berhasil ditambahkan, Anda dapat mengarahkan pengguna ke halaman lain atau memberikan pesan sukses
        header("Location: ./daftar_acara/"); // Ganti dengan halaman tujuan setelah event berhasil dibuat
        exit();
    } else {
        // Terjadi kesalahan, Anda dapat menampilkan pesan kesalahan atau mengarahkan pengguna kembali ke halaman buat acara
        echo "Terjadi kesalahan saat membuat acara: " . mysqli_error($koneksi);
    }

    // Tutup koneksi ke database jika sudah tidak digunakan
    mysqli_close($koneksi);
} else {
    // Jika akses langsung ke file ini tanpa melalui formulir, maka arahkan pengguna ke halaman buat acara
    header("Location: ./buat_acara/"); // Ganti dengan halaman tujuan jika diakses tanpa melalui formulir
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketin Dong</title>
</head>

<body style="background: #CECECE;">
    <!-- Bagian Buat Acara -->
    <div class="my-8">
        <h3 class="text-xl font-semibold mb-2">Buat Acara</h3>
        <form action="" method="POST" class="w-96">
            <div class="mb-4">
                <label for="nama_acara" class="block font-semibold">Nama Acara</label>
                <input type="text" id="nama_acara" name="nama_acara" class="w-full border rounded-md py-2 px-3" required>
            </div>
            <div class="mb-4">
                <label for="deskripsi" class="block font-semibold">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="w-full border rounded-md py-2 px-3" required></textarea>
            </div>
            <div class="mb-4">
                <label for="tanggal" class="block font-semibold">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="w-full border rounded-md py-2 px-3" required>
            </div>
            <div class="mb-4">
                <label for="harga" class="block font-semibold">Harga</label>
                <input type="number" id="harga" name="harga" class="w-full border rounded-md py-2 px-3" required>
            </div>
            <div class="mb-4">
                <label for="jumlah_tiket" class="block font-semibold">Jumlah Tiket</label>
                <input type="number" id="jumlah_tiket" name="jumlah_tiket" class="w-full border rounded-md py-2 px-3" required>
            </div>
            <div class="mb-4">
                <label for="lokasi" class="block font-semibold">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" class="w-full border rounded-md py-2 px-3" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-full">Buat Acara</button>
        </form>
    </div>

</body>

</html>