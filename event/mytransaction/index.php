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

<body>
    <?php
    session_start();
    require_once '../../components/navbar.php';
    ?>
    <div class="container mx-auto p-">
        <div id="transaksiCard" class="bg-white rounded-lg shadow p-6 mb-4">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Transaction History</h2>
            <div class="overflow-x-auto">
                <table class="table w-full whitespace-no-wrap">
                    <thead>
                        <tr class='text-center text-m'>
                            <th data-field="id">NO</th>
                            <th data-field="order_id">ORDER ID</th>
                            <th data-field="name">NAMA PEMBELI</th>
                            <th data-field="nama_acara">NAMA ACARA</th>
                            <th data-field="nominal">NOMINAL PEMBAYARAN</th>
                            <th data-field="status">STATUS</th>
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

                            echo "<tr class='text-center text-sm'>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $data['order_id'] . "</td>";
                            echo "<td>" . $data['nama'] . "</td>";
                            echo "<td>" . $data['nama_acara'] . "</td>";
                            echo "<td>" . $data['harga'] . "</td>";
                            // echo "<td>" . $data['transaction_status'] . "</td>";

                            if ($status >= 2) {
                                echo "<td><b> Pembayaran Sukses</b></span></td>";
                            } elseif ($status >= 1) {
                                echo "<td><b> Pembayaran Panding</b></span></td>";
                            } else {
                                echo "<td><b> Pembayaran Belum Dilakukan</b></span></td>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>