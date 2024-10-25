<?php
require_once '../config.php';

// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "User ID belum diatur dalam session.";
    exit;
}

// Ambil data event dengan ID
$event_id = 7; // Ganti dengan ID event yang sesuai
$query_event = "SELECT * FROM events WHERE id = ?";
$stmt_event = $conn->prepare($query_event);
$stmt_event->bind_param("i", $event_id);
$stmt_event->execute();
$result_event = $stmt_event->get_result();
$event = $result_event->fetch_assoc();

// Cek jika event ditemukan
if ($event) {
    // Ambil URL gambar dan total tamu yang hadir dari event
    $event_image = $event['image'];
    $max_participants = $event['max_participants'];
    $total_guests_attending = $event['total_guests_attending'];

    // Hitung jumlah tiket yang tersisa
    $tickets_available = $max_participants - $total_guests_attending;
} else {
    // Jika event tidak ditemukan, bisa mengatur gambar default
    $event_image = '../assets/images/default.jpg';
}

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $tickets_purchased = $_POST['quantity'];

    // Cek jika user_id ada di tabel users
    $query_user = "SELECT * FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($query_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows === 0) {
        exit;
    } else {
        $user = $result_user->fetch_assoc();
    }

    // Cek apakah jumlah tiket yang ingin dibeli melebihi tiket yang tersedia
    if ($tickets_purchased > $tickets_available) {
        echo "Jumlah tiket yang ingin dibeli melebihi tiket yang tersedia.";
    } else {
        // Mulai transaksi
        mysqli_begin_transaction($conn);

        try {
            // 1. Update total guests attending di tabel events
            $query_update_event = "UPDATE events SET total_guests_attending = total_guests_attending + ? WHERE id = ?";
            $stmt_update_event = $conn->prepare($query_update_event);
            $stmt_update_event->bind_param("ii", $tickets_purchased, $event_id);
            $stmt_update_event->execute();

            // 2. Insert ke tabel registrations
            $query_insert_registration = "INSERT INTO registration (event_id, user_id, tickets_purchased, registration_date) VALUES (?, ?, ?, NOW())";
            $stmt_insert_registration = $conn->prepare($query_insert_registration);
            $stmt_insert_registration->bind_param("iii", $event_id, $user_id, $tickets_purchased);
            $stmt_insert_registration->execute();

            // Commit transaksi
            mysqli_commit($conn);

            // Tampilkan modal pop-up
            echo '<script>
                    document.getElementById("successModal").style.display = "block";
                </script>';
        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            mysqli_rollback($conn);
            echo "Pembelian tiket gagal: " . $e->getMessage();
        }
    }
}
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
    <link rel="stylesheet" type="text/css" href="../assets/css/owl-carousel.css">
    <link rel="stylesheet" href="../assets/css/tooplate.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">
    <link rel="stylesheet" href="../user/css/ticketdetails.css">
</head>


<body>
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <a href="view_events.php" class="logo">Gala<em>Sphere</em></a>
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

    <div class="ticket-details-page">
        <div class="container">
            <form method="POST" action="">
                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="left-image">
                            <img src="<?php echo $event_image; ?>" alt="<?php echo htmlspecialchars($event['name']); ?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="right-content">
                            <h4><?php echo htmlspecialchars($event['name']); ?></h4>
                            <span><?php echo ($event['max_participants'] - $total_guests_attending); ?> Tiket tersedia</span>
                            <ul>
                                <li><i class="fa fa-clock-o"></i> <?php echo htmlspecialchars($event['date']); ?> at <?php echo htmlspecialchars($event['time']); ?></li>
                                <li><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($event['location']); ?></li>
                            </ul>
                            <div class="quantity-content">
                                <div class="left-content">
                                    <h6>Tiket Standar</h6>
                                    <p>Rp<?php echo number_format($event['price'], 0, ',', '.'); ?> per tiket</p>
                                </div>
                                <div class="right-content">
                                    <div class="quantity buttons_added">
                                        <input type="button" value="-" class="minus" onclick="changeQuantity(-1)">
                                        <input type="number" step="1" min="1" max="10" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" id="quantity-input">
                                        <input type="button" value="+" class="plus" onclick="changeQuantity(1)">
                                    </div>
                                </div>
                            </div>
                            <div class="total">
                                <h4>Total: Rp<h4 id="total-price"><?php echo number_format($event['price'], 0, ',', '.'); ?></h4>
                                </h4>
                                <div class="main-dark-button">
                                    <input type="submit" value="Beli Tiket" class="purchase-button">
                                </div>
                            </div>
                            <div class="warn">
                                <p>*Anda hanya dapat membeli 10 tiket untuk acara ini</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-dark-button" style="margin-top: 20px;">
                    <a href="shows_events.php" style="color: white; text-decoration: none; padding: 10px 20px;">Back to Shows & Events</a>
                </div>
            </form>
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
                                        <li><a href="view_events.php">Home</a></li>
                                        <li><a href="shows_events.php">Shows & Events</a></li>
                                        <li><a href="tickets.php" class="active">Tickets</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal untuk Pembelian Tiket Sukses -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Pembelian Tiket Berhasil!</h2>
            <p>Tiket Anda telah berhasil dibeli. Terima kasih!</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/jquery-2.1.0.min.js"></script>
    <script src="../assets/js/owl-carousel.js"></script>
    <script src="../assets/js/scrollreveal.min.js"></script>
    <script src="../assets/js/waypoints.min.js"></script>
    <script src="../assets/js/jquery.counterup.min.js"></script>
    <!-- Menu Toggle Script -->
    <script>
        $(document).ready(function() {
            // Menu Trigger
            $('.menu-trigger').click(function() {
                $('.main-nav .nav').toggleClass('active'); // Toggle active class for menu
            });
        });
    </script>
    <script src="../assets/js/custom.js"></script>

    <script>
        let isUpdating = false;

        function changeQuantity(amount) {
            if (isUpdating) return;
            isUpdating = true;

            const quantityInput = document.getElementById('quantity-input');
            let currentQuantity = parseInt(quantityInput.value);
            const maxTickets = 10;

            currentQuantity += amount;

            // Pastikan tidak melebihi batas dan tidak kurang dari 1
            if (currentQuantity > maxTickets) {
                currentQuantity = maxTickets;
            } else if (currentQuantity < 1) {
                currentQuantity = 1;
            }

            quantityInput.value = currentQuantity;

            // Update total price
            const ticketPrice = <?php echo $event['price']; ?>;
            document.getElementById('total-price').innerText = formatRupiah(ticketPrice * currentQuantity);

            // Debug log
            console.log("Current Quantity: ", currentQuantity);

            // Reset isUpdating setelah delay
            setTimeout(() => {
                isUpdating = false;
            }, 100);
        }

        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function showModal(quantity, totalPrice) {
            const modalContent = document.getElementById("modal-content");
            modalContent.innerHTML = `Jumlah tiket: ${quantity} <br> Total harga: ${formatRupiah(totalPrice)}`;
            document.getElementById("successModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("successModal").style.display = "none";
        }
    </script>
</body>

</html>