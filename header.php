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