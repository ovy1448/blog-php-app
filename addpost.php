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

    if(isset($_POST['submit'])){

        if(!isset($_FILES['myfile']) || $_FILES['myfile']['error'] == UPLOAD_ERR_NO_FILE) {
            $image_id = 0;
        } else {
            $currentDir = getcwd();
            $uploadDirectory = "/uploads/";

            $errors = []; // Store all foreseen and unforseen errors here

            $fileExtensions = ['jpeg','jpg','png']; // Get all the file extensions

            $fileName = $_FILES['myfile']['name'];
            $fileSize = $_FILES['myfile']['size'];
            $fileTmpName  = $_FILES['myfile']['tmp_name'];
            $fileType = $_FILES['myfile']['type'];
            $tmp           = explode('.', $fileName);
            $fileExtension = end($tmp);

            $uploadPath = $currentDir . $uploadDirectory . basename($fileName);

            if (! in_array($fileExtension,$fileExtensions)) {
                $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
            }

            if ($fileSize > 2000000) {
                $errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
            }

            if (empty($errors)) {
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
            
                if ($didUpload) {
                    echo "The file " . basename($fileName) . " has been uploaded";
                } else {
                    echo "An error occurred somewhere. Try again or contact the admin";
                }
            } else {
                foreach ($errors as $error) {
                    echo $error . "These are the errors" . "\n";
                }
            }
            $image_id = \Cloudinary\Uploader::upload($uploadPath)["public_id"];
        }
        

        $title = mysqli_escape_string($conn, $_POST['title']);
        $body = mysqli_escape_string($conn, $_POST['body']);
        $author = mysqli_escape_string($conn, $_POST['author']);
        

        $query = "INSERT INTO posts(title, author, body, image_id) VALUES('$title', '$author', '$body', '$image_id')";

        

        if(mysqli_query($conn, $query)){
            /* echo cl_image_tag("sh6rw8cnulqnitsaayah"); */
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
    <form action="<?php $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="">Upload Picture</label><br>
            <input type="file" name="myfile" id="fileToUpload">
        </div>
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

