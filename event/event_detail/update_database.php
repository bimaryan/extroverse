<?php
// Koneksi ke database Anda
require_once "../../db.php";

// Ambil data dari request AJAX
$event_id = $_POST['event_id'];
$jumlah_tiket = $_POST['jumlah_tiket'];

// Validasi dan sanitasi input
$event_id = $koneksi->real_escape_string($event_id);
$jumlah_tiket = $koneksi->real_escape_string($jumlah_tiket);

// Query untuk memperbarui database
$sql = "UPDATE events SET jumlah_tiket_terjual = jumlah_tiket_terjual + $jumlah_tiket WHERE id = $event_id";

if ($koneksi->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $koneksi->error;
}

$koneksi->close();
