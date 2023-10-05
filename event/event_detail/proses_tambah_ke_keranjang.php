<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    // Redirect pengguna yang belum masuk ke halaman login atau registrasi
    header("Location: ../auth/login/");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../../db.php"; // Ganti dengan path yang sesuai ke file koneksi database Anda.

    $user_id = $_SESSION["user_id"];
    $event_id = $_POST["event_id"];
    $quantity = $_POST["quantity"];

    // Query untuk mengambil harga tiket dari database berdasarkan event_id
    $price_query = "SELECT harga FROM events WHERE event_id = ?";
    $stmt = mysqli_prepare($koneksi, $price_query);
    mysqli_stmt_bind_param($stmt, "i", $event_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $harga_event);

    if (mysqli_stmt_fetch($stmt)) {
        // Menghitung total harga berdasarkan harga tiket dan jumlah tiket yang ingin dibeli
        $total_harga = $harga_event * $quantity;

        // Menutup pernyataan persiapan pertama
        mysqli_stmt_close($stmt);

        // Query untuk menambahkan event ke keranjang pengguna
        $insert_query = "INSERT INTO keranjang (user_id, event_id, quantity, total_harga)
                         VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $insert_query);
        mysqli_stmt_bind_param($stmt, "iiid", $user_id, $event_id, $quantity, $total_harga);

        if (mysqli_stmt_execute($stmt)) {
            // Event berhasil ditambahkan ke keranjang
            header("Location: ../event_detail/?event_id=$event_id&success=added_to_cart");
            exit();
        } else {
            // Gagal menambahkan event ke keranjang, Anda bisa menangani kesalahan sesuai kebutuhan.
            header("Location: ../event_detail/?event_id=$event_id&error=add_to_cart_failed");
            exit();
        }
    } else {
        // Event dengan event_id tertentu tidak ditemukan, Anda bisa menangani ini sesuai kebutuhan Anda
        header("Location: ../event_detail/?event_id=$event_id&error=event_not_found");
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
?>