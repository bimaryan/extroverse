<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://kit.fontawesome.com/f74deb4653.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Unbounded">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Extroverse - Mytransaction</title>
</head>

<body class='dark:bg-gray-900'>
    <?php
    session_start();
    require_once '../../components/navbar.php';
    ?>
    <div class="container mx-auto p-3">
        <div class="mt-2 mb-4">
            <a href="http://localhost/extroverse/" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"><i class="bi bi-arrow-left-circle"></i> Back</a>
        </div>
        <div id="transaksiCard" class="dark:bg-gray-800 mt-4 rounded-lg shadow p-6 mb-4">
            <h2 class="mb-4 text-xl font-bold dark:text-gray-300">Transaction History</h2>
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right dark:text-gray-300">
                    <thead class='class="text-xs uppercase dark:text-gray-800"'>
                        <tr class='text-center text-sm'>
                            <th data-field="id">NO</th>
                            <th data-field="order_id">ORDER ID</th>
                            <th data-field="name">NAMA PEMBELI</th>
                            <th data-field="nama_acara">NAMA ACARA</th>
                            <th data-field="nominal">NOMINAL PEMBAYARAN</th>
                            <th data-field="status">STATUS</th>
                            <th data-field="status">BAYAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../../db.php";
                        // error_reporting(0);

                        $query = mysqli_query($koneksi, "SELECT registrasi_tiket.*, events.nama_acara, events.harga
                            FROM registrasi_tiket
                            JOIN events ON registrasi_tiket.event_id = events.event_id
                            ORDER BY registrasi_tiket.id ASC");

                        if (!$query) {
                            die("Query failed: " . mysqli_error($koneksi));
                        }

                        $no = 1;

                        while ($data = mysqli_fetch_array($query)) {
                            $status = $data['transaction_status'];

                            echo "<tr class='text-center text-sm dark:text-gray-500'>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $data['order_id'] . "</td>";
                            echo "<td>" . $data['nama'] . "</td>";
                            echo "<td>" . $data['nama_acara'] . "</td>";
                            echo "<td>" . $data['harga'] . "</td>";
                            // echo "<td>" . $data['transaction_status'] . "</td>";

                            if ($status >= 2) {
                                echo "<td class='text-green-500 font-bold'>Sukses</td>";
                            } elseif ($status >= 1) {
                                echo "<td class='text-yellow-500 font-bold'>Panding</td>";
                            } elseif ($status >= 4) {
                                echo "<td class='text-red-500 font-bold'>Cancel</td>";
                            } elseif ($status >= 5) {
                                echo "<td class='text-red-900 font-bold'>Expire</td>";
                            } else {
                                echo "<td class='text-gray-300 font-bold'>Waiting</td>";
                            }
                        }
                        echo '<td class="px-6 py-4">';
                        if ($status < 2) {
                            echo '<a href="../checkout/payment/?event_id=' . $data['event_id'] . '&order_id=' . $data['order_id'] . '" class="inline-block px-4 py-2 leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-md active:bg-blue-600 hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue">Bayar</a>';
                        }
                        echo '</td>';
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>