<?php
// Pastikan Anda sudah membuat koneksi ke database
require_once "../../db.php";

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
                <div class="container mx-auto p-3">
                    <div class="flex items-center justify-between">
                        <!-- Bagian Logo dan Nama Aplikasi -->
                        <a href="/" class="flex items-center space-x-4">
                            <img src="../../img/extroverse.png" alt="Logo Aplikasi" class="h-8 w-8" style="width: 123px; height: 100%;">
                        </a>

                        <!-- Bagian Pencarian -->
                        <div class="w-full m-4">
                            <div class="relative">
                                <a href="../../search"><input type="text" class="w-full border rounded-md pl-8 pr-4 py-2 focus:outline-none focus:border-blue-500" placeholder="Cari event..."></a>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="bi bi-search"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Profil Pengguna -->
                        <div class="flex items-center space-x-4 relative">
                            <button id="profileButton" class="relative bg-transparent border border-transparent rounded-full cursor-pointer focus:outline-none">
                                <i class="bi bi-person-circle text-3xl"></i>
                            </button>
                            <div id="profilePopup" class="hidden absolute right-0 top-5 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-md z-10">
                                <ul class="py-2 px-4">
                                    <li><a href="../../profil/">Akun</a></li>
                                    <li><a href="../">Buat Events</a></li>
                                    <li><a href="#">Riwayat Pembelian</a></li>
                                    <li><a href="../auth/logout.php">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="w-full bg-white border rounded-lg p-4 mt-4">
                        <div class="w-full h-64 overflow-hidden mb-5">
                            <img src="../buat_acara/<?php echo $event['cover_foto']; ?>" alt="Cover Event" class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div class="flex justify-between items-center">
                            <h2 class="text-3xl font-semibold mb-4"><?php echo $event['nama_acara']; ?></h2>
                            <button id="shareButton" class="bg-gray-500 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-full" type="button"><i class="bi bi-share-fill"></i></button>
                        </div>
                        <p class="mb-8 text-lg font-semibold">Description</p>
                        <p class="text-sm mb-2 text-gray-500"><?php echo nl2br($event['deskripsi']); ?></p>
                        <hr />
                        <div class="flex justify-between items-center mt-5 text-gray-500">
                            <p class="mb-2">Tanggal</p>
                            <p class="mb-2 font-semibold"><?php echo $event['tanggal']; ?></p>
                        </div>
                        <div class="flex justify-between items-center mt-2 text-gray-500">
                            <p class="mb-2">Tiket Terjual</p>
                            <p class="mb-2 font-semibold"><?php echo $event['jumlah_tiket_terjual']; ?></p>
                        </div>
                        <div class="flex justify-between items-center mt-2 text-gray-500">
                            <p class="mb-2">Harga</p>
                            <p class="mb-2 font-semibold">Rp <?php echo number_format($event['harga'], 2); ?></p>
                        </div>
                        <div class="flex justify-between items-center mt-5 text-gray-500">
                            <!-- Tombol Chat Admin (sebelah kiri) -->
                            <div>
                                <button class="bg-purple-500 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-full" type="button">Chat</button>
                            </div>

                            <!-- Tombol Beli Tiket (di tengah) -->
                            <div>
                                <form action="../pembayaran/" method="post">
                                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-full" type="submit" onclick="buyTicket()">Checkout</button>
                                </form>
                            </div>

                            <!-- Tombol Chart (sebelah kanan) -->
                            <div>
                                <button class="bg-green-500 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-full" type="button">Cart</button>
                            </div>
                        </div>
                    </div>
                </div>

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
                <script>
                    function buyTicket() {
                        <?php if (!isset($_SESSION["user_id"])) : ?>
                            // Redirect to the login page if the user is not logged in
                            window.location.href = '../auth/login/';
                        <?php else : ?>
                            // Add the logic for buying the ticket here
                            // For example, you can show a modal or perform other actions
                            // ...
                        <?php endif; ?>
                    }
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