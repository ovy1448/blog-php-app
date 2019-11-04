<?php include('inc/header.php');?>

<?php
session_start();

$username = "";
$email    = "";
$errors = array(); 

require('config/db.php');

if (isset($_POST['login_user'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
  
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
  
    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM `registration-users` WHERE email='$email' AND `password`='$password'";
        $results = mysqli_query($conn, $query);
        if (mysqli_num_rows($results) == 1) {
          while ($row = mysqli_fetch_assoc($results)) {
            $_SESSION['email'] = $row['username'];
            header('location: index.php');
          }
          
        }else {
            array_push($errors, "Wrong email/password combination");
        }
    }
  }
  
  ?>

<form class="log-reg-form" method="post" action="login.php">
    <?php include('errors.php');?>
    <div class="form-group">
        <input type="email" class="form-control" name="email" placeholder="email">
    </div>
    <div class="form-group">
        <input class="form-control" type="password" name="password" placeholder="Password">
    </div>
    <button type="submit" class="btn btn-dark" name="login_user">Next</button>
    <h6>
        <p>
            Not yet a member? <a href="register.php">Sign up</a>
        </p>
    </h6>
</form>