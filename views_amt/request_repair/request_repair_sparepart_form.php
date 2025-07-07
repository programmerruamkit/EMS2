<?php
	session_name("EMS"); session_start();
	$path = "../";   	
	require($path.'../include/connect.php');
    
    $SESSION_AREA = $_SESSION["AD_AREA"];
    // echo "<pre>";
    // print_r($_GET);
    // print_r($CTM_GROUP);
    // echo "</pre>";

    $stmt_selsparename = "SELECT DISTINCT 
        CASE 
            WHEN a.RPSPP_NAME LIKE '%เปลี่ยนบู๊ตแหนบหน้า%' THEN 'เปลี่ยนบู๊ตแหนบหน้า'
            ELSE a.RPSPP_NAME 
        END RPSPP_NAME,a.RPSPP_NAME_GROUP  
        FROM [dbo].[REPAIR_SPAREPART] a WHERE a.RPSPP_AREA = '$SESSION_AREA' ORDER BY a.RPSPP_NAME_GROUP ASC";
    $query_selsparename = sqlsrv_query($conn, $stmt_selsparename);
    
    // $wh="AND a.RPSPP_NAME_GROUP = '".$_GET['rpsppnamegroup']."'";
    $stmt_selsparename1 = "SELECT DISTINCT 
        CASE 
            WHEN a.RPSPP_NAME LIKE '%เปลี่ยนบู๊ตแหนบหน้า%' THEN 'เปลี่ยนบู๊ตแหนบหน้า'
            ELSE a.RPSPP_NAME 
        END RPSPP_NAME,a.RPSPP_NAME_GROUP  
        FROM [dbo].[REPAIR_SPAREPART] a WHERE a.RPSPP_AREA = '$SESSION_AREA' AND a.RPSPP_NAME_GROUP = '".$_GET['rpsppnamegroup']."' ORDER BY a.RPSPP_NAME_GROUP ASC";
    $query_selsparename1= sqlsrv_query($conn, $stmt_selsparename1);
    $result_selsparename1 = sqlsrv_fetch_array($query_selsparename1, SQLSRV_FETCH_ASSOC);   
    $RPSPP_NAME=$result_selsparename1['RPSPP_NAME']

?>
<html>
<head>
    <script>        
        function selectcustomer(){
            var rpsppname = $("#RPSPP_NAME").val();
            var rpsppnamegroup = $("#RPSPP_NAME_GROUP").val();
            var getrpsppnamegroup = "?rpsppnamegroup="+rpsppnamegroup;
            loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_sparepart_form.php'+getrpsppnamegroup);
        }
        $(document).ready(function(e) {	   
            $("#button_new").click(function(){
                ajaxPopup2("<?=$path?>views_amt/request_repair/request_repair_sparepart_form.php","add","1=1","1350","670","เพิ่มใบแจ้งซ่อม");
            });
        });        
	
        function save_mileagemax(vhcrgnb,rpsppnamegroup,mileage){
            var url = "<?=$path?>views_amt/request_repair/request_repair_pm_proc.php";
            var getrpsppnamegroup = "?rpsppnamegroup="+rpsppnamegroup;
            $.ajax({
                type: 'POST',
                url: url,			
                data: {
                    target: "save_mileagemax",
                    vhcrgnb: vhcrgnb,
                    mileage: mileage
                },
                success: function (rs) {
                    loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_sparepart_form.php'+getrpsppnamegroup);
                    closeUI();
                }
            });
        }
        
        function swaldelete_requestrepair(refcode,no,rpsppnamegroup) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่...ที่จะยกเลิกรายการซ่อมของทะเบียนหมายเลข '+no+' นี้',
                text: "หากยกเลิกแล้ว คุณจะไม่สามารถกู้คืนข้อมูลได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C82333',
                confirmButtonText: 'ใช่! ยกเลิกเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ยกเลิกเรียบร้อยแล้ว',
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {	
                        // if(!confirm("ยืนยันการลบข้อมูลอีกครั้ง")) return false;
                        // var ref = getIdSelect(); 
                        var ref = refcode; 
                        var url = "<?=$path?>views_amt/request_repair/request_repair_pm_proc.php?proc=delete&id="+ref;
                        var getrpsppnamegroup = "?rpsppnamegroup="+rpsppnamegroup;
                        $.ajax({
                            type:'GET',
                            url:url,
                            data:"",				
                            success:function(data){
                                log_transac_requestrepair('LA5', '-', ref);
                                loadViewdetail('<?=$path?>views_amt/request_repair/request_repair_sparepart_form.php'+getrpsppnamegroup);
                                // alert(data);
                            }
                        });
                    })	
                }
            })
        }
    </script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
  <tr class="TOP">
    <td class="LEFT"></td>
    <td class="CENTER"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25" valign="middle" class=""><img src="../images/car_repair.png" width="48" height="48"></td>
        <td width="419" height="10%" valign="bottom" class=""><h3>&nbsp;&nbsp;ข้อมูลบันทึกเปลี่ยนอะไหล่</h3></td>
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
        <br>    
        <table>
            <tbody>
                <tr style="cursor:pointer" height="40px" align="center">                               
                    <td width="20%" align="center">
                        <div class="input-control select">    
                            <select class="time" onFocus="$(this).select();" style="width: 100%;" name="RPSPP_NAME_GROUP" id="RPSPP_NAME_GROUP" required>
                                <option value disabled selected>-------เลือกหมวดหมู่-------</option>
                                <?php while($result_selsparename = sqlsrv_fetch_array($query_selsparename)): ?>
                                    <option value="<?=$result_selsparename['RPSPP_NAME_GROUP']?>" <?php if($_GET['rpsppnamegroup']==$result_selsparename['RPSPP_NAME_GROUP']){echo "selected";} ?>><?=$result_selsparename['RPSPP_NAME']?></option>
                                <?php  endwhile; ?>
                            </select>
                        </div>
                    </td>
                    <td width="10%" align="center">
                        <button class="bg-color-blue" onclick="selectcustomer()"><font color="white"><i class="icon-search"></i> ค้นหา</font></button>
                    </td>
                    <td align="center">
                        <h4>
                            <img src="../images/color_blue.png" width="20" height="20"> = อยู่ระหว่างดำเนินการซ่อม งาน PM
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <img src="../images/color_gray.png" width="20" height="20"> = อยู่ระหว่างดำเนินการซ่อม งาน BM
                        </h4>                        
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>         
        <form id="form1" name="form1" method="post" action="#">
            <h3>ชื่อรายการอะไหล่: <?=$RPSPP_NAME?></h3><br>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="default hover pointer display" id="datatable">
                <thead>
                    <tr height="30">
                        <th rowspan="2" align="center" width="5%">ลำดับ</th>
                        <th rowspan="2" align="center" width="10%">ส่งแผน/จัดการ</th>
                        <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หัว)</th>
                        <th colspan="2" align="center" width="15%" class="ui-state-default">ข้อมูลรถ (หาง)</th>
                        <th rowspan="2" align="center" width="10%">สายงาน</th>
                            <?php if($_GET['rpsppnamegroup']=='RPSPP10'){ ?>
                                <th colspan="1" align="center" width="20%" class="ui-state-default">มาตราฐานกำหนดเปลี่ยน</th>
                            <?php }else{ ?>
                                <th colspan="2" align="center" width="20%" class="ui-state-default">มาตราฐานกำหนดเปลี่ยน</th>
                            <?php } ?>
                        <th rowspan="2" align="center" width="10%">วันที่เปลี่ยนล่าสุด</th>
                            <?php if($_GET['rpsppnamegroup']=='RPSPP10'){ ?>
                                <th rowspan="2" align="center" width="10%">เลขไมล์ที่เปลี่ยนล่าสุด</th>
                            <?php } ?>
                        <th rowspan="2" align="center" width="10%">กำหนดเปลี่ยนครั้งถัดไป</th>
                        <th rowspan="2" align="center" width="10%">เกินระยะ(วัน)</th>
                        <th rowspan="2" align="center" width="10%">เลขไมล์ปัจจุบัน</th>
                            <?php if($_GET['rpsppnamegroup']=='RPSPP09'){ ?>
                                <th rowspan="2" align="center" width="10%">รุ่นแบตเตอรี่</th>
                            <?php } ?>
                    </tr>
                    <tr height="30">
                        <th align="center">ทะเบียน</th>
                        <th align="center">ชื่อรถ</th>  
                        <th align="center">ทะเบียน</th>
                        <th align="center">ชื่อรถ</th>  
                            <?php if($_GET['rpsppnamegroup']=='RPSPP10'){ ?>
                                <th align="center">เลขไมล์</th>  
                            <?php }else{ ?>
                                <th align="center">ปี</th>  
                                <th align="center">เดือน</th>  
                            <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $rpsppnamegroup=$_GET['rpsppnamegroup'];
                        $sql_repairspaerpart = "SELECT * FROM [dbo].[REPAIR_SPAREPART] a WHERE a.RPSPP_AREA = '$SESSION_AREA' AND a.RPSPP_NAME_GROUP = '$rpsppnamegroup' ORDER BY a.RPSPP_ID ASC";
                        $query_repairspaerpart = sqlsrv_query($conn, $sql_repairspaerpart);
                        $no=0;
                        while($result_repairspaerpart = sqlsrv_fetch_array($query_repairspaerpart, SQLSRV_FETCH_ASSOC)){	
                            $no++;
                            $RPSPP_CODE=$result_repairspaerpart['RPSPP_CODE'];

                            $RPSPP_REGISHEAD=$result_repairspaerpart['RPSPP_REGISHEAD'];
                            $RPSPP_REGISTAIL=$result_repairspaerpart['RPSPP_REGISTAIL'];
                            $RPSPP_LINEWORK=$result_repairspaerpart['RPSPP_LINEWORK'];
                            
                                $sql_vehicle_head = "SELECT a.THAINAME,b.VEHICLETYPEDESC,a.AFFCOMPANY FROM TEST_VEHICLEINFO a LEFT JOIN TEST_VEHICLETYPE b ON a.VEHICLETYPECODE = b.VEHICLETYPECODE WHERE a.VEHICLEREGISNUMBER = '$RPSPP_REGISHEAD'";
                                $query_vehicle_head = sqlsrv_query($conn, $sql_vehicle_head);
                                $result_vehicle_head = sqlsrv_fetch_array($query_vehicle_head, SQLSRV_FETCH_ASSOC);  
                                    $THAINAME_HEAD=$result_vehicle_head['THAINAME'];
                                    $VEHICLETYPEDESC_HEAD=$result_vehicle_head['VEHICLETYPEDESC'];
                                    $AFFCOMPANY_HEAD=$result_vehicle_head['AFFCOMPANY'];
                                $sql_vehicle_tail = "SELECT a.THAINAME,b.VEHICLETYPEDESC,a.AFFCOMPANY FROM TEST_VEHICLEINFO a LEFT JOIN TEST_VEHICLETYPE b ON a.VEHICLETYPECODE = b.VEHICLETYPECODE WHERE a.VEHICLEREGISNUMBER = '$RPSPP_REGISTAIL'";
                                $query_vehicle_tail = sqlsrv_query($conn, $sql_vehicle_tail);
                                $result_vehicle_tail = sqlsrv_fetch_array($query_vehicle_tail, SQLSRV_FETCH_ASSOC);   
                                    $THAINAME_TAIL=$result_vehicle_tail['THAINAME'];
                                    $VEHICLETYPEDESC_TAIL=$result_vehicle_tail['VEHICLETYPEDESC'];
                                    $AFFCOMPANY_TAIL=$result_vehicle_head['AFFCOMPANY'];
                                $sql_rprq_check = "SELECT RPRQ_CODE,RPRQ_WORKTYPE,RPRQ_REGISHEAD,RPRQ_REGISTAIL,RPRQ_STATUSREQUEST,RPRQ_REQUESTCARDATE,RPRQ_REQUESTCARTIME
                                FROM REPAIRREQUEST WHERE RPRQ_STATUS = 'Y' AND RPRQ_REGISHEAD = '$RPSPP_REGISHEAD' AND NOT RPRQ_STATUSREQUEST IN('ไม่อนุมัติ','ซ่อมเสร็จสิ้น')";
                                $query_rprq_check = sqlsrv_query($conn, $sql_rprq_check);
                                $result_rprq_check = sqlsrv_fetch_array($query_rprq_check, SQLSRV_FETCH_ASSOC);
                                    $RPRQ_STATUSREQUEST=$result_rprq_check['RPRQ_STATUSREQUEST'];
                                    $RPRQ_WORKTYPE=$result_rprq_check['RPRQ_WORKTYPE'];

                            $field="VEHICLEREGISNUMBER = '$RPSPP_REGISHEAD'";
                    
                            $sql_mileage = "SELECT TOP 1 * FROM TEMP_MILEAGE WHERE $field ORDER BY CREATEDATE DESC ";
                            $params_mileage = array();
                            $query_mileage = sqlsrv_query($conn, $sql_mileage, $params_mileage);
                            $result_mileage = sqlsrv_fetch_array($query_mileage, SQLSRV_FETCH_ASSOC); 

                            // $sql_mileage = "SELECT TOP 1 * FROM vwMILEAGE WHERE VEHICLEREGISNUMBER = '$RPSPP_REGISHEAD' ORDER BY MILEAGEID DESC ";
                            // $params_mileage = array();
                            // $query_mileage = sqlsrv_query($conn, $sql_mileage, $params_mileage);
                            // $result_mileage = sqlsrv_fetch_array($query_mileage, SQLSRV_FETCH_ASSOC);  
                            if(isset($result_mileage['MAXMILEAGENUMBER'])){
                                if($result_mileage['MAXMILEAGENUMBER']>1000000){
                                    $MAXMILEAGENUMBER = $result_mileage['MAXMILEAGENUMBER']-1000000;
                                }else{
                                    $MAXMILEAGENUMBER = $result_mileage['MAXMILEAGENUMBER'];
                                }
                            }else{
                                $MAXMILEAGENUMBER = 0;
                            }
                            $RPSPP_LASTSPARE=$result_repairspaerpart['RPSPP_LASTSPARE'];
                            $convertdate=str_replace('/', '-', $RPSPP_LASTSPARE);
                            $convert_last=date('d/m/Y', strtotime($convertdate));
                            $convert_last_new=date('Y-m-d', strtotime($convertdate));

                            $RPSPP_STDYEAR=$result_repairspaerpart['RPSPP_STDYEAR'];
                            $RPSPP_STDMONTH=$result_repairspaerpart['RPSPP_STDMONTH'];
                            $RPSPP_STDDAY=$result_repairspaerpart['RPSPP_STDDAY'];
                            if(isset($RPSPP_STDYEAR) && isset($RPSPP_STDMONTH) && isset($RPSPP_STDDAY)){
                                $convert_plus = date('d/m/Y', strtotime($convertdate."+$RPSPP_STDYEAR years"."+$RPSPP_STDMONTH month"."+$RPSPP_STDDAY day"));
                                $convert_plus_new = date('Y-m-d', strtotime($convertdate."+$RPSPP_STDYEAR years"."+$RPSPP_STDMONTH month"."+$RPSPP_STDDAY day"));
                            }else if(isset($RPSPP_STDYEAR) && isset($RPSPP_STDMONTH)){
                                $convert_plus = date('d/m/Y', strtotime($convertdate."+$RPSPP_STDYEAR years"."+$RPSPP_STDMONTH month"));
                                $convert_plus_new = date('Y-m-d', strtotime($convertdate."+$RPSPP_STDYEAR years"."+$RPSPP_STDMONTH month"));
                            }else if(isset($RPSPP_STDYEAR)){
                                $convert_plus = date('d/m/Y', strtotime($convertdate."+$RPSPP_STDYEAR years"));
                                $convert_plus_new = date('Y-m-d', strtotime($convertdate."+$RPSPP_STDYEAR years"));
                            }else if(isset($RPSPP_STDMONTH)){
                                $convert_plus = date('d/m/Y', strtotime($convertdate."+$RPSPP_STDMONTH month"));
                                $convert_plus_new = date('Y-m-d', strtotime($convertdate."+$RPSPP_STDMONTH month"));
                            }                            
                            $calculate=strtotime("$convert_plus_new")-strtotime(date('Y-m-d'));
                            $summary=floor($calculate / 86400);
                            
                            $pos = strrpos($summary, "-");
                            if ($pos === false) { 
                                $color=' ';
                            }else{
                                $color=' bgcolor="Red"';
                            }
                            
                            if(($RPRQ_STATUSREQUEST=='รอตรวจสอบ')&&($RPRQ_WORKTYPE=='PM')){
                                $class_tr='class="info"';
                                $font='white';
                            }else if(($RPRQ_STATUSREQUEST=='รอจ่ายงาน')&&($RPRQ_WORKTYPE=='PM')){
                                $class_tr='class="info"';
                                $font='black';
                            }else if(($RPRQ_STATUSREQUEST=='รอคิวซ่อม')&&($RPRQ_WORKTYPE=='PM')){
                                $class_tr='class="info"';
                                $font='black';
                            }else if(($RPRQ_STATUSREQUEST=='กำลังซ่อม')&&($RPRQ_WORKTYPE=='PM')){
                                $class_tr='class="info"';
                                $font='black';
                            }else if($RPRQ_WORKTYPE=='BM'){
                                $class_tr='class="second"';
                                $font='black';
                            }else{
                                $class_tr='';
                                $font='black';
                            }
                    ?>
                    
                    <tr height="25px" align="center" <?=$class_tr?>>
                        <td><?php print "$no.";?></td>
                        <td align="center">
                            <?php if(($RPRQ_STATUSREQUEST=='รอตรวจสอบ')&&($RPRQ_WORKTYPE=='PM')){ ?>
                                <a href="javascript:void(0);" onclick="swaldelete_requestrepair('<?php print $RPRQ_CODE; ?>','<?php print $result_vehicleinfo['VEHICLEREGISNUMBER'];?>','<?php print $_GET['ctmcomcode'];?>')"><img src="../images/delete-icon24.png" width="24" height="24"></a>    
                            <?php }else if(($RPRQ_STATUSREQUEST=='รอจ่ายงาน')&&($RPRQ_WORKTYPE=='PM')){ ?>
                                <a href="javascript:void(0);" onclick="swaldelete_requestrepair('<?php print $RPRQ_CODE; ?>','<?php print $result_vehicleinfo['VEHICLEREGISNUMBER'];?>','<?php print $_GET['ctmcomcode'];?>')"><img src="../images/delete-icon24.png" width="24" height="24"></a>    
                            <?php }else if(($RPRQ_STATUSREQUEST=='รอคิวซ่อม')&&($RPRQ_WORKTYPE=='PM')){ ?>
                                <a href="#"></a>
                            <?php }else if(($RPRQ_STATUSREQUEST=='กำลังซ่อม')&&($RPRQ_WORKTYPE=='PM')){ ?>
                                <a href="#"></a>
                            <?php }else if($RPRQ_WORKTYPE=='BM'){ ?>
                                <a href="#"></a>
                            <?php }else{ ?>
                                <a href="javascript:void(0);" onClick="javascript:ajaxPopup4('<?=$path?>views_amt/request_repair/request_repair_sparepart_sent.php','add','spp&RPSPP_REGISHEAD=<?=$RPSPP_REGISHEAD;?>&THAINAME_HEAD=<?=$THAINAME_HEAD;?>&RPSPP_REGISTAIL=<?=$RPSPP_REGISTAIL;?>&THAINAME_TAIL=<?=$THAINAME_TAIL;?>&VEHICLETYPEDESC_HEAD=<?=$VEHICLETYPEDESC_HEAD;?>&RPSPP_LINEWORK=<?=$RPSPP_LINEWORK;?>&AFFCOMPANY_HEAD=<?=$AFFCOMPANY_HEAD;?>&MAXMILEAGENUMBER=<?=$MAXMILEAGENUMBER;?>&RPSPP_NAME=<?=$RPSPP_NAME;?>&RPSPP_CODE=<?=$RPSPP_CODE;?>','1=1','1350','710','เพิ่มใบแจ้งซ่อมอะไหล่');"><img src="../images/Process-Info-icon24.png" width="24" height="24"></a>    
                            <?php } ?>
                        </td>
                        <td align="center"><?=$RPSPP_REGISHEAD;?></td>
                        <td align="left" width="10%"><?=$THAINAME_HEAD;?></td>
                        <td align="center"><?=$RPSPP_REGISTAIL;?></td>
                        <td align="left" width="10%"><?=$THAINAME_TAIL;?></td>
                        <td align="center"><?=$RPSPP_LINEWORK;?></td>
                            <?php if($_GET['rpsppnamegroup']=='RPSPP10'){ ?>
                                <td align="center"><?=$result_repairspaerpart['RPSPP_STDMILE'];?></td>
                            <?php }else{ ?>
                                <td align="center"><?=$result_repairspaerpart['RPSPP_STDYEAR'];?></td>
                                <td align="center"><?=$result_repairspaerpart['RPSPP_STDMONTH'];?></td>
                            <?php } ?>
                        <td align="center"><?=$convert_last;?></td>
                            <?php if($_GET['rpsppnamegroup']=='RPSPP10'){ ?>
                                <td align="center"><?=$result_repairspaerpart['RPSPP_LASTMILE'];?></td>
                            <?php } ?>
                        <td align="center"><?=$convert_plus;?></td>
                            <?php if($_GET['rpsppnamegroup']=='RPSPP10'){ ?>
                                <td align="center"></td>
                            <?php }else{ ?>
                                <td align="center" <?=$color?>><?=$summary;?></td>
                            <?php } ?>
                        <td align="center"><?=$MAXMILEAGENUMBER;?></td>
                            <?php if($_GET['rpsppnamegroup']=='RPSPP09'){ ?>
                                <td align="left"><?=$result_repairspaerpart['RPSPP_SERIES'];?></td>
                            <?php } ?>
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
			<input type="button" class="button_gray" value="อัพเดท" onclick="selectcustomer()">
		</center>
	</td>
    <td class="RIGHT">&nbsp;</td>
  </tr>
</table>
</body>
</html>