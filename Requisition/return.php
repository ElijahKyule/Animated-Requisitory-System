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
?>
<?php
session_start();
if (!isset($_SESSION['id']) && !isset($_SESSION['user'])) 
{
  header("Location: login.php");
  exit();
}
?>
<?php
  $errors = array();
  $success = array();
  
  if (isset($_POST['return']) && isset($_SESSION['id']) && isset($_SESSION['user'])) 
  {
    $borrowername = mysqli_real_escape_string($connection, $_POST['borrowername']);
    $borrowerdept = mysqli_real_escape_string($connection, $_POST['borrowerdept']);
    $devicename = mysqli_real_escape_string($connection, $_POST['devicename']); 
    $serialno = mysqli_real_escape_string($connection, $_POST['serialno']);
    $condition = mysqli_real_escape_string($connection, $_POST['condition']);
    $verificationofficer = mysqli_real_escape_string($connection, $_POST['verificationofficer']);
    $verificationdate = mysqli_real_escape_string($connection, $_POST['verificationdate']);      

    if (empty($borrowername) || empty($borrowerdept)  || empty($devicename)  || empty($serialno) || empty($condition) || empty($verificationofficer) || empty($verificationdate)) 
    {
      array_push($errors, "Please fill all the fields!");
    }

    $duration1 = (date("Y-m-d")-$verificationdate);
    if (($duration1<0) OR ($duration1 > 1)) 
    {
      array_push($errors, "Check whether the date is valid!");
    } 
    $queryresults = mysqli_query($connection, "SELECT * from devices where deviceid = '$devicename'");
    $row = mysqli_fetch_array($queryresults);
    $Serialno = $row['serialno'];
    if ($serialno !== $Serialno) 
    {
      array_push($errors, "Serial numbers do not match!");
    }

    if (count($errors)==0)
    {
      $sql1 = mysqli_query($connection, "UPDATE devices SET devicestatus = 'Returned', returndate = '$verificationdate', verificationofficer = '$verificationofficer', devicecondition = '$condition' WHERE deviceid = '$devicename'");

      if (($sql1 == TRUE)) 
      {
        array_push($success, "Device returned successfully!");
      }
    } 
  }
?>
<!DOCTYPE html>
<html>
<head>
	      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
		    <title>Kenya School of Government</title>
	      <link href="./resources/bootstrap.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="./resources/selectize.css"  rel="stylesheet" type="text/css"media="screen" />
        <link href="./resources/all.min.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="./style.css" rel="stylesheet" type="text/css" media="screen"/>
        <script>window.jQuery || document.write('<script src="./resources/jquery-slim.min.js"><\/script>')</script>
        <script type="text/javascript" src="./resources/bootstrap.bundle.js"></script>
        <script src="./resources/popper.min.js"></script>
        <script src="./resources/bootstrap.js"></script>
        <script src="./resources/all.min.js"></script>
        <script src="./resources/jquery.js"></script>
        <script src="./resources/selectize.js"></script>
</head>
<body class="index">
  <nav class="navbar navbar-expand navbar-dark bg-dark">
      <a class="navbar-brand" href="#">Kenya School of Government.</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExample02">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="./index.php">Requisition |<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./return.php">Return</a>
          </li>
        </ul>
        <div class="my-2 my-md-0">
          <a class="text-white" href="./logout.php"> 
            <span>
              <i class="fas fa-sign-out-alt"></i> Sign Out
            </span>
          </a>
        </div>
      </div>
  </nav>

  <div class="container-fluid">
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-3 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3" style="margin-top: 25px">
              <h2><strong>Return form</strong></h2>
                <p>Fill all form fields to verify return</p>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform" method="post">
                            <fieldset>
                                <div class="form-card">
                                  <h2 class="fs-title">Return Verification</h2>
                                    <?php  if (count($errors) > 0) : ?>
                                    <div class="form-errors">
                                    <?php foreach ($errors as $errror) : ?>
                                    <span><?php echo $errror ?></span>
                                    <?php endforeach ?>
                                    </div>
                                    <?php  endif ?>

                                    <?php  if (count($success) > 0) : ?>
                                    <div class="form-success"><strong>
                                    <?php foreach ($success as $succes) : ?>
                                    <span><?php echo $succes ?></span>
                                    <?php endforeach ?>
                                    </strong></div>
                                    <?php  endif ?>
                                  <div class="form-row">
                                    <div class="form-group col-md-7"> 
                                      <input type="text" name="borrowername" placeholder="Borrower's names e.g. Elly Decs" />
                                    </div>
                                    <div class="form-group col-md-5">
                                      <input type="text" name="borrowerdept" placeholder="Department name e.g. ICT" />
                                    </div>
                                  </div>
                                  <div class="control-group" style="padding-bottom: 20px">
                                    <div class="control-group">
                                      <select type = "text" id="select-device" name="devicename" placeholder="Select borrowed device..." style="border: none; border-bottom: 1px solid #ccc;">
                                        <option value="">Select device</option>
                                        <?php 
                                        $results = mysqli_query($connection, "SELECT * from devices where devicestatus = 'Unreturned'");

                                        if(mysqli_num_rows($results)> 0)
                                        {
                                          foreach($results as $result)
                                          {   ?>
                                          <option value="<?php echo $result['deviceid'];?>">
                                            Device Type: &nbsp;<?php echo $result['devicetype'];?>;&nbsp;
                                            Device Make: &nbsp;<?php echo $result['devicemake'];?>.
                                          </option>
                                          <?php 
                                          }
                                        } ?>
                                      </select>
                                    </div>
                                    <script>
                                    $('#select-device').selectize();
                                    </script>
                                    </div>
                                  <input type="text" name="serialno" placeholder="Device serial NO"/>

                                  <textarea name="condition" placeholder="Device condition description..."></textarea>
                                  <input type="text" name="verificationofficer" placeholder="Verification officer's name e.g Alex Belle" />
                                  <label for="verificationdate">Return Date:</label>
                                  <div class="input-group">
                                    <input type="date" name="verificationdate" class="form-control">
                                  </div>
                                  <div class="text-center">
                                    <button type="submit" name="return" style="width: 25%" class="btn-primary btn-return action-button">
                                      <span><i class="fas fa-check"></i></span> Verify Return
                                    </button>
                                  </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</body>
<footer class="footer3">
  <div class="row bg-dark d-flex">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-top: 10px">
      <span class="text-left text-white" style="margin-left: 10px;">
        Copyright &copy; 2020. <b class="text-secondary">Requisition Form.</b>
      </span> 
      <span class="text-white" style="float: right; margin-right: 10px">
        Powered by: <b class="text-secondary">aleksSoftwares</b>
      </span>  
      <hr class="hr-dark">
    </div>
  </div>
</footer>
</html>
<script>
  $(document).ready(function(){
    $('#signup').modal({
      backdrop: 'static',
      keyboard: false
    });
  });
</script>

<?php
  //  Close connection
  if(isset($connection))
    {
  mysqli_close($connection);
  }
?>