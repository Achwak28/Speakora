<?php

$db_name = 'mysql:host=localhost;dbname=speakora_db';
$user_name = 'root';
$user_password = '';

$conn = new PDO($db_name, $user_name, $user_password);


session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
  
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $message = 'Email already exists!';
        echo "<script>showErrorMessage('$message');</script>";
    } else {
        if ($pass != $cpass) {
            $message[] = 'confirm password not matched!';
        } else {
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
            $insert_user->execute([$name, $email, $cpass]);
            $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
            $select_user->execute([$email, $pass]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);
            if ($select_user->rowCount() > 0) {
                $_SESSION['user_id'] = $row['id'];
                header('location:home.php');
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regsiter page</title>
    <link rel="stylesheet" href="css/style.css">
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
    <header>
        <div class="flex">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container">
                    <h1> <a class="navbar-brand logo fw-bolder" href="home.php"> <span>Spea</span>Kora.</a></h1>
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
    <main class="container-fluid container-md pb-5">
        <div class="login-form-container">


            <form action="" method="post">
                <div class="login-title">
                    <h1>REGISTER NOW</h1>
                </div>
                <div class="mb-3 ">
                    <label for="exampleInputName" class="form-label">Full Name</label>
                    <input required type="text" name="name" class="form-control" id="exampleInputName">
                </div>
                <div class="mb-3 ">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input required type="email " name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3 ">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input required type="password" name="pass" class="form-control" id="exampleInputPassword1">
                </div>
                <div class="mb-3 ">
                    <label for="exampleInputPassword2" class="form-label">Confirm password</label>
                    <input required type="password" name="cpass" class="form-control" id="exampleInputPassword2">
                </div>

                <button name="submit" type="submit" class="btn btn-primary login-btn ">Regsiter Now</button>

                <div class="go-register form-text-p">
                    already have an account? <a href="login.php" class="alert-link"> <span> login now</span></a>
                </div>
            </form>
        </div>

    </main>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>