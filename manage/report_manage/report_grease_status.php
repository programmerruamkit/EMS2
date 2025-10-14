<?php
    session_name("EMS"); session_start();
    $path = "../";   	
    require($path.'../include/connect.php');
    $SESSION_AREA = $_SESSION["AD_AREA"];

    // รับค่าค้นหา
    $search_regis = isset($_GET['search_regis']) ? trim($_GET['search_regis']) : '';
    $search_company = isset($_GET['search_company']) ? trim($_GET['search_company']) : '';
    $dateStart = isset($_GET['dateStart']) ? trim($_GET['dateStart']) : '';
    $dateEnd = isset($_GET['dateEnd']) ? trim($_GET['dateEnd']) : '';
    
    // ตัดเอาเฉพาะทะเบียนรถ (ก่อน /)
    if ($search_regis != '') {
        $regis_parts = explode('/', $search_regis);
        $search_regis_trim = trim($regis_parts[0]);
    }

    // autocomplete ทะเบียน/ชื่อรถ
    function autocomplete_regishead($CONDI) {
        global $conn;
        $data = "";
        $sql = "SELECT VEHICLEREGISNUMBER,THAINAME FROM vwVEHICLEINFO WHERE $CONDI";
        $query = sqlsrv_query($conn, $sql);
        while ($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $data .= "'".$result['VEHICLEREGISNUMBER']." / ".$result['THAINAME']."',";
        }
        return rtrim($data, ",");
    }
    $rqrp_regishead = autocomplete_regishead("ACTIVESTATUS = '1' AND VEHICLEGROUPDESC = 'Transport'");

    // แปลงวันที่
    function date_thai_to_sql($d) {
        $ex = explode("/", $d);
        if(count($ex)==3) return $ex[2].'-'.$ex[1].'-'.$ex[0];
        return '';
    }
    $dscon = date_thai_to_sql($dateStart);
    $decon = date_thai_to_sql($dateEnd);
    
	if($dateStart!='' && $dateEnd!=''){
		$getselectdaystart = $dateStart;
		$getselectdayend = $dateEnd;		
		// $dsdate = str_replace('/', '-', $dateStart);
		// $dscon = date('Y-m-d', strtotime($dsdate));
		$start = explode("/", $dateStart);
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

		// $dedate = str_replace('/', '-', $dateEnd);
		// $decon = date('Y-m-d', strtotime($dedate));		
		$end = explode("/", $dateEnd);
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
		$decon = $end[2].'-'.$endm.'-'.$end[0].' 00:00';
	}else{
		// $getselectdaystart = $GETDAYEN;
		// $getselectdayend = $GETDAYEN;
	}
?>
<html>
<head>
    <meta charset="utf-8">
    <script type="text/javascript">
        $(document).ready(function(e) {
            $('.datepic').datetimepicker({
                timepicker:false,
                lang:'th',
                format:'d/m/Y',
                closeOnDateSelect: true
            });
        });
        var source1 = [<?= $rqrp_regishead ?>];
        AutoCompleteNormal("search_regis", source1);

		function date1todate2(){
			document.getElementById('dateEnd').value = document.getElementById('dateStart').value;
		}
        function queryGreaseStatus(){
            var regis = $("#search_regis").val();
            var company = $("#search_company").val();
            var ds = $("#dateStart").val();
            var de = $("#dateEnd").val();
            var getsel = "?search_regis="+encodeURIComponent(regis)+"&search_company="+encodeURIComponent(company)+"&dateStart="+encodeURIComponent(ds)+"&dateEnd="+encodeURIComponent(de);
            loadViewdetail('<?=$path?>manage/report_manage/report_grease_status.php'+getsel);
        }
        function excel_grease_status() {
            var regis = $("#search_regis").val();
            var company = $("#search_company").val();
            var ds = $("#dateStart").val();
            var de = $("#dateEnd").val();
            window.open('../manage/report_manage/report_grease_status_excel.php?search_regis='+encodeURIComponent(regis)+'&search_company='+encodeURIComponent(company)+'&dateStart='+encodeURIComponent(ds)+'&dateEnd='+encodeURIComponent(de),'_blank');
        }
        function pdf_grease_status() {
            var regis = $("#search_regis").val();
            var company = $("#search_company").val();
            var ds = $("#dateStart").val();
            var de = $("#dateEnd").val();
            window.open('report_grease_status_pdf.php?search_regis='+encodeURIComponent(regis)+'&search_company='+encodeURIComponent(company)+'&dateStart='+encodeURIComponent(ds)+'&dateEnd='+encodeURIComponent(de),'_blank');
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
            <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;รายงานสถานะการอัดจารบี (<?=$SESSION_AREA?>)</h3></td>
            <td width="617" align="right" valign="bottom" class="" nowrap>
                <div class="toolbar">
                    <!-- <button class="bg-color-blue" style="padding-top:8px;" title="New" id="button_new"><i class='icon-plus icon-large'></i></button> -->
                    <!-- <button class="bg-color-yellow" style="padding-top:8px;" title="Edit" id="button_edit"><i class='icon-pencil icon-large'></i></button> -->
                    <!-- <button class="bg-color-red" style="padding-top:8px;" title="Del" id="button_delete"><i class="icon-cancel icon-large"></i></button> -->
                </div>
            </td>
            </tr>
        </table></td>
        <td class="RIGHT"></td>
    </tr>
    <tr class="CENTER">
        <td class="LEFT"></td>
        <td class="CENTER" align="center">
            <!-- ฟอร์มค้นหา -->
            <form method="get" action="" onsubmit="queryGreaseStatus();return false;">
                <table>
                    <tbody>
                        <tr align="center">
                            <td width="1%" align="right">&nbsp;</td>    
                            <td width="15%" align="left">
                                <div class="row input-control">ทะเบียนรถ/ชื่อรถ
                                    <input type="text" name="search_regis" id="search_regis" placeholder="ระบุทะเบียนรถ/ชื่อรถ" value="<?php if(isset($search_regis)){echo $search_regis;}?>" class="time" autocomplete="off">
                                </div>
                            </td>
                            <td width="1%" align="right">&nbsp;</td>    
                            <td width="15%" align="left">                                
                                <div class="input-control select">บริษัท                 
                                    <?php
                                        $wh="AND CTM_COMCODE IN('RKR','RKS','RKL','RCC','RRC','RATC')";
                                        $stmt_selcus = "SELECT * FROM CUSTOMER WHERE NOT CTM_STATUS IN ('D','N') AND CTM_GROUP='cusin' $wh ORDER BY CTM_COMCODE ASC";
                                        $query_selcus = sqlsrv_query($conn, $stmt_selcus);
                                    ?>
                                    <select class="time" onFocus="$(this).select();" style="width: 100%;" name="search_company" id="search_company" required>
                                        <option value disabled selected>-------เลือกบริษัท-------</option>
                                        <option value="" <?php if($search_company==''){echo "selected";} ?>>-- แสดงทั้งหมด --</option>
                                        <?php while($result_selcus = sqlsrv_fetch_array($query_selcus)): 
                                            $CTM_COMCODE=$result_selcus['CTM_COMCODE'];
                                            $CTM_GROUP1=$result_selcus["CTM_GROUP"];
                                            
                                            $sql_count = "SELECT COUNT(VEHICLEINFOID) COUNTCTMID FROM vwVEHICLEINFO WHERE AFFCOMPANY = ? AND NOT VEHICLEGROUPDESC = 'Car Pool' AND ACTIVESTATUS = '1'";
                                            $params_count = array($CTM_COMCODE);	
                                            $query_count = sqlsrv_query( $conn, $sql_count, $params_count);	
                                            $result_count = sqlsrv_fetch_array($query_count, SQLSRV_FETCH_ASSOC);
                                            $COUNTCTMID=$result_count["COUNTCTMID"];
                                            if($COUNTCTMID>0){                                                                           
                                        ?>
                                            <option value="<?=$result_selcus['CTM_COMCODE']?>" <?php if($search_company==$result_selcus['CTM_COMCODE']){echo "selected";} ?>><?=$result_selcus['CTM_COMCODE']?> - <?=$result_selcus['CTM_NAMETH']?></option>
                                        <?php  } endwhile; ?>
                                    </select>
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
                            <td width="10%" align="left"><br>
                                <button class="bg-color-blue" onclick="queryGreaseStatus();return false;"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
                            </td>
                            <td width="1%" align="right">&nbsp;</td>                          
                            <td width="10%" align="left"><br>
                                <button title="Excel" type="button" class="bg-color-green" onclick="excel_grease_status()"><font color="white" size="2"><i class="icon-file-excel icon-large"></i> พิม Excel</font></button>
                            </td>
                            <!-- <td width="1%" align="right">&nbsp;</td>                          
                            <td width="10%" align="left"><br>
                                <button title="PDF" type="button" class="bg-color-red" onclick="pdf_grease_status()"><font color="white" size="2"><i class="icon-file-pdf icon-large"></i> พิม PDF</font></button>
                            </td> -->
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table> 
            </form>
            <!-- ตารางรายงานสถานะการอัดจารบี -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
                <thead>
                    <tr style="background-color: #e8f4f8;">
                        <th width="5%">ทะเบียน</th>
                        <th width="5%">ชื่อรถ</th>
                        <th width="5%">รอบล่าสุด</th>
                        <th width="5%">เลขไมล์ที่เปลี่ยนล่าสุด</th>
                        <th width="5%">วันที่เปลี่ยนล่าสุด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT
                            RPG_ID,
                            RPG_CODE,
                            RPG_GROUP,
                            RPG_VHCRG,
                            RPG_VHCRGNM,
                            V.AFFCOMPANY,
                            RPG_LCD,
                            RPG_CREATEBY,
                            RPG_CREATEDATE,
                            RPG_EDITBY,
                            RPG_EDITDATE,
                            RPG_REMARK,
                            RPG_COUNT,
                            RPG_LASTMILEAGE
                        FROM REPAIR_GREASE
                        LEFT JOIN vwVEHICLEINFO V ON V.VEHICLEREGISNUMBER = RPG_VHCRG COLLATE THAI_CI_AI
                        WHERE 1=1
                        ";
                        
                        // สามารถเพิ่มเงื่อนไขค้นหาได้ เช่น
                        if($search_regis != '') {
                            $sql .= " AND RPG_VHCRG LIKE '%".addslashes($search_regis_trim)."%'";
                        }
                        if($search_company != '') {
                            $sql .= " AND AFFCOMPANY LIKE '%".addslashes($search_company)."%'";
                        }
                        if($dscon && $decon) {
                            $sql .= " AND (RPG_LCD BETWEEN '$dscon 00:00:00' AND '$decon 23:59:59')";
                        }
                        
                        $sql .= " ORDER BY RPG_ID ASC";
                        
                        // ดึงข้อมูล
                        $query = sqlsrv_query($conn, $sql);
                        while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                            echo "<tr>
                                <td align='center'>{$row['RPG_VHCRG']}</td>
                                <td align='center'>{$row['RPG_VHCRGNM']}</td>
                                <td align='center'>{$row['RPG_COUNT']}/{$row['RPG_GROUP']}</td>
                                <td align='center'>".number_format($row['RPG_LASTMILEAGE'])."</td>
                                <td align='center'>".($row['RPG_LCD'] ? date('d/m/Y', strtotime($row['RPG_LCD'])) : '')."</td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </td>
        <td class="RIGHT"></td>
    </tr>
    <tr class="BOTTOM">
        <td class="LEFT">&nbsp;</td>
        <td class="CENTER">&nbsp;		
            <center>
                <input type="button" class="button_gray" value="อัพเดท" onclick="javascript:loadViewdetail('<?=$path?>manage/report_manage/report_grease_status.php');">
            </center>
        </td>
        <td class="RIGHT">&nbsp;</td>
    </tr>
</table>
</body>
</html>