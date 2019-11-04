<?php include('inc/header.php');?>

<?php
session_start();

$username = "";
$email    = "";
$errors = array(); 

require('config/db.php');

if (isset($_POST['reg_user'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password_1 = mysqli_real_escape_string($conn, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($conn, $_POST['password_2']);

  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  $user_check_query = "SELECT * FROM `registration-users` WHERE username='$username' OR email='$email' LIMIT 1";
  $results = mysqli_query($conn, $user_check_query);
  $user = mysqli_fetch_assoc($results);
  
  if ($user) {
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  if (count($errors) == 0) {
  	$password = md5($password_1);

  	$query = "INSERT INTO `registration-users` (username, email, `password`) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($conn, $query);
  	$_SESSION['email'] = $username;
  	header('location: index.php');
  }
}
?>

<form method="post" action="register.php" class="log-reg-form">
    <div class="form-group">
        <input name="username" type="text" class="form-control" placeholder="Username" value="<?php echo $username; ?>">
    </div>
    <div class="form-group">
        <input name="email" type="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>">
    </div>
    <div class="form-group">
        <input class="form-control" type="password" name="password_1" placeholder="Password">
    </div>
    <div class="form-group">
        <input class="form-control" type="password" name="password_2" placeholder="Password 2">
    </div>
    <button type="submit" class="btn btn-dark" name="reg_user">Next</button>
    <h6>
        <p>
            Already a member? <a href="login.php">Sign in</a>
        </p>
    </h6>
</form>