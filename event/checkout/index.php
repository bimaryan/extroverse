<?php
require_once "../../db.php";
$query = mysqli_query($koneksi, 'SELECT * FROM events');
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
    <div class="container mx-auto p-3">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <?php foreach ($products as $product) : ?>
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="text-2xl font-semibold">Event Detail</h2>
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
                <h2 class="text-2xl font-semibold">Registration Data</h2>
                <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700" />
            </div>

            <!-- <div class="checkout-actions">
                <div class="total-price">Total: $49.98</div>
                <button class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Proceed to Payment</button>
            </div> -->
        </div>
    </div>
</body>

</html>