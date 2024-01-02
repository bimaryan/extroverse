<?php

namespace Midtrans;

require_once dirname(__FILE__) . '../../../midtrans/Midtrans.php';
// Set Your server key
// can find in Merchant Portal -> Settings -> Access keys
Config::$serverKey = 'SB-Mid-server-6Xe5piGR5BPlDXlHcWBFN01H';
Config::$clientKey = 'SB-Mid-client-NTHAxm4kbd8HpKUk';

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

// Uncomment for production environment
// Config::$isProduction = true;
Config::$isSanitized = Config::$is3ds = true;
$order_id = $_GET['order_id'];

// Required
$transaction_details = array(
    'order_id' => $order_id,
    'gross_amount' => 94000, // no decimal allowed for creditcard
);

// Fetch data from the database based on the order_id
require_once '../../db.php';
$query = "SELECT * FROM events WHERE order_id = '$order_id'";
$result = mysqli_query($koneksi, $query);

if ($result) {
    $event = mysqli_fetch_assoc($result);

    // Construct item_details array using data from events table
    $item_details = array(
        array(
            'id' => $event['event_id'], // Assuming event_id is unique
            'price' => $event['harga'],
            'quantity' => 1,
            'name' => $event['nama_acara']
        ),
    );

    // Fetch customer details from the users table based on user_id
    $user_id = $event['user_id'];
    $customer_query = "SELECT * FROM users WHERE user_id = $user_id";
    $customer_result = mysqli_query($koneksi, $customer_query);

    if ($customer_result) {
        $customer = mysqli_fetch_assoc($customer_result);

        // Optional: Customize customer details based on your actual user table structure
        $customer_details = array(
            'first_name' => $customer['username'],
            'last_name' => '', // Adjust accordingly based on your user table structure
            'email' => $customer['email'],
            'phone' => $customer['phone'],
        );
    } else {
        // Handle error fetching customer details
        echo "Error fetching customer details from the database.";
    }
} else {
    // Handle database query error
    echo "Error fetching data from the database.";
}

// Fill transaction details
$transaction = array(
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => $item_details,
);

$snap_token = '';
try {
    $snap_token = Snap::getSnapToken($transaction);
} catch (\Exception $e) {
    echo $e->getMessage();
}
// echo "snapToken = " . $snap_token;

function printExampleWarningMessage()
{
    if (strpos(Config::$serverKey, 'your ') != false) {
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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://kit.fontawesome.com/f74deb4653.js" crossorigin="anonymous"></script>
    <title>Extroverse - Checkout</title>
</head>

<body style="background: #CECECE;">

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
            <div class="bg-white rounded-lg shadow p-4 mt-4 w-full">
                <h2 class="text-2xl font-semibold"><?php echo $event['nama_acara']; ?></h2>
            </div>
            <div id="event" class="bg-white rounded-lg shadow p-4 mt-2 w-full">
                <div class="overflow-hidden mx-auto flex items-center justify-center">
                    <img src="http://localhost/extroverse/img/<?php echo $event['cover_foto']; ?>" alt="Cover Event" class="rounded-lg">
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 mt-2 w-full">
                <div class="flex items-center justify-between">
                    <span class="text"><?php echo $event['tiket_type']; ?></span>
                    <button id="pay-button" class="text-center focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Rp <?php echo number_format($event['harga'], 2); ?>
                    </button>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 mt-2 w-full">
                <h3 class="text-2xl font-semibold mb-2">Description</h3>
                <hr class="h-px mb-2 bg-gray-200 border-0 dark:bg-gray-700" />
                <p><?php echo nl2br($event['deskripsi']); ?></p>
            </div>
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