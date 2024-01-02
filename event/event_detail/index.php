<?php

namespace Midtrans;

require_once dirname(__FILE__) . '/../../midtrans/Midtrans.php';
require_once '../../db.php';
session_start();

// Set Your server key and client key
Config::$serverKey = 'SB-Mid-server-6Xe5piGR5BPlDXlHcWBFN01H';
Config::$clientKey = 'SB-Mid-client-NTHAxm4kbd8HpKUk';

// Uncomment for production environment
// Config::$isProduction = true;

// Other configurations...
Config::$isSanitized = Config::$is3ds = true;

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login/index.php");
    exit();
}

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    $query = "SELECT * FROM events WHERE event_id = $event_id";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $event = mysqli_fetch_assoc($result);

        if (!$event) {
            echo "Event not found.";
        } else {
            $item_details = array(
                array(
                    'id' => $event['event_id'],
                    'price' => $event['harga'],
                    'quantity' => 1,
                    'name' => $event['nama_acara']
                ),
            );

            $user_id = $event['user_id'];
            $customer_query = "SELECT * FROM users WHERE user_id = $user_id";
            $customer_result = mysqli_query($koneksi, $customer_query);

            if ($customer_result) {
                $customer = mysqli_fetch_assoc($customer_result);

                $customer_details = array(
                    'first_name' => $customer['username'],
                    'last_name' => '',
                    'email' => $customer['email'],
                    'phone' => $customer['phone'],
                );
            } else {
                echo "Error fetching customer details from the database.";
            }
        }
    } else {
        echo "Error fetching data from the database.";
    }

    $transaction_details = array(
        'order_id' => $event_id,
        'gross_amount' => $event['harga'],
    );

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
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
        <script src="https://kit.fontawesome.com/f74deb4653.js" crossorigin="anonymous"></script>
        <title>Extroverse</title>
        <style>
            #shareButton {
                margin-left: 10px;
            }
        </style>
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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo Config::$clientKey; ?>"></script>
        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function() {
                snap.pay('<?php echo $snap_token ?>');
            };
        </script>

    </body>

    </html>
<?php
} else {
    echo "Event ID not available.";
}
?>