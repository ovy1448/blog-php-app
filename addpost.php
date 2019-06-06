<?php 
  require('config/config.php');
  require('config/db.php');

  if(isset($_POST['submit'])){
      $title = mysqli_escape_string($conn, $_POST['title']);
      $body = mysqli_escape_string($conn, $_POST['body']);
      $author = mysqli_escape_string($conn, $_POST['author']);

      $query = "INSERT INTO posts(title, author, body) VALUES('$title', '$author', '$body')";

        if(mysqli_query($conn, $query)){
            header('Location: '.ROOT_URL.'');
        } else {
            echo 'ERROR'. mysqli_error($conn);
        }
  }
?>


<?php include('inc/header.php');?> 
<main class="main-container">
  <div class="container" id="">
    <h1>Add Post</h1>
    <form action="<?php $_SERVER['PHP_SELF'];?>" method="POST">
        <div class="form-group">
            <label for="">Title</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Author</label>
            <input type="text" name="author" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Body</label>
            <textarea name="body" class="form-control"></textarea>
        </div>
        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
    </form>
  </div>
  
</main>
<?php include('inc/footer.php');?>  

