<?php
require_once ('connection.php');
require_once ('functions.php');

// Logic to register
$messages=[];

if($_SERVER['REQUEST_METHOD']==='POST'){
  if(isset($_POST['register'])){
    $email= sanitizeInput($_POST['email']);
    $password=sanitizeInput($_POST['password']);
    $confirmPassword= sanitizeInput($_POST['c-password']);
    $hashedPassword=password_hash($password, PASSWORD_DEFAULT);

    if(empty($email)){
      array_push($messages, 'Email cannot be empty!');
    }if(empty($password)){
      array_push($messages, 'Password cannot be empty');
    }if(empty($confirmPassword)){
      array_push($messages, 'Confirm password cannot be empty');
    }if($password != $confirmPassword){
      array_push($messages, 'Passwords do not match');
    }if(!array_filter($messages)){
      
      $checkMailExistence=$conn->prepare("SELECT email FROM oop_login_tb WHERE email=:email LIMIT 1");
      $checkMailExistence->execute([
        ':email'=>$email
      ]);
      $rslt=$checkMailExistence->rowCount();

      if($rslt > 0){
        array_push($messages, 'Email already taken');
      }else

      if(!array_filter($messages)){
      try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();
        $insertQuery=$conn->prepare("INSERT INTO oop_login_tb(email,password) VALUES(:email, :password)");
        $results=$insertQuery->execute([
          ':email'=>$email,
          ':password'=>$hashedPassword
        ]);
        $conn->commit();

        if($results){
          array_push($messages, 'Registered successfully');
          header('location:index.php');
        }else
        array_push($messages, 'Sorry registration failed!');

      } catch (PDOException $ex) {
        $conn->rollBack();
        die($ex->getMessage());
      }
    }
  }

    // array_push($messages, 'Sorry something went wrong try again');

  }
}


?>


<!-- Header -->
<?php require_once('header.php')?>;
<!-- Header Ends -->



<div class="container mt-3">
  <center><h4>Simple Register & Login System</h4></center>
</div>



  <div class="container my-5 mx-auto w-25 vh-50">
    <center>
      <?php if(isset($messages)){
        foreach($messages as $message){
          print "<p class='text-danger'>{$message}</p>";
        }
      } ?>

    </center>
    <form  method="POST" class="bg-light my-3 p-5 mx-auto">
      <center> <h4 class="h4 mb-3">Registration</h4> </center>
    <div class="form-input">
      <div class="mb-3">
        <input name="email" type="text" class="form-control" placeholder="Email" value="<?php 
        if(isset($email)){print $email;} ?> ">
      </div>
    </div>
    <div class="form-input">
      <div class="mb-3">
        <input name="password" type="password" class="form-control" placeholder="Password">
      </div>
    </div>
    <div class="form-input">
      <div class="mb-3">
        <input name="c-password" type="password" class="form-control" placeholder="Confirm password">
      </div>
    </div>

    <div class="mb-3">
      <button name="register" type="submit" class="btn btn-primary w-100">Register</button>
    </div>
    
      <div class="mb-3">
        <a href="././" class="text-decoration-none">Login here</a>
      </div>
      
    </form>
  </div>
  
  <div class="container my-2">
  <center>
    <h6>Made with ❤ by Ernest Trekpah</h6>
    <small>Email@: ernesttrekpah@gmail.com</small>
  </center>
  </div>


</body>
</html>