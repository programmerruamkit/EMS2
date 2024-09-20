<?php
	session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
    
	$SESSION_AREA = $_SESSION["AD_AREA"];
		$HREF_PDF="../views_amt/request_repair/request_repair_pdf.php?id='+a1+'&area=$SESSION_AREA";
?>
<html>
<head>
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
                <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;ข้อมูลใบขอซ่อมที่ไม่ได้รับอนุมัติซ่อม</h3></td>
                <td width="617" align="right" valign="bottom" class="" nowrap>
                        <!-- <button class="bg-color-blue big" title="New" id="button_new_bm"><font color="white" size="4">New แจ้งซ่อม BM</font></button> -->
                        <!-- <button class="bg-color-orange big" title="New" onClick="loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_pm.php');"><font color="white" size="4">New แจ้งซ่อม PM</font></button> -->
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
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable1"><!-- default hover pointer display hover pointer -->
            <thead>
                <tr height="30">
                    <!-- <th rowspan="2" align="center" width="6%">ประเภทงาน</th> -->
                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลใบขอซ่อม - เดิม</th>
                    <th rowspan="2" align="center" width="10%">สาเหตุไม่อนุมัติ</th>
                    <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลใบขอซ่อม - ใหม่</th>
                    <th rowspan="2" align="center" width="5%">สถานะปัจจุบัน</th>
                    <th colspan="2" align="center" width="13%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                    <th colspan="2" align="center" width="13%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
                    <th rowspan="2" align="center" width="8%">ลักษณะงานซ่อม</th>
                    <th rowspan="2" align="center" width="25%">ปัญหาก่อนการซ่อม</th>
                </tr>
                <tr height="30">
                    <th align="center"width="8%">เลขที่</th>
                    <th align="center"width="14%">วันที่แจ้งซ่อม</th>  
                    <th align="center"width="8%">เลขที่</th>
                    <th align="center"width="14%">วันที่แจ้งซ่อม</th>  
                    <th align="center"width="5%">ทะเบียน</th>
                    <th align="center"width="10%">ชื่อรถ</th>           
                    <th align="center"width="5%">ทะเบียน</th>
                    <th align="center"width="10%">ชื่อรถ</th>    
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql_rprq_nap = "SELECT
                        DISTINCT
                        B.RPRQ_WORKTYPE WK,
                        B.RPRQ_ID ID1,
                        B.RPRQ_REQUESTCARDATE DATE1,
                        B.RPRQ_REQUESTCARTIME TIME1,
                        B.RPRQ_CREATEDATE_REQUEST DATECREATE1,
                        CAST(D.RPRQ_REMARK AS VARCHAR(MAX)) REMARK,
                        C.RPRQ_ID ID2,
                        C.RPRQ_REQUESTCARDATE DATE2,
                        C.RPRQ_REQUESTCARTIME TIME2,
                        C.RPRQ_CREATEDATE_REQUEST DATECREATE2,
                        C.RPRQ_STATUSREQUEST STATUS,
                        C.RPRQ_REGISHEAD RGH,
                        C.RPRQ_CARNAMEHEAD CNH,
                        C.RPRQ_REGISTAIL RGT,
                        C.RPRQ_CARNAMETAIL CNT,
                        C.RPC_SUBJECT_CON SUBJ,
                        C.RPC_DETAIL DET
                        FROM REPAIRREQUEST_NONAPPROVE A
                        FULL JOIN vwREPAIRREQUEST B ON B.RPRQ_CODE = A.RPRQ_NAP_OLD_CODE
                        FULL JOIN vwREPAIRREQUEST C ON C.RPRQ_CODE = A.RPRQ_NAP_NEW_CODE
                        FULL JOIN REPAIRREQUEST D ON D.RPRQ_CODE = A.RPRQ_NAP_OLD_CODE
                        WHERE B.RPRQ_AREA = '$SESSION_AREA' AND NOT B.RPRQ_STATUS = 'D' AND B.RPRQ_WORKTYPE = 'BM' AND A.RPRQ_NAP_STATUS = 'WAIT'";
                    $query_rprq_nap = sqlsrv_query($conn, $sql_rprq_nap);
                    $no=0;
                    while($result_rprq_nap = sqlsrv_fetch_array($query_rprq_nap, SQLSRV_FETCH_ASSOC)){	
                        $no++;
                ?>
                <tr style="cursor:pointer" height="25px" align="center">   
                    <!-- <td align="center"><b>< ?php print $result_rprq_nap['WK']; ?></b></td>   -->
                    <td align="center"><b><?php print $result_rprq_nap['ID1']; ?></b></td>  
                    <td align="center">
                        <?php 
                            if(isset($result_rprq_nap['ID1'])){
                                echo $result_rprq_nap["DATECREATE1"];
                                // echo $result_rprq_nap["DATE1"].' เวลา '.$result_rprq_nap["TIME1"].' น.';
                            }
                        ?>
                    </td>    
                    <td align="left"><?php print $result_rprq_nap['REMARK']; ?></td>                
                    <td align="center"><b><?php print $result_rprq_nap['ID2']; ?></b></td>  
                    <td align="center">
                        <?php 
                            if(isset($result_rprq_nap['ID2'])){
                                echo $result_rprq_nap["DATECREATE2"];
                                // echo $result_rprq_nap["DATE2"].' เวลา '.$result_rprq_nap["TIME2"].' น.';
                            }
                        ?>
                    </td>
                    <td align="center">                                
                        <?php
                            if(isset($result_rprq_nap['STATUS'])){
                                if($result_rprq_nap['STATUS']=='รอตรวจสอบ'){
                                    echo '<strong><span style="color:red">'.$result_rprq_nap['STATUS'].'</span></strong>';
                                }
                                if($result_rprq_nap['STATUS']=='รอคิวซ่อม'){
                                    echo '<strong><span style="color:red">'.$result_rprq_nap['STATUS'].'</span></strong>';
                                }
                                if($result_rprq_nap['STATUS']=='กำลังซ่อม'){
                                    echo '<strong><span style="color:red">'.$result_rprq_nap['STATUS'].'</span></strong>';
                                }
                                if($result_rprq_nap['STATUS']=='รอจ่ายงาน'){
                                    echo '<strong><span style="color:red">'.$result_rprq_nap['STATUS'].'</span></strong>';
                                }
                                if($result_rprq_nap['STATUS']=='ไม่อนุมัติ'){
                                    echo '<strong><span style="color:red">'.$result_rprq_nap['STATUS'].'</span></strong>';
                                }
                            }else{
                                echo '';
                            }
                        ?>
                    </td>   
                    <td align="center"><?php print $result_rprq_nap['RGH']; ?></td>
                    <td align="center"><?php print $result_rprq_nap['CNH']; ?></td>
                    <td align="center"><?php print $result_rprq_nap['RGT']; ?></td>
                    <td align="center"><?php print $result_rprq_nap['CNT']; ?></td>
                    <td align="center"><?php print $result_rprq_nap['SUBJ']; ?></td>
                    <td align="left"><?php print $result_rprq_nap['DET']; ?></td>
                </tr>
                <?php }; ?>
            </tbody>
        </table>      
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="BOTTOM">
    <td class="LEFT">&nbsp;</td>
    <td class="CENTER">&nbsp;		
		<center>
			<input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_checknonapprove.php');">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>