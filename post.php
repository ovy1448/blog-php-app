<?php 
  session_start(); 
  
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['email']);
  	header("location: login.php");
  }

  require('config/config.php');
  require('config/db.php');
  require('./cloudinary/cloudinary.php');

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
<main class="post-layer container">
    <?php if ( !$post['image_id']==0) {?><div class="layer"></div><?php } ?>
    <img class="post-img" src="<?php echo cloudinary_url($post['image_id'])?>" alt="">
  </div>
  <div class="post-container container">
    <br>
    <div class="main-title text-center">
      <h1><?php echo $post['title']; ?></h1><hr>
      <span class="main-author">Created on <?php echo date('j M Y', strtotime($post['created_at'])); ?> by <?php echo $post['author']; ?></span><hr>
    </div>
    <?php if(isset($_SESSION['email'])){if ($post['author'] == $_SESSION['email'] || $_SESSION['email'] == "admin") {?>
    <div class="post-btn-div">
      <a href="<?php echo ROOT_URL;?>editpost.php?id=<?php echo $post['id'];?>" class="fas fa-edit"></a>
      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="post-btn pull-right">
        <input type="hidden" name="delete_id" value="<?php echo $post['id'];?>">
        <input type="hidden" name="delete_image_id" value="<?php echo $post['image_id'];?>">
        <button type="submit" name="delete" value="Delete" class="dlt-btn"><i class="far fa-trash-alt"></i></button>
      </form>
    </div><?php }} ?>
    <p><?php echo $post['body']; ?></p>
</main>
<script type="text/javascript">
  $(window).on('resize load',function(){
    if ($(window).width()<575){
      $('.post-layer').removeClass('container');
    } 
    if ($(window).width()>575){
      $('.post-layer').addClass('container');
    } 
  });
</script>
<?php include('inc/footer.php');?>



