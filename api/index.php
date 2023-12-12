<?php
$conn = new mysqli('localhost','root','', 'extroverse');
$query = mysqli_query($conn, 'select * from events');
$data = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Add image URL to each item in the result
foreach ($data as &$item) {
    // Assuming 'cover_foto' is the column name for the image URL in your database
    $item['cover_foto'] = 'http://192.168.0.116/extroverse/event/buat_acara/' . $item['cover_foto'];
}

echo json_encode($data);
