<?php
	session_start ();
	$path='../';
	require($path."include/connect.php");
	$item_code = $_POST['item_code'];
	if ($item_code!=""){
		  $wh = " AND MN_NAME LIKE '%" .$item_code ."%'  ";
	  }
	if(!empty($item_code)  ){
		$sql = "SELECT * FROM MENU WHERE 1=1 $wh";
		$query = sqlsrv_query( $conn, $sql);
?>
<ul id="autocomplete">
  <?php while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)){ ?>
    <li onclick="sendvalue('<?php echo $result['MN_NAME'];?>')" style="cursor:pointer"> <?php  echo $result['MN_NAME'];?></li>
  <?php } ?>
</ul>
<?php } ?>