<?php
session_start(); // Start the session if not already started
require '../../db.php';
$event_id = $_GET['event_id']; // You should validate and sanitize this input

// Fetch event details from the database
$query = "SELECT * FROM events WHERE event_id = $event_id"; // Adjust the query based on your database structure
$result = mysqli_query($koneksi, $query);

if ($result) {
    $event = mysqli_fetch_assoc($result);

    // Close the database connection after fetching data
    mysqli_close($koneksi);
} else {
    // Handle the case where the query fails
    echo "Error fetching event details: " . mysqli_error($koneksi);
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://kit.fontawesome.com/f74deb4653.js" crossorigin="anonymous"></script>
    <title>Extroverse - Eventdetail</title>
</head>

<body class="dark:bg-gray-900">

    <?php
    include "../../components/navbar.php";
    ?>

    <div class="container mx-auto p-3">
        <div class="mt-1">
            <a href="http://localhost/extroverse/" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">
                <i class="bi bi-arrow-left-circle"></i> Back
            </a>
        </div>
        <div class="mt-4">
            <div class="dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg shadow p-4 mt-4 w-full">
                <h2 class="text-2xl font-semibold"><?php echo $event['nama_acara']; ?></h2>
            </div>
            <div id="event" class="dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg shadow p-4 mt-2 w-full">
                <div class="overflow-hidden mx-auto flex items-center justify-center">
                    <img src="http://localhost/extroverse/img/<?php echo $event['cover_foto']; ?>" alt="Cover Event" class="rounded-lg">
                </div>
            </div>
            <div class="dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg shadow p-4 mt-2 w-full">
                <div class="flex items-center justify-between">
                    <span class="text"><?php echo $event['tiket_type']; ?></span>
                    <a href="../checkout/?event_id=<?php echo $event['event_id']; ?>" class="text-center focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Rp <?php echo number_format($event['harga'], 2); ?>
                    </a>
                </div>
            </div>
            <div class="dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg shadow p-4 mt-2 w-full">
                <h3 class="text-2xl font-semibold mb-2">Description</h3>
                <hr class="h-px mb-2 bg-gray-200 border-0 dark:bg-gray-700" />
                <p><?php echo nl2br($event['deskripsi']); ?></p>
            </div>
        </div>
    </div>
</body>

</html>