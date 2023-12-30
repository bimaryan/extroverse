<?php
// Establish a database connection
$conn = new mysqli('localhost', 'root', '', 'extroverse');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from 'users' table
$queryUsers = mysqli_query($conn, 'SELECT * FROM users');
$dataUsers = mysqli_fetch_all($queryUsers, MYSQLI_ASSOC);

// Fetch data from 'events' table
$queryEvents = mysqli_query($conn, 'SELECT * FROM events');
$data = mysqli_fetch_all($queryEvents, MYSQLI_ASSOC);

// Add image URL to each item in the 'events' result
foreach ($data as &$item) {
    // Assuming 'cover_foto' is the column name for the image URL in your database
    $item['cover_foto'] = 'http://192.168.0.121/extroverse/event/buat_acara/' . $item['cover_foto'];
}

// Close the database connection
$conn->close();

// Set the response header to indicate JSON content
header('Content-Type: application/json');

// Output the JSON-encoded results
echo json_encode([
    'users' => $dataUsers,
    'events' => $data,
], JSON_PRETTY_PRINT);

?>