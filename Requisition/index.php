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
  
  if (isset($_POST['confirm']) && isset($_SESSION['id']) && isset($_SESSION['user'])) 
  {
    $laptopmake = mysqli_real_escape_string($connection, $_POST['laptopmake']); 
    $laptopno = mysqli_real_escape_string($connection, $_POST['laptopno']);
    $Lptissuingdate = mysqli_real_escape_string($connection, $_POST['Lptissuingdate']);
    $Lptduedate = mysqli_real_escape_string($connection, $_POST['Lptduedate']);
    $laptop = mysqli_real_escape_string($connection, $_POST['laptop']);

    $projectermake = mysqli_real_escape_string($connection, $_POST['projectermake']);
    $projecterno = mysqli_real_escape_string($connection, $_POST['projecterno']);
    $Lcdissuingdate = mysqli_real_escape_string($connection, $_POST['Lcdissuingdate']);
    $Lcdduedate = mysqli_real_escape_string($connection, $_POST['Lcdduedate']);
    $projector = mysqli_real_escape_string($connection, $_POST['projector']);

    $borrowername = mysqli_real_escape_string($connection, $_POST['borrowername']);
    $borrowerdept = mysqli_real_escape_string($connection, $_POST['borrowerdept']);
    $authorizername = mysqli_real_escape_string($connection, $_POST['authorizername']);
    $authorizeddate = mysqli_real_escape_string($connection, $_POST['authorizeddate']);

    if (empty($laptopmake) || empty($laptopno) || empty($Lptissuingdate) || empty($Lptduedate) || empty($projectermake)
      || empty($projecterno) || empty($Lcdissuingdate) || empty($Lcdduedate) || empty($borrowername) || empty($borrowerdept) || empty($authorizername) || empty($authorizeddate)) 
    {
      array_push($errors, "Please fill all the fields!");
    }
    $queryresults1 = mysqli_query($connection, "SELECT * from devices where serialno = '$laptopno'");
    $queryresults2 = mysqli_query($connection, "SELECT * from devices where serialno = '$projecterno'");
    $row1 = mysqli_fetch_array($queryresults1);
    $row2 = mysqli_fetch_array($queryresults2);
    $Serialno1 = $row1['serialno'];
    $Serialno2 = $row2['serialno'];
    if (($laptopno == $Serialno1) || ($projecterno == $Serialno2)) 
    {
      array_push($errors, "Laptop/ Projector under the provided serial NO already borrowed!");
    }

    $duration1 = (date("Y-m-d")-$Lptissuingdate);
    $duration2 = (date("Y-m-d")-$Lptduedate);
    $duration3 = (date("Y-m-d")-$Lcdissuingdate);
    $duration4 = (date("Y-m-d")-$Lcdduedate);
    $duration5 = (date("Y-m-d")-$authorizeddate);
    if ((($duration1<0) OR ($duration1 > 3)) || (($duration2<0) OR ($duration2 > 5)) || (($duration3<0) OR ($duration3 > 3)) || (($duration4<0) OR ($duration4 > 5)) || (($duration5<0) OR ($duration5 > 3))) 
    {
      array_push($errors, "Check whether all dates are valid!");
    } 

    if (count($errors)==0)
    {
      $sql1 = mysqli_query($connection, "INSERT INTO devices(devicetype, devicemake, serialno, borrowername, borrowdate, authorizername, devicestatus, returndate, verificationofficer, devicecondition) 
      VALUES ('$laptop', '$laptopmake', '$laptopno', '$borrowername', '$Lptissuingdate', '$authorizername', 'Unreturned', 'N_A', 'N_A', 'N_A')") or die(mysqli_error($connection));
      $sql2 = mysqli_query($connection, "INSERT INTO devices(devicetype, devicemake, serialno, borrowername, borrowdate, authorizername, devicestatus, returndate, verificationofficer, devicecondition) 
      VALUES ('$projector', '$projectermake', '$projecterno', '$borrowername', '$Lcdissuingdate', '$authorizername', 'Unreturned', 'N_A', 'N_A', 'N_A')") or die(mysqli_error($connection));
      if (($sql1 == TRUE) && ($sql2 == TRUE)) 
      {
        array_push($success, "Device/s borrowed successfully!");
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
        <link href="./resources/all.min.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="./style.css" rel="stylesheet" type="text/css" media="screen"/>
        <script>window.jQuery || document.write('<script src="./resources/jquery-slim.min.js"><\/script>')</script>
        <script type="text/javascript" src="./resources/bootstrap.bundle.js"></script>
        <script src="./resources/popper.min.js"></script>
        <script src="./resources/bootstrap.js"></script>
        <script src="./resources/all.min.js"></script>
</head>
<body>
  <nav class="navbar navbar-expand navbar-dark bg-dark">
      <a class="navbar-brand" href="#">Kenya School of Government.</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExample02">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Requisition |<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="return.php">Return</a>
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
<!-- MultiStep Form -->
<div class="container-fluid index">
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-3 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                <h2><strong>Requisition forms.</strong></h2>
                <p>Fill all form field to go to next step</p>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform" method="post">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="laptop"><strong>Laptop Requisition</strong></li>
                                <li id="lcd"><strong>LCD Project Requisition</strong></li>
                                <li id="authorization"><strong>Authorization</strong></li>
                                <li id="confirm"><strong>Finish</strong></li>
                            </ul> <!-- fieldsets -->
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">Laptop Requisition</h2>
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

                                    <input type="text"name="laptopmake"placeholder="Laptop make e.g. Lenovo Thinkpad"/>
                                    <input type="text" name="laptopno" placeholder="Laptop serial no." />
                                    <div class="form-row">
                                      <div class="form-group col-md-6">
                                        <label for="Lptissuingdate">Date Issued:</label>
                                        <div class="input-group">
                                          <input type="date" name="Lptissuingdate"class="form-control">
                                        </div>
                                      </div>
                                      <div class="form-group col-md-6">
                                        <label for="Lptduedate">Return Date:</label>
                                        <div class="input-group">
                                          <input type="date" name="Lptduedate"id="Lptduedate"class="form-control">
                                        </div>
                                      </div>
                                      <input type="hidden" name="laptop" value="Laptop">
                                    </div>
                                 </div>
                                <input type="button" name="next" class="next action-button" value="Next" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">LCD Project Requisition</h2> 
                                    <input type="text" name="projectermake" placeholder="LCD Projector make e.g. SONY ABCD1" />
                                    <input type="text" name="projecterno" placeholder="LCD Projector serial no." />
                                    <div class="form-row">
                                      <div class="form-group col-md-6">
                                        <label for="Lcdissuingdate">Date Issued:</label>
                                        <div class="input-group">
                                          <input type="date" name="Lcdissuingdate" class="form-control">
                                        </div>
                                      </div>
                                      <div class="form-group col-md-6">
                                        <label for="Lcdduedate">Return Date:</label>
                                        <div class="input-group">
                                          <input type="date" name="Lcdduedate"id="Lcdduedate"class="form-control">
                                        </div>
                                      </div>
                                      <input type="hidden" name="projector" value="LCD Projector">
                                    </div>
                                </div> 
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
                                <input type="button" name="next" class="next action-button" value="Next" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                  <h2 class="fs-title">Authorization</h2>
                                  <input type="text" name="borrowername" placeholder="Borrower's names e.g. Elly Decs" />
                                  <input type="text" name="borrowerdept" placeholder="Department name e.g. ICT" />
                                  <br>
                                  <input type="text" name="authorizername" placeholder="Authorization officer's name e.g Alex Belle" />
                                  <label for="authorizeddate">Authorization Date:</label>
                                  <div class="input-group">
                                    <input type="date" name="authorizeddate" class="form-control">
                                  </div>
                                </div> 
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
                                <input type="button" name="next" class="next action-button" value="Next" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title text-center">Success !</h2> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image"> </div>
                                    </div> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5>Authorization successful. Press confirm to submit.</h5>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" name="confirm" class="btn-return btn-secondary action-button">
                                  <span><i class="fas fa-check"></i></span> Confirm
                                </button>
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

<script type="text/javascript">
  $(document).ready(function(){

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;

$(".next").click(function(){

current_fs = $(this).parent();
next_fs = $(this).parent().next();

//Add Class Active
$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

//show the next fieldset
next_fs.show();
current_fs.hide();
//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
next_fs.css({'opacity': opacity});
},
duration: 600
});
});

$(".previous").click(function(){

current_fs = $(this).parent();
previous_fs = $(this).parent().prev();

//Remove class active
$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
previous_fs.show();
current_fs.hide();

//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
previous_fs.css({'opacity': opacity});
},
duration: 600
});
});

$('.radio-group .radio').click(function(){
$(this).parent().find('.radio').removeClass('selected');
$(this).addClass('selected');
});

$(".submit").click(function(){
return false;
})

});
</script>

<?php
  //  Close connection
  if(isset($connection))
    {
  mysqli_close($connection);
  }
?>