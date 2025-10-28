<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');	
    $SESSION_AREA = $_SESSION["AD_AREA"];
    $sm_code = $_GET["sm_code"];
    
    // ดึงข้อมูลกลุ่มแบบประเมิน
    $stmt_main = "SELECT * FROM SURVEY_MAIN WHERE SM_CODE = ? AND NOT SM_STATUS='D'";
    $params_main = array($sm_code);
    $query_main = sqlsrv_query($conn, $stmt_main, $params_main);
    $result_main = sqlsrv_fetch_array($query_main, SQLSRV_FETCH_ASSOC);
    $SM_NAME = $result_main["SM_NAME"];
    $SM_TARGET_GROUP = $result_main["SM_TARGET_GROUP"];
?>
<html>
<head>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#button_new_category").click(function(){
            ajaxPopup2("<?=$path?>views_amt/satisfaction_survey/survey_sub_form.php","add","sm_code=<?=$sm_code?>","700","400","เพิ่มหมวดหมู่แบบประเมิน");
        });
    });
    function swaldelete_category(cat_id, no) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่...ที่จะลบหมวดหมู่ที่ '+no+' นี้',
            text: "หากลบแล้ว คำถามในหมวดหมู่นี้จะถูกลบด้วย!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#C82333',
            confirmButtonText: 'ใช่! ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "<?=$path?>views_amt/satisfaction_survey/survey_sub_proc.php?proc=delete&id="+cat_id;
                $.ajax({
                    type:'GET',
                    url:url,
                    success:function(data){
						Swal.fire({
							icon: 'success',
							title: 'ลบข้อมูลเรียบร้อยแล้ว',
							showConfirmButton: false,
							timer: 2000
						}).then((result) => {	
							loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_sub.php?sm_code=<?=$sm_code;?>');
						})	
                    }
                });
            }
        })
    }
</script>
</head>
<body>
<input type="hidden" id="selected_id" value="">
<input type="hidden" id="selected_type" value="">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
    <tr class="TOP">
        <td class="LEFT"></td>
        <td class="CENTER">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="25" valign="middle" class=""><img src="../images/checklist-icon128.png" width="48" height="48"></td>
                    <td width="1000" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;จัดการหมวดหมู่ของกลุ่ม : <?=$SM_NAME;?> (<?=$SM_TARGET_GROUP;?>)</h3></td>
                </tr>
            </table>
        </td>
        <td class="RIGHT"></td>
    </tr>
    <tr class="CENTER">
        <td class="LEFT"></td>
        <td class="CENTER" align="center">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                <tbody>	
                    <tr>
                        <td>
                            <button type="button" class="bg-color-blue" id="button_new_category">
                                <font color="#FFFFFF"><i class="fa fa-plus"></i> เพิ่มหมวดหมู่</font>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <form id="form1" name="form1" method="post" action="#">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
                    <thead>
                        <tr height="30">
                            <th width="5%">ลำดับ.</th>
                            <th width="35%">ชื่อหมวดหมู่</th>
							<th width="20%">จัดการคำถาม</th>
							<th width="10%">พื้นที่</th>
                            <th width="10%">สถานะ</th>
                            <th width="20%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // ดึงหมวดหมู่
                            $stmt_category = "SELECT * FROM SURVEY_CATEGORY WHERE SM_CODE = ? AND NOT SC_STATUS='D' ORDER BY SC_ORDER, SC_ID";
                            $params_category = array($sm_code);
                            $query_category = sqlsrv_query($conn, $stmt_category, $params_category);
                            $no = 0;
                            while($category = sqlsrv_fetch_array($query_category, SQLSRV_FETCH_ASSOC)) {
                                $no++;
                                $SC_ID = $category["SC_ID"];
								$SC_CODE = $category["SC_CODE"];
                                $SC_NAME = $category["SC_NAME"];
                                $SC_ORDER = $category["SC_ORDER"];
                                $SC_STATUS = $category["SC_STATUS"];
								$SC_AREA = $category["SC_AREA"];
                                $status_show = ($SC_STATUS=="Y") ? "<img src='../images/check_true.gif' width='16' height='16'>" : "<img src='../images/check_del.gif' width='16' height='16'>";
							
								$sql_count = "SELECT COUNT(SC_CODE) COUNTSCCODE FROM SURVEY_QUESTION WHERE SC_CODE = ? AND NOT SQ_STATUS='D'";
								$params_count = array($SC_CODE);	
								$query_count = sqlsrv_query( $conn, $sql_count, $params_count);	
								$result_count = sqlsrv_fetch_array($query_count, SQLSRV_FETCH_ASSOC);
                        ?>
                        <tr height="25px" align="center">
                            <td align="center"><?php print intval($SC_ORDER); ?>.</td>
                            <td align="left">&nbsp;<?php print htmlspecialchars($SC_NAME); ?></td>
							<td align="center" >&nbsp;<a href="javascript:void(0);" onclick="javascript:loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_question.php?sc_code=<?php print $SC_CODE; ?>&sm_code=<?=$sm_code;?>&area=<?=$SC_AREA;?>',);"><b><?=$result_count["COUNTSCCODE"];?></b>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/add-icon16.png" width="16" height="16"></a></td>
							<td align="center"><?php print $SC_AREA; ?></td>
                            <td align="center"><?php print $status_show; ?></td>
                            <td align="center">
                                <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit"
                                    onclick="ajaxPopup4('<?=$path?>views_amt/satisfaction_survey/survey_sub_form.php','edit','<?=$SC_ID?>&sm_code=<?=$sm_code?>','1=1','1200','400','แก้ไขหมวดหมู่แบบประเมิน');">
                                    <i class='icon-pencil icon-large'></i>
                                </button>
                                &nbsp;&nbsp;
                                <button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del"
                                    onclick="swaldelete_category('<?=$SC_ID?>','<?=$no?>')">
                                    <i class="icon-cancel icon-large"></i>
                                </button>
                            </td>
                        </tr>
                        <?php }; ?>
                    </tbody>
                </table>
            </form>
        </td>
        <td class="RIGHT"></td>
    </tr>
    <tr class="BOTTOM">
        <td class="LEFT">&nbsp;</td>
        <td class="CENTER">&nbsp;		
            <center>
                <input type="button" class="button_red2" value="ย้อนกลับ" onclick="javascript:loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_main.php');">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_sub.php?sm_code=<?=$sm_code;?>&area=<?=$SESSION_AREA;?>');">
            </center>
        </td>
        <td class="RIGHT">&nbsp;</td>
    </tr>
</table>
</body>
</html>