<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets\css\style.css">
</head>
<body>
    <!-- Navbar with Search and Location -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <h1 class="navbar-brand" href="#">GalaSphere</h1>
        <form class="form-inline my-2 my-lg-0 ml-auto">
            <input class="form-control mr-sm-2" type="search" placeholder="Search events" aria-label="Search">
            <select class="form-control mr-sm-2">
                <option selected>Jakarta Selatan</option>
                <option>Jakarta Barat</option>
                <option>Jakarta Timur</option>
            </select>
            <button class="btn btn-outline-danger my-2 my-sm-0" type="submit">Search</button>
        </form>
        <a href="login.php" class="btn btn-login">Login</a>
            <a href="register.php" class="btn btn-signup">Sign Up</a>
    </nav>

    <!-- Main Banner with Image Carousel -->
    <div id="eventCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="assets\images\BoyzIIMen_Solo_2024.jpg" class="d-block w-100" alt="Boyz II Men Solo">
            <div class="carousel-caption d-none d-md-block">
                <h5>BOYZ II MEN SOLO</h5>
                <p>Spooky entertainment awaits!</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets\images\TheDreamReBORN_2024.jpg" class="d-block w-100" alt="The Dream ReBORN">
            <div class="carousel-caption d-none d-md-block">
                <h5>THE DREAM REBORN</h5>
                <p>World Tour 2024!</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/images/event_placeholder.png" class="d-block w-100" alt="Event 3">
            <div class="carousel-caption d-none d-md-block">
                <h5>KOOKY OR SPOOKY</h5>
                <p>We've got just the thing for you!</p>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#eventCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#eventCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>


    <!-- Event Categories Section -->
    <div class="container my-5">
        <div class="row text-center">
            <div class="col">
                <div class="icon-circle">
                    <img src="https://via.placeholder.com/50" alt="Music">
                </div>
                <p>Music</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="https://via.placeholder.com/50" alt="Nightlife">
                </div>
                <p>Nightlife</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="https://via.placeholder.com/50" alt="Performing Arts">
                </div>
                <p>Performing Arts</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="https://via.placeholder.com/50" alt="Halloween">
                </div>
                <p>Halloween</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="https://via.placeholder.com/50" alt="Dating">
                </div>
                <p>Dating</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="https://via.placeholder.com/50" alt="Hobbies">
                </div>
                <p>Hobbies</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="https://via.placeholder.com/50" alt="Business">
                </div>
                <p>Business</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="https://via.placeholder.com/50" alt="Food & Drink">
                </div>
                <p>Food & Drink</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap and JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
