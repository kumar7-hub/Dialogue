<?php
    $contact = false;
    if (str_contains($_SERVER['PHP_SELF'], 'contact.php')) $contact = true;
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
                        <li><a class="dropdown-item" href="?topic=Technology" style="color: cyan;">Technology</a></li>
                        <li><a class="dropdown-item" href="?topic=Travel" style="color: rgb(245, 21, 245);">Travel</a></li>
                        <li><a class="dropdown-item" href="?topic=Food" style="color: orange;">Food</a></li>
                        <li><a class="dropdown-item" href="?topic=Lifestyle" style="color: gold;">Lifestyle</a></li>
                        <li><a class="dropdown-item" href="?topic=Cars" style="color: springgreen;">Cars</a></li>
                        <li><a class="dropdown-item" href="?topic=Sports" style="color: red;">Sports</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="contact.php">Support</a></li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)">Link</a>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown">Settings</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Sign Out</a></li>
                        <li><a class="dropdown-item" href="./auth/delete.php">Delete Account</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Search Bar -->
            <?php 
                if (!$contact) {
                    $action = 'index.php';
                    if (isset($_GET['topic'])) $action = "{$action}?topic={$_GET['topic']}";

                    echo "<form class='d-flex' action='{$action}' method='POST'>
                            <input id='search' class='form-control me-2' type='text' name='search' placeholder='Search' autocomplete='off'>
                            <input id='searchButton' class='btn btn-primary' type='submit' value='Search'>
                         </form>";
                }
            ?>
        </div>
    </div>
</nav>