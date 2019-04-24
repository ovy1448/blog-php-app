<?php include('inc/header.php');?>
<?php include('server.php') ?>

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