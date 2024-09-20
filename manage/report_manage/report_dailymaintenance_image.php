<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$ru_id=$_GET["id"];

		$stmt = "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_ID = ?";
		$params = array($ru_id);	
		$query = sqlsrv_query( $conn, $stmt, $params);	
		$result_edit_repairrequest = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $RPRQ_ID=$result_edit_repairrequest["RPRQ_ID"];
    $RPC_IMAGES=$result_edit_repairrequest["RPC_IMAGES"];
?>
<html>
  
<head>
  <style>
    .radioexam{
        width:20px;
        height:2em;
    }
  </style>
</head>

<body>

    
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="24" valign="middle" class=""><img src="https://img2.pic.in.th/pic/image13.png" width="32"
                height="32"></td>
            <td valign="bottom" class="">
              <h4>&nbsp;&nbsp;รูปภาพ</h4>
            </td>
          </tr>
        </table>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="INVENDATA no-border" style="padding:3px;">
        <tr height="40">
          <td class="bg-white">
            <img src="<?=$path?>uploads/requestrepair/<?=$RPC_IMAGES;?>" width="100%" height="500px">
          </td>
        </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
        <tbody>    
          <tr align="center" height="25px">
            <td height="35" colspan="2" align="center">
              <button class="bg-color-red font-white" type="button" onClick="closeUI()">ปิดหน้าจอ</button>
            </td>
          </tr>
        </tbody>
      </table>   
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>

</html>
<style>
  .row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -7.5px;
    margin-left: -7.5px;
  }

  .col-md-6 {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%
  }
</style>