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

  $query = 'SELECT * FROM posts ORDER BY created_at DESC';
  $result = mysqli_query($conn, $query);
  $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);
  mysqli_close($conn);

 /*  if($post['created_at'] = 0){
    echo "H";
  } */
  
?>


<?php include('inc/header.php');?> 
<main class="container">
  <div class="" id="main-container">
    <?php foreach($posts as $post) : ?>
      
      <img class="post-img" src="<?php echo cloudinary_url($post['image_id'])?>" alt=""><br>
      <div class="main-post container">
        <span class="main-title"><?php echo $post['title']; ?></span><br><hr>
        <span class="main-author"><?php echo date('j M Y', strtotime($post['created_at'])); ?> by <?php echo $post['author']; ?></span><hr>
        <div class="main-body">
          <p><?php echo $post['body']; ?></p>
        </div>
        <a class="main-readmore" href="<?php echo ROOT_URL; ?>post.php?id=<?php echo $post['id']; ?>">Read More</a><br>
      </div>
    <?php endforeach; ?>
  </div>
  
</main>
<?php include('inc/footer.php');?>  

