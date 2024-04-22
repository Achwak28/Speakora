<?php

include 'connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

include 'like_post.php';

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

                            <form action="search.php" method="POST" class="d-flex justify-content-center" role="search">
                                <input class="form-control me-2" name="search_box" type="search" placeholder="Search" aria-label="Search">
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
        <div class="container-fluid container-md py-3 pb-5">

            <div class="row mb-3">
                <div style="height: 300px !important" id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner" style="height: 300px !important">
                        <div class="carousel-item active">
                            <img src="./images/slide2.jpg" class="d-block w-100" alt="slide1">
                        </div>
                        <div class="carousel-item">
                            <img src="./images/slide1.jpg" class="d-block w-100" alt="slide2">
                        </div>
                        <div class="carousel-item">
                            <img src="./images/slide3.jpg" class="d-block w-100" alt="slide3">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

            <div class="row g-0 text-center">
                <div class="col-sm-12 col-md-4 col-sm-12 side-user mt-5">
                    <?php
                    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                    $select_profile->execute([$user_id]);
                    if ($select_profile->rowCount() > 0) {
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    ?>
                        <div class="side-user-up">
                            <div class="side-user-img">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="side-user-info">
                                <p class="side-user-name text-capitalize fw-bold">
                                    <?= $fetch_profile['name']; ?>
                                </p>

                            </div>
                        </div>
                        <button class="side-update-profile"><a href="add_post.php">Create Post</a></button>
                    <?php
                    } else {
                    ?>
                        <button class="side-update-profile"><a href="login.php">Login to create posts!</a></button>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-sm-12 col-md-8 ">
                    <h1 class="heading py-3">latest posts</h1>
                    <div class="posts-container ">

                        <?php                                                                      
                        $select_posts = $conn->prepare("SELECT *, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date FROM `posts` LIMIT 6 ");
                        $select_posts->execute();
                        if ($select_posts->rowCount() > 0) {
                            while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {
                                $post_id = $fetch_posts['id'];

                                $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
                                $count_post_comments->execute([$post_id]);
                                $total_post_comments = $count_post_comments->rowCount();

                                $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
                                $count_post_likes->execute([$post_id]);
                                $total_post_likes = $count_post_likes->rowCount();

                                $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
                                $confirm_likes->execute([$user_id, $post_id]);


                        ?>
                                <div class="post-box">
                                    <div class="login-alert">

                                    </div>
                                    <div class="post-heading">

                                        <div class="post-user-img">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="post-info">
                                            <p class="post-user-name">
                                                <?= $fetch_posts['name']; ?>
                                            </p>
                                            <p class="post-pub-date">
                                                <?= $fetch_posts['formatted_date']; ?>
                                            </p>
                                        </div>

                                    </div>


                                    <div class="post-image">
                                        <?php
                                        if ($fetch_posts['image'] != '') {

                                        ?> <a href="post_detail.php?post_id=<?= $post_id; ?>">
                                                <img src="uploaded_img/<?= $fetch_posts['image']; ?>" class="post-image" alt="post img"></a>
                                        <?php
                                        }
                                        ?>

                                    </div>
                                    <div class="icons">
                                        <?php
                                        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                                        $select_profile->execute([$user_id]);
                                        if ($select_profile->rowCount() > 0) {
                                            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                            <form class="box-form" method="post" style="color: black">
                                                <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                                                <button style="color: black" type="submit" name="like_post"><i class="fas fa-heart" style="<?php if ($confirm_likes->rowCount() > 0) {
                                                                                                                                                echo 'color:var(--red);';
                                                                                                                                            } ?>  "></i><span><?= $total_post_likes; ?></span></button>
                                            </form>
                                        <?php
                                        } else {
                                        ?>

                                            <button class="like-post"><i style="color:black" class="fas fa-heart post-icon"></i><span><?= $total_post_likes; ?></span></button>
                                        <?php
                                        }
                                        ?>
                                        <a style="text-decoration:none; color: black" href="post_detail.php?post_id=<?= $post_id; ?>"><i class="fas fa-comment post-icon"></i><span><?= $total_post_comments; ?></span></a>

                                    </div>
                                    <div class="post-caption">
                                        <p class="post-user-name">
                                        <?= $fetch_posts['name']; ?>
                                        </p>
                                        <p class="caption-text"><?= $fetch_posts['caption']; ?></p>
                                    </div>
                                    <a href="post_detail.php?post_id=<?= $post_id; ?>" class="view-comments">
                                        View all comments</a>


                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
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