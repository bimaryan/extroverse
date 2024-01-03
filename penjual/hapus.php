<?php
include "../db.php";

// Check if ID parameter is provided
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Perform the deletion
    $delete_query = mysqli_query($koneksi, "DELETE FROM registrasi_tiket WHERE id = '$id'");

    if ($delete_query) {
        // Redirect back to the page with the table
        header("Location: ./");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($koneksi);
    }
} else {
    echo "ID parameter is missing.";
}
?>
