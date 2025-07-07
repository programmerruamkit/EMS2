<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
?>
<html>
<head>
<script type="text/javascript">
	$(document).ready(function(e) {	   
		$("#button_new_bm").click(function(){
			ajaxPopup2("<?=$path?>views_amt/request_repair/request_repair_sparepart_form.php","add","1=1","1350","670","เพิ่มใบแจ้งซ่อม");
		});

		$("#button_new_pm").click(function(){
			ajaxPopup2("<?=$path?>views_amt/request_repair/request_repair_sparepart_form.php","add","1=1","1350","670","เพิ่มใบแจ้งซ่อม");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/request_repair/request_repair_sparepart_form.php","edit","1=1","1350","670","แก้ไขใบแจ้งซ่อม");
		});
		
		$("#button_delete").click(function(){
			Swal.fire({
				title: 'คุณแน่ใจหรือไม่...ที่จะลบรายการนี้?',
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
						// if(!confirm("ยืนยันการลบข้อมูลอีกครั้ง")) return false;
						var ref = getIdSelect(); 
						var url = "<?=$path?>views_amt/request_repair/request_repair_sparepart_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_requestrepair('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_sparepart.php');
								// alert(data);
							}
						});
					})	
				}
			})
		});	
    });

	function swaldelete_requestrepair(refcode,no) {
		Swal.fire({
			title: 'คุณแน่ใจหรือไม่...ที่จะลบรายการที่ '+no+' นี้',
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
					// if(!confirm("ยืนยันการลบข้อมูลอีกครั้ง")) return false;
					// var ref = getIdSelect(); 
					var ref = refcode; 
					var url = "<?=$path?>views_amt/request_repair/request_repair_sparepart_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
                            log_transac_requestrepair('LA5', '-', ref);
							loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_sparepart.php');
							// alert(data);
						}
					});
				})	
			}
		})
	}
    function search_request(){
        var dateStart = $("#dateStart").val();
        var query = "?dateStart="+dateStart;
        loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_sparepart.php'+query);
        // $.ajax({
        //     type: 'post',
        //     url: '<?=$path?>views_amt/request_repair/request_repair_pm_get.php',
        //     data: {dateStart: document.getElementById("dateStart").value},
        //     success: function (result) {  
        //         document.getElementById("showrequestnew").innerHTML = result;
        //         document.getElementById("showrequestold").innerHTML = "";  
        //         dataTable('datatable1');
        //         dataTable('datatable2');
        //         dataTable('datatable3');
        //         dataTable('datatable4');
        //         dataTable('datatable5');
        //         dataTable('datatable6');
        //         dataTable('datatable7');
        //         genTabMenu("tabs_menu");    
        //     }
        // });
    }
</script>
<script type="text/javascript">
$(document).ready(function(e) {
    $('.datepic').datetimepicker({
        timepicker:false,
        lang:'th',
        format:'d/m/Y',
		beforeShowDay: noWeekends,
        closeOnDateSelect: true
    });
});
</script>
</head>
<body>
<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="25" valign="middle" class=""><img src="../images/car_repair.png" width="48" height="48"></td>
                <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;ข้อมูลบันทึกเปลี่ยนอะไหล่</h3></td>
                <td width="617" align="right" valign="bottom" class="" nowrap>
                    
                    
                    <button class="bg-color-orange big" title="New" onClick="loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_sparepart_form.php');"><font color="white" size="4">New แจ้งเปลี่ยนอะไหล่</font></button>
                    
                    
                </td>
            </tr>
        </table>
    </td>
    <td class="RIGHT"></td>
  </tr>
    <?php            
        if($_GET['dateStart']!=""){
            $getday = $_GET['dateStart'];
        }else{
            $getday = $STARTWEEK;
        }
        $SESSION_AREA = $_SESSION["AD_AREA"];
    ?>                    
  <tr class="CENTER">
    <td class="LEFT"></td>    
    <td class="CENTER" align="center">        
        <br>    
        <table>
            <tbody>
                <tr style="cursor:pointer" height="25px" align="center">                               
                    <td width="15%" align="center">
                        <div class="row input-control">     
                            <input type="text" name="dateStart" id="dateStart" class="datepic time" placeholder="วันที่เริ่มต้นสัปดาห์" autocomplete="off" value="<?=$getday;?>" onchange="search_request()">        
                        </div>
                    </td>
                    <!-- <td width="10%" align="center">
                        <button class="bg-color-blue" onclick="search_request()"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
                    </td> -->
                    <td align="center"></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>  
        <div id="showrequestold">
            <form id="form1" name="form1" method="post" action="#">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable1">
                    <thead>
                        <tr height="30">
                            <th align="center" width="5%">ลำดับ</th>
                            <th align="center" width="25%">หมวดหมู่รายการอะไหล่</th>
                            <th align="center" width="10%">ใกล้ถึงกำหนดเปลี่ยน</th>
                            <th align="center" width="10%">เกินกำหนดปลี่ยน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $D1=$result_getdate_d1d7["D1"];
                            $sql_rprq_monday = "SELECT DISTINCT * FROM vwREPAIRREQUEST_PM WHERE RPRQ_AREA = '$SESSION_AREA'  AND RPRQ_WORKTYPE = 'PM' AND RPRQ_REQUESTCARDATE = '$D1'";
                            $query_rprq_monday = sqlsrv_query($conn, $sql_rprq_monday);
                            $no=0;
                            while($result_rprq_monday = sqlsrv_fetch_array($query_rprq_monday, SQLSRV_FETCH_ASSOC)){	
                                $no++;
                        ?>
                        <tr id="<?php print $result_rprq_monday['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">       
                            <td align="center"></td>
                            <td align="center"></td>                
                            <td align="center"></td>    
                            <td align="center"></td>    
                            <td align="center"></td>    
                            <td align="center"></td>    
                            <td align="center"></td>    
                            <td align="center"></td>    
                            <td align="center"></td>    
                            <td align="center"></td>
                        </tr>
                        <?php }; ?>
                    </tbody>
                </table>       
            </form>
        </div>
        <div id="showrequestnew">
        </div>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
		<center>
			<input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_sparepart.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>