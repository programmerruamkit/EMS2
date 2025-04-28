<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
	$SESSION_AREA = $_SESSION["AD_AREA"];
		$HREF_PDF="../views_amt/request_repair/request_repair_pdf.php?id='+a1+'&area=$SESSION_AREA";
?>
<html>
<head>
<script type="text/javascript">

    function pdf_reportrepair_pm(a1) {
        window.open('<?=$HREF_PDF?>','_blank');
    }
	$(document).ready(function(e) {	   
		$("#button_new_bm").click(function(){
			ajaxPopup2("<?=$path?>views_amt/request_repair/request_repair_pm_form.php","add","1=1","1350","670","เพิ่มใบแจ้งซ่อม");
		});

		$("#button_new_pm").click(function(){
			ajaxPopup2("<?=$path?>views_amt/request_repair/request_repair_pm_form.php","add","1=1","1350","670","เพิ่มใบแจ้งซ่อม");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/request_repair/request_repair_pm_form.php","edit","1=1","1350","670","แก้ไขใบแจ้งซ่อม");
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
						var url = "<?=$path?>views_amt/request_repair/request_repair_pm_proc.php?proc=delete&id="+ref;
						$.ajax({
							type:'GET',
							url:url,
							data:"",				
							success:function(data){
								log_transac_requestrepair('LA19', '-', ref);
								loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm.php');
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
			title: 'คุณแน่ใจหรือไม่...ที่จะยกเลิกรายการซ่อมของ ID หมายเลข '+no,
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
					var url = "<?=$path?>views_amt/request_repair/request_repair_pm_proc.php?proc=delete&id="+ref;
					$.ajax({
						type:'GET',
						url:url,
						data:"",				
						success:function(data){
                            log_transac_requestrepair('LA5', '-', ref);
							loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm.php');
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
        loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm.php'+query);
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
                <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;ข้อมูลใบขอซ่อมรถ PM</h3></td>
                <td width="617" align="right" valign="bottom" class="" nowrap>
                    <!-- <button class="bg-color-blue big" title="New" id="button_new_bm"><font color="white" size="4">New แจ้งซ่อม BM</font></button> -->
                    <!-- <button class="bg-color-orange big" title="New" id="button_new_bm"><font color="white" size="4">New แจ้งซ่อม PM</font></button> -->
                    <button class="bg-color-orange big" title="New" onClick="loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm_form.php');"><font color="white" size="4">New แจ้งซ่อม PM</font></button>
                    <!-- <button class="bg-color-yellow" style="padding-top:8px;" title="Edit" id="button_edit"><i class='icon-pencil icon-large'></i></button> -->
                    <!-- <button class="bg-color-red" style="padding-top:8px;" title="Del" id="button_delete"><i class="icon-cancel icon-large"></i></button> -->
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
                <?php
                    $sql_getdate_d1d7="SELECT CONVERT(VARCHAR,DATEADD(DAY,6,CONVERT(DATETIME,'$getday',103)),103) AS 'ADDDATEWEEK',
                        CONVERT(VARCHAR,DATEADD(DAY,0,CONVERT(DATETIME,'$getday',103)),103) AS 'D1',
                        CONVERT(VARCHAR,DATEADD(DAY,1,CONVERT(DATETIME,'$getday',103)),103) AS 'D2',
                        CONVERT(VARCHAR,DATEADD(DAY,2,CONVERT(DATETIME,'$getday',103)),103) AS 'D3',
                        CONVERT(VARCHAR,DATEADD(DAY,3,CONVERT(DATETIME,'$getday',103)),103) AS 'D4',
                        CONVERT(VARCHAR,DATEADD(DAY,4,CONVERT(DATETIME,'$getday',103)),103) AS 'D5',
                        CONVERT(VARCHAR,DATEADD(DAY,5,CONVERT(DATETIME,'$getday',103)),103) AS 'D6',
                        CONVERT(VARCHAR,DATEADD(DAY,6,CONVERT(DATETIME,'$getday',103)),103) AS 'D7'";
                    $params_getdate_d1d7 = array();	
                    $query_getdate_d1d7 = sqlsrv_query( $conn, $sql_getdate_d1d7, $params_getdate_d1d7);	
                    $result_getdate_d1d7 = sqlsrv_fetch_array($query_getdate_d1d7, SQLSRV_FETCH_ASSOC);
                ?>
                <div id="tabs_menu" style="height:100%;">
                    <ul>
                        <li><a href="#tabs-1"><span style="font-size:13px">อาทิตย์ (<?=$result_getdate_d1d7["D1"]?>)</span></a></li>
                        <li><a href="#tabs-2"><span style="font-size:13px">จันทร์ (<?=$result_getdate_d1d7["D2"]?>)</span></a></li>
                        <li><a href="#tabs-3"><span style="font-size:13px">อังคาร (<?=$result_getdate_d1d7["D3"]?>)</span></a></li>
                        <li><a href="#tabs-4"><span style="font-size:13px">พุธ (<?=$result_getdate_d1d7["D4"]?>)</span></a></li>
                        <li><a href="#tabs-5"><span style="font-size:13px">พฤหัสฯ (<?=$result_getdate_d1d7["D5"]?>)</span></a></li>
                        <li><a href="#tabs-6"><span style="font-size:13px">ศุกร์ (<?=$result_getdate_d1d7["D6"]?>)</span></a></li>
                        <li><a href="#tabs-7"><span style="font-size:13px">เสาร์ (<?=$result_getdate_d1d7["D7"]?>)</span></a></li>
                    </ul>  
                    <div id="tabs-1">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable1"><!-- default hover pointer display hover pointer -->
                            <thead>
                                <tr height="30">
                                    <th rowspan="2" align="center" width="8%">เลขที่ใบขอซ่อม</th>
                                    <th rowspan="2" align="center" width="5%">สถานะ</th>
                                    <th rowspan="2" align="center" width="5%">พิมพ์</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ล่าสุด</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ถึงระยะ</th>
                                    <th rowspan="2" align="center" width="18%">ลักษณะงานซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">ปัญหาก่อนการซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">จัดการ</th>
                                </tr>
                                <tr height="30">
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>           
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>    
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
                                    <td align="center"><?php print $result_rprq_monday['RPRQ_ID']; ?></td>
                                    <td align="center">                                
                                        <?php
                                            switch($result_rprq_monday['RPRQ_STATUSREQUEST']) {
                                                case "รอตรวจสอบ":
                                                    $text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
                                                break;
                                                case "รอคิวซ่อม":
                                                    $text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
                                                break;
                                                case "กำลังซ่อม":
                                                    $text="<strong><font color='red'>กำลังซ่อม</font></strong>";
                                                break;
                                                case "ซ่อมเสร็จสิ้น":
                                                  $text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
                                                break;
                                                case "รอจ่ายงาน":
                                                    $text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
                                                break;
                                                case "ไม่อนุมัติ":
                                                    $text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
                                                break;
                                            }
                                            print $text;
                                        ?>
                                    </td>                
                                    <td align="center">
                                        <button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_pm('<?php print $result_rprq_monday['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
                                    </td>
                                    <td align="center"><?php print $result_rprq_monday['RPRQ_REGISHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_monday['RPRQ_CARNAMEHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_monday['RPRQ_REGISTAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_monday['RPRQ_CARNAMETAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_monday['RPRQ_MILEAGELAST']; ?></td>
                                    <td align="center"><?php print $result_rprq_monday['RPRQ_MILEAGEFINISH']; ?></td>
                                    <td align="left"><?php print $result_rprq_monday['RPC_SUBJECT_MERGE']; ?></td>
                                    <td align="left"><?php print $result_rprq_monday['RPC_DETAIL']; ?></td>
                                    <td align="center" >
                                        <?php if(($result_rprq_monday['RPRQ_STATUSREQUEST']=='รอตรวจสอบ')||($result_rprq_monday['RPRQ_STATUSREQUEST']=='รอจ่ายงาน')){ ?>
                                            <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','edit','<?php print $result_rprq_monday['RPRQ_CODE'];; ?>','1=1','1350','670','แก้ไขใบแจ้งซ่อม');"><i class='icon-pencil icon-large'></i></button>
                                            &nbsp;&nbsp;
                                            <button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_requestrepair('<?php print $result_rprq_monday['RPRQ_CODE']; ?>','<?php print $result_rprq_monday['RPRQ_ID']; ?>')"><i class="icon-cancel icon-large"></i></button>
                                        <?php }else if(($result_rprq_monday['RPRQ_STATUSREQUEST']=='ไม่อนุมัติ')){ ?>
                                            <?php 
                                                if(isset($result_nap_monday['CD2'])){ 
                                                    echo '<strong>ID ใหม่ '.$result_nap_monday['ID2'].'</strong>';
                                                }else{ 
                                            ?>
                                                <!-- <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','copy','<?php print $result_rprq_monday['RPRQ_CODE'];; ?>','1=1','1350','670','คัดลอกใบแจ้งซ่อม');"><i class='icon-new icon-large'></i></button> -->
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php }; ?>
                            </tbody>
                        </table>       
                        <!-- <b><font color="red" align="left" >*สามารถกด Double Click ข้อมูลในตาราง เพื่อดูรายละเอียดได้ทันที</font></b>     -->
                    </div>  
                    <div id="tabs-2">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable2">
                            <thead>
                                <tr height="30">
                                    <th rowspan="2" align="center" width="8%">เลขที่ใบขอซ่อม</th>
                                    <th rowspan="2" align="center" width="5%">สถานะ</th>
                                    <th rowspan="2" align="center" width="5%">พิมพ์</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ล่าสุด</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ถึงระยะ</th>
                                    <th rowspan="2" align="center" width="18%">ลักษณะงานซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">ปัญหาก่อนการซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">จัดการ</th>
                                </tr>
                                <tr height="30">
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>           
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $D2=$result_getdate_d1d7["D2"];
                                    $sql_rprq_tuesday = "SELECT DISTINCT * FROM vwREPAIRREQUEST_PM WHERE RPRQ_AREA = '$SESSION_AREA'  AND RPRQ_WORKTYPE = 'PM' AND RPRQ_REQUESTCARDATE = '$D2'";
                                    $query_rprq_tuesday = sqlsrv_query($conn, $sql_rprq_tuesday);
                                    $no=0;
                                    while($result_rprq_tuesday = sqlsrv_fetch_array($query_rprq_tuesday, SQLSRV_FETCH_ASSOC)){	
                                        $no++;
                                ?>
                                <tr id="<?php print $result_rprq_tuesday['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
                                    <td align="center"><?php print $result_rprq_tuesday['RPRQ_ID']; ?></td>
                                    <td align="center">                                
                                        <?php
                                            switch($result_rprq_tuesday['RPRQ_STATUSREQUEST']) {
                                                case "รอตรวจสอบ":
                                                    $text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
                                                break;
                                                case "รอคิวซ่อม":
                                                    $text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
                                                break;
                                                case "กำลังซ่อม":
                                                    $text="<strong><font color='red'>กำลังซ่อม</font></strong>";
                                                break;
                                                case "ซ่อมเสร็จสิ้น":
                                                  $text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
                                                break;
                                                case "รอจ่ายงาน":
                                                    $text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
                                                break;
                                                case "ไม่อนุมัติ":
                                                    $text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
                                                break;
                                            }
                                            print $text;
                                        ?>
                                    </td>               
                                    <td align="center">
                                        <button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_pm('<?php print $result_rprq_tuesday['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
                                    </td>
                                    <td align="center"><?php print $result_rprq_tuesday['RPRQ_REGISHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_tuesday['RPRQ_CARNAMEHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_tuesday['RPRQ_REGISTAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_tuesday['RPRQ_CARNAMETAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_tuesday['RPRQ_MILEAGELAST']; ?></td>
                                    <td align="center"><?php print $result_rprq_tuesday['RPRQ_MILEAGEFINISH']; ?></td>
                                    <td align="left"><?php print $result_rprq_tuesday['RPC_SUBJECT_MERGE']; ?></td>
                                    <td align="left"><?php print $result_rprq_tuesday['RPC_DETAIL']; ?></td>
                                    <td align="center" >
                                        <?php if(($result_rprq_tuesday['RPRQ_STATUSREQUEST']=='รอตรวจสอบ')||($result_rprq_tuesday['RPRQ_STATUSREQUEST']=='รอจ่ายงาน')){ ?>
                                            <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','edit','<?php print $result_rprq_tuesday['RPRQ_CODE'];; ?>','1=1','1350','670','แก้ไขใบแจ้งซ่อม');"><i class='icon-pencil icon-large'></i></button>
                                            &nbsp;&nbsp;
                                            <button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_requestrepair('<?php print $result_rprq_tuesday['RPRQ_CODE']; ?>','<?php print $result_rprq_tuesday['RPRQ_ID']; ?>')"><i class="icon-cancel icon-large"></i></button>
                                        <?php }else if(($result_rprq_tuesday['RPRQ_STATUSREQUEST']=='ไม่อนุมัติ')){ ?>
                                            <?php 
                                                if(isset($result_nap_tuesday['CD2'])){ 
                                                    echo '<strong>ID ใหม่ '.$result_nap_tuesday['ID2'].'</strong>';
                                                }else{ 
                                            ?>
                                                <!-- <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','copy','<?php print $result_rprq_tuesday['RPRQ_CODE'];; ?>','1=1','1350','670','คัดลอกใบแจ้งซ่อม');"><i class='icon-new icon-large'></i></button> -->
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php }; ?>
                            </tbody>
                        </table>       
                        <!-- <b><font color="red" align="left" >*สามารถกด Double Click ข้อมูลในตาราง เพื่อดูรายละเอียดได้ทันที</font></b>          -->
                    </div>   
                    <div id="tabs-3">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable3">
                            <thead>
                                <tr height="30">
                                    <th rowspan="2" align="center" width="8%">เลขที่ใบขอซ่อม</th>
                                    <th rowspan="2" align="center" width="5%">สถานะ</th>
                                    <th rowspan="2" align="center" width="5%">พิมพ์</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ล่าสุด</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ถึงระยะ</th>
                                    <th rowspan="2" align="center" width="18%">ลักษณะงานซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">ปัญหาก่อนการซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">จัดการ</th>
                                </tr>
                                <tr height="30">
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>           
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $D3=$result_getdate_d1d7["D3"];
                                    $sql_rprq_wednesday = "SELECT DISTINCT * FROM vwREPAIRREQUEST_PM WHERE RPRQ_AREA = '$SESSION_AREA'  AND RPRQ_WORKTYPE = 'PM' AND RPRQ_REQUESTCARDATE = '$D3'";
                                    $query_rprq_wednesday = sqlsrv_query($conn, $sql_rprq_wednesday);
                                    $no=0;
                                    while($result_rprq_wednesday = sqlsrv_fetch_array($query_rprq_wednesday, SQLSRV_FETCH_ASSOC)){	
                                        $no++;
                                ?>
                                <tr id="<?php print $result_rprq_wednesday['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
                                    <td align="center"><?php print $result_rprq_wednesday['RPRQ_ID']; ?></td>
                                    <td align="center">                                
                                        <?php
                                            switch($result_rprq_wednesday['RPRQ_STATUSREQUEST']) {
                                                case "รอตรวจสอบ":
                                                    $text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
                                                break;
                                                case "รอคิวซ่อม":
                                                    $text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
                                                break;
                                                case "กำลังซ่อม":
                                                    $text="<strong><font color='red'>กำลังซ่อม</font></strong>";
                                                break;
                                                case "ซ่อมเสร็จสิ้น":
                                                  $text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
                                                break;
                                                case "รอจ่ายงาน":
                                                    $text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
                                                break;
                                                case "ไม่อนุมัติ":
                                                    $text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
                                                break;
                                            }
                                            print $text;
                                        ?>
                                    </td>               
                                    <td align="center">
                                        <button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_pm('<?php print $result_rprq_wednesday['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
                                    </td>
                                    <td align="center"><?php print $result_rprq_wednesday['RPRQ_REGISHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_wednesday['RPRQ_CARNAMEHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_wednesday['RPRQ_REGISTAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_wednesday['RPRQ_CARNAMETAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_wednesday['RPRQ_MILEAGELAST']; ?></td>
                                    <td align="center"><?php print $result_rprq_wednesday['RPRQ_MILEAGEFINISH']; ?></td>
                                    <td align="left"><?php print $result_rprq_wednesday['RPC_SUBJECT_MERGE']; ?></td>
                                    <td align="left"><?php print $result_rprq_wednesday['RPC_DETAIL']; ?></td>
                                    <td align="center" >
                                        <?php if(($result_rprq_wednesday['RPRQ_STATUSREQUEST']=='รอตรวจสอบ')||($result_rprq_wednesday['RPRQ_STATUSREQUEST']=='รอจ่ายงาน')){ ?>
                                            <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','edit','<?php print $result_rprq_wednesday['RPRQ_CODE'];; ?>','1=1','1350','670','แก้ไขใบแจ้งซ่อม');"><i class='icon-pencil icon-large'></i></button>
                                            &nbsp;&nbsp;
                                            <button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_requestrepair('<?php print $result_rprq_wednesday['RPRQ_CODE']; ?>','<?php print $result_rprq_wednesday['RPRQ_ID']; ?>')"><i class="icon-cancel icon-large"></i></button>
                                        <?php }else if(($result_rprq_wednesday['RPRQ_STATUSREQUEST']=='ไม่อนุมัติ')){ ?>
                                            <?php 
                                                if(isset($result_nap_wednesday['CD2'])){ 
                                                    echo '<strong>ID ใหม่ '.$result_nap_wednesday['ID2'].'</strong>';
                                                }else{ 
                                            ?>
                                                <!-- <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','copy','<?php print $result_rprq_wednesday['RPRQ_CODE'];; ?>','1=1','1350','670','คัดลอกใบแจ้งซ่อม');"><i class='icon-new icon-large'></i></button> -->
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php }; ?>
                            </tbody>
                        </table>       
                        <!-- <b><font color="red" align="left" >*สามารถกด Double Click ข้อมูลในตาราง เพื่อดูรายละเอียดได้ทันที</font></b>        -->
                    </div> 
                    <div id="tabs-4">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable4">
                            <thead>
                                <tr height="30">
                                    <th rowspan="2" align="center" width="8%">เลขที่ใบขอซ่อม</th>
                                    <th rowspan="2" align="center" width="5%">สถานะ</th>
                                    <th rowspan="2" align="center" width="5%">พิมพ์</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ล่าสุด</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ถึงระยะ</th>
                                    <th rowspan="2" align="center" width="18%">ลักษณะงานซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">ปัญหาก่อนการซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">จัดการ</th>
                                </tr>
                                <tr height="30">
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>           
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>   
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $D4=$result_getdate_d1d7["D4"];
                                    $sql_rprq_thursday = "SELECT DISTINCT * FROM vwREPAIRREQUEST_PM WHERE RPRQ_AREA = '$SESSION_AREA'  AND RPRQ_WORKTYPE = 'PM' AND RPRQ_REQUESTCARDATE = '$D4'";
                                    $query_rprq_thursday = sqlsrv_query($conn, $sql_rprq_thursday);
                                    $no=0;
                                    while($result_rprq_thursday = sqlsrv_fetch_array($query_rprq_thursday, SQLSRV_FETCH_ASSOC)){	
                                        $no++;
                                ?>
                                <tr id="<?php print $result_rprq_thursday['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
                                    <td align="center"><?php print $result_rprq_thursday['RPRQ_ID']; ?></td>
                                    <td align="center">                                
                                        <?php
                                            switch($result_rprq_thursday['RPRQ_STATUSREQUEST']) {
                                                case "รอตรวจสอบ":
                                                    $text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
                                                break;
                                                case "รอคิวซ่อม":
                                                    $text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
                                                break;
                                                case "กำลังซ่อม":
                                                    $text="<strong><font color='red'>กำลังซ่อม</font></strong>";
                                                break;
                                                case "ซ่อมเสร็จสิ้น":
                                                  $text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
                                                break;
                                                case "รอจ่ายงาน":
                                                    $text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
                                                break;
                                                case "ไม่อนุมัติ":
                                                    $text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
                                                break;
                                            }
                                            print $text;
                                        ?>
                                    </td>                   
                                    <td align="center">
                                        <button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_pm('<?php print $result_rprq_thursday['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
                                    </td>
                                    <td align="center"><?php print $result_rprq_thursday['RPRQ_REGISHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_thursday['RPRQ_CARNAMEHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_thursday['RPRQ_REGISTAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_thursday['RPRQ_CARNAMETAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_thursday['RPRQ_MILEAGELAST']; ?></td>
                                    <td align="center"><?php print $result_rprq_thursday['RPRQ_MILEAGEFINISH']; ?></td>
                                    <td align="left"><?php print $result_rprq_thursday['RPC_SUBJECT_MERGE']; ?></td>
                                    <td align="left"><?php print $result_rprq_thursday['RPC_DETAIL']; ?></td>
                                    <td align="center" >
                                        <?php if(($result_rprq_thursday['RPRQ_STATUSREQUEST']=='รอตรวจสอบ')||($result_rprq_thursday['RPRQ_STATUSREQUEST']=='รอจ่ายงาน')){ ?>
                                            <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','edit','<?php print $result_rprq_thursday['RPRQ_CODE'];; ?>','1=1','1350','670','แก้ไขใบแจ้งซ่อม');"><i class='icon-pencil icon-large'></i></button>
                                            &nbsp;&nbsp;
                                            <button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_requestrepair('<?php print $result_rprq_thursday['RPRQ_CODE']; ?>','<?php print $result_rprq_thursday['RPRQ_ID']; ?>')"><i class="icon-cancel icon-large"></i></button>
                                        <?php }else if(($result_rprq_thursday['RPRQ_STATUSREQUEST']=='ไม่อนุมัติ')){ ?>
                                            <?php 
                                                if(isset($result_nap_thursday['CD2'])){ 
                                                    echo '<strong>ID ใหม่ '.$result_nap_thursday['ID2'].'</strong>';
                                                }else{ 
                                            ?>
                                                <!-- <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','copy','<?php print $result_rprq_thursday['RPRQ_CODE'];; ?>','1=1','1350','670','คัดลอกใบแจ้งซ่อม');"><i class='icon-new icon-large'></i></button> -->
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php }; ?>
                            </tbody>
                        </table>       
                        <!-- <b><font color="red" align="left" >*สามารถกด Double Click ข้อมูลในตาราง เพื่อดูรายละเอียดได้ทันที</font></b>         -->
                    </div> 
                    <div id="tabs-5">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable5">
                            <thead>
                                <tr height="30">
                                    <th rowspan="2" align="center" width="8%">เลขที่ใบขอซ่อม</th>
                                    <th rowspan="2" align="center" width="5%">สถานะ</th>
                                    <th rowspan="2" align="center" width="5%">พิมพ์</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ล่าสุด</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ถึงระยะ</th>
                                    <th rowspan="2" align="center" width="18%">ลักษณะงานซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">ปัญหาก่อนการซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">จัดการ</th>
                                </tr>
                                <tr height="30">
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>           
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>   
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $D5=$result_getdate_d1d7["D5"];
                                    $sql_rprq_friday = "SELECT DISTINCT * FROM vwREPAIRREQUEST_PM WHERE RPRQ_AREA = '$SESSION_AREA'  AND RPRQ_WORKTYPE = 'PM' AND RPRQ_REQUESTCARDATE = '$D5'";
                                    $query_rprq_friday = sqlsrv_query($conn, $sql_rprq_friday);
                                    $no=0;
                                    while($result_rprq_friday = sqlsrv_fetch_array($query_rprq_friday, SQLSRV_FETCH_ASSOC)){	
                                        $no++;
                                ?>
                                <tr id="<?php print $result_rprq_friday['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
                                    <td align="center"><?php print $result_rprq_friday['RPRQ_ID']; ?></td>
                                    <td align="center">                                
                                        <?php
                                            switch($result_rprq_friday['RPRQ_STATUSREQUEST']) {
                                                case "รอตรวจสอบ":
                                                    $text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
                                                break;
                                                case "รอคิวซ่อม":
                                                    $text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
                                                break;
                                                case "กำลังซ่อม":
                                                    $text="<strong><font color='red'>กำลังซ่อม</font></strong>";
                                                break;
                                                case "ซ่อมเสร็จสิ้น":
                                                  $text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
                                                break;
                                                case "รอจ่ายงาน":
                                                    $text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
                                                break;
                                                case "ไม่อนุมัติ":
                                                    $text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
                                                break;
                                            }
                                            print $text;
                                        ?>
                                    </td>           
                                    <td align="center">
                                        <button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_pm('<?php print $result_rprq_friday['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
                                    </td>
                                    <td align="center"><?php print $result_rprq_friday['RPRQ_REGISHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_friday['RPRQ_CARNAMEHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_friday['RPRQ_REGISTAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_friday['RPRQ_CARNAMETAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_friday['RPRQ_MILEAGELAST']; ?></td>
                                    <td align="center"><?php print $result_rprq_friday['RPRQ_MILEAGEFINISH']; ?></td>
                                    <td align="left"><?php print $result_rprq_friday['RPC_SUBJECT_MERGE']; ?></td>
                                    <td align="left"><?php print $result_rprq_friday['RPC_DETAIL']; ?></td>
                                    <td align="center" >
                                        <?php if(($result_rprq_friday['RPRQ_STATUSREQUEST']=='รอตรวจสอบ')||($result_rprq_friday['RPRQ_STATUSREQUEST']=='รอจ่ายงาน')){ ?>
                                            <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','edit','<?php print $result_rprq_friday['RPRQ_CODE'];; ?>','1=1','1350','670','แก้ไขใบแจ้งซ่อม');"><i class='icon-pencil icon-large'></i></button>
                                            &nbsp;&nbsp;
                                            <button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_requestrepair('<?php print $result_rprq_friday['RPRQ_CODE']; ?>','<?php print $result_rprq_friday['RPRQ_ID']; ?>')"><i class="icon-cancel icon-large"></i></button>
                                        <?php }else if(($result_rprq_friday['RPRQ_STATUSREQUEST']=='ไม่อนุมัติ')){ ?>
                                            <?php 
                                                if(isset($result_nap_friday['CD2'])){ 
                                                    echo '<strong>ID ใหม่ '.$result_nap_friday['ID2'].'</strong>';
                                                }else{ 
                                            ?>
                                                <!-- <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onclick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','copy','<?php print $result_rprq_friday['RPRQ_CODE'];; ?>','1=1','1350','670','คัดลอกใบแจ้งซ่อม');"><i class='icon-new icon-large'></i></button> -->
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php }; ?>
                            </tbody>
                        </table>       
                        <!-- <b><font color="red" align="left" >*สามารถกด Double Click ข้อมูลในตาราง เพื่อดูรายละเอียดได้ทันที</font></b>         -->
                    </div> 
                    <div id="tabs-6">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable6">
                            <thead>
                                <tr height="30">
                                    <th rowspan="2" align="center" width="8%">เลขที่ใบขอซ่อม</th>
                                    <th rowspan="2" align="center" width="5%">สถานะ</th>
                                    <th rowspan="2" align="center" width="5%">พิมพ์</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ล่าสุด</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ถึงระยะ</th>
                                    <th rowspan="2" align="center" width="18%">ลักษณะงานซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">ปัญหาก่อนการซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">จัดการ</th>
                                </tr>
                                <tr height="30">
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>           
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>   
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $D6=$result_getdate_d1d7["D6"];
                                    $sql_rprq_saturday = "SELECT DISTINCT * FROM vwREPAIRREQUEST_PM WHERE RPRQ_AREA = '$SESSION_AREA'  AND RPRQ_WORKTYPE = 'PM' AND RPRQ_REQUESTCARDATE = '$D6'";
                                    $query_rprq_saturday = sqlsrv_query($conn, $sql_rprq_saturday);
                                    $no=0;
                                    while($result_rprq_saturday = sqlsrv_fetch_array($query_rprq_saturday, SQLSRV_FETCH_ASSOC)){	
                                        $no++;
                                ?>
                                <tr id="<?php print $result_rprq_saturday['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
                                    <td align="center"><?php print $result_rprq_saturday['RPRQ_ID']; ?></td>
                                    <td align="center">                                
                                        <?php
                                            switch($result_rprq_saturday['RPRQ_STATUSREQUEST']) {
                                                case "รอตรวจสอบ":
                                                    $text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
                                                break;
                                                case "รอคิวซ่อม":
                                                    $text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
                                                break;
                                                case "กำลังซ่อม":
                                                    $text="<strong><font color='red'>กำลังซ่อม</font></strong>";
                                                break;
                                                case "ซ่อมเสร็จสิ้น":
                                                  $text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
                                                break;
                                                case "รอจ่ายงาน":
                                                    $text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
                                                break;
                                                case "ไม่อนุมัติ":
                                                    $text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
                                                break;
                                            }
                                            print $text;
                                        ?>
                                    </td>                  
                                    <td align="center">
                                        <button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_pm('<?php print $result_rprq_saturday['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
                                    </td>
                                    <td align="center"><?php print $result_rprq_saturday['RPRQ_REGISHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_saturday['RPRQ_CARNAMEHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_saturday['RPRQ_REGISTAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_saturday['RPRQ_CARNAMETAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_saturday['RPRQ_MILEAGELAST']; ?></td>
                                    <td align="center"><?php print $result_rprq_saturday['RPRQ_MILEAGEFINISH']; ?></td>
                                    <td align="left"><?php print $result_rprq_saturday['RPC_SUBJECT_MERGE']; ?></td>
                                    <td align="left"><?php print $result_rprq_saturday['RPC_DETAIL']; ?></td>
                                    <td align="center" >                                  
                                        <?php if(($result_rprq_saturday['RPRQ_STATUSREQUEST']=='รอตรวจสอบ')||($result_rprq_saturday['RPRQ_STATUSREQUEST']=='รอจ่ายงาน')){ ?>
                                            <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','edit','<?php print $result_rprq_saturday['RPRQ_CODE'];; ?>','1=1','1350','670','แก้ไขใบแจ้งซ่อม');"><i class='icon-pencil icon-large'></i></button>
                                            &nbsp;&nbsp;
                                            <button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_requestrepair('<?php print $result_rprq_saturday['RPRQ_CODE']; ?>','<?php print $result_rprq_saturday['RPRQ_ID']; ?>')"><i class="icon-cancel icon-large"></i></button>
                                        <?php }else if(($result_rprq_saturday['RPRQ_STATUSREQUEST']=='ไม่อนุมัติ')){ ?>
                                            <?php 
                                                if(isset($result_nap_saturday['CD2'])){ 
                                                    echo '<strong>ID ใหม่ '.$result_nap_saturday['ID2'].'</strong>';
                                                }else{ 
                                            ?>
                                                <!-- <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','copy','<?php print $result_rprq_saturday['RPRQ_CODE'];; ?>','1=1','1350','670','คัดลอกใบแจ้งซ่อม');"><i class='icon-new icon-large'></i></button> -->
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php }; ?>
                            </tbody>
                        </table>       
                        <!-- <b><font color="red" align="left" >*สามารถกด Double Click ข้อมูลในตาราง เพื่อดูรายละเอียดได้ทันที</font></b>        -->
                    </div> 
                    <div id="tabs-7">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable7">
                            <thead>
                                <tr height="30">
                                    <th rowspan="2" align="center" width="8%">เลขที่ใบขอซ่อม</th>
                                    <th rowspan="2" align="center" width="5%">สถานะ</th>
                                    <th rowspan="2" align="center" width="5%">พิมพ์</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ล่าสุด</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ถึงระยะ</th>
                                    <th rowspan="2" align="center" width="18%">ลักษณะงานซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">ปัญหาก่อนการซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">จัดการ</th>
                                </tr>
                                <tr height="30">
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>           
                                    <th align="center"width="5%">ทะเบียน</th>
                                    <th align="center"width="10%">ชื่อรถ</th>   
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $D7=$result_getdate_d1d7["D7"];
                                    $sql_rprq_sonday = "SELECT DISTINCT * FROM vwREPAIRREQUEST_PM WHERE RPRQ_AREA = '$SESSION_AREA'  AND RPRQ_WORKTYPE = 'PM' AND RPRQ_REQUESTCARDATE = '$D7'";
                                    $query_rprq_sonday = sqlsrv_query($conn, $sql_rprq_sonday);
                                    $no=0;
                                    while($result_rprq_sonday = sqlsrv_fetch_array($query_rprq_sonday, SQLSRV_FETCH_ASSOC)){	
                                        $no++;
                                ?>
                                <tr id="<?php print $result_rprq_sonday['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">   
                                    <td align="center"><?php print $result_rprq_sonday['RPRQ_ID']; ?></td>
                                    <td align="center">                                
                                        <?php
                                            switch($result_rprq_sonday['RPRQ_STATUSREQUEST']) {
                                                case "รอตรวจสอบ":
                                                    $text="<strong><font color='red'>รอตรวจสอบ</font></strong>";
                                                break;
                                                case "รอคิวซ่อม":
                                                    $text="<strong><font color='red'>รอคิวซ่อม</font></strong>";
                                                break;
                                                case "กำลังซ่อม":
                                                    $text="<strong><font color='red'>กำลังซ่อม</font></strong>";
                                                break;
                                                case "ซ่อมเสร็จสิ้น":
                                                  $text="<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
                                                break;
                                                case "รอจ่ายงาน":
                                                    $text="<strong><font color='blue'>รอจ่ายงาน</font></strong>";
                                                break;
                                                case "ไม่อนุมัติ":
                                                    $text="<strong><font color='red'>ไม่อนุมัติ</font></strong>";
                                                break;
                                            }
                                            print $text;
                                        ?>
                                    </td>            
                                    <td align="center">
                                        <button type="button" class="mini bg-color-red" onclick="pdf_reportrepair_pm('<?php print $result_rprq_sonday['RPRQ_CODE']; ?>')"><font color="white" size="2"><i class="icon-file-pdf"></i> </font></button>
                                    </td>
                                    <td align="center"><?php print $result_rprq_sonday['RPRQ_REGISHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_sonday['RPRQ_CARNAMEHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq_sonday['RPRQ_REGISTAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_sonday['RPRQ_CARNAMETAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq_sonday['RPRQ_MILEAGELAST']; ?></td>
                                    <td align="center"><?php print $result_rprq_sonday['RPRQ_MILEAGEFINISH']; ?></td>
                                    <td align="left"><?php print $result_rprq_sonday['RPC_SUBJECT_MERGE']; ?></td>
                                    <td align="left"><?php print $result_rprq_sonday['RPC_DETAIL']; ?></td>
                                    <td align="center" >
                                        <?php if(($result_rprq_sonday['RPRQ_STATUSREQUEST']=='รอตรวจสอบ')||($result_rprq_sonday['RPRQ_STATUSREQUEST']=='รอจ่ายงาน')){ ?>
                                            <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','edit','<?php print $result_rprq_sonday['RPRQ_CODE'];; ?>','1=1','1350','670','แก้ไขใบแจ้งซ่อม');"><i class='icon-pencil icon-large'></i></button>
                                            &nbsp;&nbsp;
                                            <button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del" onclick="swaldelete_requestrepair('<?php print $result_rprq_sonday['RPRQ_CODE']; ?>','<?php print $result_rprq_sonday['RPRQ_ID']; ?>')"><i class="icon-cancel icon-large"></i></button>
                                        <?php }else if(($result_rprq_sonday['RPRQ_STATUSREQUEST']=='ไม่อนุมัติ')){ ?>
                                            <?php 
                                                if(isset($result_nap_sonday['CD2'])){ 
                                                    echo '<strong>ID ใหม่ '.$result_nap_sonday['ID2'].'</strong>';
                                                }else{ 
                                            ?>
                                                <!-- <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_bm_form.php','copy','<?php print $result_rprq_sonday['RPRQ_CODE'];; ?>','1=1','1350','670','คัดลอกใบแจ้งซ่อม');"><i class='icon-new icon-large'></i></button> -->
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php }; ?>
                            </tbody>
                        </table>       
                        <!-- <b><font color="red" align="left" >*สามารถกด Double Click ข้อมูลในตาราง เพื่อดูรายละเอียดได้ทันที</font></b>       -->
                    </div>           
                </div>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>