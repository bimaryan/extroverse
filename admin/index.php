<?php
session_start();
// Include the database connection
require_once "../db.php";

// Check if the user is logged in and has the admin role
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header('Location: http://localhost/extroverse/auth/login'); // Redirect to login page if not logged in or not an admin
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id'], $_POST['edit_username'], $_POST['edit_email'], $_POST['edit_role'])) {
        $user_id = $_POST['user_id'];
        $edit_username = $_POST['edit_username'];
        $edit_email = $_POST['edit_email'];
        $edit_role = $_POST['edit_role'];

        // Perform the update only if all necessary fields are set
        $sql = "UPDATE users SET username = '$edit_username', email = '$edit_email', role = '$edit_role' WHERE user_id = $user_id";

        if (mysqli_query($koneksi, $sql)) {
            header("Location: http://localhost/extroverse/admin/#editCard");
            exit();
        } else {
            // Handle the error, for now, let's just display it
            echo "Error updating user: " . mysqli_error($koneksi);
        }
    } else {
        // Handle the case where not all required fields are set
        echo "Incomplete data received";
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["event_id"])) {
    $event_id = $_POST["event_id"];

    // Check if other keys exist before accessing them
    $edit_event_name = isset($_POST["edit_event_name"]) ? $_POST["edit_event_name"] : '';
    $edit_event_deskripsi = isset($_POST["edit_event_deskripsi"]) ? $_POST["edit_event_deskripsi"] : '';
    $edit_event_date = isset($_POST["edit_event_date"]) ? $_POST["edit_event_date"] : '';
    $edit_event_price = isset($_POST["edit_event_price"]) ? $_POST["edit_event_price"] : '';
    $edit_event_tiket = isset($_POST["edit_event_tiket"]) ? $_POST["edit_event_tiket"] : '';
    $edit_event_lokasi = isset($_POST["edit_event_lokasi"]) ? $_POST["edit_event_lokasi"] : '';
    $edit_event_sold = isset($_POST["edit_event_sold"]) ? $_POST["edit_event_sold"] : '';
    $edit_event_type = isset($_POST["edit_event_type"]) ? $_POST["edit_event_type"] : '';

    // Update the events table with the new data
    $sql = "UPDATE events SET 
            nama_acara = '$edit_event_name',
            deskripsi = '$edit_event_deskripsi',
            tanggal = '$edit_event_date',
            harga = '$edit_event_price',
            jumlah_tiket = '$edit_event_tiket',
            lokasi = '$edit_event_lokasi',
            jumlah_tiket_terjual = '$edit_event_sold',
            tiket_type = '$edit_event_type'
            WHERE event_id = $event_id";

    // Execute the query
    mysqli_query($koneksi, $sql);

    // Redirect to the page where the event list is displayed
    header("Location: http://localhost/extroverse/admin/");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["event_id"])) {
    // Get user_id from the query parameters
    $event_id = $_GET["event_id"];

    // Perform deletion in the database
    $sql = "DELETE FROM events WHERE event_id = $event_id";
    mysqli_query($koneksi, $sql);

    // Redirect to the page where the user list is displayed
    header("Location: http://localhost/extroverse/admin/");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get user_id from your session or any other means
    $user_id = $_SESSION['user_id']; // Change this to the actual way you retrieve user_id

    // Get other form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    // Handle profile image upload
    $target_dir = "../profil/logo/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["profile_image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // Redirect or handle the error as needed
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["profile_image"]["name"])) . " has been uploaded.";

            // Update user information in the database, including the new profile image path
            $sql = "UPDATE users SET username = '$username', email = '$email', role = '$role', profile_image = '$target_file' WHERE user_id = $user_id";
            mysqli_query($koneksi, $sql);

            // Redirect or handle success as needed
        } else {
            echo "Sorry, there was an error uploading your file.";
            // Redirect or handle the error as needed
        }
    }
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION["role"];
$username = $_SESSION["username"];
$email = $_SESSION['email'];

function getUserProfile($koneksi, $user_id)
{
    $userProfile = array(); // Initialize an empty array to store user profile data

    // Query to get user profile information based on user_id
    $sql = "SELECT * FROM users WHERE user_id = ?";

    // Using prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($koneksi, $sql);

    // Bind user_id parameter
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch user profile data
    if ($row = mysqli_fetch_assoc($result)) {
        $userProfile = $row;
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    return $userProfile;
}
$userProfile = getUserProfile($koneksi, $user_id);

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

function getEventsData($koneksi)
{
    $eventsData = array();

    $sql = "SELECT * FROM events";
    $result = mysqli_query($koneksi, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $eventsData[] = $row;
    }

    return $eventsData;
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
                                <?php if (!empty($userProfile['profile_image'])) : ?>
                                    <img src="<?php echo $userProfile['profile_image']; ?>" alt="Profile Image" class="w-8 h-8 rounded-full">
                                <?php else : ?>
                                    <img src="https://flowbite.com/docs/images/people/profile-picture-3.jpg" alt="Default Image" class="w-8 h-8 rounded-full">
                                <?php endif; ?>
                                <!-- <img class="w-8 h-8 rounded-full" src="<?php echo $userProfile['profile_image']; ?>" alt="user photo"> -->
                            </button>
                        </div>
                        <div class="max-w-full z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900 dark:text-white" role="none">
                                    <?php echo $username; ?>
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                    <?php echo $email; ?>
                                </p>
                            </div>
                            <hr class="bg-gray-200 border-1 dark:bg-gray-700" />
                            <ul class="py-1" role="none">
                                <li>
                                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer" role="menuitem" onclick="showCard('profile')">Account</a>
                                </li>
                                <li>
                                    <a href="../daftar_distributor/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Register Distributor</a>
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
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
                                            <a data-modal-target='editUserModal{$userData['user_id']}' data-modal-toggle='editUserModal{$userData['user_id']}' class='text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800 cursor-pointer'><i class='bi bi-pencil-square'></i> Edit</a>
                                            <a class='text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-2 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 delete-user cursor-pointer' data-user-id='" . $userData['user_id'] . "'><i class='bi bi-trash'></i> Delete</a>
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

            <div id="eventCard" class="bg-white rounded-lg shadow p-6" style="display: none;">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Events</h2>
                <div class="relative overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover Foto</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tickets</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">location</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tickets sold</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tickets type</th>

                                <!-- Add more columns as needed -->
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $eventsData = getEventsData($koneksi);

                            foreach ($eventsData as $eventData) {
                                echo "<tr>
                            <td class='px-6 py-4 whitespace-nowrap'>" . $eventData['event_id'] . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'><img src='http://localhost/extroverse/img/" . $eventData['cover_foto'] . "'/></td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . $eventData['nama_acara'] . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . $eventData['deskripsi'] . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . $eventData['tanggal'] . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . $eventData['harga'] . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . $eventData['jumlah_tiket'] . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . $eventData['lokasi'] . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . $eventData['jumlah_tiket_terjual'] . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . $eventData['tiket_type'] . "</td>
                            <!-- Add more columns as needed -->
                            <td class='px-6 py-4 whitespace-nowrap'>
                                <a data-modal-target='editEventModal{$eventData['event_id']}' data-modal-toggle='editEventModal{$eventData['event_id']}' class='text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800 cursor-pointer'><i class='bi bi-pencil-square'></i> Edit</a>
                                <a class='text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-2 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 delete-event cursor-pointer' data-event-id='" . $eventData['event_id'] . "'><i class='bi bi-trash'></i> Delete</a>
                            </td>
                        </tr>";

                                echo "<div id='editEventModal{$eventData['event_id']}' tabindex='-1' aria-hidden='true' class='hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full'>
                            <div class='relative p-4 w-full max-w-2xl max-h-full mt-5'>
                                <!-- Modal content -->
                                <div class='relative bg-white rounded-lg shadow dark:bg-gray-700'>
                                    <!-- Modal header -->
                                    <div class='flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600'>
                                        <h3 class='text-xl font-semibold text-gray-900 dark:text-white'>
                                            Edit Event
                                        </h3>
                                        <button type='button' class='text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white' data-modal-hide='editEventModal{$eventData['event_id']}'>
                                            <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
                                                <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6' />
                                            </svg>
                                            <span class='sr-only'>Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class='p-4 md:p-5'>
                                        <form method='POST' action=''>
                                            <div class='sm:col-span-2 mb-4'>
                                                <label for='edit_event_foto' class='block text-gray-700 text-sm font-bold mb-2'>Cover Foto</label>
                                                <input type='file' id='edit_event_foto' name='edit_event_foto' value='{$eventData['cover_foto']}' class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
                                            </div>
                                            <div class='grid gap-4 sm:grid-cols-2 sm:gap-6'>
                                                <!-- Add input fields for event details -->
                                                <input type='hidden' name='event_id' value='{$eventData['event_id']}'>
                                                <div class='w-full'>
                                                    <label for='edit_event_name' class='block text-gray-700 text-sm font-bold mb-2'>Event Name</label>
                                                    <input type='text' id='edit_event_name' name='edit_event_name' value='{$eventData['nama_acara']}' class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
                                                </div>
                                                <div class='w-full'>
                                                    <label for='edit_event_deskripsi' class='block text-gray-700 text-sm font-bold mb-2'>Description</label>
                                                    <input type='text' id='edit_event_deskripsi' name='edit_event_deskripsi' value='{$eventData['deskripsi']}' class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
                                                </div>
                                                <div class='w-full'>
                                                    <label for='edit_event_date' class='block text-gray-700 text-sm font-bold mb-2'>Event Date</label>
                                                    <input type='date' id='edit_event_date' name='edit_event_date' value='{$eventData['tanggal']}' class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
                                                </div>
                                                <div class='w-full'>
                                                    <label for='edit_event_price' class='block text-gray-700 text-sm font-bold mb-2'>Price</label>
                                                    <input type='number' id='edit_event_price' name='edit_event_price' value='{$eventData['harga']}' class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
                                                </div>
                                                <div class='w-full'>
                                                    <label for='edit_event_tiket' class='block text-gray-700 text-sm font-bold mb-2'>Tickets</label>
                                                    <input type='number' id='edit_event_tiket' name='edit_event_tiket' value='{$eventData['jumlah_tiket']}' class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
                                                </div>
                                                <div class='w-full'>
                                                    <label for='edit_event_lokasi' class='block text-gray-700 text-sm font-bold mb-2'>Location</label>
                                                    <input type='text' id='edit_event_lokasi' name='edit_event_lokasi' value='{$eventData['lokasi']}' class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
                                                </div>
                                            </div>
                                            <div class='w-full mb-4 mt-4'>
                                                <label for='edit_event_type' class='block text-gray-700 text-sm font-bold mb-2'>Tickets Type</label>
                                                <input type='text' id='edit_event_type' name='edit_event_type' value='{$eventData['tiket_type']}' class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
                                            </div>
                                            <!-- Add more fields as needed -->

                                            <!-- Modal footer -->
                                            <div class='flex items-center border-t border-gray-200 rounded-b dark:border-gray-600'>
                                                <button data-modal-hide='editEventModal{$eventData['event_id']}' class='mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800' type='submit'>Save</button>
                                                <button data-modal-hide='editEventModal{$eventData['event_id']}' type='button' class='mt-4 ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600'>Cancel</button>
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

            <div class="bg-white rounded-lg shadow p-6" id="profile" style="display: none;">
                <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">User Profile</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-1 text-center">
                        <?php if (!empty($userProfile['profile_image'])) : ?>
                            <img src="<?php echo $userProfile['profile_image']; ?>" alt="Profile Image" class="mx-auto max-w-full h-auto rounded">
                        <?php else : ?>
                            <img src="https://flowbite.com/docs/images/people/profile-picture-3.jpg" alt="Default Image" class="mx-auto max-w-full h-auto rounded">
                        <?php endif; ?>
                    </div>
                    <div class="col-1">
                        <form class="space-y-2" action="" method="POST" enctype="multipart/form-data">
                            <!-- Add enctype="multipart/form-data" for handling file uploads -->
                            <div>
                                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="<?php echo $username; ?>" readonly>
                            </div>
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="<?php echo $email; ?>" readonly>
                            </div>
                            <div>
                                <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                <input type="text" name="role" id="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="<?php echo $role; ?>" readonly>
                            </div>
                            <div>
                                <label for="profile_image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Profile Image</label>
                                <input type="file" name="profile_image" id="profile_image" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                            </div>
                            <div>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Function to hide all cards
        function hideAllCards() {
            var cards = ['dashboard', 'eventCard', 'addCard', 'messageCard', 'customerCard', 'dataCard', 'historyCard', 'profile'];
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

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-event')) {
                event.preventDefault();

                // Get the user ID from the data-user-id attribute
                var eventId = event.target.getAttribute('data-event-id');

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
                        window.location.href = '?event_id=' + eventId;
                    }
                });
            }
        });
    </script>

</body>

</html>