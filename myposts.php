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

  $query = "SELECT * FROM posts WHERE author = '".$_SESSION["email"]."'ORDER BY created_at DESC";
  $result = mysqli_query($conn, $query);
  $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);
  mysqli_close($conn);
?>

<?php include('inc/header.php');?> 

<main class="container">
  <div class="row">
    <?php foreach($posts as $post) : ?>
      <div class="main-post col-6">
        <a href="<?php echo ROOT_URL; ?>post.php?id=<?php echo $post['id']; ?>">
          <div class="main-container text-center">
            <?php if ( !$post['image_id']==0) {?><img class="post-img" src="<?php echo cloudinary_url($post['image_id'])?>" alt=""> <?php } ?>
            <div class="container">
              <span class="main-title"><?php echo $post['title']; ?></span><hr>
              <span class="main-author"><?php echo date('j M Y', strtotime($post['created_at'])); ?> by <?php echo $post['author']; ?></span><hr>
              <p class="main-body"><?php echo strip_tags($post['body']); ?></p>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<script type="text/javascript">
  $(window).on('resize load',function(){
    if ($(window).width()<768){
      $('.main-post').removeClass('col-6');
    } 
    if ($(window).width()>768){
      $('.main-post').addClass('col-6');
    } 
  });
</script>
<?php include('inc/footer.php');?>