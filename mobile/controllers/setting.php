<?php include('../models/setting.php');
	// echo "<script>";
	// echo "alert($logact);";
	// echo "</script>";
    if ($_POST['keyword'] == "login_session") {
		$KEYWORD=$_POST['keyword'];
		$username=$_POST['username'];
		$password=$_POST['password'];
		$area=$_POST['area'];  
		$rs = loginsession($KEYWORD,  $username,  $password, $area);
		switch ($rs) {
		  case 'complete':{
				// echo json_encode(array("statusCode"=>200));
			}
		  break;
		  case 'error':{
				// echo json_encode(array("statusCode"=>201));
			}
		  break;
			default :{echo $rs;}
		  break;
		}
	}
    if ($_POST['keyword'] == "login_session_mobile") {
		$KEYWORD=$_POST['keyword'];
		$username=$_POST['username'];
		$password=$_POST['password'];
		$area=$_POST['area'];
		$rs = loginsessionmobile($KEYWORD,  $username,  $password, $area);
		switch ($rs) {
		  case 'complete':{
				// echo json_encode(array("statusCode"=>200));
			}
		  break;
		  case 'error':{
				// echo json_encode(array("statusCode"=>201));
			}
		  break;
			default :{echo $rs;}
		  break;
		}
	}
    if ($_POST['keyword'] == "log_outsession") {
		$KEYWORD=$_POST['keyword'];
		$personcode=$_POST['personcode'];
		$logact=$_POST['logact'];
		$rs = logout($KEYWORD,  $personcode,  $logact);
		switch ($rs) {
		  case 'complete':{
				session_destroy();
				// echo json_encode(array("statusCode"=>200));
			}
		  break;
		  case 'error':{
				// echo json_encode(array("statusCode"=>201));
			}
		  break;
			default :{echo $rs;}
		  break;
		}
	}
?>
