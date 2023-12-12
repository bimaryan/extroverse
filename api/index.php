<?php
$conn = new mysqli('localhost','root','', 'extroverse');
$query = mysqli_query($conn, 'select * from events');
$data = mysqli_fetch_all($query, MYSQLI_ASSOC);
echo json_encode($data);
