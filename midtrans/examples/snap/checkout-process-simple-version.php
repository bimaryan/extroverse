<?php
namespace Midtrans;

require_once dirname(__FILE__) . '/../../Midtrans.php';
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
require_once '../../../db.php';
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
echo "snapToken = " . $snap_token;

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