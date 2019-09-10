<?php 
  session_start(); 
  
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['email']);
  	header("location: login.php");
  }

  require('config/config.php');
  require('config/db.php');
  require('cloudinary.php');

  if(isset($_POST['delete'])){
    $delete_id = mysqli_escape_string($conn, $_POST['delete_id']);
    $delete_image_id = mysqli_escape_string($conn, $_POST['delete_image_id']);

    \Cloudinary\Uploader::destroy($delete_image_id);
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


<?php include('inc/header.php');?>
  <div class="container">
    <img class="post-img" src="<?php echo cloudinary_url($post['image_id'])?>" alt=""><br>
    <h1><?php echo $post['title']; ?></h1>
    <small>Created on <?php echo $post['created_at']; ?> by <?php echo $post['author']; ?></small>
    <p><?php echo $post['body']; ?></p>
    <hr>
    <?php if(isset($_SESSION['email'])){if ($post['author'] == $_SESSION['email']) {?>
      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="pull-right">
      <input type="hidden" name="delete_id" value="<?php echo $post['id'];?>">
      <input type="hidden" name="delete_image_id" value="<?php echo $post['image_id'];?>">
      <input type="submit" name="delete" value="Delete" class="btn btn-danger">
    </form>
    <a href="<?php echo ROOT_URL;?>editpost.php?id=<?php echo $post['id'];?>" class="btn btn-primary">Edit</a><?php }} ?>
  </div>
<?php include('inc/footer.php');?>


