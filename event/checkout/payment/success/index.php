<?php
session_start();
include "../../../../db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "pengguna") {
    header("Location: ../auth/login/");
    exit();
}

// Assuming you have a session and a user ID
// You can modify this based on your authentication mechanism
$user_id = $_SESSION["user_id"];

// Fetch user data
$user_query = mysqli_query($koneksi, "SELECT * FROM users WHERE user_id = '$user_id'");
$user_data = mysqli_fetch_assoc($user_query);

// Fetch registrasi_tiket data for the user
$registrasi_query = mysqli_query($koneksi, "SELECT * FROM registrasi_tiket WHERE user_id = '$user_id'");
$registrasi_data = mysqli_fetch_assoc($registrasi_query);

// Assuming you have an event ID from somewhere
$event_id = $registrasi_data['event_id'];

// Fetch event data
$event_query = mysqli_query($koneksi, "SELECT * FROM events WHERE event_id = '$event_id'");
$event_data = mysqli_fetch_assoc($event_query);

// Get order ID
$order_id = $registrasi_data['order_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Unbounded">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Unbounded">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <title>Extroverse - Payment(success)</title>
</head>

<body class="dark:bg-gray-900 text-gray-700 dark:text-gray-300">
    <?php
    require '../../../../components/navbar.php';
    ?>
    <div class="container mx-auto p-4">
        <div class="card dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg shadow p-6">
            <h2 class="text-2xl font-semibold mb-4 text-center">Payment Success</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="text-center">
                    <img src="http://localhost/extroverse/img/<?php echo $event_data['cover_foto']; ?>" alt="Event Cover Photo" class="w-full rounded-lg">
                </div>
                <div>
                    <div class="text-gray-500 mb-4">
                        <p><strong><i class="bi bi-calendar-event"></i> Event:</strong> <?php echo $event_data['nama_acara']; ?></p>
                        <p><strong><i class="bi bi-person"></i> Nama:</strong> <?php echo $registrasi_data['nama']; ?></p>
                        <p><strong><i class="bi bi-calendar2-week"></i> Tanggal:</strong> <?php echo $event_data['tanggal']; ?></p>
                        <p><strong><i class="bi bi-geo-alt"></i> Lokasi:</strong> <?php echo $event_data['lokasi']; ?></p>
                        <p><strong><i class="bi bi-qr-code"></i> Code Ticket:</strong> <?php echo $order_id; ?></p>
                    </div>
                    <div id="qrcode" class="mt-4 flex justify-center"></div>
                </div>
            </div>

            <div class="mt-4 flex justify-center">
                <a href="#" class="bg-green-500 text-white rounded-lg px-4 py-2">Continue</a>
            </div>
        </div>
    </div>

    <script>
        // Generate QR code using qrcode.js
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo $order_id; ?>",
            width: 128,
            height: 128
        });
    </script>
</body>

</html>