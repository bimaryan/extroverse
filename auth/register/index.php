<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Unbounded">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Extroverse</title>
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
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $email = $_POST["email"]; // Add email field
        $password = $_POST["password"];

        // Validasi data
        if (empty($username) || empty($email) || empty($password)) {
            echo "<script>Swal.fire('Error', 'Isi semua field.', 'error');</script>";
        } elseif (strlen($username) < 5) {
            echo "<script>Swal.fire('Error', 'Username minimal 5 karakter.', 'error');</script>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>Swal.fire('Error', 'Email tidak valid.', 'error');</script>";
        } elseif (strlen($password) < 8) {
            echo "<script>Swal.fire('Error', 'Password minimal 8 karakter.', 'error');</script>";
        } else {
            // Koneksi ke database (gunakan file koneksi Anda)
            require_once "../../db.php";

            // Sanitasi data
            $username = mysqli_real_escape_string($koneksi, $username);
            $email = mysqli_real_escape_string($koneksi, $email);

            // Hash password sebelum menyimpannya di database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'pengguna')";

            if (mysqli_query($koneksi, $sql)) {
                $_SESSION["email"] = $email;
                echo "<script>Swal.fire('Success', 'Registration successful.', 'success').then(() => { window.location.href = '../login/'; });</script>";
            } else {
                echo "<script>Swal.fire('Error', 'Terjadi kesalahan dalam registrasi: " . mysqli_error($koneksi) . "', 'error');</script>";
            }

            mysqli_close($koneksi);
        }
    }
    ?>
    <div id="particles-js" class="absolute top-0 left-0 w-full h-full"></div>
    <div class="container mx-auto p-5 mt-16 relative z-10" style="background-color: rgba(0, 0, 0, 0.5); width: 350px; border-radius: 15px; box-shadow: 8px 8px 5px 0px rgba(0, 0, 0, 0.25);">
        <div class="text-center">
            <img src="http://localhost/extroverse/logo/extroverse.png" style="width: 110px;" alt="Avatar" class="mx-auto">
            <hr>
            <h2 class="text-lg font-semibold text-white">R E G I S T R A S I</h2>
        </div>
        <form action="" class="max-w-sm mx-auto mt-2" method="POST">
            <label class="text-white" for="username">Username</label>
            <input type="text" id="username" name="username" autocomplete="off" placeholder="Masukkan Username..." class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" required>

            <label class="text-white" for="email">Email</label>
            <input type="email" id="email" name="email" autocomplete="off" placeholder="Masukkan Email..." class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" required>

            <label class="text-white" for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="off" placeholder="Masukkan Password..." class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" required>
            <div class="mt-6">
                <button type="submit" class="w-full text-center text-white p-3 rounded focus:outline-none focus:shadow-outline-blue" style="background-color: #240e4d;">
                    Registration
                </button>
            </div>
        </form>
        <div class="text-center text-white mt-5">Already have an Extroverse account? <a href="../login/" class="text-blue-500">Login</a></div>
    </div>
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