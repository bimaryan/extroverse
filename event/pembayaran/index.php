<?php
// Pastikan Anda sudah membuat koneksi ke database
require_once "../../db.php";

// Periksa apakah event_id tersedia dalam parameter POST
if (isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];

    // Query untuk mengambil detail event berdasarkan event_id
    $query = "SELECT * FROM events WHERE event_id = $event_id";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $event = mysqli_fetch_assoc($result);
        if (!$event) {
            // Event dengan event_id tertentu tidak ditemukan, Anda dapat menangani ini sesuai kebutuhan Anda
            echo "Event tidak ditemukan.";
        } else {
            // Contoh: Mengirimkan pesanan ke Tripay
            $tripay_api_url = 'https://api.tripay.co.id/v2/payment';
            $tripay_api_key = 'YOUR_TRIPAY_API_KEY';

            // Persiapkan data pesanan sesuai dengan format Tripay
            $order_data = [
                'method' => 'Qris',
                'merchant_ref' => 'Order-' . time(),
                'amount' => $event['harga'],
                'customer_name' => '<?php echo $username; ?>', // Gantilah dengan informasi pelanggan yang sesungguhnya
                'customer_email' => '<?php echo $email; ?>',
                'order_items' => [
                    [
                        'name' => $event['nama_acara'],
                        'price' => $event['harga'],
                        'quantity' => 1,
                    ],
                ],
            ];

            // Mengirim permintaan ke Tripay
            $ch = curl_init($tripay_api_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $tripay_api_key,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));

            $response = curl_exec($ch);
            curl_close($ch);

            $response_data = json_decode($response, true);

            if (isset($response_data['data']['payment_url'])) {
                // Jika berhasil mendapatkan URL pembayaran dari Tripay, arahkan pengguna ke halaman pembayaran
                header("Location: " . $response_data['data']['payment_url']);
                exit();
            } else {
                // Jika terjadi kesalahan dalam permintaan ke Tripay, Anda dapat menangani ini sesuai kebutuhan Anda
                echo "Gagal menginisiasi pembayaran.";
            }
        }
    } else {
        // Terjadi kesalahan dalam menjalankan query, Anda dapat menangani ini sesuai kebutuhan Anda
        echo "Terjadi kesalahan dalam mengambil detail event.";
    }

    // Tutup koneksi ke database jika sudah tidak digunakan
    mysqli_close($koneksi);
} else {
    // Jika event_id tidak tersedia dalam parameter POST, Anda dapat menangani ini sesuai kebutuhan Anda
    echo "Event ID tidak tersedia.";
}
?>
