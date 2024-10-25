<?php
// config.php - mengatur koneksi database
include '../config.php';

// Tentukan jumlah acara per halaman
$limit = 3;

// Ambil nomor halaman dari URL, default ke 1 jika tidak ada
$page_upcoming = isset($_GET['page_upcoming']) ? (int)$_GET['page_upcoming'] : 1;
$offset_upcoming = ($page_upcoming - 1) * $limit;

// Ambil total acara untuk menghitung total halaman untuk acara yang akan datang
$totalUpcomingQuery = "SELECT COUNT(*) as total FROM events WHERE date > CURDATE()";
$totalUpcomingResult = mysqli_query($conn, $totalUpcomingQuery);
$totalUpcoming = mysqli_fetch_assoc($totalUpcomingResult)['total'];
$totalUpcomingPages = ceil($totalUpcoming / $limit); // Total halaman acara yang akan datang

// Ambil acara yang akan datang dengan pagination
$query_upcoming = "SELECT id, name, description, date, time, location, total_guests_attending, image FROM events WHERE date > CURDATE() ORDER BY date ASC LIMIT $limit OFFSET $offset_upcoming";
$result_upcoming = mysqli_query($conn, $query_upcoming);

// Ambil nomor halaman dari URL untuk acara yang sudah berlalu, default ke 1 jika tidak ada
$page_past = isset($_GET['page_past']) ? (int)$_GET['page_past'] : 1;
$offset_past = ($page_past - 1) * $limit;

// Ambil total acara untuk menghitung total halaman untuk acara yang sudah berlalu
$totalPastQuery = "SELECT COUNT(*) as total FROM events WHERE date <= CURDATE()";
$totalPastResult = mysqli_query($conn, $totalPastQuery);
$totalPast = mysqli_fetch_assoc($totalPastResult)['total'];
$totalPastPages = ceil($totalPast / $limit); // Total halaman acara yang sudah berlalu

// Ambil acara yang sudah berlalu dengan pagination
$query_past = "SELECT id, name, description, date, time, location, total_guests_attending, image FROM events WHERE date <= CURDATE() ORDER BY date DESC LIMIT $limit OFFSET $offset_past";
$result_past = mysqli_query($conn, $query_past);
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
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/owl-carousel.css">
    <link rel="stylesheet" href="../assets/css/tooplate.css">
</head>

<body>
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <a href="shows_events.php" class="logo">Gala<em>Sphere</em></a>
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav" id="nav">
                            <li><a href="view_events.php">Home</a></li>
                            <li><a href="shows_events.php" class="active">Shows & Events</a></li>
                            <li><a href="tickets.php">Tickets</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropbtn">Profile</a>
                                <div class="dropdown-content">
                                    <a href="profile.php">View My Profile</a>
                                    <a href="../logout.php">Logout</a>
                                </div>
                            </li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>


    <!-- ***** About Us Page ***** -->
    <div class="page-heading-shows-events">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Our Shows & Events</h2>
                    <span>Check out upcoming and past shows & events.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="shows-events-tabs">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row" id="tabs">
                        <div class="col-lg-12">
                            <div class="heading-tabs">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <ul>
                                            <li><a href='#tabs-1'>Upcoming</a></li>
                                            <li><a href='#tabs-2'>Past</a></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <section class='tabs-content'>
                                <article id='tabs-1'>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="heading">
                                                <h2>Upcoming</h2>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="sidebar">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="heading-sidebar">
                                                            <h4>Sort The Upcoming Shows & Events By:</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="month">
                                                            <h6>Month</h6>
                                                            <ul>
                                                                <li><a href="#">October</a></li>
                                                                <li><a href="#">November</a></li>
                                                                <li><a href="#">December</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="category">
                                                            <h6>Category</h6>
                                                            <ul>
                                                                <li><a href="#">Pop Music</a></li>
                                                                <li><a href="#">Rock Music</a></li>
                                                                <li><a href="#">Hip Hop Music</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="venues">
                                                            <h6>Location</h6>
                                                            <ul>
                                                                <li><a href="#">Jakarta</a></li>
                                                                <li><a href="#">Tangerang Selatan</a></li>
                                                                <li><a href="#">Tangerang</a></li>
                                                                <li><a href="#">US</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <?php while ($event = mysqli_fetch_assoc($result_upcoming)): ?>
                                                        <div class="event-item">
                                                            <div class="row">
                                                                <div class="col-lg-4">
                                                                    <div class="left-content">
                                                                        <h4><?php echo $event['name']; ?></h4>
                                                                        <p><?php echo $event['description']; ?></p>
                                                                        <div class="main-dark-button">
                                                                            <a href="ticketdetails<?php echo $event['id']; ?>.php?id=<?php echo $event['id']; ?>">Discover More</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="thumb">
                                                                        <img src="<?php echo $event['image']; ?>" alt="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="right-content">
                                                                        <ul>
                                                                            <li>
                                                                                <i class="fa fa-clock-o"></i>
                                                                                <h6><?php echo date("M d l", strtotime($event['date'])); ?><br><?php echo $event['time']; ?></h6>
                                                                            </li>
                                                                            <li>
                                                                                <i class="fa fa-map-marker"></i>
                                                                                <span><?php echo $event['location']; ?></span>
                                                                            </li>
                                                                            <li>
                                                                                <i class="fa fa-users"></i>
                                                                                <span><?php echo $event['total_guests_attending']; ?> Total Guests Attending</span>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endwhile; ?>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="pagination">
                                                        <ul>
                                                            <li><a href="?page_upcoming=<?php echo max(1, $page_upcoming - 1); ?>">Prev</a></li>
                                                            <?php for ($i = 1; $i <= $totalUpcomingPages; $i++): ?>
                                                                <li class="<?php echo ($i === $page_upcoming) ? 'active' : ''; ?>">
                                                                    <a href="?page_upcoming=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                </li>
                                                            <?php endfor; ?>
                                                            <li><a href="?page_upcoming=<?php echo min($totalUpcomingPages, $page_upcoming + 1); ?>">Next</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                <!-- ***** PAAAAAAAAAAAAAAASSSSSSSSSSSSSSSSSSSSSSTTTTTTTTTTTTTTTTTTTTT ***** -->
                                <article id='tabs-2'>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="heading">
                                                <h2>Past Events</h2>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="sidebar">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="heading-sidebar">
                                                            <h4>Sort The Past Shows & Events By:</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="month">
                                                            <h6>Month</h6>
                                                            <ul>
                                                                <li><a href="#">July</a></li>
                                                                <li><a href="#">Agustus</a></li>
                                                                <li><a href="#">September</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="category">
                                                            <h6>Category</h6>
                                                            <ul>
                                                                <li><a href="#">Pop Music</a></li>
                                                                <li><a href="#">Rock Music</a></li>
                                                                <li><a href="#">Hip Hop Music</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="venues">
                                                            <h6>Location</h6>
                                                            <ul>
                                                                <li><a href="#">Jakarta</a></li>
                                                                <li><a href="#">Tangerang Selatan</a></li>
                                                                <li><a href="#">Tangerang</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="row">
                                                <?php
                                                if (mysqli_num_rows($result_past) > 0) {
                                                    while ($event = mysqli_fetch_assoc($result_past)) {
                                                ?>
                                                        <div class="col-lg-12">
                                                            <div class="event-item">
                                                                <div class="row">
                                                                    <div class="col-lg-4">
                                                                        <div class="left-content">
                                                                            <h4><?php echo $event['name']; ?></h4>
                                                                            <p><?php echo $event['description']; ?></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <div class="thumb">
                                                                            <img src="<?php echo $event['image']; ?>" alt="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <div class="right-content">
                                                                            <ul>
                                                                                <li>
                                                                                    <i class="fa fa-clock-o"></i>
                                                                                    <h6><?php echo date("F d, Y", strtotime($event['date'])); ?><br><?php echo $event['time']; ?></h6>
                                                                                </li>
                                                                                <li>
                                                                                    <i class="fa fa-map-marker"></i>
                                                                                    <span><?php echo $event['location']; ?></span>
                                                                                </li>
                                                                                <li>
                                                                                    <i class="fa fa-users"></i>
                                                                                    <span><?php echo $event['total_guests_attending']; ?> Total Guests Attending</span>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<p>No past events found.</p>";
                                                }
                                                ?>
                                                <div class="col-lg-12">
                                                    <div class="pagination">
                                                        <ul>
                                                            <li><a href="?page_past=<?php echo max(1, $page_past - 1); ?>">Prev</a></li>
                                                            <?php for ($i = 1; $i <= $totalPastPages; $i++): ?>
                                                                <li class="<?php echo ($i === $page_past) ? 'active' : ''; ?>">
                                                                    <a href="?page_past=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                </li>
                                                            <?php endfor; ?>
                                                            <li><a href="?page_past=<?php echo min($totalPastPages, $page_past + 1); ?>">Next</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- *** Footer *** -->
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
                            <li><a href="#">Imanuel Calvin</a></li>
                            <li><a href="#">Muhammad Rahadian</a></li>
                            <li><a href="#">Muhammad Naufal</a></li>
                            <li><a href="#">Muhammad Irsal</a></li>
                            <li><a href="#">Mikail Rizqy</a></li>
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
                                        <li><a href="view_events.php" class="active">Home</a></li>
                                        <li><a href="shows_events.php">Shows & Events</a></li>
                                        <li><a href="tickets.php">Tickets</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="social-links">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
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
    <script src="../assets/js/jquery-2.1.0.min.js"></script>
    <!-- Bootstrap -->
    <script src="../assets/js/popper.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- Plugins -->
    <script src="../assets/js/scrollreveal.min.js"></script>
    <script src="../assets/js/waypoints.min.js"></script>
    <script src="../assets/js/jquery.counterup.min.js"></script>
    <script src="../assets/js/imgfix.min.js"></script>
    <script src="../assets/js/accordions.js"></script>
    <script src="../assets/js/owl-carousel.js"></script>
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
    <script src="../assets/js/custom.js"></script>

</body>

</html>

<?php
$conn->close();
?>