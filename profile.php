<?php

include 'connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
};

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   if (!empty($name)) {
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $user_id]);
   }

   if (!empty($email)) {
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_email->execute([$email]);
      if ($select_email->rowCount() > 0) {
         $message[] = 'email already taken!';
      } else {
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if ($old_pass != $empty_pass) {
      if ($old_pass != $prev_pass) {
         $message[] = 'old password not matched!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'confirm password not matched!';
      } else {
         if ($new_pass != $empty_pass) {
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $user_id]);
            $message[] = 'password updated successfully!';
         } else {
            $message[] = 'please enter a new password!';
         }
      }
   }
}
include 'like_post.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>posts</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">



   <link rel="stylesheet" href="./css/style.css">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>


</head>

<body>
<?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
        }
    }
    ?>
   <?php include 'header.php'; ?>



   <div class="user-profil-container row m-auto mt-5">
      <div class="profile-user-pic col-12 col-sm-12 col-6-md"">
         
                        <i class=" fas fa-user"></i>

      </div>
      <div class="profile-user-detail d-flex flex-column text-center text-md-end col-12 col-6-md">
         <p class="fs-3 fw-bolder"><?= $fetch_profile['name']; ?></p>
         <p class="fs-4 text-dark-emphasis"><?= $fetch_profile['email']; ?></p>
      </div>
   </div>

   <div class="col-sm-12 col-md-12 m-auto ">
   <?php
   $select_posts = $conn->prepare("SELECT *, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date FROM `posts` WHERE user_id = ?");
   $select_posts->execute([$user_id]);
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
      echo '<p class="empty">no posts found!</p>';
   }
   ?>
</div>








   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>


</body>

</html>