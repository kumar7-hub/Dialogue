<?php
    $index = true;
    $indexPath = '/~skumar/3680/final/index.php';
    if ($_SERVER['PHP_SELF'] !== $indexPath) $index = false;
?>

<nav class="navbar navbar-expand-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Dialogue</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Links -->
        <div class="collapse navbar-collapse" id="mynavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown">Topics</a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="index.php?topic=Technology">
                                <i class='fa-solid fa-computer' style='color: cyan; margin-right: 15px;'></i>Technology
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="index.php?topic=Travel">
                                <i class='fa-solid fa-plane' style='color: rgb(245, 21, 245); margin-right: 17px;'></i>Travel
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="index.php?topic=Food">
                                <i class='fa-solid fa-utensils' style='color: orange; margin-right: 23px;'></i>Food
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="index.php?topic=Lifestyle">
                                <i class='fa-solid fa-user' style='color: gold; margin-right: 23px;'></i>Lifestyle
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="index.php?topic=Cars">
                                <i class='fa-solid fa-car' style='color: springgreen; margin-right: 20px;'></i>Cars
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="index.php?topic=Sports">
                                <i class='fa-solid fa-medal' style='color: red; margin-right: 19px;'></i>Sports
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="contact.php">Support</a></li>

                <?php
                    // Show login link if not logged in
                    if (!isset($_SESSION['loggedIn'])) echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                    else {
                        // If logged in, show settings dropdown menu 
                        echo '<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown">Settings</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                        <li><a class="dropdown-item" href="logout.php">Sign Out</a></li>
                                    </ul>
                              </li>';

                        if ($index) echo '<li class="nav-item" style="margin-left: 20px;"><a class="nav-link" data-bs-toggle="modal" data-bs-target="#createPost">Create Post</a></li>';
                    }
                ?>
            </ul>

            <!-- Search Bar -->
            <?php 
                // Display search bar only on index page
                if ($index) {
                    isset($_GET['topic']) ? $action = "index.php?topic={$_GET['topic']}" : $action = 'index.php';

                    echo "<form class='d-flex' action='{$action}' method='POST'>
                            <div class='search-container'>
                                <i class='fa-solid fa-magnifying-glass search-icon'></i>
                                <input id='search' class='form-control me-2' type='text' name='search' autocomplete='off'>
                            </div>
                            <input id='searchButton' class='btn btn-primary' type='submit' value='Search'>
                         </form>";
                }
            ?>
        </div>
    </div>
</nav>