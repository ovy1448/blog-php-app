<?php 
  session_start(); 
  
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['email']);
  	header("location: login.php");
  }

  require('config/config.php');
  require('config/db.php');

  if(isset($_POST['delete'])){
    $delete_id = mysqli_escape_string($conn, $_POST['delete_id']);
    
  $query = "DELETE FROM posts WHERE id = {$delete_id}";

      if(mysqli_query($conn, $query)){
          header('Location: '.ROOT_URL.'');
      } else {
          echo 'ERROR'. mysqli_error($conn);
      }
  }

  $id = mysqli_real_escape_string($conn, $_GET['id']);
  $query = 'SELECT * FROM posts WHERE id='.$id;
  $result = mysqli_query($conn, $query);
  $post = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  mysqli_close($conn);
  
?>

<div class="main-container">
    <?php include('inc/header.php');?>
      <a href="<?php echo ROOT_URL; ?>">Back</a>
      <h1><?php echo $post['title']; ?></h1>
      <small>Created on <?php echo $post['created_at']; ?> by <?php echo $post['author']; ?></small>
      <p><?php echo $post['body']; ?></p>
      <hr>
      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="pull-right">
        <input type="hidden" name="delete_id" value="<?php echo $post['id'];?>">
        <input type="submit" name="delete" value="Delete" class="btn btn-danger">
      </form>

      <a href="<?php echo ROOT_URL;?>editpost.php?id=<?php echo $post['id'];?>" class="btn btn-primary">Edit</a>
    <?php include('inc/footer.php');?>
</div>

