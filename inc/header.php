<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP Blog</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css?<?php echo time(); ?>">
</head>
<body>
    <header>
        <nav>
            <div class="container-fluid">
                <div class="row">
                    <div class="col" >
                        <a class="nav-btn" href="http://localhost/blog-php-app/index.php">Blog</a>
                        <a href="<?php echo ROOT_URL; ?>addpost.php">Add Post</a>
                    </div>
                    <div class="col text-right col-md-auto" >
                        <?php
                            if (isset($_SESSION['email'])){
                                echo '<div id="welcome">Welcome <strong>'.$_SESSION['email'].'</strong>
                                <br><a id="logout" href="index.php?logout=1">Logout</a></div>';
                            } 
                            elseif (stripos($_SERVER['REQUEST_URI'], 'login.php')){
                                echo '<span class="nav-btn-sel">Login</span>';
                            }
                            elseif(stripos($_SERVER['REQUEST_URI'], 'register.php')){
                                echo '<span class="nav-btn-sel">Register</span>';
                            } else {
                                echo '<a class="nav-btn" href="http://localhost/blog-php-app/register.php">Register</a>|<a class="nav-btn" href="http://localhost/blog-php-app/login.php">Login</a>';
                            }
                        ?>                       
                    </div>
                </div>
            </div>
        </nav>
    </header>