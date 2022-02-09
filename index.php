<?php
session_start();
require_once dirname(__FILE__).'/Connection.php';
require_once dirname(__FILE__).'/functions.php';

// Check if user is already logged in
if(isset($_SESSION['email'])){
  header('location:welcome.php');
}

// Login logic
$errors=[];

if($_SERVER['REQUEST_METHOD']==='POST'){
  if(isset($_POST['login'])){
    $email=sanitizeInput($_POST['email']);
    $password=sanitizeInput($_POST['password']);

    if(empty($email)){
      array_push($errors, 'Email cannot be empty');
    }
    if(empty($password)){
      array_push($errors, 'Password cannot be empty');
    }
    if(!array_filter($errors)){
      try {
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $selectQuery=$conn->prepare("SELECT * FROM oop_login_tb WHERE email=:email  LIMIT 1");
  
        $selectQuery->execute([
          ':email'=>$email
        ]);
        
        $results=$selectQuery->rowCount();
        $row=$selectQuery->fetch();
        if($results > 0){
          if (password_verify($password, $row['password'])) {
              $_SESSION['email']=$email;
              $_SESSION['password']=$password;
              header('location:welcome.php');
          }else
          array_push($messages, 'Username or password incorrect');
        }else
        array_push($errors, 'Username or password incorrect');
   
      } catch (PDOException $ex) {
        die( $ex->getMessage());
      }
    }
  }
}

?>
<!-- Header -->
<?php require_once('header.php')?>;
<!-- Header Ends -->

<div class="container mt-5">
  <center><h4>Simple Register & Login System</h4></center>
</div>

  <div class="container my-5 mx-auto w-25 vh-50">
    <center>
      <?php if(isset($errors)){
        foreach($errors as $error){
          print "<p class='text-danger'>{$error}</p>";
        }
      } ?>

    </center>
    <form  method="POST" class="bg-light my-3 p-5 mx-auto">
      <center> <h4 class="h4 mb-3">Login</h4> </center>
    <div class="form-input">
      <div class="mb-3">
        <input name="email" type="email" class="form-control" placeholder="Email" value="<?php
        if(isset($email)) print $email; ?>">
      </div>
    </div>
    <div class="form-input">
      <div class="mb-3">
        <input name="password" type="password" class="form-control" placeholder="Password">
      </div>
    </div>

    <div class="mb-3">
      <button name="login" type="submit" class="btn btn-primary w-100">Login</button>
    </div>
    
      <div class="mb-3">
        <a href="register.php" class="text-decoration-none">Register here</a>
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