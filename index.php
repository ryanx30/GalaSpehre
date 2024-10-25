<?php
// Menghubungkan ke database
include 'config.php';

// Mengambil semua event dari database
$query = "SELECT * FROM events";
$result = $conn->query($query);

// Memeriksa apakah ada event
if ($result->num_rows > 0) {
    // Membuat array untuk menyimpan nama event
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    // Memilih event secara acak
    $randomEvent = $events[array_rand($events)];
} else {
    $randomEvent = null; // Jika tidak ada event
}

// Mengambil data acara untuk ditampilkan di carousel
$sqlCarousel = "SELECT * FROM events WHERE status = 'open'";
$resultCarousel = $conn->query($sqlCarousel);

// Mengambil data acara untuk bagian sales event dengan harga terendah
$sqlSalesEvent = "SELECT * FROM events WHERE status = 'open' ORDER BY price ASC LIMIT 3";
$resultSalesEvent = $conn->query($sqlSalesEvent);

// Mengambil data acara untuk bagian coming events
$sqlComingEvents = "SELECT * FROM events WHERE status = 'open' ORDER BY date ASC, time ASC LIMIT 3";
$resultComingEvents = $conn->query($sqlComingEvents);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tooplate">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">

    <title>GalaSphere</title>


    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/owl-carousel.css">
    <link rel="stylesheet" href="./assets/css/tooplate.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
</head>

<body>

    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <a href="index.php" class="logo">Gala<em>Sphere</em></a>
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav" id="nav">
                            <li><a href="index.php" class="active">Home</a></li>
                            <li><a href="register.php">Shows & Events</a></li>
                            <li><a href="register.php">Tickets</a></li>
                            <li><a href="./admin/admin_login.php">Admin</a></li>
                            <a href="login.php" class="btn btn-outline-secondary ml-2 mr-2">Log In</a>
                            <div class="main-dark-button">
                                <a href="register.php" class="sign-button">Sign up</a>
                            </div>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-content">
                        <h6>All Events Promotion in 2024</h6>
                        <h2><?php echo $randomEvent ? $randomEvent['name'] : 'No Event Available'; ?></h2>
                        <div class="main-white-button">
                            <a href="register.php">register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="show-events-carousel">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-show-events owl-carousel">
                        <?php while ($event = $resultCarousel->fetch_assoc()) { ?>
                            <div class="item">
                                <img src="<?php echo $event['image']; ?>" alt="<?php echo $event['name']; ?>">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- *** Venues & Tickets ***-->
    <div class="venue-tickets">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>SALES</h2>
                    </div>
                </div>
                <?php
                // Menampilkan event dengan harga terendah
                if ($resultSalesEvent->num_rows > 0) {
                    while ($event = $resultSalesEvent->fetch_assoc()) { ?>
                        <div class="col-lg-4">
                            <div class="venue-item">
                                <div class="thumb">
                                    <img src="<?php echo $event['image']; ?>" alt="<?php echo $event['name']; ?>">
                                </div>
                                <div class="down-content">
                                    <div class="left-content">
                                        <div class="main-white-button">
                                            <a href="register.php">Register</a>
                                        </div>
                                    </div>
                                    <div class="right-content">
                                        <h4><?php echo $event['name']; ?></h4>
                                        <p><?php echo $event['description']; ?></p>
                                        <ul>
                                            <li><i class="fa fa-user"></i><?php echo $event['max_participants']; ?></li>
                                            <li><i class="fa fa-money"></i>Rp <?php echo number_format($event['price'], 0, ',', '.'); ?></li>
                                        </ul>
                                        <div class="price">
                                            <span>1 ticket<br>from <em>Rp <?php echo number_format($event['price'], 0, ',', '.'); ?></em></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php }
                } else {
                    echo '<div class="col-lg-12">No Sales Events Available</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- *** Coming Events ***-->
    <div class="coming-events">
        <div class="left-button">
            <div class="main-white-button">
                <a href="register.php">Discover More</a>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php
                // Reset pointer dan ambil data lagi untuk coming events
                $resultComingEvents->data_seek(0); // Kembali ke awal
                while ($event = $resultComingEvents->fetch_assoc()) { ?>
                    <div class="col-lg-4">
                        <div class="event-item">
                            <div class="thumb">
                                <img src="<?php echo $event['image']; ?>" alt="<?php echo $event['name']; ?>">
                            </div>
                            <div class="down-content">
                                <h4><?php echo $event['name']; ?></h4>
                                <ul>
                                    <li><i class="fa fa-clock-o"></i> <?php echo $event['date'] . ' ' . $event['time']; ?></li>
                                    <li><i class="fa fa-map-marker"></i> <?php echo $event['location']; ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="address">
                        <h4>Kelompok 8</h4>
                        <span>Universitas<br>Multimedia<br>Nusantara</span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="hours"></div>
                </div>
                <div class="col-lg-4">
                    <div class="links">
                        <h4>About Us</h4>
                        <ul>
                            <li><a href="https://www.instagram.com/imanuel.vin/" target="_blank">Imanuel Calvin</a></li>
                            <li><a href="https://www.instagram.com/m.rahadiann/" target="_blank">Muhammad Rahadian</a></li>
                            <li><a href="https://www.instagram.com/irsal_ginanjar/" target="_blank">Muhammad Irsal Ginanjar</a></li>
                            <li><a href="https://www.instagram.com/mnoppall_/" target="_blank">Muhammad Naufal</a></li>
                            <li><a href="https://www.instagram.com/mklrzqy/" target="_blank">Mikail Rizqy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="under-footer">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <p>Gading Serpong, Tangerang Selatan</p>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <p class="copyright">Copyright 2024 GalaSphere Company
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="sub-footer">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="logo"><span>Gala<em>Sphere</em></span></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="menu">
                                    <ul>
                                        <li><a href="index.php">Home</a></li>
                                        <li><a href="index.php">Shows & Events</a></li>
                                        <li><a href="index.php" class="active">Tickets</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="assets/js/jquery-2.1.0.min.js"></script>
    <!-- Bootstrap -->
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Plugins -->
    <script src="assets/js/scrollreveal.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    <script src="assets/js/imgfix.min.js"></script>
    <script src="assets/js/accordions.js"></script>
    <script src="assets/js/owl-carousel.js"></script>

    <!-- Menu Toggle Script -->
    <script>
        $(document).ready(function() {
            // Menu Trigger
            $('.menu-trigger').click(function() {
                $('.main-nav .nav').toggleClass('active'); // Toggle active class for menu
            });
        });
    </script>

    <!-- Global Init -->
    <script src="assets/js/custom.js"></script>

</body>

</html>

<?php
$conn->close();
?>