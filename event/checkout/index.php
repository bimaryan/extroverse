<?php
session_start();
require '../../db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "pengguna") {
    header("Location: ../../auth/login/");
    exit();
}

$nama_acara = $harga = "";

// Check if event_id is provided
if (isset($_GET['event_id'])) {
    $event_id = mysqli_real_escape_string($koneksi, $_GET['event_id']);

    // Fetch event information
    $event_query = mysqli_query($koneksi, "SELECT nama_acara, harga, jumlah_tiket_terjual, cover_foto, tanggal, lokasi FROM events WHERE event_id = '$event_id'");
    $event_data = mysqli_fetch_assoc($event_query);

    // Set values for display
    $nama_acara = $event_data['nama_acara'];
    $harga = $event_data['harga'];
    $jumlah_tiket_terjual = $event_data['jumlah_tiket_terjual'];
    $cover_foto_url = $event_data['cover_foto'];
    $tanggal = $event_data['tanggal'];
    $lokasi = $event_data['lokasi'];
}

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"]; // Get user_id from the session

    $nama = mysqli_real_escape_string($koneksi, $_POST["nama"]);
    $nik = mysqli_real_escape_string($koneksi, $_POST["nik"]);
    $email = mysqli_real_escape_string($koneksi, $_POST["email"]);
    $day = mysqli_real_escape_string($koneksi, $_POST["day"]);
    $month = mysqli_real_escape_string($koneksi, $_POST["month"]);
    $year = mysqli_real_escape_string($koneksi, $_POST["year"]);
    $tanggal_lahir = "$year-$month-$day";
    $gender = mysqli_real_escape_string($koneksi, $_POST["gender"]);

    // Generate a unique order_id using user_id and timestamp
    $order_id = rand();

    $transaction_status = 1;
    $transaction_id = "";

    $event_id = mysqli_real_escape_string($koneksi, $_POST["event_id"]);

    $jumlahProduk = 1;

    // SQL query to insert data into registrasi_tiket table
    $sql = "INSERT INTO registrasi_tiket (user_id, nama, nik, email, tanggal_lahir, gender, order_id, event_id, transaction_status, transaction_id)
            VALUES ('$user_id', '$nama', '$nik', '$email', '$tanggal_lahir', '$gender', '$order_id', '$event_id', '$transaction_status', '$transaction_id')";

    if ($koneksi->query($sql) === TRUE) {
        // Update jumlah_tiket_terjual in the events table
        $jumlah_tiket_terjual += $jumlahProduk;
        $update_query = "UPDATE events SET jumlah_tiket_terjual = '$jumlah_tiket_terjual' WHERE event_id = '$event_id'";
        $koneksi->query($update_query);

        header("location:../checkout/payment/?event_id=$event_id&order_id=$order_id");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}

$koneksi->close();
?>

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
    <title>Extroverse - Checkout</title>
</head>

<body class="dark:bg-gray-900 text-gray-700 dark:text-gray-300">
    <?php
    include "../../components/navbar.php";
    ?>
    <div class="container p-4 mx-auto mt-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
            <div class="card p-5 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg shadow">
                <h2 class="text-2xl font-semibold mb-4 text-center">Event Details</h2>
                <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
                <img src="http://localhost/extroverse/img/<?php echo $cover_foto_url; ?>" class="w-full" alt="Event Cover Photo">
                <div class="m-2">
                    <p class="font-semibold"><?php echo $nama_acara; ?></p>
                    <p class="text-gray-500 text-sm"><i class="bi bi-alarm"></i> <?php echo $tanggal; ?></p>
                    <p class="text-gray-500 text-sm"><i class="bi bi-geo-alt"></i> <?php echo $lokasi; ?></p>
                </div>
            </div>
            <div class="card p-5 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg shadow">
                <h2 class="text-2xl font-semibold mb-4 text-center">Registrasi Tiket</h2>
                <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
                <form method="post">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                                Lengkap :</label>
                            <input type="text" name="nama" class="mt-1 p-2 w-full text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email:</label>
                            <input type="email" name="email" class="mt-1 p-2 w-full border rounded-md border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label for="nama" class="text-sm font-medium dark:text-gray-300">Harga:</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" id="hargaTiket" name="harga" value="<?php echo $harga; ?>" class="p-2 w-25 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <div class="flex items-center space-x-2">
                                <!-- Minus Button -->
                                <a onclick="kurangiJumlah()" class="cursor-pointer focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">-</a>

                                <!-- Quantity Input -->
                                <input type="text" id="jumlahProduk" value="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 text-center w-full" readonly />

                                <!-- Plus Button -->
                                <a onclick="tambahJumlah()" class="cursor-pointer focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">+</a>
                            </div>
                        </div>
                    </div>
                    <label for="nik" class="block mt-4 text-sm font-medium dark:text-gray-300">NIK:</label>
                    <input type="text" name="nik" class="mt-1 p-2 w-full border rounded-mdborder border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <!-- Add three separate input fields for day, month, and year -->
                    <!-- <label class="block mt-4 text-sm font-medium dark:text-gray-300">Tanggal Lahir:</label>
                    <div class="grid grid-cols-3 gap-2 mt-1">
                        <div>
                            <label for="day" class="sr-only dark:text-gray-300">Day</label>
                            <input type="number" name="day" id="day" class="p-2 w-full border rounded-md border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="DD" required>
                        </div>
                        <div>
                            <label for="month" class="sr-only dark:text-gray-300">Month</label>
                            <input type="number" name="month" id="month" class="p-2 w-full border rounded-md border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="MM" required>
                        </div>
                        <div>
                            <label for="year" class="sr-only dark:text-gray-300">Year</label>
                            <input type="number" name="year" id="year" class="p-2 w-full border rounded-md border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="YYYY" required>
                        </div>
                    </div> -->
                    <label for="gender" class="block mt-4 text-sm font-medium dark:text-gray-300">Gender:</label>
                    <select name="gender" class="mt-1 p-2 w-full border rounded-md border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                    <button type="submit" class="mt-4 px-4 py-2 w-full bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let jumlahProduk = 1;
        const hargaPerTiket = <?php echo $harga; ?>; // Harga per tiket

        function updateHarga() {
            const totalHarga = jumlahProduk * hargaPerTiket;
            document.getElementById('hargaTiket').value = totalHarga;
        }

        function tambahJumlah() {
            jumlahProduk++;
            document.getElementById('jumlahProduk').value = jumlahProduk;
            updateHarga();
        }

        function kurangiJumlah() {
            if (jumlahProduk > 1) {
                jumlahProduk--;
                document.getElementById('jumlahProduk').value = jumlahProduk;
                updateHarga();
            }
        }
    </script>

</body>

</html>