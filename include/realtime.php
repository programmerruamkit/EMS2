<?php

if($SESSION_AREA=="AMT"){
	$SET1='24';
	$SET2='2';
	$SET3='3';
	$SET4='4';
}else{
	$SET1='25';
	$SET2='9';
	$SET3='10';
	$SET4='11';
}

$sql_setting1="SELECT  * FROM SETTING WHERE ST_ID = '$SET1' AND ST_STATUS = 'Y'";
$params_setting1 = array();	
$query_setting1 = sqlsrv_query( $conn, $sql_setting1, $params_setting1);	
$result_setting1 = sqlsrv_fetch_array($query_setting1, SQLSRV_FETCH_ASSOC);

$sql_setting2="SELECT  * FROM SETTING WHERE ST_ID = '$SET2' AND ST_STATUS = 'Y'";
$params_setting2 = array();	
$query_setting2 = sqlsrv_query( $conn, $sql_setting2, $params_setting2);	
$result_setting2 = sqlsrv_fetch_array($query_setting2, SQLSRV_FETCH_ASSOC);

$sql_setting3="SELECT  * FROM SETTING WHERE ST_ID = '$SET3' AND ST_STATUS = 'Y'";
$params_setting3 = array();	
$query_setting3 = sqlsrv_query( $conn, $sql_setting3, $params_setting3);	
$result_setting3 = sqlsrv_fetch_array($query_setting3, SQLSRV_FETCH_ASSOC);

$sql_setting4="SELECT  * FROM SETTING WHERE ST_ID = '$SET4' AND ST_STATUS = 'Y'";
$params_setting4 = array();	
$query_setting4 = sqlsrv_query( $conn, $sql_setting4, $params_setting4);	
$result_setting4 = sqlsrv_fetch_array($query_setting4, SQLSRV_FETCH_ASSOC);

if(isset($result_setting1["ST_ID"])){ $ST_TIME1 = $result_setting1["ST_TIME"]; ?>
	<script type="text/javascript">
		$(function(){
			setInterval(function(){ 
				var getData=$.ajax({ 
						url:'../include/noncheckrequest.php',
						data: { 
							target: "6"
						},
						async:false,
						success:function(getData){
							$("span#checksiderepair").html(getData); 
						}
				}).responseText;
			},<?=$ST_TIME1?>);    
		});
	</script>
<?php }

if(isset($result_setting2["ST_ID"])){ $ST_TIME2 = $result_setting2["ST_TIME"]; ?>
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
			},<?=$ST_TIME2?>);    
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
			},<?=$ST_TIME2?>);    
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
			},<?=$ST_TIME2?>);    
		});
	</script>
<?php }

if(isset($result_setting3["ST_ID"])){ $ST_TIME3 = $result_setting3["ST_TIME"]; ?>
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
			},<?=$ST_TIME3?>);    
		});
	</script>
<?php }

if(isset($result_setting4["ST_ID"])){ $ST_TIME4 = $result_setting4["ST_TIME"]; ?>
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
			},<?=$ST_TIME4?>);    
		});
	</script>
<?php } ?>