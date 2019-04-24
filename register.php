<?php include('inc/header.php');?>
<?php include('server.php') ?>

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