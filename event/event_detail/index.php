<?php
// Pastikan Anda sudah membuat koneksi ke database
require_once "../../db.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login/index.php");
    exit();
}

// Periksa apakah event_id tersedia dalam parameter URL
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Query untuk mengambil detail event berdasarkan event_id
    $query = "SELECT * FROM events WHERE event_id = $event_id";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $event = mysqli_fetch_assoc($result);
        if (!$event) {
            // Event dengan event_id tertentu tidak ditemukan, Anda dapat menangani ini sesuai kebutuhan Anda
            echo "Event tidak ditemukan.";
        } else {
            // Event ditemukan, tampilkan detailnya
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
                <title>Extroverse</title>
                <style>
                    #shareButton {
                        margin-left: 10px;
                        /* Atur jarak antara tombol Share dan nama acara */
                    }
                </style>
            </head>

            <body class="bg-gray-100">
                <?php
                include "../../components/navbar.php";
                ?>
                <div class="container mx-auto p-3">
                    <div class="mt-2">
                        <a href="http://localhost/extroverse/" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"><i class="bi bi-arrow-left-circle"></i> Back</a>
                    </div>
                    <div class="mt-4">
                        <div class="bg-white rounded-lg shadow p-4 mt-4 w-full">
                            <h2 class="text-2xl font-semibold"><?php echo $event['nama_acara']; ?></h2>
                        </div>
                        <div id="event" class="bg-white rounded-lg shadow p-4 mt-2 w-full">
                            <div class="overflow-hidden mx-auto flex items-center justify-center">
                                <img src="http://localhost/extroverse/img/<?php echo $event['cover_foto']; ?>" alt="Cover Event" class="rounded-lg">
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-4 mt-2 w-full">
                            <div class="flex items-center justify-between">
                                <span class="text">Rp <?php echo number_format($event['harga'], 2); ?></span>
                                <a href="../../event/checkout/" class="text-center focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Buy</a>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-4 mt-2 w-full">
                            <h3 class="text-2xl font-semibold mb-2">Description</h3>
                            <hr class="h-px mb-2 bg-gray-200 border-0 dark:bg-gray-700" />
                            <p><?php echo nl2br($event['deskripsi']); ?></p>
                        </div>
                    </div>
                </div>

                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                    const profileButton = document.getElementById("profileButton");
                    const profilePopup = document.getElementById("profilePopup");

                    profileButton.addEventListener("click", () => {
                        profilePopup.classList.toggle("hidden");
                    });

                    // Sembunyikan pop-up saat mengklik di luar pop-up
                    window.addEventListener("click", (e) => {
                        if (!profileButton.contains(e.target) && !profilePopup.contains(e.target)) {
                            profilePopup.classList.add("hidden");
                        }
                    });
                </script>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const shareButton = document.getElementById('shareButton');
                        const eventLink = window.location.href; // Mendapatkan URL halaman acara

                        shareButton.addEventListener('click', function() {
                            // Salin link ke clipboard
                            const tempInput = document.createElement('input');
                            tempInput.value = eventLink;
                            document.body.appendChild(tempInput);
                            tempInput.select();
                            document.execCommand('copy');
                            document.body.removeChild(tempInput);

                            // Tampilkan SweetAlert untuk memberi tahu pengguna bahwa link telah disalin
                            Swal.fire({
                                title: 'Link Telah Disalin',
                                text: 'Link ke acara ini telah disalin ke clipboard.',
                                icon: 'success',
                                timer: 2000, // Durasi SweetAlert (ms)
                                showConfirmButton: false
                            });
                        });
                    });
                </script>
            </body>

            </html>
<?php
        }
    } else {
        // Terjadi kesalahan dalam menjalankan query, Anda dapat menangani ini sesuai kebutuhan Anda
        echo "Terjadi kesalahan dalam mengambil detail event.";
    }

    // Tutup koneksi ke database jika sudah tidak digunakan
    mysqli_close($koneksi);
} else {
    // Jika event_id tidak tersedia dalam parameter URL, Anda dapat menangani ini sesuai kebutuhan Anda
    echo "Event ID tidak tersedia.";
}
?>