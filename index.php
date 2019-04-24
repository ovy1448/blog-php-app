<?php 
  session_start(); 
  
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['email']);
  	header("location: login.php");
  }
?>

<div class="main-container">
    <?php include('inc/header.php');?>
    <?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
    <?php endif ?>
    <?php include('inc/footer.php');?>
</div>

