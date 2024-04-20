<?php

include 'connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <div class="flex">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container">
                    <h1> <a class="navbar-brand logo fw-bolder" href="#"> <span>Spea</span>Kora.</a></h1>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex justify-content-evenly">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                            </li>

                            <form class="d-flex justify-content-center" role="search">
                                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn btn-search btn-outline-success" type="submit">Search</button>
                            </form>


                        </ul>



                        <div class="d-flex align-items-center">
                            <?php
                            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                            $select_profile->execute([$user_id]);
                            if ($select_profile->rowCount() > 0) {
                                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                            ?>
                                <div class="nav-item dropdown">
                                    <a style="color: #7431f9" class="nav-link dropdown-toggle text-capitalize fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= $fetch_profile['name']; ?>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="profile.php">profile</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a href="user_logout.php" onclick="return confirm('logout from this website?');" class="dropdown-item" href="#">logout</a></li>
                                    </ul>
                                </div>
                                <div style="color: #7431f9;" id="user-btn" class="fas fa-user mx-3"></div>

                            <?php
                            } else {
                            ?>
                                <div id="user-btn" class="fas fa-user mx-3"></div>
                            <?php
                            }
                            ?>



                        </div>




                    </div>
                </div>
            </nav>

        </div>
    </header>
    <main>
        <div class="container py-3 pb-5">

            <div class="row g-0 text-center">
                <div class="col-6 col-md-4">user profile</div>
                <div class="col-sm-6 col-md-8">
                    <h1 class="heading py-3">latest posts</h1>
                    <div class="post-box">
                        <div class="post-heading">
                            <div class="post-user-img">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="post-info">
                                <p class="post-user-name">
                                    Achouak Cherif
                                </p>
                                <p class="post-pub-date">
                                    2024-04-20
                                </p>
                            </div>

                        </div>
                        <div class="post-image">
                            <img src="./pic.jpg" alt="post img">
                        </div>
                        <div class="icons">


                            <button type="submit" name="like_post"><i class="fas fa-heart post-icon"></i><span>1</span></button>
                            <a href=""><i class="fas fa-comment post-icon"></i><span>1</span></a>
                        </div>
                        <div class="post-caption">
                            <p class="post-user-name">
                                Achouak Cherif
                            </p>
                            <p class="caption-text">Good Moring.</p>
                        </div>
                        <a href="" class="view-comments">View all comments</a>
                    </div>
                </div>

            </div>
        </div>

    </main>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>