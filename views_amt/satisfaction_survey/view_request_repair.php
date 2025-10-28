<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
    
	$SESSION_AREA = $_SESSION["AD_AREA"];
		$HREF_PDF="../views_amt/satisfaction_survey/request_repair_pdf.php?id='+a1+'&area=$SESSION_AREA";
?>
<html>
<head>
<script type="text/javascript">
	$(document).ready(function(e) {	   
		$("#button_new_bm").click(function(){
			ajaxPopup2("<?=$path?>views_amt/satisfaction_survey/view_request_repair_form.php","add","1=1","1350","670","เพิ่มใบแจ้งซ่อม");
		});

		$("#button_new_pm").click(function(){
			ajaxPopup2("<?=$path?>views_amt/satisfaction_survey/view_request_repair_form.php","add","1=1","1350","670","เพิ่มใบแจ้งซ่อม");
		});
	
		$("#button_edit").click(function(){
			ajaxPopup2("<?=$path?>views_amt/satisfaction_survey/view_request_repair_form.php","edit","1=1","1350","670","แก้ไขใบแจ้งซ่อม");
		});
    });
    
    function search_request(){
        var dateStart = $("#dateStart").val();
        var query = "?dateStart="+dateStart;
        loadViewdetail('<?=$path?>views_amt/satisfaction_survey/view_request_repair.php'+query);
        // $.ajax({
        //     type: 'post',
        //     url: '<?=$path?>views_amt/satisfaction_survey/view_request_repair_get.php',
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

    function pdf_reportrepair_bm(a1) {
        window.open('<?=$HREF_PDF?>','_blank');
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
    function search_request_sendplan(){
        var query = "?dateStart=sendplan";
        loadViewdetail('<?=$path?>views_amt/satisfaction_survey/view_request_repair.php'+query);
    }
</script>
</head>
<body>
<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/car_repair.png" width="48" height="48"></td>
                <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;ข้อมูลใบขอซ่อมรถ</h3></td>
                <td width="617" align="right" valign="bottom" class="" nowrap>
                        <!-- <button class="bg-color-blue big" title="New" id="button_new_bm"><font color="white" size="4">New แจ้งซ่อม BM</font></button> -->
                        <!-- <button class="bg-color-orange big" title="New" onClick="loadViewdetail('<?=$path?>views_amt/satisfaction_survey/request_repair_pm.php');"><font color="white" size="4">New แจ้งซ่อม PM</font></button> -->
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
			if($_GET['dateStart']=="sendplan"){
                $SS_PERSONCODE = $_SESSION["AD_PERSONCODE"];
                if( ($SS_PERSONCODE=='040886')||($SS_PERSONCODE=='090367')||($SS_PERSONCODE=='040885') ){
                    $wh="AND RPRQ_STATUSREQUEST = 'รอส่งแผน' AND RPRQ_LINEOFWORK IN('RCC','RATC')";
                }else if( ($SS_PERSONCODE=='050131')||($SS_PERSONCODE=='050137')||($SS_PERSONCODE=='050116')||($SS_PERSONCODE=='050085') ){
                    $wh="AND RPRQ_STATUSREQUEST = 'รอส่งแผน' AND RPRQ_LINEOFWORK IN('RRC')";
                }else{
                    $wh="AND RPRQ_STATUSREQUEST = 'รอส่งแผน'";
                }
                
				$display_head="<h4>สถานะ <font color='blue'>รอส่งแผน</font> ทั้งหมด</h4>";
			}else{
				$getday = $_GET['dateStart'];			
				$sql_getdate_d1d7="SELECT
					CONVERT(VARCHAR,DATEADD(DAY,0,CONVERT(DATETIME,'$getday',103)),103) AS 'D1',
					CONVERT(VARCHAR,DATEADD(DAY,6,CONVERT(DATETIME,'$getday',103)),103) AS 'D7'";
				$params_getdate_d1d7 = array();	
				$query_getdate_d1d7 = sqlsrv_query( $conn, $sql_getdate_d1d7, $params_getdate_d1d7);	
				$result_getdate_d1d7 = sqlsrv_fetch_array($query_getdate_d1d7, SQLSRV_FETCH_ASSOC);
				$DAYSTARTWEEK = $result_getdate_d1d7['D1'];
				$DAYENDWEEK = $result_getdate_d1d7['D7'];	
				$display_head="<h4>ข้อมูลประจำวันที่ $DAYSTARTWEEK - $DAYENDWEEK</h4>";
			}
        }else{
            $getday = $STARTWEEK;
            $DAYSTARTWEEK = $STARTWEEK;
            $DAYENDWEEK = $ENDWEEK;
			// $wh="AND RPRQ_CREATEDATE_REQUEST BETWEEN '$DAYSTARTWEEK' AND '$DAYENDWEEK'";
			// $wh="AND RPRQ_REQUESTCARDATE = ";
			$wh="AND RPRQ_CREATEDATE_REQUEST = ";
			// $wh="AND RPC_INCARDATE = ";
			$display_head="<h4>ข้อมูลประจำวันที่ $DAYSTARTWEEK - $DAYENDWEEK</h4>";
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
			<?=$display_head;?>
                <?php
                    if($_GET['dateStart']!="sendplan"){
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
                    }
                ?>
                <div id="tabs_menu" style="height:100%;">
                    <ul>
                        <?php if($_GET['dateStart']!="sendplan"){ ?>
                            <li><a href="#tabs-1"><span style="font-size:13px">อาทิตย์ (<?=$result_getdate_d1d7["D1"]?>)</span></a></li>
                            <li><a href="#tabs-2"><span style="font-size:13px">จันทร์ (<?=$result_getdate_d1d7["D2"]?>)</span></a></li>
                            <li><a href="#tabs-3"><span style="font-size:13px">อังคาร (<?=$result_getdate_d1d7["D3"]?>)</span></a></li>
                            <li><a href="#tabs-4"><span style="font-size:13px">พุธ (<?=$result_getdate_d1d7["D4"]?>)</span></a></li>
                            <li><a href="#tabs-5"><span style="font-size:13px">พฤหัสฯ (<?=$result_getdate_d1d7["D5"]?>)</span></a></li>
                            <li><a href="#tabs-6"><span style="font-size:13px">ศุกร์ (<?=$result_getdate_d1d7["D6"]?>)</span></a></li>
                            <li><a href="#tabs-7"><span style="font-size:13px">เสาร์ (<?=$result_getdate_d1d7["D7"]?>)</span></a></li>
                        <?php }else { ?>
                            <li><a href="#tabs-1"><span style="font-size:13px">ข้อมูลทั้งหมด</span></a></li>
                        <?php } ?> 
                    </ul>
                    
                    <?php
                    // สร้าง array สำหรับวันในสัปดาห์
                    $days_config = array();
                    if($_GET['dateStart']!="sendplan"){
                        $days_config = array(
                            1 => array('name' => 'อาทิตย์', 'date' => $result_getdate_d1d7["D1"], 'var' => 'sonday'),
                            2 => array('name' => 'จันทร์', 'date' => $result_getdate_d1d7["D2"], 'var' => 'monday'),
                            3 => array('name' => 'อังคาร', 'date' => $result_getdate_d1d7["D3"], 'var' => 'tuesday'),
                            4 => array('name' => 'พุธ', 'date' => $result_getdate_d1d7["D4"], 'var' => 'wednesday'),
                            5 => array('name' => 'พฤหัสฯ', 'date' => $result_getdate_d1d7["D5"], 'var' => 'thursday'),
                            6 => array('name' => 'ศุกร์', 'date' => $result_getdate_d1d7["D6"], 'var' => 'friday'),
                            7 => array('name' => 'เสาร์', 'date' => $result_getdate_d1d7["D7"], 'var' => 'saturday')
                        );
                    } else {
                        $days_config = array(
                            1 => array('name' => 'ข้อมูลทั้งหมด', 'date' => '', 'var' => 'all')
                        );
                    }
                    
                    // ฟังก์ชันสำหรับแสดงสถานะ
                    function getStatusText($status) {
                        switch($status) {
                            case "รอส่งแผน":
                                return "<strong><font color='brown'>รอส่งแผน</font></strong>";
                            case "รอตรวจสอบ":
                                return "<strong><font color='red'>รอตรวจสอบ</font></strong>";
                            case "รอคิวซ่อม":
                                return "<strong><font color='red'>รอคิวซ่อม</font></strong>";
                            case "กำลังซ่อม":
                                return "<strong><font color='red'>กำลังซ่อม</font></strong>";
                            case "ซ่อมเสร็จสิ้น":
                                return "<strong><font color='green'>ซ่อมเสร็จสิ้น</font></strong>";
                            case "รอจ่ายงาน":
                                return "<strong><font color='blue'>รอจ่ายงาน</font></strong>";
                            case "ไม่อนุมัติ":
                                return "<strong><font color='red'>ไม่อนุมัติ</font></strong>";
                            default:
                                return $status;
                        }
                    }
                    
                    // ฟังก์ชันสำหรับสร้าง SQL query
                    function generateSqlQuery($day_date, $SESSION_AREA, $wh) {
                        return "SELECT * FROM vwREPAIRREQUEST WHERE RPRQ_AREA = '$SESSION_AREA' AND RPRQ_CREATEDATE_REQUEST = '$day_date' AND RPRQ_TYPECUSTOMER = 'cusout'";
                    }
                    
                    // ฟังก์ชันสำหรับแสดงปุ่มจัดการ
                    function renderManageButtons($result_rprq, $result_nap, $path, $customer_name) {
                        $output = '';
                        $customer_name_encoded = urlencode($customer_name);
                        $repair_id = $result_rprq['RPRQ_ID'];
                        $params = "repair_id={$repair_id}&customer_name={$customer_name_encoded}";
                        
                        $output .= '<button type="button" class="mini bg-color-blue" style="padding-top:12px;" title="ส่งแบบสอบถาม" onclick="javascript:ajaxPopup4(\''.$path.'views_amt/satisfaction_survey/view_request_repair_form.php\',\'edit\',\''.$repair_id.'\',\''.$params.'\',\'750\',\'600\',\'ส่งแบบสอบถาม\');"><i class=\'icon-arrow-right icon-large\'></i></button>';
                        return $output;
                    }
                    
                    // วนลูปสร้าง tabs
                    foreach($days_config as $tab_num => $day_info):
                    ?>
                    <div id="tabs-<?php echo $tab_num; ?>">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable<?php echo $tab_num; ?>">
                            <thead>
                                <tr height="30">
                                    <th rowspan="2" align="center" width="2%">ลำดับ</th>
                                    <th rowspan="2" align="center" width="8%">เลขที่ใบขอซ่อม</th>
                                    <th rowspan="2" align="center" width="5%">สถานะ</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                                    <th rowspan="2" align="center" width="8%">ลักษณะงานซ่อม</th>
                                    <th rowspan="2" align="center" width="22%">ปัญหาก่อนการซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">ส่งแบบสอบถาม</th>
                                </tr>
                                <tr height="30">
                                    <th align="center" width="5%">ทะเบียน</th>
                                    <th align="center" width="10%">ชื่อรถ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query ข้อมูลสำหรับแต่ละวัน
                                $sql_rprq = generateSqlQuery($day_info['date'], $SESSION_AREA, $wh);
                                $query_rprq = sqlsrv_query($conn, $sql_rprq);
                                $no = 0;
                                
                                while($result_rprq = sqlsrv_fetch_array($query_rprq, SQLSRV_FETCH_ASSOC)){
                                    $no++;
                                    
                                    // Query ข้อมูล NONAPPROVE
                                    $sql_nap = "SELECT DISTINCT B.RPRQ_ID ID1,B.RPRQ_CODE CD1,C.RPRQ_ID ID2,C.RPRQ_CODE CD2
                                        FROM REPAIRREQUEST_NONAPPROVE A
                                        LEFT JOIN vwREPAIRREQUEST B ON B.RPRQ_CODE = A.RPRQ_NAP_OLD_CODE
                                        LEFT JOIN vwREPAIRREQUEST C ON C.RPRQ_CODE = A.RPRQ_NAP_NEW_CODE
                                        WHERE B.RPRQ_CODE = '".$result_rprq['RPRQ_CODE']."' ORDER BY B.RPRQ_ID ASC";
                                    $query_nap = sqlsrv_query($conn, $sql_nap);
                                    $result_nap = sqlsrv_fetch_array($query_nap, SQLSRV_FETCH_ASSOC);

                                    // เช็คชื่อลูกค้า
                                    if(isset($result_rprq['RPRQ_LINEOFWORK']) && !empty($result_rprq['RPRQ_LINEOFWORK'])) {
                                        $customer_name = $result_rprq['RPRQ_LINEOFWORK'];
                                    } else {
                                        $customer_name = $result_rprq['RPRQ_COMPANYCASH'];
                                    }
                                ?>
                                <tr id="<?php print $result_rprq['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">
                                    <td align="center"><?php print $no; ?></td>
                                    <td align="center"><?php print $result_rprq['RPRQ_ID']; ?></td>
                                    <td align="center"><?php echo getStatusText($result_rprq['RPRQ_STATUSREQUEST']); ?></td>
                                    <td align="center"><?php print $result_rprq['RPRQ_REGISHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq['RPRQ_CARNAMEHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq['RPC_SUBJECT_CON']; ?></td>
                                    <td align="left"><?php print $result_rprq['RPC_DETAIL']; ?></td>
                                    <td align="center"><?php echo renderManageButtons($result_rprq, $result_nap, $path, $customer_name); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <!-- <b><font color="red" align="left" >*สามารถกด Double Click ข้อมูลในตาราง เพื่อดูรายละเอียดได้ทันที</font></b> -->
                    </div>
                    <?php endforeach; ?>
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
			<input type="button" class="button_gray" value="อัพเดท" onClick="javascript:loadViewdetail('<?=$path?>views_amt/satisfaction_survey/view_request_repair.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>