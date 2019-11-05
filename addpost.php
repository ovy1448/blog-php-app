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

    if(isset($_POST['upload'])){
        $currentDir = getcwd();
        $uploadDirectory = "/uploads/";

        $errors = []; 

        $fileExtensions = ['jpeg','jpg','png']; 

        $fileName = $_FILES['myfile']['name'];
        $fileSize = $_FILES['myfile']['size'];
        $fileTmpName  = $_FILES['myfile']['tmp_name'];
        $fileType = $_FILES['myfile']['type'];
        $tmp           = explode('.', $fileName);
        $fileExtension = end($tmp);

        $uploadPath = $currentDir . $uploadDirectory . basename($fileName);
        $targetPath = "uploads/" . $_FILES['myfile']['name'];

        if (! in_array($fileExtension,$fileExtensions)) {
            $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
        }

        if ($fileSize > 5000000) {
            $errors[] = "This file is more than 5MB. Sorry, it has to be less than or equal to 5MB";
        }

        if (empty($errors)) {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
        
            if ($didUpload) {
                $_SESSION['target_path'] = $uploadPath;
            } else {
                echo "An error occurred somewhere. Try again or contact the admin";
            }
        } else {
            foreach ($errors as $error) {
                echo $error . "These are the errors" . "\n";
            }
        }
    }

    if(isset($_POST['submit'])){
        if(isset($_SESSION['target_path'])){
            $image_id = \Cloudinary\Uploader::upload($_SESSION['target_path'])["public_id"];
        } else {
            $image_id = 0;
        }

        $title = strtoupper(mysqli_escape_string($conn, $_POST['title']));
        $body = mysqli_escape_string($conn, $_POST['body']);
        $author = $_SESSION['email'];

        $query = "INSERT INTO posts(title, author, body, image_id) VALUES('$title', '$author', '$body', '$image_id')";

        if(mysqli_query($conn, $query)){
            unlink("uploads/" . basename($_SESSION['target_path']));
            unset($_SESSION["target_path"]);
            header('Location: '.ROOT_URL.'');
        } else {
            echo 'ERROR'. mysqli_error($conn);
        }
    }
?>

<?php include('inc/header.php');?> 
<main>
  <div class="container">
    <h1>Add Post</h1>
    <form action="<?php $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
        <div class="form-group post-img">
            <label for="">Upload Picture</label><br>
            <input type="file" name="myfile" id="userImage" class="inputFile">
            <input type="submit" name="upload" value="Upload" class="btnSubmit">
            <div id="pre-crop-image">
                <?php if (! empty($_POST["upload"])) {if($targetPath){?><img src="<?php echo $targetPath; ?>" id="cropbox"/><br/> <?php }} ?>
            </div>
            <div id="btn">
                <input type='button' id="crop" value='CROP'>
            </div>
            <div>
                <img src="#" id="cropped_img" style="display: none;">
            </div>
        </div>
        <div class="form-group">
            <label for="">Title</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Body</label>
            <textarea name="body" id="editor1" class="form-control"></textarea>
        </div>
        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
    </form>
  </div>
</main>
<script type="text/javascript">
    $(document).ready(function(){
        CKEDITOR.replace( 'editor1' );
        var size;
        $('#cropbox').Jcrop({
            setSelect: [0,200,0,0],
            aspectRatio: 16/9,
            
            onSelect: function(c){
                size = {x:c.x,y:c.y,w:c.w,h:c.h};

                $("#crop").css("visibility", "visible");     
            }
        });
    
        $("#crop").click(function(){
            var img = $("#cropbox").attr('src');
            
            $("#cropped_img").show();
            $("#cropped_img").attr('src','image-crop.php?x='+size.x+'&y='+size.y+'&w='+size.w+'&h='+size.h+'&img='+img);
            $("#pre-crop-image").hide();
            $("#crop").hide();
        });
    });
</script>

<?php include('inc/footer.php');?>  

