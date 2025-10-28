<?php
    session_name("EMS"); session_start();
    $path = "../";
    require($path.'../include/connect.php');

    $sc_code = isset($_GET["sc_code"]) ? $_GET["sc_code"] : "";
    $area = isset($_GET["area"]) ? $_GET["area"] : "";
    $sm_code = isset($_GET["sm_code"]) ? $_GET["sm_code"] : "";
    $SESSION_AREA = $_SESSION["AD_AREA"];

	// echo"<pre>";
	// print_r($_GET);
	// echo"</pre>";
	// exit();

    // ดึงชื่อหมวดหมู่
    $stmt_cat = "SELECT SC_NAME FROM SURVEY_CATEGORY WHERE SC_CODE = ? AND NOT SC_STATUS='D'";
    $params_cat = array($sc_code);
    $query_cat = sqlsrv_query($conn, $stmt_cat, $params_cat);
    $result_cat = sqlsrv_fetch_array($query_cat, SQLSRV_FETCH_ASSOC);
    $SC_NAME = $result_cat ? $result_cat["SC_NAME"] : "";

?>
<html>
<head>
    <meta charset="UTF-8">
    <title>จัดการคำถามในหมวดหมู่</title>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#button_new_question").click(function(){
                ajaxPopup2("<?=$path?>views_amt/satisfaction_survey/survey_question_form.php","add","sc_code=<?=$sc_code?>&sm_code=<?=$sm_code?>&area=<?=$area?>","700","400","เพิ่มคำถาม");
            });
        });
        function swaldelete_question(sq_id, no) {
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
                    var url = "<?=$path?>views_amt/satisfaction_survey/survey_question_proc.php?proc=delete&id="+sq_id;
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
                                loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_question.php?sc_code=<?=$sc_code?>&sm_code=<?=$sm_code?>&area=<?=$SESSION_AREA;?>');
                            })	
                        }
                    });
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
                    <td width="25" valign="middle" class=""><img src="../images/checklist-icon128.png" width="40" height="40"></td>
                    <td valign="bottom" class="">
                        <h3>&nbsp;&nbsp;จัดการคำถามในหมวดหมู่ : <?=htmlspecialchars($SC_NAME)?></h3>
                    </td>
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
                            <button type="button" class="bg-color-blue" id="button_new_question">
                                <font color="#FFFFFF"><i class="fa fa-plus"></i> เพิ่มคำถาม</font>
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
                            <th width="55%">คำถาม</th>
                            <th width="20%">สถานะ</th>
                            <th width="20%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $stmt_q = "SELECT * FROM SURVEY_QUESTION WHERE SC_CODE = ? AND NOT SQ_STATUS='D' ORDER BY SQ_ORDER, SQ_ID";
                            $params_q = array($sc_code);
                            $query_q = sqlsrv_query($conn, $stmt_q, $params_q);
                            $no = 0;
                            while($q = sqlsrv_fetch_array($query_q, SQLSRV_FETCH_ASSOC)) {
                                $no++;
                                $SQ_ID = $q["SQ_ID"];
                                $SQ_CODE = $q["SQ_CODE"];
                                $SQ_QUESTION = $q["SQ_QUESTION"];
                                $SQ_ORDER = $q["SQ_ORDER"];
                                $SQ_STATUS = $q["SQ_STATUS"];
                                $status_show = ($SQ_STATUS=="Y") ? "<img src='../images/check_true.gif' width='16' height='16'>" : "<img src='../images/check_del.gif' width='16' height='16'>";
                        ?>
                        <tr height="25px" align="center">
                            <td><?=$SQ_ORDER?></td>
                            <td align="left"><?=htmlspecialchars($SQ_QUESTION)?></td>
                            <td><?=$status_show?></td>
                            <td>
                                <button type="button" class="mini bg-color-yellow" style="padding-top:12px;" title="Edit"
                                    onclick="ajaxPopup4('<?=$path?>views_amt/satisfaction_survey/survey_question_form.php','edit','<?=$SQ_CODE?>&sc_code=<?=$sc_code?>&sm_code=<?=$sm_code?>&area=<?=$area?>','1=1','1300','400','แก้ไขคำถาม');">
                                    <i class='icon-pencil icon-large'></i>
                                </button>
                                &nbsp;&nbsp;
                                <button type="button" class="mini bg-color-red" style="padding-top:12px;" title="Del"
                                    onclick="swaldelete_question('<?=$SQ_ID?>','<?=$no?>')">
                                    <i class="icon-cancel icon-large"></i>
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
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
                <input type="button" class="button_red2" value="ย้อนกลับ" onclick="javascript:loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_sub.php?sm_code=<?=$sm_code;?>&area=<?=$SESSION_AREA;?>');">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_question.php?sc_code=<?=$sc_code;?>&sm_code=<?=$sm_code;?>&area=<?=$SESSION_AREA;?>');">
            </center>
        </td>
        <td class="RIGHT">&nbsp;</td>
    </tr>
</table>
</body>
</html>