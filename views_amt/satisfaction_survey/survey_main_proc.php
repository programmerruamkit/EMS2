<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();

	$proc = $_GET["proc"] ? $_GET["proc"] : $_POST["proc"];
	
	if($proc == "add") {
		$sm_code = $_POST["sm_code"];
		$sm_name = $_POST["sm_name"];
		$sm_description = $_POST["sm_description"];
		$sm_target_group = $_POST["sm_target_group"];
		$sm_status = $_POST["sm_status"];
		$session_area = $_POST["session_area"];
		$session_personcode = $_POST["session_personcode"];

		$sql = "INSERT INTO SURVEY_MAIN (SM_CODE, SM_NAME, SM_DESCRIPTION, SM_TARGET_GROUP, SM_STATUS, SM_AREA, SM_CREATED_BY, SM_CREATED_DATE) 
				VALUES (?, ?, ?, ?, ?, ?, ?, GETDATE())";
		$params = array($sm_code, $sm_name, $sm_description, $sm_target_group, $sm_status, $session_area, $session_personcode);

		$stmt = sqlsrv_query($conn, $sql, $params);
		if($stmt === false) {
			die(print_r(sqlsrv_errors(), true));
		} else {
			print "เพิ่มกลุ่มแบบประเมินเรียบร้อยแล้ว";	
		}
	}
	######################################################################################################
	if($proc == "edit") {
		$sm_code_old = $_POST["sm_code_old"];
		$sm_code = $_POST["sm_code"];
		$sm_name = $_POST["sm_name"];
		$sm_description = $_POST["sm_description"];
		$sm_target_group = $_POST["sm_target_group"];
		$sm_status = $_POST["sm_status"];
		$session_personcode = $_POST["session_personcode"];

		$sql = "UPDATE SURVEY_MAIN SET 
				SM_NAME = ?, 
				SM_DESCRIPTION = ?, 
				SM_TARGET_GROUP = ?, 
				SM_STATUS = ?, 
				SM_UPDATED_BY = ?, 
				SM_UPDATED_DATE = GETDATE() 
				WHERE SM_CODE = ?";
		$params = array($sm_name, $sm_description, $sm_target_group, $sm_status, $session_personcode, $sm_code_old);

		$stmt = sqlsrv_query($conn, $sql, $params);
		if($stmt === false) {
			die(print_r(sqlsrv_errors(), true));
		} else {
			print "แก้ไขกลุ่มแบบประเมินเรียบร้อยแล้ว";	
		}
	}
	######################################################################################################
	if($proc == "delete") {
		$sm_code = $_GET["id"];
		
		$sql = "UPDATE SURVEY_MAIN SET SM_STATUS = 'D' WHERE SM_CODE = ?";
		$params = array($sm_code);

		$stmt = sqlsrv_query($conn, $sql, $params);
		if($stmt === false) {
			die(print_r(sqlsrv_errors(), true));
		} else {
			print "ลบกลุ่มแบบประเมินเรียบร้อยแล้ว";	
		}
	}
?>