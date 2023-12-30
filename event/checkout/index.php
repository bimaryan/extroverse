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

<body>
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="http://localhost/extroverse/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="http://localhost/extroverse/logo/extroverse.png" class="h-8" alt="Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Extroverse</span>
            </a>
            <div class="flex items-center gap-3 md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <a href="http://localhost/extroverse/search">
                    <div class="flex md:order-2">
                        <div class="relative hidden md:block">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only">Search icon</span>
                            </div>
                            <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="search" name="Mencari" placeholder="Cari event...">
                        </div>
                    </div>
                </a>
                <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full" src="/docs/images/people/profile-picture-3.jpg" alt="user photo">
                </button>
                <!-- Dropdown menu -->
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="http://localhost/extroverse/profil" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Account</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Daftar Distributor</a>
                        </li>
                        <li>
                            <a href="http://localhost/extroverse/auth/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</a>
                        </li>
                    </ul>
                </div>
                <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-user" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <div class="relative md:hidden mb-2">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
                        </div>
                    </li>
                    <!-- <li>
                    <a href="http://localhost/extroverse/" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Home</a>
                </li> -->
                    <!-- <li>
                    <a href="http://localhost/extroverse/event/buat_acara/" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Buat Acara</a>
                </li> -->
                    <!-- <li>
                    <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Riwayat Pembelian</a>
                </li> -->
                    <!-- <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Pricing</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Contact</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mx-auto p-3">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
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
            <div class="bg-white rounded-lg p-4 shadow">
                <h2 class="text-2xl font-semibold text-center">Registration Data</h2>
                <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700" />

                <form method="post" action="" class="grid grid-cols-3 gap-4">
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
                            <label for="sameAsRegistration" class="text-sm text-gray-600">Same as Registration Data</label>
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


            <!-- <div class="checkout-actions">
                <div class="total-price">Total: $49.98</div>
                <button class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Proceed to Payment</button>
            </div> -->
        </div>
    </div>

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