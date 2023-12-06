<?php
session_start();
// Include the database connection
require "../../db.php";

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: http://localhost/extroverse/auth/login'); // Redirect to login page if not logged in or not an admin
    exit();
}

function getAdminData($koneksi)
{
    $data = array();

    // Get user details
    $data['users'] = getUsers($koneksi);

    return $data;
}

function getUsers($koneksi)
{
    $users = array();

    $sql = "SELECT user_id, username, email FROM users";
    $result = mysqli_query($koneksi, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    return $users;
}

// Admin-only content here
$data = getAdminData($koneksi);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Unbounded">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Unbounded">
    <title>Extroverse Admin</title>
    <style>
        body {
            font-family: Unbounded;
        }

        .hvr:hover {
            color: black
        }
    </style>

</head>

<body>
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar" style="background-color: #240e4d;">
        <div class="h-full px-3 py-4 overflow-y-auto">
            <a href="http://localhost/extroverse/admin" class="flex items-center ps-2.5 mb-5">
                <img src="http://localhost/extroverse/img/extroverse.png" class="h-6 me-3 sm:h-7" alt="Flowbite Logo" />
                <span class="self-center text-xl font-semibold whitespace-nowrap text-white">Extroverse</span>
            </a>
            <hr />
            <ul class="space-y-2 font-medium mt-2">
                <li>
                    <a href="http://localhost/extroverse/admin" class="hvr flex items-center p-2 text-white rounded-lg dark:text-dark hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="bi bi-house-door"></i>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                            <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Kanban</span>
                        <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">Pro</span>
                    </a>
                </li> -->
                <!-- <li>
                    <a href="#" class="hvr flex items-center p-2 text-white rounded-lg dark:text-dark hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="bi bi-chat-dots"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Inbox</span>
                        <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span>
                    </a>
                </li> -->
                <li>
                    <a href="./" class="hvr flex items-center p-2 text-white rounded-lg dark:text-dark hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="bi bi-people"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="hvr flex items-center p-2 text-white rounded-lg dark:text-dark hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="bi bi-calendar2-event"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Event</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="http://localhost/extroverse/auth/login" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="bi bi-box-arrow-left"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Sign In</span>
                    </a>
                </li> -->
                <li>
                    <a href="http://localhost/extroverse/auth/logout.php" class="hvr flex items-center p-2 text-white rounded-lg dark:text-dark hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Log Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <nav class="border-purple-200 rounded text-white" style="background-color: #240e4d;">
            <div class="flex flex-wrap items-center justify-between mx-auto p-4">
                <a class="flex items-center space-x-3 rtl:space-x-reverse">
                    <!-- <img src="http://localhost/extroverse/img/extroverse.png" class="h-8" alt="Flowbite Logo" /> -->
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Admin <?php echo $_SESSION['username']; ?></span>
                </a>
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center justify-center p-2 w-10 h-10 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <!-- <button data-collapse-toggle="navbar-hamburger" type="button" class="inline-flex items-center justify-center p-2 w-10 h-10 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-hamburger" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button> -->
                <div class="hidden w-full" id="navbar-hamburger">
                    <ul class="flex flex-col font-medium mt-4 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">

                    </ul>
                </div>
            </div>
        </nav>
        <div class="mt-2">
            <h2 class="text-2xl font-semibold mb-2">Users</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($data['users'] as $user) : ?>
                    <div class="p-3 bg-gray-800 text-white rounded-md">
                        <h3 class="text-lg font-semibold mb-2"><?php echo $user['username']; ?></h3>
                        <p class="text-gray-400"><?php echo $user['email']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- <div class="mt-2" id="inbox">
            <h1 class="text-2xl font-semibold mb-2">Inbox</h1>
            <div class="grid grid-cols-3 gap-4">
                <div class="p-4 text-center items-center justify-center rounded bg-blue-500 dark:bg-gray-800 gap-2">
                    <p class="text-3xl text-white"><?php echo $eventCount; ?></p>
                    <p class="text-white">Events</p>
                </div>
                <div class="p-4 text-center items-center justify-center rounded bg-blue-500 dark:bg-gray-800">
                    <p class="text-3xl text-white"><?php echo $ticketCount; ?></p>
                    <p class="text-white">Tickets</p>
                </div>
                <div class="p-4 text-center items-center justify-center rounded bg-blue-500 dark:bg-gray-800">
                    <p class="text-3xl text-white"><?php echo $userCount; ?></p>
                    <p class="text-white">Users</p>
                </div>
            </div>
        </div> -->
    </div>

    <footer>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>

</body>

</html>