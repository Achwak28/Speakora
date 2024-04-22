<?php

include 'connect.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}
// Fetch user profile data from the database
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
if (isset($_POST['publish'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $caption = $_POST['caption'];
   $caption = filter_var($caption, FILTER_SANITIZE_STRING);


   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = './uploaded_img/' . $image;

   $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND user_id = ?");
   $select_image->execute([$image, $user_id]);

   if (isset($image)) {
      if ($select_image->rowCount() > 0 and $image != '') {
         $message[] = 'image name repeated!';
      } elseif ($image_size > 20000000) {
         $message[] = 'images size is too large!';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   } else {
      $image = '';
   }

   if ($select_image->rowCount() > 0 and $image != '') {
      $message[] = 'please rename your image!';
   } else {
      $insert_post = $conn->prepare("INSERT INTO `posts`(user_id, name, caption, image) VALUES(?,?,?,?)");
      $insert_post->execute([$user_id, $name, $caption, $image]);
      $message[] = 'post published!';
   }
}


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
   <?php include 'header.php'; ?>

   <div class="container-fluid" style=" background-color: #f5f5f5;">
      <section class="post-editor container-fluid container-md pb-5">

         <h1 class="heading text-capitalize">Add new post</h1>

         <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="name" value="<?= $fetch_profile['name']; ?>">
            <p>post caption <span>*</span></p>
            <textarea name="caption" class="box" required maxlength="10000" placeholder="write your caption..." cols="30" rows="8"></textarea>
            <p>post image <span>*</span></p>
            <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
            <div class="publish-btn">
               <input type="submit" value="Publish Post" name="publish" class="btn">
            </div>
         </form>

      </section>

   </div>






   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>


</body>

</html>