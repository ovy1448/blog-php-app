<?php 
  session_start(); 
  
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['email']);
  	header("location: login.php");
  }

  require('config/config.php');
  require('config/db.php');
  $query = 'SELECT * FROM posts ORDER BY created_at DESC';
  $result = mysqli_query($db, $query);
  $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);
  mysqli_close($db);
  
?>


<?php include('inc/header.php');?> 
<main class="main-container">
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
  <div class="container" id="posts">
    <?php foreach($posts as $post) : ?>
      <div class="main-posts">
        <span><?php echo $post['title']; ?></span><br>
        <small>Created on <?php echo $post['created_at']; ?> by <?php echo $post['author']; ?></small>
        <p><?php echo $post['body']; ?></p>
        <a href="<?php echo ROOT_URL; ?>post.php?id=<?php echo $post['id']; ?>">Read More</a><br>
      </div>
    <?php endforeach; ?>
  </div>
  
</main>
<?php include('inc/footer.php');?>  

