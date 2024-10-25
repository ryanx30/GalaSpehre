<?php
// Koneksi ke database
include '../config.php';

// Query untuk mengambil data tiket
$query = "SELECT * FROM events";
$result = $conn->query($query);

// Initialize filter variables
$month = isset($_GET['month']) ? $_GET['month'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';

// Convert month name to month number
$months = [
    'January' => 1,
    'February' => 2,
    'March' => 3,
    'April' => 4,
    'May' => 5,
    'June' => 6,
    'July' => 7,
    'August' => 8,
    'September' => 9,
    'October' => 10,
    'November' => 11,
    'December' => 12,
];

// Initialize the base query
$query_upcoming = "SELECT id, name, description, date, time, location, total_guests_attending, price, image FROM events WHERE date > CURDATE()";

// Apply filters
if ($month && isset($months[$month])) {
    $month_number = $months[$month]; // Convert month name to number
    $query_upcoming .= " AND MONTH(date) = $month_number";
}

if ($location) {
    $query_upcoming .= " AND location = '$location'";
}

if ($price) {
    list($min_price, $max_price) = explode('-', $price);
    $query_upcoming .= " AND price BETWEEN $min_price AND $max_price";
}

$query_upcoming .= " ORDER BY date ASC";
$result_upcoming = mysqli_query($conn, $query_upcoming);
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
                        <a href="tickets.php" class="logo">Gala<em>Sphere</em></a>
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav" id="nav">
                            <li><a href="view_events.php">Home</a></li>
                            <li><a href="shows_events.php">Shows & Events</a></li>
                            <li><a href="tickets.php" class="active">Tickets</a></li>
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
                    <h2>Tickets On Sale Now!</h2>
                    <span>Check out upcoming and past shows & events and grab your ticket right now.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="tickets-page">
        <div class="container">
            <div class="search-box mt-3">
                <form id="subscribe" action="" method="get">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="search-heading">
                                <h4>Sort The Upcoming Shows & Events By:</h4>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col-lg-3">
                                    <select name="month" id="month" class="form-control">
                                        <option value="">Select Month</option>
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="location" id="location" class="form-control">
                                        <option value="">Select Location</option>
                                        <option value="Jakarta">Jakarta</option>
                                        <option value="Tangerang Selatan">Tangerang Selatan</option>
                                        <option value="Tangerang">Tangerang</option>
                                        <option value="US">US</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="price" id="price" class="form-control">
                                        <option value="">Select Price Range</option>
                                        <option value="0-100000">Rp. 0 - Rp. 100,000</option>
                                        <option value="100000-500000">Rp. 100,000 - Rp. 500,000</option>
                                        <option value="500000-1000000">Rp. 500,000 - Rp. 1,000,000</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <button type="submit" id="form-submit" class="btn btn-dark">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="event-list mt-5">
                <div class="row">
                    <?php
                    if (mysqli_num_rows($result_upcoming) > 0) {
                        while ($event = mysqli_fetch_assoc($result_upcoming)) {
                            // Mengatur href berdasarkan ID event
                            $eventId = $event['id'];
                            $href = "ticketdetails" . $eventId . ".php";

                            // Pengecekan nilai max_participants
                            $maxParticipants = isset($event['max_participants']) ? $event['max_participants'] : 0;
                            $ticketsLeft = $maxParticipants > 0 ? ($maxParticipants - $event['total_guests_attending']) : "Unknown";

                            echo '<div class="col-lg-4">
                    <div class="ticket-item">
                        <div class="thumb">
                            <img src="' . $event['image'] . '" alt="" class="img-fluid"> <!-- Mengambil gambar dari database -->
                            <div class="price">
                                <span>1 ticket<br>from <em>Rp ' . number_format($event['price'], 0, ',', '.') . '</em></span>
                            </div>
                        </div>
                        <div class="down-content">
                            <span>There Are ' . ($ticketsLeft != "Unknown" ? $ticketsLeft . ' Tickets Left For This Show' : 'Unknown Tickets Left For This Show') . '</span>
                            <h4>' . $event['name'] . '</h4>
                            <ul>
                                <li><i class="fa fa-clock-o"></i> ' . date('l', strtotime($event['date'])) . ': ' . date('h:i A', strtotime($event['time'])) . '</li>
                                <li><i class="fa fa-map-marker"></i>' . $event['location'] . '</li>
                            </ul>
                            <div class="main-dark-button">
                                <a href="' . $href . '">Purchase Tickets</a>
                            </div>
                        </div>
                    </div>
                </div>';
                        }
                    } else {
                        echo '<div class="col-lg-12"><h4>No upcoming events found matching your criteria.</h4></div>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- ***** Buy Tickets ***** -->
        <div class="col-lg-12">
            <div class="heading">
                <h2>Buy Tickets</h2>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    while ($event = $result->fetch_assoc()) {
                        // Mengatur href berdasarkan ID event
                        $eventId = $event['id'];
                        $href = "ticketdetails" . $eventId . ".php";

                        echo '<div class="col-lg-4">
                        <div class="ticket-item">
                            <div class="thumb">
                                <img src="' . $event['image'] . '" alt="">
                                <div class="price">
                                <span>1 ticket<br>from <em>Rp ' . number_format($event['price'], 0, ',', '.') . '</em></span>
                                </div>
                            </div>
                            <div class="down-content">
                                <span>There Are ' . ($event['max_participants'] - $event['total_guests_attending']) . ' Tickets Left For This Show</span>
                                <h4>' . $event['name'] . '</h4>
                                <ul>
                                    <li><i class="fa fa-clock-o"></i> ' . date('l', strtotime($event['date'])) . ': ' . date('h:i A', strtotime($event['time'])) . '</li>
                                    <li><i class="fa fa-map-marker"></i>' . $event['location'] . '</li>
                                </ul>
                                <div class="main-dark-button">
                                    <a href="' . $href . '">Purchase Tickets</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                    }
                } else {
                    echo '<div class="col-lg-12"><h4>No upcoming events found.</h4></div>';
                }
                ?>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="pagination">
                <ul>
                    <li><a href="#">Prev</a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">Next</a></li>
                </ul>
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
    <script src="assets/js/custom.js"></script>

</body>

</html>