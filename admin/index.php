<?php
session_start();
// Include the database connection
require_once "../db.php";

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id'])) {
    header('Location: http://localhost/extroverse/auth/login'); // Redirect to login page if not logged in or not an admin
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Perform necessary validation and sanitization

    $user_id = $_POST["user_id"];
    $edit_username = $_POST["edit_username"];
    $edit_password = $_POST["edit_password"];
    $edit_email = $_POST["edit_email"];
    $edit_role = $_POST["edit_role"];

    // Add more validation as needed

    // Update user details in the database
    $hashed_password = password_hash($edit_password, PASSWORD_DEFAULT);
    // Use the correct column name for the WHERE clause
    $sql = "UPDATE users SET username = '$edit_username', email = '$edit_email', password = '$hashed_password', role = '$edit_role' WHERE user_id = $user_id";
    mysqli_query($koneksi, $sql);

    // Redirect to the page where the user list is displayed
    header("Location: http://localhost/extroverse/admin/#editCard");
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

function getUsersData($koneksi)
{
    $usersData = array();

    $sql = "SELECT * FROM users";
    $result = mysqli_query($koneksi, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $usersData[] = $row;
    }

    return $usersData;
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
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <a href="./" class="flex ms-2 md:me-24">
                        <img src="http://localhost/extroverse/logo/extroverse.png" class="h-8 me-3" alt="Extroverse" />
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Extroverse</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-full" src="<?php echo $data_user['profile_image'] ?>" alt="user photo">
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
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
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Akun</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Pengaturan</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Daftar Distributor</a>
                                </li>
                                <li>
                                    <a href="http://localhost/extroverse/auth/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('dashboard')">
                        <span class="ms-3"><i class="bi bi-house-door"></i> Dashboard</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('addCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-people"></i> Users</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('messageCard')">

                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-chat-dots"></i> Messages</span>
                        <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span>
                    </a>
                </li>
                <!-- <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('customerCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-people"></i> View Users</span>
                    </a>
                </li> -->
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('eventCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-calendar4-event"></i> Events</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('historyCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-clock-history"></i> History
                            Transaction</span>
                    </a>
                </li>
                <li>
                    <a style="cursor:pointer;" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" onclick="showCard('dataCard')">
                        <span class="flex-1 ms-3 whitespace-nowrap"><i class="bi bi-graph-up"></i> Data Recapping</span>
                    </a>
                </li>
            </ul>
            <footer class="fixed bottom-0 left-0 right-0 bg-white dark:bg-blue-900">
                <div class="text-center p-2">
                    <hr class="border-gray-200 w-full dark:border-gray-700" />
                    <div class="text-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">© 2023 <a href="../">Extroverse</a>
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="rounded-lg mt-14">
            <div class="bg-white rounded-lg shadow p-6" id="dashboard">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Dashboard</h2>
                <div class="text-center grid grid-cols-3 gap-2">
                    <div class="p-4 rounded bg-blue-500 dark:bg-gray-800">
                        <p class="text-3xl text-white">
                            <?php echo $eventCount; ?> <br />
                            <span class="text-base">Events</span>
                        </p>
                        <p class="text-white"></p>
                    </div>
                    <div class="p-4 rounded bg-blue-500 dark:bg-gray-800">
                        <p class="text-3xl text-white">
                            <?php echo $ticketCount; ?> <br />
                            <span class="text-base">Tikets</span>
                        </p>
                    </div>
                    <div class="p-4 rounded bg-blue-500 dark:bg-gray-800">
                        <p class="text-3xl text-white">
                            <?php echo $userCount; ?> <br />
                            <span class="text-base">Users</span>
                        </p>
                    </div>
                </div>
            </div>

            <div id="addCard" class="bg-white rounded-lg shadow p-6" style="display: none;">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Users</h2>
                <div class="relative overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $usersData = getUsersData($koneksi);

                            foreach ($usersData as $userData) {
                                echo "<tr>
                                        <td class='px-6 py-4 whitespace-nowrap'>" . $userData['user_id'] . "</td>
                                        <td class='px-6 py-4 whitespace-nowrap'>" . $userData['username'] . "</td>
                                        <td class='px-6 py-4 whitespace-nowrap'>" . $userData['email'] . "</td>
                                        <td class='px-6 py-4 whitespace-nowrap'>" . $userData['role'] . "</td>
                                        <td class='px-6 py-4 whitespace-nowrap'>
                                            <a data-modal-target='editUserModal{$userData['user_id']}' data-modal-toggle='editUserModal{$userData['user_id']}' class='text-blue-500 hover:text-blue-700 cursor-pointer'>Edit</a>
                                        </td>
                                        <td class='text-center'>
                                            <a href='#' class='text-red-500 font-semibold py-1 px-2 rounded-full delete-user' data-user-id='" . $userData['user_id'] . "'>Delete</a>
                                        </td>
                                    </tr>";

                                echo "<div id='editUserModal{$userData['user_id']}' tabindex='-1' aria-hidden='true' class='hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full'>
                                            <div class='relative p-4 w-full max-w-2xl max-h-full'>
                                                <!-- Modal content -->
                                                <div class='relative bg-white rounded-lg shadow dark:bg-gray-700'>
                                                    <!-- Modal header -->
                                                    <div class='flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600'>
                                                        <h3 class='text-xl font-semibold text-gray-900 dark:text-white'>
                                                            Edit User
                                                        </h3>
                                                        <button type='button' class='text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white' data-modal-hide='editUserModal{$userData['user_id']}'>
                                                            <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
                                                                <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6' />
                                                            </svg>
                                                            <span class='sr-only'>Close modal</span>
                                                        </button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <div class='p-4 md:p-5'>
                                                        <form method='POST' action=''>
                                                            <!-- Add input fields for user details -->
                                                            <input type='hidden' name='user_id' value='{$userData['user_id']}'>
                                                            <div class='mb-4'>
                                                                <label for='edit_username' class='block text-gray-700 text-sm font-bold mb-2'>Username</label>
                                                                <input type='text' id='edit_username' name='edit_username' value='{$userData['username']}' class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
                                                            </div>
                                                            <div class='mb-4'>
                                                                <label for='edit_email' class='block text-gray-700 text-sm font-bold mb-2'>Email</label>
                                                                <input type='email' id='edit_email' name='edit_email' value='{$userData['email']}' class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
                                                            </div>
                                                            <div class='mb-4'>
                                                                <label for='edit_role' class='block text-gray-700 text-sm font-bold mb-2'>Role</label>
                                                                <input type='text' id='edit_role' name='edit_role' value='{$userData['role']}' class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
                                                            </div>
                                                            <!-- Add more fields as needed -->

                                                            <!-- Modal footer -->
                                                            <div class='flex items-center border-t border-gray-200 rounded-b dark:border-gray-600'>
                                                                <button data-modal-hide='editUserModal{$userData['user_id']}' class='mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800' type='submit'>Save</button>
                                                                <button data-modal-hide='editUserModal{$userData['user_id']}' type='button' class='mt-4 ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600'>Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Function to hide all cards
        function hideAllCards() {
            var cards = ['dashboard', 'eventCard', 'addCard', 'messageCard', 'customerCard', 'dataCard', 'historyCard'];
            cards.forEach(function(cardId) {
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
    </script>
    <script>
        // Add a click event listener to the delete-user class
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-user')) {
                event.preventDefault();

                // Get the user ID from the data-user-id attribute
                var userId = event.target.getAttribute('data-user-id');

                // Show SweetAlert for confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to the delete_user.php with the user ID
                        window.location.href = 'delete.php?user_id=' + userId;
                    }
                });
            }
        });
    </script>

</body>

</html>