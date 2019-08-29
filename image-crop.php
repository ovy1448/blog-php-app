<?php
  SESSION_start();
  $img_r = imagecreatefromjpeg($_GET['img']);
  $dst_r = ImageCreateTrueColor( $_GET['w'], $_GET['h'] );
  $src = $_SESSION['target_path'];
 
  imagecopyresampled($dst_r, $img_r, 0, 0, $_GET['x'], $_GET['y'], $_GET['w'], $_GET['h'], $_GET['w'],$_GET['h']);
  
  header('Content-type: image/jpeg');
  echo imagejpeg($dst_r);
  echo imagejpeg($dst_r, 'uploads/' . basename($src));
 
  exit;
?>