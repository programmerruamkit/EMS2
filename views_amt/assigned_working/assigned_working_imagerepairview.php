<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
  
	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();
	
	$proc=$_GET["proc"];
	$RPRQ_CODE=$_GET["id"];
	$SUBJECT=$_GET["subject"];
	$RPATIMGROUP=$_GET["groub"];
	$RPATIM_IMAGES=$_GET["image"];
?>
<html>
  
<head>
  <script>
    function swaldelete_image(a1,a2,a3,a4) {
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่...ที่จะลบรูปนี้',
        text: "หากลบแล้ว คุณจะไม่สามารถกู้คืนข้อมูลได้!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#C82333',
        confirmButtonText: 'ใช่! ลบเลย',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            icon: 'success',
            title: 'ลบข้อมูลเรียบร้อยแล้ว',
            showConfirmButton: false,
            timer: 2000
          }).then((result) => {	
            var url = "<?=$path?>views_amt/assigned_working/assigned_working_proc.php?proc=delete&id="+a1+"&subject="+a2+"&groub="+a3+"&image="+a4;
            $.ajax({
              type:'GET',
              url:url,
              data:"",				
              success:function(data){
                ajaxPopup4('<?=$path?>views_amt/assigned_working/assigned_working_imagerepair.php','edit','<?php print $RPRQ_CODE;?>&subject=<?php print $SUBJECT;?>&type=IMAGE','1=1','1300','330','เพิ่มรูปภาพ');
                // alert(data);
              }
            });
          })	
        }
      })
    }
  </script>
</head>

<body>

    
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="24" valign="middle" class=""><img src="../images/image13.png" width="32"
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
            <img src="<?=$path?>uploads/requestrepair/<?=$RPATIM_IMAGES;?>" width="100%" height="500px">
          </td>
        </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
        <tbody>    
          <tr align="center" height="25px">
            <td height="35" colspan="2" align="center">
              <button type="button" class="bg-color-red font-white" onclick="swaldelete_image('<?=$RPRQ_CODE;?>','<?=$SUBJECT;?>','<?=$RPATIMGROUP;?>','<?=$RPATIM_IMAGES;?>')"><b><font color="white">ลบรูปภาพ</font></b></button>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="bg-color-yellow font-black" type="button" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/assigned_working/assigned_working_imagerepair.php','edit','<?php print $RPRQ_CODE;?>&subject=<?php print $SUBJECT;?>&type=IMAGE','1=1','1300','330','เพิ่มรูปภาพ');"><b>ย้อนกลับ</b></button>
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