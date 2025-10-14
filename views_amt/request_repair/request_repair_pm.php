<?php
session_name("EMS"); 
session_start();
$path = "../";   	
require($path.'../include/connect.php');

// Variables
$SESSION_AREA = $_SESSION["AD_AREA"];
$SS_ROLE_NAME = $_SESSION["SS_ROLE_NAME"];

// Get date parameter
if(isset($_GET['dateStart']) && $_GET['dateStart'] != ""){
    $getday = $_GET['dateStart'];
} else {
    $getday = $STARTWEEK;
    $DAYSTARTWEEK = $STARTWEEK;
    $DAYENDWEEK = $ENDWEEK;
}

// Helper Functions
function getStatusTextPM($status) {
    $status_colors = array(
        "รอตรวจสอบ" => "red",
        "รอคิวซ่อม" => "red", 
        "กำลังซ่อม" => "orange",
        "ซ่อมเสร็จสิ้น" => "green",
        "รอจ่ายงาน" => "blue",
        "ไม่อนุมัติ" => "red"
    );
    
    $color = isset($status_colors[$status]) ? $status_colors[$status] : "black";
    return "<strong><font color='$color'>$status</font></strong>";
}

function generateSqlQueryPM($day_date, $SESSION_AREA) {
    return "SELECT DISTINCT * FROM vwREPAIRREQUEST_PM 
            WHERE RPRQ_AREA = '$SESSION_AREA' 
            AND RPRQ_WORKTYPE = 'PM' 
            AND RPRQ_REQUESTCARDATE = '$day_date'
            ORDER BY RPRQ_ID ASC";
}

function renderManageButtonsPM($result_rprq, $result_nap, $path) {
    $output = '';
    $status = $result_rprq['RPRQ_STATUSREQUEST'];
    
    if(in_array($status, array('รอตรวจสอบ', 'รอจ่ายงาน'))){
        $output .= '<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="แก้ไข" 
                    onClick="javascript:ajaxPopup4(\''.$path.'views_amt/request_repair/request_repair_pm_form.php\',\'edit\',\''.$result_rprq['RPRQ_CODE'].'\',\'1=1\',\'1350\',\'670\',\'แก้ไขใบแจ้งซ่อม PM\');">
                    <i class="icon-pencil icon-large"></i></button>';
        $output .= '&nbsp;&nbsp;';
        $output .= '<button type="button" class="mini bg-color-red" style="padding-top:12px;" title="ลบ" 
                    onclick="swaldelete_requestrepair(\''.$result_rprq['RPRQ_CODE'].'\',\''.$result_rprq['RPRQ_ID'].'\')">
                    <i class="icon-cancel icon-large"></i></button>';
    } else if($status == 'ไม่อนุมัติ'){
        if(isset($result_nap['CD2']) && $result_nap['CD2'] != ''){
            $output .= '<strong>ID ใหม่ '.$result_nap['ID2'].'</strong>';
        } else {
            $output .= '<button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="คัดลอก" 
                        onClick="javascript:ajaxPopup4(\''.$path.'views_amt/request_repair/request_repair_pm_form.php\',\'copy\',\''.$result_rprq['RPRQ_CODE'].'\',\'1=1\',\'1350\',\'670\',\'คัดลอกใบแจ้งซ่อม PM\');">
                        <i class="icon-new icon-large"></i></button>';
        }
    }
    return $output;
}

// Get date range for week
$sql_getdate_d1d7 = "SELECT 
    CONVERT(VARCHAR,DATEADD(DAY,0,CONVERT(DATETIME,'$getday',103)),103) AS 'D1',
    CONVERT(VARCHAR,DATEADD(DAY,1,CONVERT(DATETIME,'$getday',103)),103) AS 'D2',
    CONVERT(VARCHAR,DATEADD(DAY,2,CONVERT(DATETIME,'$getday',103)),103) AS 'D3',
    CONVERT(VARCHAR,DATEADD(DAY,3,CONVERT(DATETIME,'$getday',103)),103) AS 'D4',
    CONVERT(VARCHAR,DATEADD(DAY,4,CONVERT(DATETIME,'$getday',103)),103) AS 'D5',
    CONVERT(VARCHAR,DATEADD(DAY,5,CONVERT(DATETIME,'$getday',103)),103) AS 'D6',
    CONVERT(VARCHAR,DATEADD(DAY,6,CONVERT(DATETIME,'$getday',103)),103) AS 'D7'";

$query_getdate_d1d7 = sqlsrv_query($conn, $sql_getdate_d1d7);	
$result_getdate_d1d7 = sqlsrv_fetch_array($query_getdate_d1d7, SQLSRV_FETCH_ASSOC);

// Days configuration
$days_config = array(
    1 => array('name' => 'อาทิตย์', 'date' => $result_getdate_d1d7["D1"]),
    2 => array('name' => 'จันทร์', 'date' => $result_getdate_d1d7["D2"]),
    3 => array('name' => 'อังคาร', 'date' => $result_getdate_d1d7["D3"]),
    4 => array('name' => 'พุธ', 'date' => $result_getdate_d1d7["D4"]),
    5 => array('name' => 'พฤหัสฯ', 'date' => $result_getdate_d1d7["D5"]),
    6 => array('name' => 'ศุกร์', 'date' => $result_getdate_d1d7["D6"]),
    7 => array('name' => 'เสาร์', 'date' => $result_getdate_d1d7["D7"])
);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ข้อมูลใบขอซ่อมรถ PM</title>
<script type="text/javascript">
function pdf_reportrepair_pm(refcode) {
    var url = '<?=$path?>views_amt/request_repair/request_repair_pdf.php?id=' + refcode + '&area=<?=$SESSION_AREA?>';
    window.open(url, '_blank');
}

function swaldelete_requestrepair(refcode, no) {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        html: 'ที่จะยกเลิกรายการซ่อมของ ID หมายเลข <strong>' + no + '</strong>',
        text: "หากลบแล้ว คุณจะไม่สามารถกู้คืนข้อมูลได้!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#C82333',
        confirmButtonText: 'ใช่! ลบเลย',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            var url = "<?=$path?>views_amt/request_repair/request_repair_pm_proc.php?proc=delete&id=" + refcode;
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ลบข้อมูลเรียบร้อยแล้ว',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm.php');
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถลบข้อมูลได้'
                    });
                }
            });
        }
    });
}

function search_request(){
    var dateStart = $("#dateStart").val();
    if(dateStart === '') {
        Swal.fire({
            icon: 'warning',
            title: 'กรุณาเลือกวันที่',
            showConfirmButton: true
        });
        return;
    }
    var query = "?dateStart=" + dateStart;
    loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm.php' + query);
}

function initializeDataTables() {
    // Destroy existing DataTables
    $('.display').each(function() {
        if ($.fn.DataTable.isDataTable(this)) {
            $(this).DataTable().destroy();
        }
    });
    
    // Initialize new DataTables
    <?php foreach($days_config as $tab_num => $day_info): ?>
        $('#datatable<?php echo $tab_num; ?>').DataTable({
            "destroy": true,
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 25,
            "language": {
                "url": "<?=$path?>assets/plugins/datatables/Thai.json"
            }
        });
    <?php endforeach; ?>
}

$(document).ready(function() {
    // Initialize date picker
    $('.datepic').datetimepicker({
        timepicker: false,
        lang: 'th',
        format: 'd/m/Y',
        closeOnDateSelect: true
    });
    
    // Initialize tab menu first
    $("#tabs_menu").tabs({
        activate: function(event, ui) {
            // Re-initialize DataTable when tab is activated
            var tableId = ui.newPanel.find('table').attr('id');
            if (tableId) {
                if ($.fn.DataTable.isDataTable('#' + tableId)) {
                    $('#' + tableId).DataTable().destroy();
                }
                $('#' + tableId).DataTable({
                    "destroy": true,
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "pageLength": 25,
                    "language": {
                        "url": "<?=$path?>assets/plugins/datatables/Thai.json"
                    }
                });
            }
        }
    });
    
    // Initialize DataTables
    setTimeout(initializeDataTables, 100);
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
                    <td width="25" valign="middle">
                        <img src="<?=$path?>images/car_repair.png" width="48" height="48">
                    </td>
                    <td width="419" height="10%" valign="bottom">
                        <h3>&nbsp;&nbsp;ข้อมูลใบขอซ่อมรถ PM</h3>
                    </td>
                    <td width="617" align="right" valign="bottom" nowrap>
                        <button class="bg-color-orange big" title="สร้างใหม่" 
                                onClick="loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm_form.php');">
                            <font color="white" size="4">New แจ้งซ่อม PM</font>
                        </button>
                    </td>
                </tr>
            </table>
        </td>
        <td class="RIGHT"></td>
    </tr>
    
    <tr class="CENTER">
        <td class="LEFT"></td>    
        <td class="CENTER" align="center">        
            <br>    
            <table>
                <tbody>
                    <tr style="height:25px" align="center">                               
                        <td width="15%" align="center">
                            <div class="input-control">     
                                <input type="text" name="dateStart" id="dateStart" class="datepic time" 
                                       placeholder="วันที่เริ่มต้นสัปดาห์" autocomplete="off" 
                                       value="<?=$getday;?>" onchange="search_request()">        
                            </div>
                        </td>                       
                        <td align="center"></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                </tbody>
            </table>  
            
            <div id="showrequestold">
                <div id="tabs_menu" style="height:100%;">
                    <ul>
                        <?php foreach($days_config as $tab_num => $day_info): ?>
                            <li><a href="#tabs-<?php echo $tab_num; ?>">
                                <span style="font-size:13px"><?php echo $day_info['name']; ?> (<?php echo $day_info['date']; ?>)</span>
                            </a></li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <?php foreach($days_config as $tab_num => $day_info): ?>
                    <div id="tabs-<?php echo $tab_num; ?>">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" 
                               class="default hover pointer display" id="datatable<?php echo $tab_num; ?>">
                            <thead>
                                <tr height="30">
                                    <th rowspan="2" align="center" width="2%">ลำดับ</th>
                                    <th rowspan="2" align="center" width="8%">เลขที่ใบขอซ่อม</th>
                                    <th rowspan="2" align="center" width="5%">สถานะ</th>
                                    <th rowspan="2" align="center" width="5%">พิมพ์</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ล่าสุด</th>
                                    <th rowspan="2" align="center" width="6%">ไมล์ถึงระยะ</th>
                                    <th rowspan="2" align="center" width="18%">ลักษณะงานซ่อม</th>
                                    <th rowspan="2" align="center" width="10%">ปัญหาก่อนการซ่อม</th>
                                    <th rowspan="2" align="center" width="7%">จัดการ</th>
                                </tr>
                                <tr height="30">
                                    <th align="center" width="5%">ทะเบียน</th>
                                    <th align="center" width="10%">ชื่อรถ</th>
                                    <th align="center" width="5%">ทะเบียน</th>
                                    <th align="center" width="10%">ชื่อรถ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query ข้อมูลสำหรับแต่ละวัน
                                $sql_rprq = generateSqlQueryPM($day_info['date'], $SESSION_AREA);
                                $query_rprq = sqlsrv_query($conn, $sql_rprq);
                                $no = 0;
                                
                                if($query_rprq){
                                    while($result_rprq = sqlsrv_fetch_array($query_rprq, SQLSRV_FETCH_ASSOC)){
                                        $no++;
                                        // Query ข้อมูล NONAPPROVE
                                        $sql_nap = "SELECT DISTINCT B.RPRQ_ID ID1,B.RPRQ_CODE CD1,C.RPRQ_ID ID2,C.RPRQ_CODE CD2
                                            FROM REPAIRREQUEST_NONAPPROVE A
                                            LEFT JOIN vwREPAIRREQUEST B ON B.RPRQ_CODE = A.RPRQ_NAP_OLD_CODE
                                            LEFT JOIN vwREPAIRREQUEST C ON C.RPRQ_CODE = A.RPRQ_NAP_NEW_CODE
                                            WHERE B.RPRQ_CODE = '".$result_rprq['RPRQ_CODE']."'";
                                        $query_nap = sqlsrv_query($conn, $sql_nap);
                                        $result_nap = sqlsrv_fetch_array($query_nap, SQLSRV_FETCH_ASSOC);
                                ?>
                                <tr id="<?php print $result_rprq['RPRQ_CODE']; ?>" style="cursor:pointer" height="25px" align="center">
                                    <td align="center"><?php print $no; ?></td>
                                    <td align="center"><?php print $result_rprq['RPRQ_ID']; ?></td>
                                    <td align="center"><?php echo getStatusTextPM($result_rprq['RPRQ_STATUSREQUEST']); ?></td>
                                    <td align="center">
                                        <button type="button" class="mini bg-color-red" title="พิมพ์ PDF"
                                                onclick="pdf_reportrepair_pm('<?php print $result_rprq['RPRQ_CODE']; ?>')">
                                            <font color="white" size="2"><i class="icon-file-pdf"></i></font>
                                        </button>
                                    </td>
                                    <td align="center"><?php print $result_rprq['RPRQ_REGISHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq['RPRQ_CARNAMEHEAD']; ?></td>
                                    <td align="center"><?php print $result_rprq['RPRQ_REGISTAIL']; ?></td>
                                    <td align="center"><?php print $result_rprq['RPRQ_CARNAMETAIL']; ?></td>
                                    <td align="center"><?php print number_format($result_rprq['RPRQ_MILEAGELAST']); ?></td>
                                    <td align="center"><?php print number_format($result_rprq['RPRQ_MILEAGEFINISH']); ?></td>
                                    <td align="left"><?php print $result_rprq['RPC_SUBJECT_MERGE']; ?></td>
                                    <td align="left"><?php print $result_rprq['RPC_DETAIL']; ?></td>
                                    <td align="center"><?php echo renderManageButtonsPM($result_rprq, $result_nap, $path); ?></td>
                                </tr>
                                <?php 
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </td>
        <td class="RIGHT"></td>
    </tr>
    
    <tr class="BOTTOM">
        <td class="LEFT">&nbsp;</td>
        <td class="CENTER">&nbsp;		
            <center>
                <input type="button" class="button_gray" value="อัพเดท" 
                       onClick="javascript:loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm.php');">
            </center>
        </td>
        <td class="RIGHT">&nbsp;</td>
    </tr>
</table>
</body>
</html>