<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');

	// echo"<pre>";
	// print_r($_POST);
	// echo"</pre>";
	// exit();

	$proc=$_POST["proc"].$_GET["proc"];
	$id=$_GET["id"];	
	$CTMC_AREA = $_POST["area"];
	
	$SESSION_AREA=$_SESSION["AD_AREA"];
	
	if($proc=="add"){
		$CTMC_CODE = $_POST["ctmc_code"];
		$VEHICLEREGISNUMBER = $_POST["VEHICLEREGISNUMBER"];
		$VEHICLEGROUPDESC = $_POST["VEHICLEGROUPDESC"];
		$VEHICLETYPEDESC = $_POST["VEHICLETYPEDESC"];
		$BRANDDESC = $_POST["BRANDDESC"];
		$SERIES = $_POST["SERIES"];
		$COLORDESC = $_POST["COLORDESC"];
		$GEARTYPEDESC = $_POST["GEARTYPEDESC"];
		$HORSEPOWER = $_POST["HORSEPOWER"];
		$CC = $_POST["CC"];
		$MACHINENUMBER = $_POST["MACHINENUMBER"];
		$CHASSISNUMBER = $_POST["CHASSISNUMBER"];
		$ENERGY = $_POST["ENERGY"];
		$WEIGHT = $_POST["WEIGHT"];
		$AXLETYPE = $_POST["AXLETYPE"];
		$PISTON = $_POST["PISTON"];
		$MAXIMUMLOAD = $_POST["MAXIMUMLOAD"];
		$USED = $_POST["USED"];
		$AFFCOMPANY = $_POST["AFFCOMPANY"];
		$REMARK = $_POST["REMARK"];
		$ACTIVESTATUS = $_POST["ACTIVESTATUS"];
		$CTMC_CREATEBY = $_SESSION["AD_PERSONCODE"];
		$CTMC_CREATEDATE = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO CUSTOMER_CAR (CTMC_CODE,VEHICLEREGISNUMBER,VEHICLEGROUPDESC,VEHICLETYPEDESC,BRANDDESC,SERIES,COLORDESC,GEARTYPEDESC,HORSEPOWER,CC,MACHINENUMBER,CHASSISNUMBER,ENERGY,WEIGHT,AXLETYPE,PISTON,MAXIMUMLOAD,USED,AFFCOMPANY,REMARK,ACTIVESTATUS,CTMC_CREATEBY,CTMC_CREATEDATE) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$params = array($CTMC_CODE,$VEHICLEREGISNUMBER,$VEHICLEGROUPDESC,$VEHICLETYPEDESC,$BRANDDESC,$SERIES,$COLORDESC,$GEARTYPEDESC,$HORSEPOWER,$CC,$MACHINENUMBER,$CHASSISNUMBER,$ENERGY,$WEIGHT,$AXLETYPE,$PISTON,$MAXIMUMLOAD,$USED,$AFFCOMPANY,$REMARK,$ACTIVESTATUS,$CTMC_CREATEBY,$CTMC_CREATEDATE);
		
		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "บันทึกข้อมูลเรียบร้อยแล้ว";	
		}	
	};
	######################################################################################################
	if($proc=="edit"){
		$VEHICLEINFOID = $_POST["VEHICLEINFOID"];
		$VEHICLEREGISNUMBER = $_POST["VEHICLEREGISNUMBER"];
		$VEHICLEGROUPDESC = $_POST["VEHICLEGROUPDESC"];
		$VEHICLETYPEDESC = $_POST["VEHICLETYPEDESC"];
		$BRANDDESC = $_POST["BRANDDESC"];
		$SERIES = $_POST["SERIES"];
		$COLORDESC = $_POST["COLORDESC"];
		$GEARTYPEDESC = $_POST["GEARTYPEDESC"];
		$HORSEPOWER = $_POST["HORSEPOWER"];
		$CC = $_POST["CC"];		
		$MACHINENUMBER = $_POST["MACHINENUMBER"];
		$CHASSISNUMBER = $_POST["CHASSISNUMBER"];
		$ENERGY = $_POST["ENERGY"];
		$WEIGHT = $_POST["WEIGHT"];
		$AXLETYPE = $_POST["AXLETYPE"];
		$PISTON = $_POST["PISTON"];
		$MAXIMUMLOAD = $_POST["MAXIMUMLOAD"];
		$USED = $_POST["USED"];
		$REMARK = $_POST["REMARK"];
		$ACTIVESTATUS = $_POST["ACTIVESTATUS"];
		$CTMC_EDITBY = $_SESSION["AD_PERSONCODE"];
		$CTMC_EDITDATE = date("Y-m-d H:i:s");
	
		$sql = "UPDATE CUSTOMER_CAR SET 
					VEHICLEREGISNUMBER = ?,
					VEHICLEGROUPDESC = ?,
					VEHICLETYPEDESC = ?,
					BRANDDESC = ?,
					SERIES = ?,
					COLORDESC = ?,
					GEARTYPEDESC = ?,
					HORSEPOWER = ?,
					CC = ?,
					MACHINENUMBER = ? ,
					CHASSISNUMBER = ? ,
					ENERGY  = ? ,
					WEIGHT = ? ,
					AXLETYPE = ?,
					PISTON = ?,
					MAXIMUMLOAD = ?,
					USED = ?,
					REMARK = ? ,
					ACTIVESTATUS = ? ,
					CTMC_EDITBY = ?,
					CTMC_EDITDATE = ?
					WHERE VEHICLEINFOID = ? ";
		$params = array($VEHICLEREGISNUMBER,$VEHICLEGROUPDESC,$VEHICLETYPEDESC,$BRANDDESC,$SERIES,$COLORDESC,$GEARTYPEDESC,$HORSEPOWER,$CC,$MACHINENUMBER,$CHASSISNUMBER,$ENERGY,$WEIGHT,$AXLETYPE,$PISTON,$MAXIMUMLOAD,$USED,$REMARK,$ACTIVESTATUS,$CTMC_EDITBY,$CTMC_EDITDATE,$VEHICLEINFOID);
	
		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "แก้ไขข้อมูลเรียบร้อยแล้ว";	
		}		
	};
	######################################################################################################
	if($proc=="delete" && !empty($id)){
		
		$ACTIVESTATUS = '0';
		$CTMC_EDITBY = $_SESSION["AD_PERSONCODE"];
		$CTMC_EDITDATE = date("Y-m-d H:i:s");
		$sql = "UPDATE CUSTOMER_CAR SET ACTIVESTATUS = ?,CTMC_EDITBY = ?,CTMC_EDITDATE = ? WHERE CTMC_CODE = ? ";
		$params = array($ACTIVESTATUS, $CTMC_EDITBY, $CTMC_EDITDATE, $id);

		// $sql = "DELETE FROM CUSTOMER_CAR
		// 		WHERE VEHICLEINFOID = ? ";
		// $params = array($id);

		$stmt = sqlsrv_query( $conn, $sql, $params);
		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
		else
		{
			print "ลบข้อมูลเรียบร้อยแล้ว";	
		}		
	};	
	######################################################################################################
	if($_POST['target'] =="save_cargroup"){
              
		$VHCRGNB = $_POST["vhcrgnb"];
		$VHCCT_ID = $_POST["vhcctid"];		
		$PROCESS_BY = $_SESSION["AD_PERSONCODE"];
		$PROCESS_DATE = date("Y-m-d H:i:s");		
		
		$check = "SELECT * FROM VEHICLECARTYPEMATCHGROUP WHERE VHCTMG_VEHICLEREGISNUMBER = '$VHCRGNB'";
		$querycheck = sqlsrv_query( $conn, $check);
		$resultcheck = sqlsrv_fetch_array($querycheck, SQLSRV_FETCH_ASSOC);
		$chk1null = $resultcheck['VHCTMG_VEHICLEREGISNUMBER'];
			
		$sql_cartype = "SELECT * FROM VEHICLECARTYPE WHERE VHCCT_ID = '$VHCCT_ID' AND VHCCT_AREA = '$SESSION_AREA'";
		$query_cartype = sqlsrv_query($conn, $sql_cartype);
		$result_cartype = sqlsrv_fetch_array($query_cartype, SQLSRV_FETCH_ASSOC);
			$VHCCT_ID=$result_cartype['VHCCT_ID'];
			$VHCCT_CODE=$result_cartype['VHCCT_CODE'];
			$VHCCT_LINEOFWORK=$result_cartype['VHCCT_LINEOFWORK'];
			$VHCCT_SERIES=$result_cartype['VHCCT_SERIES'];
			$VHCCT_NAMETYPE=$result_cartype['VHCCT_NAMETYPE']; 

		if($chk1null != ""){    
			
			$sql = "UPDATE VEHICLECARTYPEMATCHGROUP SET 
					VHCCT_ID = ?,
					VHCTMG_LINEOFWORK = ?,
					VHCTMG_SERIES = ?,
					VHCTMG_NAMETYPE = ?,
					VHCTMG_EDITBY = ?,
					VHCTMG_EDITDATE = ?
					WHERE VHCTMG_VEHICLEREGISNUMBER = ? ";
			$params = array($VHCCT_ID,$VHCCT_LINEOFWORK,$VHCCT_SERIES,$VHCCT_NAMETYPE,$PROCESS_BY,$PROCESS_DATE,$VHCRGNB);
			$stmt = sqlsrv_query( $conn, $sql, $params);

			if( $stmt === false ) {
				die( print_r( sqlsrv_errors(), true));
			}else{
				print "อัพเดทเรียบร้อย";	
			}
		}else if($chk1null == ""){      
			
			$n=6;
			function RandNum($n) {
				$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$randomString = '';      
				for ($i = 0; $i < $n; $i++) {
					$index = rand(0, strlen($characters) - 1);
					$randomString .= $characters[$index];
				}      
				return $randomString;
			}  
			$rand="VHCCTMG_".RandNum($n);

			$sql1 = "INSERT INTO VEHICLECARTYPEMATCHGROUP (VHCTMG_CODE,VHCCT_ID,VHCTMG_VEHICLEREGISNUMBER,VHCTMG_LINEOFWORK,VHCTMG_SERIES,VHCTMG_NAMETYPE,VHCTMG_CREATEBY,VHCTMG_CREATEDATE) VALUES (?,?,?,?,?,?,?,?)";
			$params1 = array($rand,$VHCCT_ID,$VHCRGNB,$VHCCT_LINEOFWORK,$VHCCT_SERIES,$VHCCT_NAMETYPE,$PROCESS_BY,$PROCESS_DATE);
			$stmt1 = sqlsrv_query( $conn, $sql1, $params1);	
			if( $stmt1 === false ) {
				die( print_r( sqlsrv_errors(), true));
			}else{
				print "เพิ่มเรียบร้อย";	
			}
		}
	}
	######################################################################################################
	if($_POST['target'] =="save_kiloforday"){
              
		$VHCRGNB = $_POST["vhcrgnb"];
		$VHCTMG_KILOFORDAY = $_POST["kilo"];	
		$PROCESS_BY = $_SESSION["AD_PERSONCODE"];
		$PROCESS_DATE = date("Y-m-d H:i:s");		
		
		$check = "SELECT * FROM VEHICLECARTYPEMATCHGROUP WHERE VHCTMG_VEHICLEREGISNUMBER = '$VHCRGNB'";
		$querycheck = sqlsrv_query( $conn, $check);
		$resultcheck = sqlsrv_fetch_array($querycheck, SQLSRV_FETCH_ASSOC);
		$chk1null = $resultcheck['VHCTMG_VEHICLEREGISNUMBER'];

		if($chk1null != ""){    
			
			$sql = "UPDATE VEHICLECARTYPEMATCHGROUP SET 
					VHCTMG_KILOFORDAY = ?,
					VHCTMG_EDITBY = ?,
					VHCTMG_EDITDATE = ?
					WHERE VHCTMG_VEHICLEREGISNUMBER = ? ";
			$params = array($VHCTMG_KILOFORDAY,$PROCESS_BY,$PROCESS_DATE,$VHCRGNB);
			$stmt = sqlsrv_query( $conn, $sql, $params);

			if( $stmt === false ) {
				die( print_r( sqlsrv_errors(), true));
			}else{
				print "อัพเดทเรียบร้อย";	
			}
		}else if($chk1null == ""){      
			
			$n=6;
			function RandNum($n) {
				$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$randomString = '';      
				for ($i = 0; $i < $n; $i++) {
					$index = rand(0, strlen($characters) - 1);
					$randomString .= $characters[$index];
				}      
				return $randomString;
			}  
			$rand="VHCCTMG_".RandNum($n);

			$sql1 = "INSERT INTO VEHICLECARTYPEMATCHGROUP (VHCTMG_CODE,VHCTMG_VEHICLEREGISNUMBER,VHCTMG_KILOFORDAY,VHCTMG_CREATEBY,VHCTMG_CREATEDATE) VALUES (?,?,?,?,?)";
			$params1 = array($rand,$VHCRGNB,$VHCTMG_KILOFORDAY,$PROCESS_BY,$PROCESS_DATE);
			$stmt1 = sqlsrv_query( $conn, $sql1, $params1);	
			if( $stmt1 === false ) {
				die( print_r( sqlsrv_errors(), true));
			}else{
				print "เพิ่มเรียบร้อย";	
			}
		}
	}
	######################################################################################################
?>