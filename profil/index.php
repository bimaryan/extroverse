<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "pengguna") {
    header("Location: ../auth/login/");
    exit();
}

require_once "../db.php";

$user_id = $_SESSION['user_id'];
$role = $_SESSION["role"];
$username = $_SESSION["username"];
$email = $_SESSION['email'];

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
    <title>Extroverse - Profil</title>
</head>

<body style="background: #CECECE;">
    <?php
    include "../components/navbar.php";
    ?>
    <div class="container mx-auto p-3">
        <div class="mt-2 mb-4">
            <a href="http://localhost/extroverse/" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"><i class="bi bi-arrow-left-circle"></i> Back</a>
        </div>
        <div class="bg-white rounded-lg shadow p-6 mt-4" id="profile">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Profil Penjual</h2>
            <div class="flex items-center">
                <img class="w-16 h-16 rounded-full" src="<?php echo $data_user['profile_image'] ?>" alt="user photo">
                <div class="ms-4">
                    <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo $username; ?></p>
                    <p class="text-sm text-gray-500 dark:text-gray-300"><?php echo $email; ?></p>
                    <p class="text-sm text-gray-500 dark:text-gray-300">Role: <?php echo $role; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>