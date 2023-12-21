<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    require_once "../db.php";

    // Function to sanitize input data
    function sanitizeInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Retrieve and sanitize form data
    $cover_foto = sanitizeInput($_FILES['cover_foto']['name']);
    $nama_acara = sanitizeInput($_POST['nama_acara']);
    $deskripsi = sanitizeInput($_POST['deskripsi']);
    $tanggal = sanitizeInput($_POST['tanggal']);
    $harga = sanitizeInput($_POST['harga']);
    $tiket_type = sanitizeInput($_POST['tiket_type']);
    $jumlah_tiket = sanitizeInput($_POST['jumlah_tiket']);
    $lokasi = sanitizeInput($_POST['lokasi']);

    // Validate form data
    $errors = array();

    if (empty($cover_foto)) {
        $errors[] = "Cover Foto is required.";
    }

    if (empty($nama_acara)) {
        $errors[] = "Nama Acara is required.";
    }

    // Add more validation as needed
    $allowed_file_types = array("jpg", "jpeg", "png", "gif");

    $file_extension = pathinfo($cover_foto, PATHINFO_EXTENSION);

    if (!in_array(strtolower($file_extension), $allowed_file_types)) {
        $errors[] = "Invalid file type. Please upload a valid image file.";
    }

    $max_length_nama_acara = 255; // Set the maximum length as needed

    if (strlen($nama_acara) > $max_length_nama_acara) {
        $errors[] = "Nama Acara must not exceed $max_length_nama_acara characters.";
    }

    $valid_tiket_types = array("Presale 1", "Presale 2", "Presale 3");

    if (!in_array($tiket_type, $valid_tiket_types)) {
        $errors[] = "Invalid ticket type selected.";
    }


    // Check for errors before proceeding
    if (empty($errors)) {
        // Move uploaded file to a specified directory
        $target_directory = "../img/"; // Change this path as needed
        $target_file = $target_directory . basename($cover_foto);

        if (move_uploaded_file($_FILES['cover_foto']['tmp_name'], $target_file)) {
            // Update the event in the database
            $query = "UPDATE events SET
                cover_foto = '$cover_foto',
                nama_acara = '$nama_acara',
                deskripsi = '$deskripsi',
                tanggal = '$tanggal',
                harga = '$harga',
                tiket_type = '$tiket_type',
                jumlah_tiket = '$jumlah_tiket',
                lokasi = '$lokasi'
                WHERE event_id = $event_id"; // Replace $event_id with the actual event_id

            $result = mysqli_query($koneksi, $query);

            if ($result) {
                echo "Event updated successfully!";
            } else {
                echo "Error updating event: " . mysqli_error($koneksi);
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }

    // Close the database connection
    mysqli_close($koneksi);
} else {
    echo "Invalid request method.";
}
