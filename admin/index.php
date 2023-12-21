<?php
session_start();
// Include the database connection
require_once "../db.php";

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: http://localhost/extroverse/auth/login'); // Redirect to login page if not logged in or not an admin
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION["role"];
$username = $_SESSION["username"];
$email = $_SESSION['email'];

function getAdminData($koneksi)
{
    $data = array();

    // Get counts
    $data['eventCount'] = getCount($koneksi, 'events');
    $data['ticketCount'] = getCount($koneksi, 'tickets');
    $data['userCount'] = getCount($koneksi, 'users');

    return $data;
}

// Function to get counts from the database
function getCount($koneksi, $events)
{
    $sql = "SELECT COUNT(*) as count FROM $events";
    $result = mysqli_query($koneksi, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

// Get counts
$eventCount = getCount($koneksi, 'events');
$ticketCount = getCount($koneksi, 'tickets');
$userCount = getCount($koneksi, 'users');

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
    <title>Extroverse - Admin</title>
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
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <a href="./" class="flex ms-2 md:me-24">
                        <img src="http://localhost/extroverse/logo/extroverse.png" class="h-8 me-3" alt="Extroverse" />
                        <span
                            class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Extroverse</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button"
                                class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-full" src="<?php echo $data_user['profile_image'] ?>"
                                    alt="user photo">
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                            id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900 dark:text-white" role="none">
                                    <?php echo $username; ?>
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                    <?php echo $email; ?>
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Akun</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Pengaturan</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Daftar Distributor</a>
                                </li>
                                <li>
                                    <a href="http://localhost/extroverse/auth/logout.php"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Sign out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
        aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('dashboard')">
                        <span class="ms-3"><i class="bi bi-house-door"></i> Dashboard</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('addCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-plus-square"></i> Add Users</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('messageCard')">

                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-chat-dots"></i> Messages</span>
                        <span
                            class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('customerCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-people"></i> View Users</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('eventCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-calendar4-event"></i> View
                            Events</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('historyCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-clock-history"></i> History
                            Transaction</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        onclick="showCard('dataCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-graph-up"></i> Data Recapping</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">

                        <span class="flex-1 ms-3 whitespace-nowrap">Sign Up</span>
                    </a>
                </li> -->
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="rounded-lg mt-14">
            <div style="display: none" class="bg-white rounded-lg shadow p-6" id="dashboard">
                <h1 class="text-2xl font-semibold mb-2">Dashboard</h1>
                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 text-center items-center justify-center rounded bg-blue-500 dark:bg-gray-800 gap-2">
                        <p class="text-3xl text-white">
                            <?php echo $eventCount; ?>
                        </p>
                        <p class="text-white">Events</p>
                    </div>
                    <div class="p-4 text-center items-center justify-center rounded bg-blue-500 dark:bg-gray-800">
                        <p class="text-3xl text-white">
                            <?php echo $ticketCount; ?>
                        </p>
                        <p class="text-white">Tickets</p>
                    </div>
                    <div class="p-4 text-center items-center justify-center rounded bg-blue-500 dark:bg-gray-800">
                        <p class="text-3xl text-white">
                            <?php echo $userCount; ?>
                        </p>
                        <p class="text-white">Users</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6" id="addCard">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Add Users</h2>

                <!-- Modal HTML -->
                <div id="addUserModal" class="modal p-3 rounded-md">
                    <div class="modal-content card">
                        <!-- Add User Form -->
                        <form action="" method="POST">
                            <div class="mb-4">
                                <label for="username"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                                        </svg>
                                    </span>
                                    <input type="text" id="username"
                                        name="username"
                                        class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="elonmusk" required>
                                </div>
                                <!-- <label for="username" class="block text-sm font-medium">Username:</label>
                                <input type="text" name="username" class="mt-1 p-2 border rounded-md w-full" required> -->
                            </div>
                            <div class="mb-4">
                                <label for="email"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                    Email</label>
                                <div class="relative mb-6">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                            <path
                                                d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z" />
                                            <path
                                                d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z" />
                                        </svg>
                                    </div>
                                    <input type="email" id="email" name="email"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="bimaryan046@gmail.com" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium">Password:</label>
                                <input type="password" name="password" class="mt-1 p-2 border rounded-md w-full"
                                    required>
                            </div>
                            <button type="submit" class="bg-green-500 p-2 rounded-md">Add User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>

    </footer>
    <script>
        // Function to hide all cards
        function hideAllCards() {
            var cards = ['dashboard', 'eventCard', 'addCard', 'messageCard', 'customerCard', 'dataCard', 'historyCard'];
            cards.forEach(function (cardId) {
                var card = document.getElementById(cardId);
                if (card) {
                    card.style.display = 'none';
                }
            });
        }

        // Function to show specific card
        function showCard(cardId) {
            hideAllCards();
            var card = document.getElementById(cardId);
            if (card) {
                card.style.display = 'block';
            }
        }

        // Use these functions in your onclick attributes
        // For example: onclick="showCard('eventCard')"
    </script>

</body>

</html>