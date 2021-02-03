<?php session_start(); ?>
<?php
define("server","127.0.0.1");
define("user","root");
define("pass","");
define("name","Requisition");

$connection = mysqli_connect(server,user,pass);
if(!$connection)
{
    die("Database connection failed: " . mysqli_error($connection));
    exit();
}

$db_select = mysqli_select_db($connection, name);
if(!$db_select)
{
    die("Database selection failed: " . mysqli_error($connection));
}

$errors = array();

if(isset($_POST['login'])) 
{
	$username = mysqli_real_escape_string($connection, $_POST['username']); 
	$password =  mysqli_real_escape_string($connection, $_POST['password']); 
	$queryresult = mysqli_query($connection, "select * from user where username = '$username' and password = '$password'") or die(mysqli_error());
	$row = mysqli_fetch_array($queryresult);
	$user = $row['username'];
	$pass = $row['password'];

	if (($user != $username) || ($pass != $password))
    {
        array_push($errors, "Username/password incorrect!");
    }
	elseif ((count($errors)==0) && ($user == $username) && ($pass == $password))
	{
		$_SESSION['id'] = $row['userid'];
        $_SESSION['user'] =$row['username'];

		if ((isset($_SESSION['id'])) && (isset($_SESSION['user']))) 
		{   
		    session_start();
		    header("Location: index.php");
		    exit();
		}	
	} 
}
?>
<!DOCTYPE html>
<html>
<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
		<title>Login School of Government, KE</title>
	     <link href="./resources/bootstrap.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="./resources/all.min.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="./style.css" rel="stylesheet" type="text/css" media="screen"/>
        <script>window.jQuery || document.write('<script src="./resources/jquery-slim.min.js"><\/script>')</script>
        <script type="text/javascript" src="./resources/bootstrap.bundle.js"></script>
        <script src="./resources/popper.min.js"></script>
        <script src="./resources/bootstrap.js"></script>
        <script src="./resources/all.min.js"></script>
</head>

<body style="padding: 0">
 <div class="cont">
 
  	<!-- login modal -->
    <div class="modal fade show" style = "padding-right: 0" id="login" tabindex="-1" role="dialog" aria-labelledby="LogIN" aria-hidden="false">
      <div class="modal-dialog modal-dialog-centered modal-large" role="document">
        <div class="modal-content">
          <div class="modal-header bg-light">
            <h5 class="modal-title text-muted">Log In</h5>
          </div>
          <form action="" method="POST">
            <div class="modal-body text-left">
              <div>
              	<p class="text-secondary text-muted" style="font-size: 16px;">Please provide your password and username to log in.</p>
              	<label for="username" class="pull-left" style="font-size: 14px;">Username:</label> 
                <?php  if (count($errors) > 0) : ?>
                <div class="form-errors">
                <?php foreach ($errors as $error) : ?>
                <span class="text-left" style="font-size: 14px;"><?php echo $error ?></span>
                <?php endforeach ?>
                </div>
                <?php  endif ?>
                
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                  <input type="text" name="username" id="username" class="form-control" placeholder="Enter your Username" required="required">
                </div>
              </div> 
              <br>
              <label for="password" class="pull-left" style="font-size: 14px;">Password:</label>  
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-lock"></i></span>
                </div>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your Password..." required="required">
              </div>
            </div>
            <br>
            <div style="margin-left: 15px;"><a href="signup.php">Create Account</a></div>
            <br>
            <div class="modal-footer bg-light">
              <button type="submit" name="login" class="btn buttons btn-primary btn-small">
                <i class="fas fa-sign-in-alt"></i> Log in
              </button>
            </div>

          </form>
        </div>
      </div>
    </div> <!-- end login modal   -->

    <br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br>
 
 </div>

 <footer class="footer2">
  <div class="row bg-dark">
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
	  <hr class="hr-dark">
	  <div class="buttons">
		<p class="text-center">
		  <a href="#">
            <button class="btn btn-social-icon btn-gplus btn-outline"><i class="fab fa-google-plus-g"></i></button>
          </a>&nbsp;
          <a href="#">
            <button class="btn btn-social-icon btn-twitter btn-outline"><i class="fab fa-twitter"></i></button>
          </a>&nbsp;
          <a href="#">
            <button class="btn btn-social-icon btn-facebook btn-outline"><i class="fab fa-facebook-f"></i></button>
          </a>
        </p> 
        <p class="text-center text-white">
          &copy; Copyright: aleksSoftwares.
        </p>   
      </div>
      <hr class="hr-dark">
    </div>
   </div>
  </footer>

</body>
</html>
<script>
  $(document).ready(function(){
    $('#login').modal({
      backdrop: 'static',
      keyboard: false
    });
  });
</script>