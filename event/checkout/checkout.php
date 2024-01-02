<?php

namespace Midtrans;

require_once dirname(__FILE__) . '/../../midtrans/Midtrans.php';
// Set Your server key
// can find in Merchant Portal -> Settings -> Access keys
Config::$serverKey = 'SB-Mid-server-6Xe5piGR5BPlDXlHcWBFN01H';
Config::$clientKey = 'SB-Mid-client-NTHAxm4kbd8HpKUk';

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

// Uncomment for the production environment
// Config::$isProduction = true;
Config::$isSanitized = Config::$is3ds = true;

// Check if order_id is set in the URL
if (!isset($_GET['order_id'])) {
    echo "Order ID is missing.";
    exit();
}

$order_id = $_GET['order_id'];

// Fetch data from the database based on the order_id using prepared statement
require_once '../../db.php';
$query = "SELECT * FROM events WHERE order_id = ?";
$stmt = mysqli_prepare($koneksi, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $order_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        $event = mysqli_fetch_assoc($result);

        // Check if data is retrieved
        if (!$event) {
            echo "Event not found.";
            exit();
        }

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
        $customer_query = "SELECT * FROM users WHERE user_id = ?";
        $stmt_customer = mysqli_prepare($koneksi, $customer_query);

        if ($stmt_customer) {
            mysqli_stmt_bind_param($stmt_customer, "i", $user_id);
            mysqli_stmt_execute($stmt_customer);
            $customer_result = mysqli_stmt_get_result($stmt_customer);
            mysqli_stmt_close($stmt_customer);

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
                exit();
            }
        } else {
            // Handle prepared statement error
            echo "Error preparing customer query.";
            exit();
        }
    } else {
        // Handle database query error
        echo "Error fetching data from the database.";
        exit();
    }
} else {
    // Handle prepared statement error
    echo "Error preparing query.";
    exit();
}

// Fill transaction details
$transaction_details = array(
    'order_id' => $order_id,
    'gross_amount' => $event['harga'], // Use the retrieved amount from the database
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
    exit();
}

echo "snapToken = " . $snap_token;
?>

<!DOCTYPE html>
<html>

<body>
    <button id="pay-button">Pay!</button>
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