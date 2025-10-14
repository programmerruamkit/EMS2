<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');
    
    $SESSION_AREA = $_SESSION["AD_AREA"];
    if($SESSION_AREA=="AMT"){
        $HREF_EXCEL="../manage/report_manage/report_repairhistory_l1_amt_excel.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&co='+com+'&cu='+cus+'&dt='+detail+'&area=$SESSION_AREA";
        $HREF_PDF="../manage/report_manage/report_repairhistory_l1_amt_pdf.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&co='+com+'&cu='+cus+'&dt='+detail+'&area=$SESSION_AREA";
    }else{
        $HREF_EXCEL="../manage/report_manage/report_repairhistory_l1_gw_excel.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&co='+com+'&cu='+cus+'&dt='+detail+'&area=$SESSION_AREA";
        $HREF_PDF="../manage/report_manage/report_repairhistory_l1_gw_pdf.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&co='+com+'&cu='+cus+'&dt='+detail+'&area=$SESSION_AREA";
    }

    $ds = $_GET['ds'];
    $de = $_GET['de'];
    $rg = $_GET['rg'];
    $co = $_GET['co'];
    $cu = $_GET['cu'];
    $dt = $_GET['dt'];
    
    // ปรับส่วนการแปลงวันที่ให้เข้าใจง่ายขึ้น
    if($ds!='' && $de!=''){
        $getselectdaystart = $ds;
        $getselectdayend = $de;		
        
        // แปลงวันที่จาก dd/mm/yyyy เป็น yyyy-mm-dd
        $start = explode("/", $ds);
        $dscon = $start[2].'-'.str_pad($start[1], 2, '0', STR_PAD_LEFT).'-'.str_pad($start[0], 2, '0', STR_PAD_LEFT).' 00:00';
        
        $end = explode("/", $de);
        $decon = $end[2].'-'.str_pad($end[1], 2, '0', STR_PAD_LEFT).'-'.str_pad($end[0], 2, '0', STR_PAD_LEFT).' 00:00';
    }else{
        $getselectdaystart = '';
        $getselectdayend = '';
    }
    
    if(isset($rg)){
        $rgsub = substr($rg,0,7);
    }else{
        $rgsub = '';
    }

    function autocomplete_regishead($CONDI) {
        $path = "../";   	
        require($path.'../include/connect.php');
        $data = "";
        $sql = "SELECT VEHICLEREGISNUMBER,THAINAME FROM vwVEHICLEINFO  WHERE $CONDI
        UNION	
        SELECT VEHICLEREGISNUMBER COLLATE Thai_CI_AI,THAINAME COLLATE Thai_CI_AI FROM vwVEHICLEINFO_OUT WHERE $CONDI";
        $query = sqlsrv_query($conn, $sql);
        while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $data .= "'".$result['VEHICLEREGISNUMBER']." / ".$result['THAINAME']."',";
        }
        return rtrim($data, ",");
    }
    $rqrp_regishead = autocomplete_regishead("ACTIVESTATUS = '1' AND VEHICLEGROUPDESC = 'Transport'");
?>

<script type="text/javascript">
    $(document).ready(function(e) {
        $('.datepic').datetimepicker({
            timepicker:false,
            lang:'th',
            format:'d/m/Y',
            closeOnDateSelect: true
        });
    });
    
    function date1todate2(){
        document.getElementById('dateEnd').value = document.getElementById('dateStart').value;
    }
    
    function excel_reportl1() {
        var datestartdetail = document.getElementById('dateStart').value;
        var dateenddetail   = document.getElementById('dateEnd').value;
        var vhcrg   = document.getElementById('VHCRGNM').value;
        var com   = document.getElementById('company').value;
        var cus   = document.getElementById('customer').value;
        var detail   = document.getElementById('detailrepair').value;
        window.open('<?=$HREF_EXCEL?>','_blank');
    }
    
    function pdf_reportl1() {
        var datestartdetail = document.getElementById('dateStart').value;
        var dateenddetail   = document.getElementById('dateEnd').value;
        var vhcrg   = document.getElementById('VHCRGNM').value;
        var com   = document.getElementById('company').value;
        var cus   = document.getElementById('customer').value;
        var detail   = document.getElementById('detailrepair').value;
        window.open('<?=$HREF_PDF?>','_blank');
    }
    
    function queryreportl1(){
        var ds = $("#dateStart").val();
        var de = $("#dateEnd").val();
        var rg = $("#VHCRGNM").val();	
        var co = $("#company").val();	
        var cu = $("#customer").val();		
        var dt = $("#detailrepair").val();					
        
        var getsel = "?ds="+ds+"&de="+de+"&rg="+rg+"&co="+co+"&cu="+cu+"&dt="+dt;
        loadViewdetail('<?=$path?>manage/report_manage/report_repairhistory_l1.php'+getsel);
    }
    
    var source1 = [<?= $rqrp_regishead ?>];
    AutoCompleteNormal("VHCRGNM", source1); 
</script>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="25" valign="middle"><img src="https://img2.pic.in.th/pic/reports-icon48.png" width="48" height="48"></td>
                <td width="419" height="10%" valign="bottom"><h3>&nbsp;&nbsp;ประวัติการซ่อมบำรุง (L1)</h3></td>
                <td width="617" align="right" valign="bottom" nowrap>
                    <div class="toolbar"></div>
                </td>
            </tr>
        </table>
    </td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">
        <table>
            <tbody>
                <tr align="center">
                    <!-- <td colspan="11" align="right">&nbsp;</td>   -->
                    <td colspan="8" align="left">
                        <font color="red">** สามารถค้นหาข้อมูลได้ตั้งแต่ปี 2008 - เดือนตุลาคม 2020 หลังจากนี้สามารถค้นหาประวัติการซ่อมได้ที่เมนูประวัติการซ่อมบำรุง (HDMS) **</font>
                    </td>  
                    <td width="1%" align="right">&nbsp;</td>  
                    <td colspan="2" align="center">
                        <div class="row input-control"><br>
                            <button title="Excel" type="button" class="bg-color-green" onclick="excel_reportl1()">
                                <font color="white" size="2"><i class="icon-file-excel icon-large"></i> พิม Excel</font>
                            </button>
                        </div>
                    </td>
                    <!-- <td colspan="1" align="right">&nbsp;</td>  -->
                    <td colspan="2" align="center">
                        <div class="row input-control"><br>
                            <button title="PDF" type="button" class="bg-color-red" onclick="pdf_reportl1()">
                                <font color="white" size="2"><i class="icon-file-pdf icon-large"></i> พิม PDF</font>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr align="center">
                    <td width="1%" align="right">&nbsp;</td>    
                    <td width="15%" align="left">
                        <div class="row input-control">ทะเบียนรถ/ชื่อรถ
                            <input type="text" name="VHCRGNM" id="VHCRGNM" placeholder="ระบุทะเบียนรถ/ชื่อรถ" value="<?php if(isset($rg)){echo $rg;}?>" class="time" autocomplete="off">
                        </div>
                    </td>
                    <td width="1%" align="right">&nbsp;</td>    
                    <td width="15%" align="left">
                        <div class="row input-control">บริษัท
                            <input type="text" name="company" id="company" placeholder="ระบุบริษัท" value="<?php if(isset($co)){echo $co;}?>" class="time" autocomplete="off">
                        </div>
                    </td>
                    <input type="hidden" name="customer" id="customer" placeholder="ระบุลูกค้า" value="<?php if(isset($cu)){echo $cu;}?>" class="time" autocomplete="off">
                    <td width="1%" align="right">&nbsp;</td>    
                    <td width="15%" align="left">
                        <div class="row input-control">รายละเอียดงานซ่อม
                            <input type="text" name="detailrepair" id="detailrepair" placeholder="ระบุรายละเอียดงานซ่อม" value="<?php if(isset($dt)){echo $dt;}?>" class="time" autocomplete="off">
                        </div>
                    </td>
                    <td width="1%" align="right">&nbsp;</td>    
                    <td width="15%" align="left">
                        <div class="row input-control">วันที่เริ่มต้น
                            <input type="text" name="dateStart" id="dateStart" class="datepic time" placeholder="วันที่เริ่มต้น" autocomplete="off" value="<?=$getselectdaystart;?>" onchange="date1todate2()">
                        </div>
                    </td>
                    <td width="1%" align="right">&nbsp;</td>                          
                    <td width="15%" align="left">
                        <div class="row input-control">วันที่สิ้นสุด
                            <input type="text" name="dateEnd" id="dateEnd" class="datepic time" placeholder="วันที่สิ้นสุด" autocomplete="off" value="<?=$getselectdayend;?>">
                        </div>
                    </td>
                    <td width="1%" align="right">&nbsp;</td>                          
                    <td width="15%" align="left"><br>
                        <button class="bg-color-blue" onclick="queryreportl1()">
                            <font color="white"><i class="icon-search"></i> ค้นหา</font>
                        </button>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
            </tbody>
        </table> 
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
            <thead>
                <tr height="30">
                    <th rowspan="2" align="center" width="3%" class="ui-state-default">ลำดับ</th>
                    <th rowspan="2" align="center" width="8%" class="ui-state-default">บริษัท</th>
                    <th rowspan="2" align="center" width="7%" class="ui-state-default">วันที่</th>
                    <th rowspan="2" align="center" width="4%" class="ui-state-default">ปี</th>
                    <th rowspan="2" align="center" width="3%" class="ui-state-default">เดือน</th>
                    <th rowspan="2" align="center" width="6%" class="ui-state-default">ประเภท</th>
                    <th colspan="3" align="center" width="24%" class="ui-state-default">ข้อมูลรถ</th>
                    <th rowspan="2" align="center" width="6%" class="ui-state-default">ลูกค้า</th>
                    <th rowspan="2" align="center" width="6%" class="ui-state-default">เลขข้างรถ</th>
                    <th colspan="3" align="center" width="18%" class="ui-state-default">ค่าใช้จ่าย</th>
                    <th rowspan="2" align="center" width="8%" class="ui-state-default">เลข JOB</th>
                    <th rowspan="2" align="center" width="15%" class="ui-state-default">รายละเอียด</th>
                </tr>
                <tr height="30">
                    <th align="center">ทะเบียนรถ</th>
                    <th align="center">รุ่นรถ</th>
                    <th align="center">เลขไมล์</th>
                    <th align="center">ค่าแรง</th>
                    <th align="center">ค่าอะไหล่</th>
                    <th align="center">รวม</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // ปรับเงื่อนไขการค้นหาให้เหมาะกับตาราง IMPORT_L1
                    if($rgsub!=''){
                        $rsrg="TRUCKNO LIKE '%$rgsub%' AND ";
                    }else{$rsrg="";} 
                    
                    if($dscon!='' && $decon!=''){
                        $dscon_parts = explode('-', str_replace(' 00:00', '', $dscon));
                        if(count($dscon_parts) == 3){
                            $dscon_converted = $dscon_parts[2] . '/' . $dscon_parts[1] . '/' . $dscon_parts[0]; // dd/mm/yyyy
                        } else {
                            $dscon_converted = $dscon;
                        }
                        
                        $decon_parts = explode('-', str_replace(' 00:00', '', $decon));
                        if(count($decon_parts) == 3){
                            $decon_converted = $decon_parts[2] . '/' . $decon_parts[1] . '/' . $decon_parts[0]; // dd/mm/yyyy
                        } else {
                            $decon_converted = $decon;
                        }
                        
                        $rsse="CONVERT(datetime, DATE, 103) BETWEEN CONVERT(datetime, '$dscon_converted', 103) AND CONVERT(datetime, '$decon_converted', 103) AND ";
                    }else{$rsse="";}
                    
                    if($co!=''){
                        $rsco="COMPANY LIKE '%$co%' AND ";
                    }else{$rsco="";}
                    if($cu!=''){
                        $rscu="CUSTOMER LIKE '%$cu%' AND ";
                    }else{$rscu="";}
                    if($dt!=''){
                        $rsdt="REPDESC LIKE '%$dt%' AND";
                    }else{$rsdt="";}
                    $wh=$rsrg.$rsse.$rsco.$rscu.$rsdt;
                    if($wh==''){
                        $rswh="CONVERT(datetime, DATE, 103) BETWEEN CONVERT(datetime, '01/01/1900', 103) AND CONVERT(datetime, '01/01/1900', 103) ";
                    }else{
                        $rswh=$wh;
                    }

                    $sql_reportl1 = "SELECT * FROM IMPORT_L1 WHERE ".$rswh." 1=1 ORDER BY CONVERT(datetime, DATE, 103) ASC";
                    $query_reportl1 = sqlsrv_query($conn, $sql_reportl1);
                    $no=0;
					// echo $sql_reportl1."<br>";
                    while($result_reportl1 = sqlsrv_fetch_array($query_reportl1, SQLSRV_FETCH_ASSOC)){	
                        $no++;
                        $temp1 = $result_reportl1['COMPANY'];
                        
                        $dateOriginal = $result_reportl1['DATE'];
                        if(!empty($dateOriginal) && $dateOriginal != ''){
                            $dateParts = explode('/', $dateOriginal);
                            if(count($dateParts) == 3){
                                $day = str_pad($dateParts[0], 2, '0', STR_PAD_LEFT);
                                $month = str_pad($dateParts[1], 2, '0', STR_PAD_LEFT);
                                $year = $dateParts[2];
                                $temp2 = $year . '-' . $month . '-' . $day;
                            } else {
                                $temp2 = $dateOriginal;
                            }
                        } else {
                            $temp2 = '';
                        }
                        
                        $temp3 = $result_reportl1['YYYY'];
                        $temp4 = $result_reportl1['MM'];
                        $temp5 = $result_reportl1['TYPE'];
                        $temp6 = $result_reportl1['JOBNO'];
                        $temp7 = $result_reportl1['GROUP'];
                        $temp8 = $result_reportl1['MILEAGE'];
                        $temp9 = $result_reportl1['CUSTOMER'];
                        $temp10 = $result_reportl1['TRUCKNO'];
                        $temp11 = $result_reportl1['SERVICEFEE'];
                        $temp12 = $result_reportl1['PARTFEE'];
                        $temp13 = $result_reportl1['TOTAL'];
                        $temp14 = $result_reportl1['RUNNO'];
                        $temp15 = $result_reportl1['REPDESC'];
                ?>
                <tr height="25px">
                    <td align="center"><?php print "$no";?></td>
                    <td align="center"><?php print "$temp1";?></td>
                    <td align="center"><?php print "$temp2";?></td>
                    <td align="center"><?php print "$temp3";?></td>
                    <td align="center"><?php print "$temp4";?></td>
                    <td align="center"><?php print "$temp5";?></td>
                    <td align="center"><?php print "$temp6";?></td>
                    <td align="center"><?php print "$temp7";?></td>
                    <td align="right"><?php print "$temp8";?></td>
                    <td align="center"><?php print "$temp9";?></td>
                    <td align="center"><?php print "$temp10";?></td>
                    <td align="right"><?php print "$temp11";?></td>
                    <td align="right"><?php print "$temp12";?></td>
                    <td align="right"><?php print "$temp13";?></td>
                    <td align="center"><?php print "$temp14";?></td>
                    <td align="left"><?php print "$temp15";?></td>
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
            <input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_repairhistory_l1.php');">
        </center>
    </td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>