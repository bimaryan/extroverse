<?php
// delete_user.php

session_start();

// Include the database connection
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["user_id"])) {
    // Get user_id from the query parameters
    $user_id = mysqli_real_escape_string($koneksi, $_GET["user_id"]);

    // Perform deletion in the database
    $sql = "DELETE FROM users WHERE user_id = $user_id";
    mysqli_query($koneksi, $sql);

    // Redirect to the page where the user list is displayed
    header("Location: http://localhost/extroverse/admin/index.php#customerCard");
    exit();
}
?>
