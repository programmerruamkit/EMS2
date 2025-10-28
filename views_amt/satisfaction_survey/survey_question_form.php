<?php
    session_name("EMS"); session_start();
    $path = "../";
    require($path.'../include/connect.php');

    $proc = isset($_GET["proc"]) ? $_GET["proc"] : "";
    $sc_code = isset($_GET["sc_code"]) ? $_GET["sc_code"] : "";
    $sq_id = isset($_GET["id"]) ? $_GET["id"] : "";
    $sm_code = isset($_GET["sm_code"]) ? $_GET["sm_code"] : "";
    $area = isset($_GET["area"]) ? $_GET["area"] : "";

    $SESSION_AREA = $_SESSION["AD_AREA"];
    $SESSION_PERSONCODE = $_SESSION["AD_PERSONCODE"];

    if($proc == "edit" && $sq_id != "") {
        $stmt = "SELECT * FROM SURVEY_QUESTION WHERE SQ_CODE = ? AND NOT SQ_STATUS='D'";
        $params = array($sq_id);
        $query = sqlsrv_query($conn, $stmt, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        $SQ_QUESTION = $result["SQ_QUESTION"];
        $SQ_ORDER = $result["SQ_ORDER"];
        $SQ_STATUS = $result["SQ_STATUS"];
        $SC_CODE = $result["SC_CODE"];
        $SQ_CODE = $result["SQ_CODE"];
    } else {
        // หา order ล่าสุด
        $stmt = "SELECT ISNULL(MAX(SQ_ORDER),0) AS MAX_ORDER FROM SURVEY_QUESTION WHERE SC_CODE = ? AND NOT SQ_STATUS='D'";
        $params = array($sc_code);
        $query = sqlsrv_query($conn, $stmt, $params);
        $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
        $SQ_ORDER = $result["MAX_ORDER"] + 1;
        $SQ_QUESTION = "";
        $SQ_STATUS = "Y";
    }  

    $n=6;
    function RandNum($n) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';      
        for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
        }      
        return $randomString;
    }  
    $rand="SQ_".RandNum($n);
?>
<html>
<head>
    <meta charset="UTF-8">
    <script type="text/javascript">
        function save_question() {
            var url = "<?=$path?>views_amt/satisfaction_survey/survey_question_proc.php";
            $.ajax({
                type: "POST",
                url: url,
                data: $("#form_question").serialize(),
                success: function (data) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: data,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        if(buttonname=='add'){
                            log_transac_menu('LA17', '-', '<?=$rand;?>');
                        }else{
                            log_transac_menu('LA18', '<?=$SQ_QUESTION;?>', '<?=$SQ_CODE;?>');
                        }
                        loadViewdetail('<?=$path?>views_amt/satisfaction_survey/survey_question.php?sc_code=<?=$sc_code?>&sm_code=<?=$sm_code?>&area=<?=$area?>');
                        closeUI();
                    });
                }
            });
        }
    </script>
</head>
<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" width="60%">
                <div class="panel panel-default" style="margin-left:5px; margin-right:5px;">
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
                        <tr class="TOP">
                            <td class="LEFT"></td>
                            <td class="CENTER">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <?php if($proc=="edit"){ ?>
                                            <td width="24" valign="middle"><img src="../images/Process-Info-icon32.png" width="32" height="32"></td>
                                            <td valign="bottom"><h4>&nbsp;&nbsp;แก้ไขคำถาม</h4></td>
                                        <?php }else{ ?>
                                            <td width="24" valign="middle"><img src="../images/plus-icon32.png" width="32" height="32"></td>
                                            <td valign="bottom"><h4>&nbsp;&nbsp;เพิ่มคำถาม</h4></td>
                                        <?php } ?>
                                    </tr>
                                </table>
                            </td>
                            <td class="RIGHT"></td>
                        </tr>
                        <tr class="CENTER">
                            <td class="LEFT"></td>
                            <td class="CENTER" align="center">
                                <form action="#" method="post" enctype="multipart/form-data" name="form_question" id="form_question">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default">
                                        <tbody>
                                            <tr align="center" height="25px">
                                                <td width="30%" height="35" align="right" class="ui-state-default"><strong>ลำดับที่ :</strong></td>
                                                <td width="70%" height="35" align="left" class="bg-white">
                                                    <div class="input-control text">
                                                        <input type="number" name="sq_order" class="time" min="1" value="<?=intval($SQ_ORDER)?>" style="width:100%;height:30px;" required />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr align="center" height="25px">
                                                <td width="30%" height="35" align="right" class="ui-state-default"><strong>คำถาม :</strong></td>
                                                <td width="70%" height="35" align="left" class="bg-white">
                                                    <textarea name="sq_text" id="sq_text" class="time" placeholder="กรอกคำถาม" required style="width:100%;height:70px;resize:vertical;"><?=htmlspecialchars($SQ_QUESTION)?></textarea>
                                                </td>
                                            </tr>
                                            <tr align="center" height="25px">
                                                <td height="35" align="right" class="ui-state-default"><strong>สถานะ :</strong></td>
                                                <td height="35" align="left" class="bg-white">
                                                    <div class="input-control text">
                                                        <select class="time" style="width:100%;height:30px;" name="sq_status" id="sq_status" required>
                                                            <option value disabled selected>-------โปรดเลือก-------</option>
                                                            <option value="Y" <?php if($SQ_STATUS== "Y"){echo "selected";} ?>>เปิดใช้งาน</option>
                                                            <option value="N" <?php if($SQ_STATUS== "N"){echo "selected";} ?>>ปิดใช้งาน</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <input type="hidden" name="proc" value="<?=$proc?>">
                                            <input type="hidden" name="session_area" value="<?=$SESSION_AREA?>">
                                            <input type="hidden" name="session_personcode" value="<?=$SESSION_PERSONCODE?>">
                                            <input type="hidden" name="sc_code" value="<?=$sc_code?>">
                                            <input type="hidden" name="sm_code" value="<?=$sm_code?>">                                            
                                            <tr align="center" height="25px">
                                                <td height="35" colspan="2" align="center">
                                                    <?php if($proc=="edit"){ ?>
                                                        <input type="hidden" name="sq_code" id="sq_code" value="<?=$SQ_CODE; ?>">
                                                        <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="edit" onclick="save_question()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                                                    <?php }else{ ?>
                                                        <input type="hidden" name="sq_code" id="sq_code" value="<?=$rand; ?>">
                                                        <button class="bg-color-green font-white" type="button" name="buttonname" id="buttonname" value="add" onclick="save_question()">บันทึกข้อมูล</button>&nbsp;&nbsp;&nbsp;
                                                    <?php } ?>
                                                    <button class="bg-color-red font-white" type="button" onclick="closeUI()">ปิดหน้าจอ</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </td>
                            <td class="RIGHT"></td>
                        </tr>
                        <tr class="BOTTOM">
                            <td class="LEFT">&nbsp;</td>
                            <td class="CENTER">&nbsp;</td>
                            <td class="RIGHT">&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </td>
            <?php if($proc=="edit"){ ?>
            <td valign="top">
                <div style="width:100%;" align="right">
                    <div class="panel panel-default" style="height:100%">
                        <div align="left" class="panel-heading">
                            <h5><img src="../images/task.png" width="32" height="32"> Log Transaction</h5>
                        </div>
                        <table width="100%" class="table table-hover table-striped">
                            <thead>
                                <tr height="35">
                                    <th width="5%" align="center"><strong>#</strong></th>
                                    <th width="15%" align="left"><strong>กิจกรรม</strong></th>
                                    <th width="40%" align="left"><strong>หมายเหตุ</strong></th>
                                    <th width="20%" align="left"><strong>ผู้บันทึก</strong></th>
                                    <th width="20%" align="center"><strong>วันเวลาที่บันทึก</strong></th>
                                </tr>
                            </thead>
                            <?php
                                $sql_log = "SELECT * FROM vwLOG_TRANSAC WHERE LOG_RESERVE_CODE = ? AND LOGACT_CODE BETWEEN 'LA17' AND 'LA19' ORDER BY TIMESTAMP ASC";
                                $params = array($sq_code);	
                                $query_log = sqlsrv_query($conn, $sql_log, $params);	
                                $no=0;
                                while($result_log = sqlsrv_fetch_array($query_log, SQLSRV_FETCH_ASSOC)){	
                                    $no++;
                                    $MEAN=$result_log['MEAN'];
                                    $LOG_REMARK=$result_log['LOG_REMARK'];
                                    $WORKINGBY=$result_log['WORKINGBY'];
                                    $DATETIME1038=$result_log['DATETIME1038'];
                            ?>
                                <tr height="25">
                                    <td align="center"><font size="2"><?php print "$no.";?></font></td>
                                    <td><font size="2"><?php print "$MEAN.";?></font></td>
                                    <td><font size="2"><?php print "$LOG_REMARK.";?></font></td>
                                    <td><font size="2"><?php print "$WORKINGBY.";?></font></td>
                                    <td align="center"><font size="2"><?php print "$DATETIME1038.";?></font></td>
                                </tr>
                            <?php }; ?>
                        </table>
                    </div>
                </div>
            </td>
            <?php } ?>
        </tr>
    </table>
</body>
</html>