<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();

	$proc=$_POST["proc"].$_GET["proc"];
	######################################################################################################
    if ($proc == "add") {
        $data1=$_POST['group']; 
        $data2=$_POST['value']; 
        $data3=date("Y/m/d");
        $data4=$_POST['type']; 
		$AREA=$_SESSION["AD_AREA"];
		$PROCESSBY=$_SESSION["AD_PERSONCODE"];
		$PROCESSDATE=date("Y-m-d H:i:s");


		$check = "SELECT * FROM CHECKMAN WHERE CM_DATE='$data3' AND CM_GROUP='$data1' AND CM_AREA='$AREA'";
		$querycheck = sqlsrv_query($conn,$check);
		$resultcheck = sqlsrv_fetch_array($querycheck, SQLSRV_FETCH_ASSOC);
		$chk1null = $resultcheck['CM_ID'];
		if($chk1null != ""){    			
			$sql = "UPDATE CHECKMAN SET $data4=?,CM_EDITDATE=?,CM_EDITBY=? WHERE CM_ID = ? ";
			$params = array($data2,$PROCESSDATE,$PROCESSBY,$chk1null);
			$stmt = sqlsrv_query( $conn, $sql, $params);
		}else if($chk1null == ""){      
			$sql = "INSERT INTO CHECKMAN ($data4,CM_DATE,CM_CREATEDATE,CM_CREATEBY,CM_GROUP,CM_AREA) VALUES (?,?,?,?,?,?)";
			$params = array($data2,$data3,$PROCESSDATE,$PROCESSBY,$data1,$AREA);	
		}

		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}else{
			print "บันทึกข้อมูลเรียบร้อยแล้ว";	
		}	
    }	
?>