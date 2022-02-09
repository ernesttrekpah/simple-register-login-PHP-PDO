<!-- Header -->
<?php require_once('header.php')?>;
<!-- Header Ends -->

<?php
session_start();
if(!isset($_SESSION['email'])){
  header('location:index.php');
}

?>

<div class="container">
<center class='btn btn-primary w-75 v-75'> Welcome <?= ucfirst($_SESSION['email']) ?> 😊</center>
</div>

<div class="container my-3">
  <form action="logout.php" method="post">
    <div class="mb-5">
    <button name="btn-logout" type="submit" class=" btn btn-danger">Logout</button>
    </div>
  </form>
</div>