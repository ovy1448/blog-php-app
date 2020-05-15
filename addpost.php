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
<div class="over"></div>
<main class="add-main post-layer">
  <div>
    <h1 class="add-edit-text">Add Post</h1>
    <form action="<?php $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
        <div class="form-group post-img">
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" name="myfile" id="userImage" class="custom-file-input inputFile" aria-describedby="inputGroupFileAddon01">
                    <label class="file-input custom-file-label" for="inputGroupFile01"><?php if(isset($_POST['upload'])){echo $fileName;} else {echo "Choose image";};?></label>
                </div>
            </div>
            <div>
                <input type="submit" name="upload" value="Upload" class="btnSubmit">
            </div>
            <div id="pre-crop-image">
                <?php if (! empty($_POST["upload"])) {if($targetPath){?><img src="<?php echo $targetPath; ?>" id="cropbox"/><br/>
                    <script type="text/javascript">
                        imgexist();
                    </script>
                    <?php }} ?>
            </div>
            <div id="btn">
                <?php if (! empty($_POST["upload"])) {if($targetPath){?><input type='button' id="crop" value='CROP' class="crop btn btn-primary"><?php }} ?>
            </div>
            <div>
                <div class="pre-layer"></div>
                
                <img src="#" class="cropped_img" id="cropped_img" style="display: none;">
            </div>
        </div>
        <div class="form-group input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Title</span>
            </div>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="form-group">
            <textarea name="body" id="editor1" class="form-control"></textarea>
        </div>
        <input type="submit" name="submit" value="Submit" class="btn-submit btn btn-primary">
    </form>
  </div>
</main>
<script type="text/javascript">
    $(document).ready(function(){
        CKEDITOR.replace( 'editor1' );
        var size;
        $('#cropbox').Jcrop({
            setSelect: [2000,0,0,0],
            aspectRatio: 16/9,
            boxWidth: 800,
            
            onSelect: function(c){
                size = {x:c.x,y:c.y,w:c.w,h:c.h};
            }
        });
    
        $("#crop").click(function(){
            var img = $("#cropbox").attr('src');
            
            $(".over").removeClass("overlay");
            $("#cropped_img").show();
            $("#cropped_img").attr('src','image-crop.php?x='+size.x+'&y='+size.y+'&w='+size.w+'&h='+size.h+'&img='+img);
            $("#pre-crop-image").hide();
            $("#crop").hide();
            $(".add-main").addClass("container");
            $('.pre-layer').addClass('layer');
        });

        $("#userImage").on('change',function(){
            $(".file-input").empty();
            $(".file-input").append($('.inputFile').val().split('\\').pop());
            $(".btnSubmit").click();
        });
        $("#userImage").on('click',function(){
            $(".over").addClass("overlay");
        }); 
        /* $("#userImage").on('focusout',function(){
            $(".over").removeClass("overlay");
        });  */
        $(window).focus(function() {
            $(".over").removeClass("overlay");
        });
    });
</script>

<?php include('inc/footer.php');?>  

