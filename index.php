<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets\css\style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titan+One&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navbar with Search and Location -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light py-3">
        <a class="navbar-brand" href="index.php">GalaSphere</a>
        
        <!-- Search and Location -->
        <form class="form-inline mx-auto">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="search-addon">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
                <input class="form-control" type="search" placeholder="Search events" aria-label="Search">
            </div>

            <div class="input-group ml-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="location-addon">
                        <i class="fa fa-map-marker-alt"></i>
                    </span>
                </div>
                <select class="form-control">
                    <option selected>Jakarta Selatan</option>
                    <option>Jakarta Barat</option>
                    <option>Jakarta Timur</option>
                    <option>Jakarta Pusat</option>
                    <option>Jakarta Utara</option>
                </select>
            </div>
            <button class="btn btn-danger ml-2" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </form>

        <!-- Navbar Links -->
        <div class="navbar-nav">
            <a href="find-events.php" class="nav-link">Find Events</a>
            <a href="create-events.php" class="nav-link">Create Events</a>
            <a href="help.php" class="nav-link">Help Center</a>
            <a href="tickets.php" class="nav-link">Find My Tickets</a>
            <a href="login.php" class="btn btn-outline-secondary ml-3">Log In</a>
            <a href="register.php" class="btn btn-primary ml-2">Sign Up</a>
        </div>
    </nav>
</body>
</html>


    <!-- Main Banner with Image Carousel -->
    <div id="eventCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="assets\images\BoyzIIMen_Solo_2024.jpg" class="d-block w-100" alt="Boyz II Men Solo">
        </div>
        <div class="carousel-item">
            <img src="assets/images/TheDreamReBORN_2024.jpg" class="d-block w-100" alt="The Dream ReBORN">
        </div>
        <div class="carousel-item">
            <img src="assets\images\VoiceAgainstReason.jpg" class="d-block w-100" alt="Voice Against Reason">
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
                    <img src="assets\images\musical-notes.png" alt="Music">
                </div>
                <p class="icon-text">Music</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="assets\images\disco-ball.png" alt="Nightlife">
                </div>
                <p class="icon-text">Nightlife</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="assets\images\theatre.png" alt="Performing Arts">
                </div>
                <p class="icon-text">Performing Arts</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="assets\images\pumpkin.png" alt="Halloween">
                </div>
                <p class="icon-text">Halloween</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="assets\images\game-console.png" alt="Hobbies">
                </div>
                <p class="icon-text">Hobbies</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="assets\images\cosmetics.png" alt="Fashion & Beauty">
                </div>
                <p class="icon-text">Fashion & Beauty</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="assets\images\training.png" alt="Conferences & Seminars">
                </div>
                <p class="icon-text">Conferences & Seminars</p>
            </div>
            <div class="col">
                <div class="icon-circle">
                    <img src="assets\images\family.png" alt="Family & Kids">
                </div>
                <p class="icon-text">Family & Kids</p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
