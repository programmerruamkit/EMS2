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
    if ($_POST['keyword'] == "log_repass") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logrepass($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "role_session") {
		$KEYWORD=$_POST['keyword'];
		$username=$_POST['username'];
		$password=$_POST['password'];
		$role=$_POST['role'];
		$roleaccount=$_POST['roleaccount'];
		$rs = rolesession($KEYWORD,  $username,  $password, $role, $roleaccount);
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
    if ($_POST['keyword'] == "log_transac_menu") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacmenu($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_role") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacrole($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_roleaccount") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacroleaccount($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_repairhole") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacrepairhole($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_repairman") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacrepairman($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_outergarage") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacoutergarage($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_customer") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransaccustomer($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_customer_car") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransaccustomercar($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_nature") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacnature($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_nature_sub") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacnaturesub($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_mileagepm") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacmileagepm($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_sparepart") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacsparepart($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_typerepairwork") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransactyperepairwork($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_checklist") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacchecklist($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_cartype") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransaccartype($KEYWORD,  $logcode,  $remark,  $refcode);
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
    if ($_POST['keyword'] == "log_transac_requestrepair") {
		$KEYWORD=$_POST['keyword'];
		$logcode=$_POST['logcode'];
		$remark=$_POST['remark'];
		$refcode=$_POST['refcode'];
		$rs = logtransacrequestrepair($KEYWORD,  $logcode,  $remark,  $refcode);
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
?>
