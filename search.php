<?php
include 'connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Search</title>

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
                            <form action="search.php" method="POST" class="search-form d-flex justify-content-center" role="search">
                                <input class="form-control me-2" name="search_box" type="search" placeholder="Search for users" aria-label="Search">
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
    if (isset($_POST['search_box'])) {
        // Search for users based on the entered name
        $search_box = $_POST['search_box'];
        $search_box = '%' . $search_box . '%';
        $select_users = $conn->prepare("SELECT * FROM `users` WHERE name LIKE ?");
        $select_users->execute([$search_box]);

        if ($select_users->rowCount() > 0) {
    ?>
            <section class="users-container class="container-fluid container-md pb-5">
                <div class="box-container">
                    <?php
                    while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
                    ?>

                        <div class="user-profil-container">
                            <div class="profile-user-pic">

                                <i class="fas fa-user"></i>

                            </div>
                            <div class="profile-user-detail d-flex flex-column text-md-end">
                                <p class="fs-3 fw-bolder"><?= $fetch_users['name']; ?></p>
                                <p class="fs-4 text-dark-emphasis"><?= $fetch_users['email']; ?></p>
                            </div>
                        </div>
                     
                    <?php
                    }
                    ?>
                </div>
            </section>
    <?php
        } else {
            echo '<section><p class="empty">No users found!</p></section>';
        }
    } else {
        echo '<section><p class="empty">Enter a name to search for users!</p></section>';
    }
    ?>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>