<?php 
session_name("EMS"); session_start();
$path = '../';
// print_r ($_SESSION);
include($path.'include/connect.php');
if($_GET['menu_id']=="") exit;
?>
<ul id="browser" class="filetree treeview-famfamfam">
	<?php 
		$menu_id=$_GET['menu_id'];
		$SS_AREA=$_SESSION["AD_AREA"];
		$SS_ROLENAME=$_SESSION["AD_ROLE_NAME"];
		if($menu_id!="14"){
			$sql_menumain = "SELECT * FROM vwMENU 
			WHERE MN_ID = '$menu_id' AND MN_STATUS = 'Y' AND RM_STATUS = 'Y' AND AREA = '$SS_AREA' AND RU_NAME = '$SS_ROLENAME' ORDER BY MN_SORT ASC";
			$query_menumain = sqlsrv_query($conn, $sql_menumain);
			while($result_menumain = sqlsrv_fetch_array($query_menumain, SQLSRV_FETCH_ASSOC)){		
				print "<span class=\"\" style=\"font-weight:bold;\" ><li><font size='3px'><b>".$result_menumain['MN_NAME']."</b></font></li></span>";   ////////////  head1
			}
			$sql_menusub = "SELECT * FROM vwMENU 
			WHERE MN_LEVEL = '2' AND MN_STATUS = 'Y' AND RM_STATUS = 'Y' AND MN_PARENT = '$menu_id' AND AREA = '$SS_AREA' AND RU_NAME = '$SS_ROLENAME' ORDER BY MN_SORT ASC";
			$query_menusub = sqlsrv_query($conn, $sql_menusub);
			while($result_menusub = sqlsrv_fetch_array($query_menusub, SQLSRV_FETCH_ASSOC)){		
				print "<li onclick=\"loadViewdetail('".$path.$result_menusub['MN_URL']."','".$result_menusub["MN_ID"]."')\" style='cursor:pointer'><i class='gg-chevron-right'></i>&nbsp;&nbsp;&nbsp;<font size='3px'>".$result_menusub['MN_NAME']."</font></li>";
			}
		}else{
			$sql_menumain = "SELECT * FROM MENU WHERE MN_CODE = 'MN_SETTING'";
			$query_menumain = sqlsrv_query($conn, $sql_menumain);
			while($result_menumain = sqlsrv_fetch_array($query_menumain, SQLSRV_FETCH_ASSOC)){		
				print "<span class=\"\" style=\"font-weight:bold;\" ><li><font size='3px'><b>".$result_menumain['MN_NAME']."</b></font></li></span>";   ////////////  head1
			}
			$sql_menusub = "SELECT * FROM MENU WHERE MN_PARENT = '14'";
			$query_menusub = sqlsrv_query($conn, $sql_menusub);
			while($result_menusub = sqlsrv_fetch_array($query_menusub, SQLSRV_FETCH_ASSOC)){		
				print "<li onclick=\"loadViewdetail('".$path.$result_menusub['MN_URL']."','".$result_menusub["MN_ID"]."')\" style='cursor:pointer'><i class='gg-chevron-right'></i>&nbsp;&nbsp;&nbsp;<font size='3px'>".$result_menusub['MN_NAME']."</font></li>";
			}
		}
?>
</ul>

<style>	
	/* gg-asterisk */
		.gg-asterisk {
			box-sizing: border-box;
			position: absolute;
			display: block;
			transform: scale(var(--ggs, 1));
			width: 12px;
			height: 12px;
			border-left: 5px solid transparent;
			border-right: 5px solid transparent;
			box-shadow: inset 0 0 0 2px
		}
		.gg-asterisk::after,
		.gg-asterisk::before {
			content: "";
			display: block;
			position: absolute;
			box-sizing: border-box;
			width: 2px;
			height: 12px;
			background: currentColor;
			transform: rotate(55deg)
		}
		.gg-asterisk::after {
			transform: rotate(-55deg)
		}

	/* chevron-right */
		.gg-chevron-right {
			box-sizing: border-box;
			position: absolute;
			display: block;
			transform: scale(var(--ggs, 1));
			width: 15px;
			height: 15px;
			border: 2px solid transparent;
			border-radius: 100px
		}
		.gg-chevron-right::after {
			content: "";
			display: block;
			box-sizing: border-box;
			position: absolute;
			width: 8px;
			height: 8px;
			border-bottom: 2px solid;
			border-right: 2px solid;
			transform: rotate(-45deg);
			right: 6px;
			top: 4px
		}
</style>
