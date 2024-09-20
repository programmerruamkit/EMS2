<?php
	session_start ();
	$path='../';
	require($path."include/authen.php"); 
	require($path."include/connect.php");
	require($path."include/head.php");		
	require($path."include/script.php"); 
	// print_r ($_SESSION);
	##########################################################################################################################
		// echo "<br>";
		// echo date("D, F j, Y H:i:s");
		// echo "<br>";
		// echo getenv("REMOTE_ADDR");
		// echo "<br>";
		// echo getenv("HTTP_USER_AGENT");
		// echo "<br>";
		// echo getenv("HTTP_REFERER");
		// echo "<br>";
		// echo PHP_VERSION;
		// echo "<br>";
		// echo PHP_OS;
		// echo "<br>";
		// echo getenv("SERVER_SOFTWARE");
		// echo "<br>";
		// echo getenv("SERVER_NAME");
		// echo "<br>";
		// echo getenv("SCRIPT_NAME");	
?>
<script type="text/javascript">
	var global_path = "<?=$path?>";
	$(document).ready(function(e) {
		$(this).scroll(function() {
			if(parseInt($(this).scrollTop())>=(parseInt($("table.comtop tr td img").attr("height"))+5) )$("#main_menu_top").css("position","fixed");
			else $("#main_menu_top").css("position","");
		});
		treeMenu(); 
        genMenuSidebar("<?=$_GET['menu_id']?>"); 
		//Avariable For Function Dialog Popup Step 1#########################
		$('#dialog_popup').dialog({
				autoOpen: false,
				modal: true,
				draggable : true
		});
		$("#dialog_popup").on("dialogclose",function(event,ui){$("#dialog_popup").empty();});
		//Avariable For Function Dialog Popup Step 1#########################
    });/// END $(document).ready
</script>
<body> 
	<?php
		// echo"<pre>";
		// print_r($_GET);
		// print_r($_POST);
		// echo"</pre>";
		// exit();
	?>
	<div id="dialog_popup" title="เพิ่ม / แก้ไขรายการ" align="center"></div>
	<table width="100%"  height="100%"  border="0" cellpadding="0" cellspacing="0" class="no-border"> <!--main_data -->
		<tr valign="top">
			<td height="1"><?php include ($path."include/navtop.php");?></td><!-- height="18" -->
		</tr>
		<tr valign="top">
			<td><input type="hidden" id="toggle_menu" value="ปิด">
				<input name="current_menu_id" type="hidden" id="current_menu_id">
				<?php if($_GET["menu_id"]!='dashboard'){ ?>
					<table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" id="main">
						<tr>
							<?php if(trim($_GET["menu_id"])!=""){ ?>
								<td width="15%" valign="top" id="td_sidebar" bgcolor="#DEDEDE"><div class="sidebar" id="sidebar"></div> </td>
							<?php }else{ ?>
								<td width="15%" valign="top" id="td_sidebar"><div class="sidebar" id="sidebar"></div> </td>
							<?php } ?>
							<td bgcolor="#666666" onClick="toggleTreeMenu()" style="cursor:pointer;">
								<?php if(trim($_GET["menu_id"])!=""){?>
									<table width="100%" height="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999">
										<tr>
											<td><div id="toggle"><img src="https://img2.pic.in.th/pic/middle_toggle_left.gif" style="cursor:pointer"></div></td>
										</tr>
									</table>
								<?php }?>
							</td>
							<td width="86%" valign="top" id="td_detail">		
								<div id="show_detail" class="show_detail" align="center">
									<?php if(isset($_GET["getpage"])){?>
										<?php if($_GET["getpage"]=='repairbm'){?>
											<script type="text/javascript">
												function loadpage(){loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_bm.php');}window.onload=loadpage;
											</script>
										<?php } ?>
										<?php if($_GET["getpage"]=='repairpm'){?>
											<script type="text/javascript">
												function loadpage(){loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm.php');}window.onload=loadpage;
											</script>
										<?php } ?>
										<?php if($_GET["getpage"]=='chknap'){?>
											<script type="text/javascript">
												function loadpage(){loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_checknonapprove.php');}window.onload=loadpage;
											</script>
										<?php } ?>
										<?php if($_GET["getpage"]=='approve'){?>
											<script type="text/javascript">
												function loadpage(){loadViewdetail('<?=$path?>views_amt/approve_manage/approve_manage.php?type=bm');}window.onload=loadpage;
											</script>
										<?php } ?>
										<?php if($_GET["getpage"]=='checkrpm'){?>
											<script type="text/javascript">
												function loadpage(){loadViewdetail('<?=$path?>views_amt/repairman_manage/check_repairman.php');}window.onload=loadpage;
											</script>
										<?php } ?>
									<?php } ?>
								</div>
							</td>
						</tr>
					</table>
				<?php } ?>
			</td>
		</tr>				
		<tr valign="bottom">
			<td class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br" height="5">&nbsp;</td>
		</tr>
	</table>
</body>
</html>
<?php 

$SESSION_AREA=$_SESSION["AD_AREA"];
if($SESSION_AREA=="AMT"){
	$SET2='2';
	$SET3='3';
	$SET4='4';
}else{
	$SET2='9';
	$SET3='10';
	$SET4='11';
}
$sql_setting1="SELECT  * FROM SETTING WHERE ST_ID = '$SET2' AND ST_STATUS = 'Y'";
$params_setting1 = array();	
$query_setting1 = sqlsrv_query( $conn, $sql_setting1, $params_setting1);	
$result_setting1 = sqlsrv_fetch_array($query_setting1, SQLSRV_FETCH_ASSOC);

$sql_setting2="SELECT  * FROM SETTING WHERE ST_ID = '$SET3' AND ST_STATUS = 'Y'";
$params_setting2 = array();	
$query_setting2 = sqlsrv_query( $conn, $sql_setting2, $params_setting2);	
$result_setting2 = sqlsrv_fetch_array($query_setting2, SQLSRV_FETCH_ASSOC);

$sql_setting3="SELECT  * FROM SETTING WHERE ST_ID = '$SET4' AND ST_STATUS = 'Y'";
$params_setting3 = array();	
$query_setting3 = sqlsrv_query( $conn, $sql_setting3, $params_setting3);	
$result_setting3 = sqlsrv_fetch_array($query_setting3, SQLSRV_FETCH_ASSOC);

?>
<?php if(isset($result_setting1["ST_ID"])){ $ST_TIME1 = $result_setting1["ST_TIME"]; ?>
	<?php if($_GET['menu_id']==26 || $_GET['menu_id']==62){ ?>
		<script type="text/javascript">
			$(function(){
			setInterval(function(){ 
				var getData=$.ajax({ 
					url:'../include/noncheckrequest.php',
					data: { 
					target: "1"
					},
					async:false,
					success:function(getData){
					$("span#checksidebm").html(getData); 
					}
				}).responseText;
			},<?=$ST_TIME1?>);    
			});
			$(function(){
			setInterval(function(){ 
				var getData=$.ajax({ 
					url:'../include/noncheckrequest.php',
					data: { 
					target: "2"
					},
					async:false,
					success:function(getData){
					$("span#checksidepm").html(getData); 
					}
				}).responseText;
			},<?=$ST_TIME1?>);    
			});
		</script>
	<?php } ?>
	<script type="text/javascript">
		$(function(){
			setInterval(function(){ 
				var getData=$.ajax({ 
						url:'../include/noncheckrequest.php',
						data: { 
							target: "3"
						},
						async:false,
						success:function(getData){
							$("span#checksidebmpm").html(getData); 
						}
				}).responseText;
			},<?=$ST_TIME1?>);    
		});
	</script>
<?php } ?>

<?php if(isset($result_setting2["ST_ID"])){ $ST_TIME2 = $result_setting2["ST_TIME"]; ?>
	<script type="text/javascript">
		$(function(){
			setInterval(function(){ 
				var getData=$.ajax({ 
						url:'../include/noncheckrequest.php',
						data: { 
							target: "4"
						},
						async:false,
						success:function(getData){
							$("span#checksideassign").html(getData); 
						}
				}).responseText;
			},<?=$ST_TIME2?>);    
		});
	</script>
<?php } ?>

<?php if(isset($result_setting3["ST_ID"])){ $ST_TIME3 = $result_setting3["ST_TIME"]; ?>
	<script type="text/javascript">
		$(function(){
			setInterval(function(){ 
				var getData=$.ajax({ 
						url:'../include/noncheckrequest.php',
						data: { 
							target: "5"
						},
						async:false,
						success:function(getData){
							$("span#checksidework").html(getData); 
						}
				}).responseText;
			},<?=$ST_TIME3?>);    
		});
	</script>
<?php } ?>