<?php
ob_start();
?>
<?php 
  session_start();
  if ($_SESSION["ema"] ==null){
    header('Location: ../index.php');
  }
  else{
    header('Location: ../templates/menu.php');
  }
?>
<?php
ob_end_flush();
?>