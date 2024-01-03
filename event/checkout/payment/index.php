<?php

require_once(dirname(__FILE__) . '../../../../midtrans/Midtrans.php');
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "pengguna") {
    header("Location: ../auth/login/");
    exit();
}

use Midtrans\Config;
use Midtrans\Snap;

// Set Your server key
Config::$serverKey = 'SB-Mid-server-6Xe5piGR5BPlDXlHcWBFN01H';
Config::$clientKey = 'SB-Mid-client-NTHAxm4kbd8HpKUk';

// Uncomment for the production environment
// Config::$isProduction = true;
Config::$isSanitized = Config::$is3ds = true;

// Fetch event_id from the query parameters
$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : '';
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';
if (empty($event_id)) {
    die("Event ID is missing.");
}

// Fetch data from the database based on the event_id
require_once('../../../db.php');
$query = "SELECT events.*, registrasi_tiket.*
          FROM events
          JOIN registrasi_tiket ON events.event_id = registrasi_tiket.event_id
          WHERE events.event_id = '$event_id'";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error fetching data from the database.");
}

$event = mysqli_fetch_assoc($result);

$biaya = $event['harga'];
$transaction_details = array(
    'order_id' => $order_id, // Use event_id as the order_id
    'gross_amount' =>  $biaya, // no decimal allowed for credit card
);

// Construct item_details array using data from events table
$item_details = array(
    array(
        'id' => $event['event_id'],
        'price' => $event['harga'],
        'quantity' => 1,
        'name' => $event['nama_acara']
    ),
);

// Fetch customer details from the users table based on user_id
$user_id = $event['user_id'];
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error fetching customer details from the database.");
}

$customer = mysqli_fetch_assoc($result);

// Fill transaction details
$transaction_details = array(
    'order_id' => $order_id,
    'gross_amount' =>  $event['harga'], // Make sure this is a valid numeric value
);

$transaction = array(
    'transaction_details' => $transaction_details,
    'customer_details' => $customer,
    'item_details' => $item_details,
);

$snap_token = '';
try {
    $snap_token = Snap::getSnapToken($transaction);
} catch (\Exception $e) {
    die($e->getMessage());
}

function printExampleWarningMessage()
{
    if (strpos(Config::$serverKey, 'your ') !== false) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<your server key>\';');
        die();
    }
}
?>

<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Unbounded">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Unbounded">
<title>Extroverse - Payment</title>

<body class='bg-gray-300'>
    <?php
    require '../../../components/navbar.php';
    ?>
    <div class="container mx-auto p-4">
        <div class="card p-5 bg-white rounded-lg shadow">
            <p>Registrasi Tiket Berhasil. Silahkan selesaikan pembayaran anda!</p>
            <button id="pay-button" type="button" class="w-full mt-3 focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Bayar Sekarang</button>
        </div>
    </div>
    <!-- TODO: Remove ".sandbox" from script src URL for the production environment. Also input your client key in "data-client-key" -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo Config::$clientKey; ?>"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            // SnapToken acquired from the previous step
            snap.pay('<?php echo $snap_token ?>');
        };
    </script>
</body>

</html>