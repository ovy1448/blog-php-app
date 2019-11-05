<?php
require('config/db.php');

$email    = "";
$errors = array(); 

if (isset($_POST['news_user'])) {
  $email = mysqli_real_escape_string($conn, $_POST['email']);

  if (empty($email)) { array_push($errors, "Email is required"); }

  $email_check_query = "SELECT * FROM `newsletter` WHERE email='$email' LIMIT 1";
  $results = mysqli_query($conn, $email_check_query);
  $user = mysqli_fetch_assoc($results);
  
  if ($user) {
    if ($user['email'] === $email) {
      array_push($errors, "Email already exists");
    }
  }

  if (count($errors) == 0) {

  	$query = "INSERT INTO `newsletter` (email) 
  			  VALUES('$email')";
  	mysqli_query($conn, $query);
  }
}
?>
        <footer>
            <div class="container-fluid">
                <div id="footer" class="row">
                    <div class="col" id="footer-nav">
                        <a class="footer-nav-link" href="http://localhost/blog-php-app/about.php">About</a><br>
                        <a class="footer-nav-link" href="http://localhost/blog-php-app/privacy.php">Privacy</a><br>
                        <a class="footer-nav-link" href="http://localhost/blog-php-app/contact.php">Contact</a><br>
                        <a class="footer-nav-link" href="http://localhost/blog-php-app/login.php">Admin login</a>
                    </div>
                    <div class="col text-left">
                        <span id="newsletter-text">Newsletter</span><br>
                        Sign up to our newsletter and stay up to date.<br>
                        <form method="post" action="<?php echo ROOT_URL;?>post.php?id=<?php echo $post['id'];?>">
                            <input type="email" name="email" id="newsletter-input" placeholder="Enter Your Email"><button type="submit" class="news_btn" name="news_user"><i class="arrow"></i></button><br>
                        </form>
                        <a href="https://facebook.com" target="_blank" class="fab fa-facebook-square fa-3x"></a>
                        <a href="https://github.com" target="_blank" class="fab fa-github-square fa-3x"></a>
                        <a href="https://www.linkedin.com/" target="_blank" class="fab fa-linkedin fa-3x"></a>
                    </div>
                    </div>
                <div id="copyright" class="row">
                    <div class="text-center col">
                        <span>© <?php echo date("Y");?> Daniel Ovári </span>
                    </div>
                </div>    
            </div> 
        </footer>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js" integrity="sha256-fTuUgtT7O2rqoImwjrhDgbXTKUwyxxujIMRIK7TbuNU=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>