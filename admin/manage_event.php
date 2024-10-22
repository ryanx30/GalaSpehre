<?php
include '../config.php';
session_start();

// Cek jika pengguna bukan admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Cek koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$editing = false;
$event = [
    'name' => '',
    'description' => '',
    'date' => '',
    'time' => '',
    'location' => '',
    'price' => '',
    'max_participants' => '',
    'image' => '',
    'status' => 'open'
];

// Jika mengedit event, ambil data event berdasarkan ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitasi input
    $sql = "SELECT * FROM events WHERE id = $id";
    $result = $conn->query($sql);
    $event = $result->fetch_assoc();
    
    if (!$event) {
        echo "Event tidak ditemukan!";
        exit();
    }
    $editing = true;
}

// Update atau tambah event
if (isset($_POST['save_event'])) {
    // Sanitasi dan ambil data dari POST
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);
    $location = $conn->real_escape_string($_POST['location']);
    $price = str_replace('.', '', $_POST['price']);
    $max_participants = intval($_POST['max_participants']);
    $image = $conn->real_escape_string($_POST['image']);
    $status = $conn->real_escape_string($_POST['status']);

    if ($editing) {
        // Update event
        $updateQuery = "UPDATE events SET 
            name = '$name',
            description = '$description',
            date = '$date',
            time = '$time',
            location = '$location',
            price = '$price',
            max_participants = $max_participants,
            image = '$image',
            status = '$status'
            WHERE id = $id";

        if ($conn->query($updateQuery) === TRUE) {
            echo "Event berhasil diperbarui!";
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Tambah event baru
        $insertQuery = "INSERT INTO events (name, description, date, time, location, price, max_participants, image, status) VALUES 
            ('$name', '$description', '$date', '$time', '$location', '$price', $max_participants, '$image', '$status')";

        if ($conn->query($insertQuery) === TRUE) {
            echo "Event berhasil ditambahkan!";
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $editing ? 'Edit' : 'Tambah'; ?> Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/admin_event.css">
    <script>
        // Function to format currency
        function formatRupiah(angka) {
            let reverse = angka.replace(/[^0-9]/g, '').toString().split('').reverse().join(''),
                ribuan = reverse.match(/.{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return ribuan;
        }

        // Event listener for price input
        function updatePriceInput(input) {
            let formattedValue = formatRupiah(input.value);
            input.value = formattedValue;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1><?php echo $editing ? 'Edit' : 'Tambah'; ?> Event</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Nama Event:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi:</label>
                <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="date">Tanggal:</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo $event['date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="time">Waktu:</label>
                <input type="time" class="form-control" id="time" name="time" value="<?php echo $event['time']; ?>" required>
            </div>

            <div class="form-group">
                <label for="location">Lokasi:</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
            </div>

            <div class="form-group">
                <label for="price">Harga (Rupiah):</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo number_format($event['price'], 0, ',', '.'); ?>" oninput="updatePriceInput(this)" required> <!-- Format price with PHP and call JavaScript -->
            </div>

            <div class="form-group">
                <label for="max_participants">Maksimal Peserta:</label>
                <input type="number" class="form-control" id="max_participants" name="max_participants" value="<?php echo $event['max_participants']; ?>" required>
            </div>

            <div class="form-group">
                <label for="image">URL Gambar:</label>
                <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($event['image']); ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="open" <?php echo ($event['status'] == 'open') ? 'selected' : ''; ?>>Open</option>
                    <option value="closed" <?php echo ($event['status'] == 'closed') ? 'selected' : ''; ?>>Closed</option>
                    <option value="canceled" <?php echo ($event['status'] == 'canceled') ? 'selected' : ''; ?>>Canceled</option>
                </select>
            </div>

            <button type="submit" name="save_event" class="btn btn-primary"><?php echo $editing ? 'Update' : 'Tambah'; ?> Event</button>
        </form>
    </div>
</body>
</html>
