<?php

include 'connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

include 'like_post.php';

$get_id = $_GET['post_id'];

if (isset($_POST['add_comment'])) {

    $user_name = $_POST['user_name'];
    $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
    $comment = $_POST['comment'];
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);

    $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ? AND user_id = ? AND user_name = ? AND comment = ?");
    $verify_comment->execute([$get_id, $user_id, $user_name, $comment]);

    if ($verify_comment->rowCount() > 0) {
        $message[] = 'comment already added!';
    } else {
        $insert_comment = $conn->prepare("INSERT INTO `comments`(post_id, user_id, user_name, comment) VALUES(?,?,?,?)");
        $insert_comment->execute([$get_id, $user_id, $user_name, $comment]);
        $message[] = 'your comment edited successfully!';
 
    }
}


if (isset($_POST['delete_comment'])) {
    $delete_comment_id = $_POST['comment_id'];
    $delete_comment_id = filter_var($delete_comment_id, FILTER_SANITIZE_STRING);
    $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
    $delete_comment->execute([$delete_comment_id]);
    $message[] = 'comment deleted successfully!';
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



    <link rel="stylesheet" href="css/style.css">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>


</head>

<body>
    <?php include 'header.php'; ?>


    <div class="alerts">

    </div>


    <main>
        <div class="container py-3 pb-5">

            <div class="row g-0 text-center">

                <div class="col-sm-12 col-md-12 m-auto ">




                    <div class="post-box">

                        <?php
                        $select_posts = $conn->prepare("SELECT *, DATE_FORMAT(date, '%Y-%m-%d') AS formatted_date FROM `posts` WHERE id = ?");
                        $select_posts->execute([$get_id]);
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
                                    ?> <a href="post_detail.php">
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
<!--

                                    <button type="submit" name="like_post"><i class="fas fa-heart post-icon"></i><span><?= $total_post_likes; ?></span></button>
                                    <a style="text-decoration: none;" href="#"><i class="fas fa-comment post-icon"></i><span><?= $total_post_comments; ?></span></a>

                                </div>-->
                                <div class="post-caption">
                                    <p class="post-user-name">
                                        Achouak Cherif
                                    </p>
                                    <p class="caption-text">coments</p>
                                </div>
                        <?php
                            }
                        } else {
                            echo '<p class="empty">no posts found!</p>';
                        }
                        ?>
                    </div>



                </div>

            </div>

            <div class="row">
                <section class="comments-container col-sm-12 col-md-10 m-auto">
                    <?php
                    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                    $select_profile->execute([$user_id]);
                    if ($select_profile->rowCount() > 0) {
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    ?>

                        <p class="comment-title text-left">add comment</p>

                        <form action="" method="post" class="add-comment">

                            <input type="hidden" name="user_name" value="<?= $fetch_profile['name']; ?>">
                            <p class="user"><i class="fas fa-user"></i><?= $fetch_profile['name']; ?></p>
                            <textarea name="comment" maxlength="1000" class="comment-box" cols="30" rows="10" placeholder="write your comment" required></textarea>
                            <input type="submit" value="add comment" class="inline-btn btn-add-comment" name="add_comment">
                        </form>
                    <?php
                    } else {
                    ?>

                        <div class="add-comment d-flex flex-column align-items-start ">
                            <p class="login-comment-text">please login to add your comment</p>
                            <a href="login.php" class="inline-btn login-add-comment flex-shrink-1">login now</a>
                        </div>
                    <?php
                    }
                    ?>


                    <p class="comment-title">post comments</p>
                    <div class="user-comments-container">
                        <?php
                        $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
                        $select_comments->execute([$get_id]);
                        if ($select_comments->rowCount() > 0) {
                            while ($fetch_comments = $select_comments->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                                <div class="show-comments" style="<?php if ($fetch_comments['user_id'] == $user_id) {
                                                                        echo 'order:-1;';
                                                                    } ?>">
                                    <div class="comment-user">
                                        <i class="fas fa-user"></i>
                                        <div>
                                            <span><?= $fetch_comments['user_name']; ?></span>
                                            <div class="text-left"><?= $fetch_comments['date']; ?></div>
                                        </div>
                                    </div>
                                    <div class="comment-box text-left" style="<?php if ($fetch_comments['user_id'] == $user_id) {
                                                                        echo 'color:var(--white); background:var(--black);';
                                                                    } ?>"><?= $fetch_comments['comment']; ?></div>
                                    <?php
                                    if ($fetch_comments['user_id'] == $user_id) {
                                    ?>
                                        <form action="" method="POST">
                                            <input type="hidden" name="comment_id" value="<?= $fetch_comments['id']; ?>">
                                            <button type="submit" class="inline-delete-btn login-delete-comment" name="delete_comment" onclick="return confirm('delete this comment?');">delete comment</button>
                                        </form>
                                    <?php
                                    }
                                    ?>
                                </div>
                        <?php
                            }
                        } else {
                            echo '<p class="empty">no comments added yet!</p>';
                        }
                        ?>

                    </div>

                </section>
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