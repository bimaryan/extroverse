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
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Validasi data
        if (empty($email) || empty($password)) {
            echo "<script>Swal.fire('Error', 'Isi semua field.', 'error');</script>";
        } else {
            // Koneksi ke database (gunakan file koneksi Anda)
            require_once "../../db.php";

            // Sanitasi data
            $email = mysqli_real_escape_string($koneksi, $email);

            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($koneksi, $sql);

            if ($result) {
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);
                    if (password_verify($password, $row['password'])) {
                        // Login berhasil
                        $_SESSION["user_id"] = $row["user_id"];
                        $_SESSION["username"] = $row["username"];
                        $_SESSION["role"] = $row["role"];
                        $_SESSION['email'] = $email;

                        // Redirect based on user role
                    if ($_SESSION["role"] === 'admin') {
                        header("Location: ../../admin/");
                        exit();
                    } else {
                        header("Location: ../../dashboard/");
                        exit();
                    }
                    } else {
                        echo "<script>Swal.fire('Error', 'Kata sandi salah.', 'error');</script>";
                    }
                } else {
                    echo "<script>Swal.fire('Error', 'Email tidak ditemukan.', 'error');</script>";
                }
            } else {
                echo "<script>Swal.fire('Error', 'Terjadi kesalahan dalam pengolahan permintaan.', 'error');</script>";
            }

            mysqli_close($koneksi);
        }
    }
    ?>

    <div id="particles-js" class="absolute top-0 left-0 w-full h-full"></div>
    <div class="container mx-auto p-5 mt-16 relative z-10" style="background-color: rgba(0, 0, 0, 0.5); width: 350px; border-radius: 15px; box-shadow: 8px 8px 5px 0px rgba(0, 0, 0, 0.25);">
        <div class="text-center">
            <img src="http://localhost/extroverse/img/extroverse.png" style="width: 110px;" alt="Avatar" class="mx-auto">
            <hr>
            <h2 class="text-lg text-white font-semibold">L O G I N</h2>
        </div>
        <form class="max-w-sm mx-auto mt-2" method="POST">
            <label class="text-white" id="email" for="email" class="mb-1">Email</label>
            <input type="email" id="email" name="email" autocomplete="off" placeholder="Masukkan Email..." name="email" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" required>

            <label class="text-white" id="password" for="password" class="mb-1 mt-3">Password</label>
            <input type="password" id="password" autocomplete="off" placeholder="Masukkan Password..." name="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500" required>

            <div class="flex items-center justify-between mt-3">
                <label class="flex items-center space-x-2 text-white">
                    <input type="checkbox" checked="checked" name="remember" class="form-checkbox text-blue-500 focus:outline-none focus:border-blue-500">
                    <span class="text-sm">Remember me</span>
                </label>
                <span class="text-sm"><a href="../reset/" class="text-blue-500">Forgot password?</a></span>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full text-white p-3 rounded focus:outline-none focus:shadow-outline-blue" style="background-color: #240e4d;">
                    Login
                </button>
            </div>
        </form>

        <div class="text-center text-white mt-5">Don't have an Extroverse account? <a href="../register/" class="text-blue-500">Registration</a></div>
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