<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');
    $SESSION_AREA = $_SESSION["AD_AREA"];
    if($SESSION_AREA=="AMT"){
        $HREF_EXCEL="../manage/report_manage/report_outergarage_history_amt_excel.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&pr='+problem+'&ca='+cause+'&cu='+cus+'&area=$SESSION_AREA";
        $HREF_PDF="../manage/report_manage/report_outergarage_history_amt_pdf.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&pr='+problem+'&ca='+cause+'&cu='+cus+'&area=$SESSION_AREA";
    }else{
        $HREF_EXCEL="../manage/report_manage/report_outergarage_history_gw_excel.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&pr='+problem+'&ca='+cause+'&cu='+cus+'&area=$SESSION_AREA";
        $HREF_PDF="../manage/report_manage/report_outergarage_history_gw_pdf.php?ds='+datestartdetail+'&de='+dateenddetail+'&rg='+vhcrg+'&pr='+problem+'&ca='+cause+'&cu='+cus+'&area=$SESSION_AREA";
    }

    $ds = $_GET['ds'];
    $de = $_GET['de'];
    $rg = $_GET['rg'];
    $pr = $_GET['pr']; // ปัญหา
    $ca = $_GET['ca']; // สาเหตุ
    $cu = $_GET['cu']; // อู่ซ่อม
    $dt = $_GET['dt']; // วิธีซ่อม
    
    if($ds!='' && $de!=''){
        $getselectdaystart = $ds;
        $getselectdayend = $de;		
        $start = explode("/", $ds);
        $startd = $start[0];
        $startif = $start[1];
            if($startif == "01"){
                $startm = "01";
            }else if($startif == "02"){
                $startm = "02";
            }else if($startif == "03"){
                $startm = "03";
            }else if($startif == "04"){
                $startm = "04";
            }else if($startif == "05"){
                $startm = "05";
            }else if($startif == "06"){
                $startm = "06";
            }else if($startif == "07"){
                $startm = "07";
            }else if($startif== "08"){
                $startm = "08";
            }else if($startif == "09"){
                $startm = "09";
            }else if($startif == "10"){
                $startm = "10";
            }else if($startif == "11"){
                $startm = "11";
            }else if($startif == "12"){
                $startm = "12";
            }
        $dscon = $start[2].'-'.$startm.'-'.$start[0].' 00:00';

        $end = explode("/", $de);
        $endd = $end[0];
        $endif = $end[1];
            if($endif == "01"){
                $endm = "01";
            }else if($endif == "02"){
                $endm = "02";
            }else if($endif == "03"){
                $endm = "03";
            }else if($endif == "04"){
                $endm = "04";
            }else if($endif == "05"){
                $endm = "05";
            }else if($endif == "06"){
                $endm = "06";
            }else if($endif == "07"){
                $endm = "07";
            }else if($endif== "08"){
                $endm = "08";
            }else if($endif == "09"){
                $endm = "09";
            }else if($endif == "10"){
                $endm = "10";
            }else if($endif == "11"){
                $endm = "11";
            }else if($endif == "12"){
                $endm = "12";
            }
        $decon = $end[2].'-'.$endm.'-'.$end[0].' 23:59';
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
<html>
<head>
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
        function excel_reporthdms() {
            var datestartdetail = document.getElementById('dateStart').value;
            var dateenddetail   = document.getElementById('dateEnd').value;
            var vhcrg   = document.getElementById('VHCRGNM').value;
            var problem = document.getElementById('problem').value;
            var cause   = document.getElementById('cause').value;
            var cus     = document.getElementById('garage').value;
            window.open('<?=$HREF_EXCEL?>','_blank');
        }
        function pdf_reporthdms() {
            var datestartdetail = document.getElementById('dateStart').value;
            var dateenddetail   = document.getElementById('dateEnd').value;
            var vhcrg   = document.getElementById('VHCRGNM').value;
            var problem = document.getElementById('problem').value;
            var cause   = document.getElementById('cause').value;
            var cus     = document.getElementById('garage').value;
            window.open('<?=$HREF_PDF?>','_blank');
        }
        
        var source1 = [<?= $rqrp_regishead ?>];
        AutoCompleteNormal("VHCRGNM", source1); 
    
        function queryreporthdms(){
            var ds = $("#dateStart").val();
            var de = $("#dateEnd").val();
            var rg = $("#VHCRGNM").val();	
            var pr = $("#problem").val();	
            var ca = $("#cause").val();
            var cu = $("#garage").val();		
            
            var getsel = "?ds="+ds+"&de="+de+"&rg="+rg+"&pr="+pr+"&ca="+ca+"&cu="+cu;
            loadViewdetail('<?=$path?>manage/report_manage/report_outergarage_history.php'+getsel);
        } 
    </script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25" valign="middle" class=""><img src="https://img2.pic.in.th/pic/reports-icon48.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;ประวัติรถซ่อมอู่นอก</h3></td>
        <td width="617" align="right" valign="bottom" class="" nowrap>
            <div class="toolbar">
            </div>
        </td>
        </tr>
    </table></td>
    <td class="RIGHT"></td>
  </tr>
  <tr class="CENTER">
    <td class="LEFT"></td>
    <td class="CENTER" align="center">
        <table>
            <tbody>
                <tr align="center">
                    <td colspan="11" align="right">&nbsp;</td>  
                    <td align="center">
                        <div class="row input-control"><br>
                            <button title="Excel" type="button" class="bg-color-green" onclick="excel_reporthdms()"><font color="white" size="2"><i class="icon-file-excel icon-large"></i> พิม Excel</font></button>
                        </div>
                    </td>
                    <td colspan="1" align="right">&nbsp;</td> 
                    <td align="center">
                        <div class="row input-control"><br>
                            <button title="PDF" type="button" class="bg-color-red" onclick="pdf_reporthdms()"><font color="white" size="2"><i class="icon-file-pdf icon-large"></i> พิม PDF</font></button>
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
                        <div class="row input-control">ปัญหา
                            <input type="text" name="problem" id="problem" placeholder="ระบุปัญหา" value="<?php if(isset($pr)){echo $pr;}?>" class="time" autocomplete="off">
                        </div>
                    </td>
                    <td width="1%" align="right">&nbsp;</td>    
                    <td width="15%" align="left">
                        <div class="row input-control">สาเหตุ
                            <input type="text" name="cause" id="cause" placeholder="ระบุสาเหตุ" value="<?php if(isset($ca)){echo $ca;}?>" class="time" autocomplete="off">
                        </div>
                    </td>
                    <td width="1%" align="right">&nbsp;</td>    
                    <td width="15%" align="left">
                        <div class="row input-control">อู่ซ่อม
                            <input type="text" name="garage" id="garage" placeholder="ระบุอู่ซ่อม" value="<?php if(isset($cu)){echo $cu;}?>" class="time" autocomplete="off">
                        </div>
                    </td>
                <!-- </tr> -->
                <!-- <tr align="center"> -->
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
                        <button class="bg-color-blue" onclick="queryreporthdms()"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
                    </td>
                    <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table> 
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
            <thead>
                <tr height="30">
                    <th rowspan="2" align="center" width="5%" class="ui-state-default">ลำดับ</th>
                    <th rowspan="2" align="center" width="10%" class="ui-state-default">ทะเบียนรถ</th>
                    <th rowspan="2" align="center" width="8%" class="ui-state-default">สายงาน</th>
                    <th rowspan="2" align="center" width="10%" class="ui-state-default">วันที่ซ่อม</th>
                    <th rowspan="2" align="center" width="10%" class="ui-state-default">วันที่เสร็จ</th>
                    <th colspan="3" align="center" width="40%" class="ui-state-default">รายละเอียดงานซ่อม</th>
                    <th rowspan="2" align="center" width="8%" class="ui-state-default">อู่ซ่อม</th>
                    <th rowspan="2" align="center" width="8%" class="ui-state-default">ราคาซ่อม</th>
                    <th rowspan="2" align="center" width="15%" class="ui-state-default">หมายเหตุ</th>
                </tr>
                <tr height="30">
                    <th align="center">ปัญหา</th>
                    <th align="center">สาเหตุ</th>
                    <th align="center">วิธีซ่อม</th>
                </tr>
            </thead>
            <tbody>
                <?php
					// เพิ่มฟังก์ชันแปลงวันที่ที่บนสุดของไฟล์
					function formatDate($date) {
						if($date && $date != '') {
							if(is_object($date)) {
								return $date->format('d/m/Y');
							} else {
								$dateObj = new DateTime($date);
								return $dateObj->format('d/m/Y');
							}
						}
						return '';
					}
                    if($rgsub!=''){
                        $rsrg="Truck LIKE '%$rgsub%' AND ";
                    }else{$rsrg="";} 
                    if($dscon!='' && $decon!=''){
                        $rsse="RepairDate BETWEEN '$dscon' AND '$decon' AND ";
                    }else{$rsse="";}
                    if($pr!=''){
                        $rspr="Problems LIKE '%$pr%' AND ";
                    }else{$rspr="";}
                    if($ca!=''){
                        $rsca="Cause LIKE '%$ca%' AND ";
                    }else{$rsca="";}
                    if($cu!=''){
                        $rscu="Garage LIKE '%$cu%' AND ";
                    }else{$rscu="";}
                    $wh=$rsrg.$rsse.$rspr.$rsca.$rscu;
                    if($wh==''){
                        $rswh="RepairDate BETWEEN '1900-01-01 00:00:00' AND '1900-01-01 00:00:00' AND ";
                    }else{
                        $rswh=$wh;
                    }

                    $sql_reporthdms = "SELECT * FROM IMPORT_OUTER_GARAGE WHERE ".$rswh." 1=1 ORDER BY RepairDate ASC";
                    $query_reporthdms = sqlsrv_query($conn, $sql_reporthdms);
                    $no=0;
					// echo $sql_reporthdms;
                    while($result_reporthdms = sqlsrv_fetch_array($query_reporthdms, SQLSRV_FETCH_ASSOC)){	
                        $no++;
                        $temp_id = $result_reporthdms['ID'];
                        $temp_truck = $result_reporthdms['Truck'];        
                        $temp_section = $result_reporthdms['Section'];        
                        $temp_problems = $result_reporthdms['Problems'];             
                        $temp_cause = $result_reporthdms['Cause'];
                        $temp_repairmethod = $result_reporthdms['RepairMethod'];
                        $temp_repairdate = $result_reporthdms['RepairDate'];      
                        $temp_completedate = $result_reporthdms['CompleteDate'];
                        $temp_garage = $result_reporthdms['Garage'];     
                        $temp_repairprice = $result_reporthdms['RepairPrice'];               
                        $temp_remark = $result_reporthdms['Remark'];            
                        $temp_createby = $result_reporthdms['CREATEBY'];               
                        $temp_createdate = $result_reporthdms['CREATEDATE'];           
                ?>
                <tr height="25px">
                    <td align="center"><?php print "$no";?></td>
                    <td align="center"><?php print "$temp_truck";?></td>
                    <td align="center"><?php print "$temp_section";?></td>
					<td align="center"><?php echo formatDate($temp_repairdate); ?></td>
					<td align="center"><?php echo formatDate($temp_completedate); ?></td>
                    <td align="left"><?php print "$temp_problems";?></td>
                    <td align="left"><?php print "$temp_cause";?></td>
                    <td align="left"><?php print "$temp_repairmethod";?></td>
                    <td align="center"><?php print "$temp_garage";?></td>
                    <td align="right"><?php if($temp_repairprice > 0) echo number_format($temp_repairprice, 2); ?></td>
                    <td align="left"><?php print "$temp_remark";?></td>
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
            <input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_outergarage_history.php');">
        </center>
    </td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>