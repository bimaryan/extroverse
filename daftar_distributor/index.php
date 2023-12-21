<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Unbounded">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Extroverse - Distributor Registration</title>
    <style>
        body {
            font-family: Unbounded;
        }

        /* Style the input fields */
        input[type="email"],
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        /* Add a hover effect for buttons */
        button[type="submit"]:hover {
            opacity: 0.8;
        }

        /* Style the horizontal ruler */
        hr {
            border: 1px solid #6842ad;
            margin-bottom: 25px;
        }
    </style>
</head>

<body class="bg-gray-200 bg-cover bg-center" style="background-color: #240e4d;">
    <!-- Your existing HTML content here -->
    <?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $phone = $_POST["phone"];
        $gender = $_POST["gender"];

        // Additional code for handling profile image upload
        $profileImageName = $_FILES['profile_image']['name'];
        $profileImageTmpName = $_FILES['profile_image']['tmp_name'];
        $profileImageSize = $_FILES['profile_image']['size'];

        // Validasi data
        if (empty($username) || empty($email) || empty($password) || empty($phone) || empty($gender) || empty($profileImageName)) {
            echo "<script>Swal.fire('Error', 'Isi semua field.', 'error');</script>";
        } else {
            // Koneksi ke database (gunakan file koneksi Anda)
            require_once "../db.php";

            // Sanitasi data
            $username = mysqli_real_escape_string($koneksi, $username);
            $email = mysqli_real_escape_string($koneksi, $email);
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Move uploaded profile image to a folder (adjust the folder path as needed)
            $uploadPath = "../profil/logo/";
            $profileImagePath = $uploadPath . $profileImageName;
            move_uploaded_file($profileImageTmpName, $profileImagePath);

            // Insert data into the users table
            $sql = "INSERT INTO users (username, email, password, role, phone, gender, profile_image) VALUES ('$username', '$email', '$passwordHash', 'penjual', '$phone', '$gender', '$profileImagePath')";

            if (mysqli_query($koneksi, $sql)) {
                echo "<script>Swal.fire('Success', 'Registration successful.', 'success').then(() => { window.location.href = '../auth/login/'; });</script>";
            } else {
                echo "<script>Swal.fire('Error', 'Terjadi kesalahan dalam pendaftaran.', 'error');</script>";
            }

            mysqli_close($koneksi);
        }
    }
    ?>

    <div id="particles-js" class="absolute top-0 left-0 w-full h-full"></div>
    <div class="container mx-auto p-5 mt-10 relative z-10" style="background-color: rgba(0, 0, 0, 0.5); width: 350px; border-radius: 15px; box-shadow: 8px 8px 5px 0px rgba(0, 0, 0, 0.25);">
        <div class="text-center">
            <img src="http://localhost/extroverse/logo/extroverse.png" style="width: 110px;" alt="Avatar" class="mx-auto">
            <hr>
            <h2 class="text-lg text-white font-semibold">Distributor Registration</h2>
        </div>
        <form class="max-w-sm mx-auto mt-2" method="POST" enctype="multipart/form-data" action="">
            <!-- Add your registration form fields here -->
            <label class="text-white" for="username" class="mb-1">Username</label>
            <input type="text" id="username" name="username" autocomplete="off" placeholder="Enter Username..." class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" required>

            <label class="text-white" for="email" class="mb-1 mt-3">Email</label>
            <input type="email" id="email" name="email" autocomplete="off" placeholder="Enter Email..." class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" required>

            <label class="text-white" for="password" class="mb-1 mt-3">Password</label>
            <input type="password" id="password" autocomplete="off" placeholder="Enter Password..." name="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" required>

            <label class="text-white" for="phone" class="mb-1 mt-3">Phone</label>
            <input type="text" id="phone" name="phone" autocomplete="off" placeholder="Enter Phone..." class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" required>

            <label class="text-white" for="gender" class="mb-1 mt-3">Gender</label>
            <select id="gender" name="gender" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>

            <label class="text-white" for="profile_image" class="mb-1 mt-3">Profile Image</label>
            <input type="file" id="profile_image" name="profile_image" class="text-white w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" required>

            <!-- Your existing form fields -->

            <div class="mt-6">
                <button type="submit" class="w-full text-white p-3 rounded focus:outline-none focus:shadow-outline-blue" style="background-color: #240e4d;">
                    Register
                </button>
            </div>
        </form>
    </div>

    <!-- Your existing script and styling imports -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: '#ffffff'
                },
                shape: {
                    type: 'circle',
                    stroke: {
                        width: 0,
                        color: '#000000'
                    },
                    polygon: {
                        nb_sides: 5
                    },
                    image: {
                        src: 'img/github.svg',
                        width: 100,
                        height: 100
                    }
                },
                opacity: {
                    value: 0.5,
                    random: false,
                    anim: {
                        enable: false,
                        speed: 1,
                        opacity_min: 0.1,
                        sync: false
                    }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: {
                        enable: false,
                        speed: 40,
                        size_min: 0.1,
                        sync: false
                    }
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#ffffff',
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 6,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false,
                    attract: {
                        enable: false,
                        rotateX: 600,
                        rotateY: 1200
                    }
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'grab'
                    },
                    onclick: {
                        enable: true,
                        mode: 'push'
                    },
                    resize: true
                },
                modes: {
                    grab: {
                        distance: 140,
                        line_linked: {
                            opacity: 1
                        }
                    },
                    bubble: {
                        distance: 400,
                        size: 40,
                        duration: 2,
                        opacity: 8,
                        speed: 3
                    },
                    repulse: {
                        distance: 200,
                        duration: 0.4
                    },
                    push: {
                        particles_nb: 4
                    },
                    remove: {
                        particles_nb: 2
                    }
                }
            },
            retina_detect: true
        });
    </script>
</body>

</html>