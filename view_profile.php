<?php
include 'connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   header('location: home.php');
   exit(); 
}

$user_id = $_SESSION['user_id'];
include 'like_post.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Profile</title>

   <!-- CSS styles -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
   <header>
      <div class="flex">
         <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">
               <h1><a class="navbar-brand logo fw-bolder" href="home.php"><span>Spea</span>Kora.</a></h1>
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

                  <!-- User profile dropdown -->
                  <div class="d-flex align-items-center">
                     <?php
                     // Fetch user profile if logged in
                     if (isset($_SESSION['user_id'])) {
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
                                 <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                 <li>
                                    <hr class="dropdown-divider">
                                 </li>
                                 <li><a href="user_logout.php" onclick="return confirm('Logout from this website?');" class="dropdown-item" href="#">Logout</a></li>
                              </ul>
                           </div>
                           <div style="color: #7431f9;" id="user-btn" class="fas fa-user mx-3"></div>
                        <?php
                        } else {
                        ?>
                           <div id="user-btn" class="fas fa-user mx-3"></div>
                     <?php
                        }
                     }
                     ?>
                  </div>
               </div>
            </div>
         </nav>
      </div>
   </header>

   <?php
   if (isset($_GET['user_id'])) {
      $view_user_id = $_GET['user_id'];
      $select_user_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
      $select_user_profile->execute([$view_user_id]);
      if ($select_user_profile->rowCount() > 0) {
         $fetch_user_profile = $select_user_profile->fetch(PDO::FETCH_ASSOC);
   ?>
         <div class="user-profil-container row m-auto mt-5">
            <div class="profile-user-pic col-12 col-sm-12 col-6-md">
               <i class=" fas fa-user"></i>
            </div>
            <div class="profile-user-detail d-flex flex-column text-center text-md-end col-12 col-6-md">
               <p class="fs-3 fw-bolder"><?= $fetch_user_profile['name']; ?></p>
               <p class="fs-4 text-dark-emphasis"><?= $fetch_user_profile['email']; ?></p>
            </div>
         </div>
         <div class="col-sm-12 col-md-12 m-auto ">
            <?php
            $select_posts = $conn->prepare("SELECT *, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date FROM `posts` WHERE user_id = ?");
            $select_posts->execute([$view_user_id]);
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
                  $confirm_likes->execute([$view_user_id, $post_id]);
            ?>
                  <div style="width:80% !important; margin-right: auto" class="post-box">
                     <div class="post-heading">
                        <input type="hidden" name="post_id" value="<?= $post_id; ?>">

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
                        $select_profile->execute([$fetch_user_profile['id']]);
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
                        <a style="text-decoration:none; color: black" href="#"><i class="fas fa-comment post-icon"></i><span><?= $total_post_comments; ?></span></a>

                     </div>

                     <div class="icons">

                        <div class="post-caption">
                           <p class="post-user-name">
                              <?= $fetch_posts['name']; ?>
                           </p>
                           <p class="caption-text"><?= $fetch_posts['caption']; ?></p>
                        </div>
                     </div>
                  </div>
            <?php
               }
            } else {
               echo '<p class="empty row d-flex text-center justify-content-center">no posts found!</p>';
            }
            ?>
         </div>
   <?php
      } else {
         echo '<p class="empty">User not found!</p>';
      }
   } else {
      echo '<p class="empty">User ID not provided!</p>';
   }
   ?>

   <script src="js/script.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>