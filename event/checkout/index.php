<?php
require_once "../../db.php";
// Add error handling for the SQL query
$query = mysqli_query($koneksi, 'SELECT * FROM events');
if (!$query) {
    die('Error fetching events: ' . mysqli_error($koneksi));
}

$products = mysqli_fetch_all($query, MYSQLI_ASSOC);
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
    <title>Checkout</title>
</head>

<body style="background: #CECECE;">
    <?php
    include "../../components/navbar.php";
    ?>
    <div class="container mx-auto p-3">
        <div class="mt-2">
            <a href="http://localhost/extroverse/" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"><i class="bi bi-arrow-left-circle"></i> Back</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
            <?php foreach ($products as $product) : ?>
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="text-2xl font-semibold text-center">Event Detail</h2>
                    <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700" />
                    <div class="mx-auto text-center">
                        <img src="http://localhost/extroverse/img/<?php echo $product['cover_foto']; ?>" alt="<?php echo $product['nama_acara']; ?>" style="width: 500px" class="mx-auto">
                    </div>
                    <div class="mt-3">
                        <p class="text-lg"><?php echo $product['nama_acara']; ?></p>
                        <div class="mt-3 text-sm text-gray-500">
                            <i class="bi bi-alarm"></i> <?php echo $product['tanggal']; ?>
                        </div>
                        <div class="mt-3 text-sm text-gray-500">
                            <i class="bi bi-geo-alt"></i> <?php echo $product['lokasi']; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="flex flex-col gap-4">
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="text-2xl font-semibold text-center">Registration Data</h2>
                    <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700" />

                    <form method="post" action="" id="sameAsRegistration">
                        <!-- Name Input -->
                        <div class="my-2 col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-600">Name:</label>
                            <input type="text" id="name" name="name" placeholder="Enter your name" class="mt-1 p-2 border rounded-md w-full" />
                        </div>

                        <!-- Email Input -->
                        <div class="my-2 col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-600">Email:</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" class="mt-1 p-2 border rounded-md w-full" />
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <!-- Date Input (Day) -->
                            <div class="my-2">
                                <label for="day" class="block text-sm font-medium text-gray-600">Day:</label>
                                <input type="number" id="day" name="day" placeholder="DD" class="mt-1 p-2 border rounded-md w-full" />
                            </div>

                            <!-- Date Input (Month) -->
                            <div class="my-2">
                                <label for="month" class="block text-sm font-medium text-gray-600">Month:</label>
                                <input type="number" id="month" name="month" placeholder="MM" class="mt-1 p-2 border rounded-md w-full" />
                            </div>

                            <!-- Date Input (Year) -->
                            <div class="my-2">
                                <label for="year" class="block text-sm font-medium text-gray-600">Year:</label>
                                <input type="number" id="year" name="year" placeholder="YYYY" class="mt-1 p-2 border rounded-md w-full" />
                            </div>
                        </div>

                        <div class="my-2 col-span-3">
                            <label for="phone" class="block text-sm font-medium text-gray-600">Phone Number:</label>
                            <input type="text" id="phone" name="phone" placeholder="+6285157433395" class="mt-1 p-2 border rounded-md w-full" />
                        </div>

                        <!-- Gender Input -->
                        <div class="my-2 col-span-3">
                            <label for="gender" class="block text-sm font-medium text-gray-600">Gender:</label>
                            <select id="gender" name="gender" class="mt-1 p-2 border rounded-md w-full">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="text-2xl font-semibold text-center">Ticket User</h2>
                    <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700" />

                    <form method="post" action="">
                        <div class="flex items-center justify-between">
                            <div class="text-lg">
                                <p><?php echo $product['tiket_type']; ?></p>
                            </div>

                            <!-- Checkbox to indicate the same registration data -->
                            <div class="my-2">
                                <input type="checkbox" id="sameAsRegistration" name="sameAsRegistration" class="mr-2" />
                                <label for="sameAsRegistration" class="text-sm text-gray-600">Same as Registration
                                    Data</label>
                            </div>
                        </div>

                        <!-- Name Input -->
                        <div class="my-2 col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-600">Name:</label>
                            <input type="text" id="name" name="name" placeholder="Enter your name" class="mt-1 p-2 border rounded-md w-full" />
                        </div>

                        <!-- Email Input -->
                        <div class="my-2 col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-600">Email:</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" class="mt-1 p-2 border rounded-md w-full" />
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <!-- Date Input (Day) -->
                            <div class="my-2">
                                <label for="day" class="block text-sm font-medium text-gray-600">Day:</label>
                                <input type="number" id="day" name="day" placeholder="DD" class="mt-1 p-2 border rounded-md w-full" />
                            </div>

                            <!-- Date Input (Month) -->
                            <div class="my-2">
                                <label for="month" class="block text-sm font-medium text-gray-600">Month:</label>
                                <input type="number" id="month" name="month" placeholder="MM" class="mt-1 p-2 border rounded-md w-full" />
                            </div>

                            <!-- Date Input (Year) -->
                            <div class="my-2">
                                <label for="year" class="block text-sm font-medium text-gray-600">Year:</label>
                                <input type="number" id="year" name="year" placeholder="YYYY" class="mt-1 p-2 border rounded-md w-full" />
                            </div>
                        </div>

                        <div class="my-2 col-span-3">
                            <label for="phone" class="block text-sm font-medium text-gray-600">Phone Number:</label>
                            <input type="text" id="phone" name="phone" placeholder="+6285157433395" class="mt-1 p-2 border rounded-md w-full" />
                        </div>

                        <!-- Gender Input -->
                        <div class="my-2 col-span-3">
                            <label for="gender" class="block text-sm font-medium text-gray-600">Gender:</label>
                            <select id="gender" name="gender" class="mt-1 p-2 border rounded-md w-full">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="text-2xl font-semibold text-center">Payment Method</h2>
                    <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700" />
                    <div class="grid grid-cols-1 md:grid-cols-3 text-center gap-4">
                        <button onclick="showBankOptions()" type="button" class="metode-pembayaran text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Bank
                            Transfer</button>
                        <button onclick="showEwalletOptions()" type="button" class="metode-pembayaran text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">E-Wallet</button>
                        <button onclick="showOtherOptions()" type="button" class="metode-pembayaran text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Other</button>
                    </div>
                    <hr class="h-px my-2 mb-4 bg-gray-200 border-0 dark:bg-gray-700" />
                    <div id="bankOptions" class="hidden">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center text-gray-900 border border-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:focus:ring-gray-800">
                                <input type="radio" id="mandiri" name="bank" onclick="SelectBank('mandiri')" class="mr-2" />
                                <img src="https://yesplis.com/img/MANDIRI.6fb652b5.svg" alt="Mandiri" class="w-8 me-2 -ms-1">
                                <label for="mandiri">
                                    Mandiri
                                </label>
                            </div>

                            <div class="flex items-center text-gray-900 border border-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:focus:ring-gray-800">
                                <input type="radio" id="bri" name="bank" onclick="SelectBank('bri')" class="mr-2" />
                                <img src="https://yesplis.com/img/BRI.75199d76.svg" alt="BRI" class="w-8 me-2 -ms-1">
                                <label for="bri">
                                    BRI
                                </label>
                            </div>

                            <div class="flex items-center text-gray-900 border border-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:focus:ring-gray-800">
                                <input type="radio" id="bca" name="bank" onclick="SelectBank('bca')" class="mr-2" />
                                <img src="https://yesplis.com/img/BCA.02c302ec.svg" alt="BCA" class="w-8 me-2 -ms-1">
                                <label for="bca">
                                    BCA
                                </label>
                            </div>

                            <div class="flex items-center text-gray-900 border border-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:focus:ring-gray-800">
                                <input type="radio" id="bni" name="bank" onclick="SelectBank('bni')" class="mr-2" />
                                <img src="https://yesplis.com/img/BNI.88c38b37.svg" alt="BNI" class="w-8 me-2 -ms-1">
                                <label for="bni">
                                    BNI
                                </label>
                            </div>

                            <div class="flex items-center text-gray-900 border border-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:focus:ring-gray-800">
                                <input type="radio" id="other" name="bank" onclick="SelectBank('other')" class="mr-2" />
                                <img src="https://yesplis.com/img/OTHER.fe38c8e5.svg" alt="CIMB" class="w-8 me-2 -ms-1">
                                <label for="other">
                                    Other Bank
                                </label>
                            </div>
                        </div>
                        <!-- Tambahkan bank-bank lainnya sesuai kebutuhan -->
                    </div>
                    <div id="ewalletOptions" class="hidden">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center text-gray-900 border border-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:focus:ring-gray-800">
                                <input type="radio" name="ewallet" onclick="SelectEwallet()" class="mr-2" />
                                <img src="http://localhost/extroverse/event/img/dana.png" alt="Logo" class="w-8 me-2 -ms-1">
                                <label>Dana</label>
                            </div>

                            <div class="flex items-center text-gray-900 border border-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:focus:ring-gray-800">
                                <input type="radio" name="ewallet" onclick="SelectEwallet()" class="mr-2" />
                                <img class="w-8 me-2 -ms-1" src="https://yesplis.com/img/shopee.c13f3b36.svg" alt="Shopee">
                                <label>ShopeePay</label>
                            </div>

                            <div class="flex items-center text-gray-900 border border-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:focus:ring-gray-800">
                                <input type="radio" name="ewallet" onclick="SelectEwallet()" class="mr-2" />
                                <img class="w-8 me-2 -ms-1" src="https://gopay.co.id/_nuxt/img/site-logo.7064e6fv143.png" alt="GoPay">
                                <label>GoPay</label>
                            </div>
                            <!-- Add more e-wallet options as needed -->
                        </div>
                    </div>

                    <div id="otherOptions" class="hidden">
                        <!-- Opsi Pembayaran Lainnya -->
                        <ul>
                            <li><b> MAAF SAAT INI BELUM TERSEDIA METODE LAIN !! </b></li>
                        </ul>
                        <!-- Isi dengan elemen-elemen pilihan pembayaran lainnya -->
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="text-2xl font-semibold text-center">Payment Details</h2>
                    <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700" />

                    <!-- Ticket Total -->
                    <div class="flex justify-between my-2">
                        <span class="font-semibold">Ticket Total:</span>
                        <span>Rp <?php echo isset($event) ? number_format($event['harga'] * $selectedTicketQuantity, 2) : '0.00'; ?></span>
                    </div>

                    <!-- Total Payment -->
                    <div class="flex justify-between my-2">
                        <span class="font-semibold">Total Payment:</span>
                        <span>Rp <?php echo isset($event) ? number_format($event['harga'] * $selectedTicketQuantity, 2) : '0.00'; ?></span>
                    </div>

                    <!-- Pay Now Button -->
                    <button class="text-white bg-blue-500 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 mt-4 w-full" type="button" onclick="payNow()">Pay Now</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let jumlah = 0; // Jumlah produk awal
        const hargaPerTiket = <?php echo $product['harga']; ?>; // Ambil harga per tiket dari PHP

        function updateHarga() {
            const hargaTotal = jumlah * hargaPerTiket;
            document.getElementById('hargaTotal').textContent = hargaTotal;
        }

        function tambahJumlah() {
            jumlah++;
            document.getElementById('jumlahProduk').value = jumlah;
            updateHarga();
        }

        function kurangiJumlah() {
            if (jumlah > 1) {
                jumlah--;
                document.getElementById('jumlahProduk').value = jumlah;
                updateHarga();
            }
        }
    </script>

    <script>
        function showBankOptions() {
            document.getElementById('bankOptions').style.display = 'block';
            document.getElementById('ewalletOptions').style.display = 'none';
            document.getElementById('otherOptions').style.display = 'none';
        }

        function showEwalletOptions() {
            document.getElementById('bankOptions').style.display = 'none';
            document.getElementById('ewalletOptions').style.display = 'block';
            document.getElementById('otherOptions').style.display = 'none';
        }

        function showOtherOptions() {
            document.getElementById('bankOptions').style.display = 'none';
            document.getElementById('ewalletOptions').style.display = 'none';
            document.getElementById('otherOptions').style.display = 'block';
        }
    </script>

    <script>
        // Add JavaScript to disable/enable fields based on checkbox state
        const sameAsRegistrationCheckbox = document.getElementById('sameAsRegistration');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const dayInput = document.getElementById('day');
        const monthInput = document.getElementById('month');
        const yearInput = document.getElementById('year');
        const genderSelect = document.getElementById('gender');

        sameAsRegistrationCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;

            // Disable/enable fields based on checkbox state
            nameInput.disabled = isChecked;
            emailInput.disabled = isChecked;
            dayInput.disabled = isChecked;
            monthInput.disabled = isChecked;
            yearInput.disabled = isChecked;
            genderSelect.disabled = isChecked;
        });
    </script>
</body>

</html>