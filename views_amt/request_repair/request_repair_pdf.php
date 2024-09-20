<?php
session_start();
$path = "../";   	
require($path.'../include/connect.php');
require($path.'../include/Classes/PDF/vendor/autoload.php');

// echo"<pre>";
// print_r($_GET);
// echo"</pre>";

$mpdf = new mPDF('th', 'A4', '0', '');


$sql_chktypeplan = "SELECT RPRQ_WORKTYPE JOBTYPE,RPRQ_AREA AREA FROM REPAIRREQUEST WHERE RPRQ_CODE='".$_GET['id']."'";
$params_chktypeplan = array();
$query_chktypeplan = sqlsrv_query($conn, $sql_chktypeplan, $params_chktypeplan);
$result_chktypeplan = sqlsrv_fetch_array($query_chktypeplan, SQLSRV_FETCH_ASSOC); 

$JOBTYPE=$result_chktypeplan["JOBTYPE"];
$AREA=$result_chktypeplan["AREA"];
    
if ($JOBTYPE == 'PM') {

    $style_section_pm = '<style>
                body{
                    font-family: "Garuda";//เรียกใช้font Garuda สำหรับแสดงผล ภาษาไทย
                }
                .textboxcolor {
                    background-color: lightgrey;
                    color: black;
                }
                .textunderline {
                    text-decoration: underline;
                }
                .textlinevirtual {
                    text-decoration: line-through;
                }    
            </style>';

    $tb_section_pm_1 = '<table style="border-collapse: collapse;">
                        <tr>
                            <td style="border-bottom:3px solid #000;text-align:left;"><img src="../../img/logonew.png"></td>
                            <td style="border-bottom:3px solid #000;text-align:center;font-size:14;">&nbsp;&nbsp;&nbsp;&nbsp;<b>RUAMKIT RUNGRUENG TRUCK DETAILS CO., LTD. / บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด</b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                    </table>';

    // QuerySet
        $sql_seRepairplan = "SELECT RPRQ_WORKTYPE 'JOBTYPE',RPRQ_COMPANYCASH 'COMPANY',RPRQ_REGISHEAD 'VEHICLEREGISNUMBER1',RPRQ_REQUESTCARDATE 'CARINPUTDATE',RPRQ_USECARTIME 'CARINPUTTIME',RPRQ_USECARDATE 'CARUSEDATE',RPRQ_USECARTIME 'CARUSETIME',
            CONVERT(VARCHAR(10), RPRQ_CREATEDATE, 103) AS 'CREATEDATE',CONVERT(VARCHAR(5), RPRQ_CREATEDATE, 14) AS 'CREATETIME',
            CASE WHEN RPRQ_PRODUCTINCAR = 'ist' THEN 'มีสินค้า' WHEN RPRQ_PRODUCTINCAR = 'ost' THEN 'ไม่มีสินค้า' ELSE '' END 'CARPRODUCT',
            RPRQ_MILEAGELAST 'KM',RPRQ_REQUESTBY_SQ 'TENKOEMPLOYEE',RPRQ_REQUESTBY 'TECHNICIANEMPLOYEE',RPRQ_COMPANYCASH 'CUSTOMER',
            CASE WHEN RPRQ_NATUREREPAIR = 'bfw' THEN 'ก่อนปฏิบัติงาน' WHEN RPRQ_NATUREREPAIR = 'wwk' THEN 'ขณะปฏิบัติงาน' WHEN RPRQ_NATUREREPAIR = 'atw' THEN 'หลังปฏิบัติงาน' ELSE '' END 'DRIVINGSTATUS',
            RPRQ_COMPANYCASH 'COMPANYPAYMENT','' 'REPAIRAREADETAIL',RPRQ_REQUESTBY 'DRIVEREMPLOYEE'FROM REPAIRREQUEST WHERE RPRQ_CODE='".$_GET['id']."'";
        $params_seRepairplan = array();
        $query_seRepairplan = sqlsrv_query($conn, $sql_seRepairplan, $params_seRepairplan);
        $result_seRepairplan = sqlsrv_fetch_array($query_seRepairplan, SQLSRV_FETCH_ASSOC); 

        $sql_seRepaircarinput = "SELECT TOP 1 RPC_INCARDATE+' '+RPC_INCARTIME AS 'CARINPUTDATE' FROM REPAIRCAUSE a WHERE a.RPRQ_CODE='".$_GET['id']."'";
        $params_seRepaircarinput = array();
        $query_seRepaircarinput = sqlsrv_query($conn, $sql_seRepaircarinput, $params_seRepaircarinput);
        $result_seRepaircarinput = sqlsrv_fetch_array($query_seRepaircarinput, SQLSRV_FETCH_ASSOC);

        $sql_seRepaircarcomplete = "SELECT TOP 1 RPC_OUTCARDATE+' '+RPC_OUTCARTIME AS 'COMPLETEDDATE' FROM REPAIRCAUSE a WHERE a.RPRQ_CODE='".$_GET['id']."'";
        $params_seRepaircarcomplete = array();
        $query_seRepaircarcomplete = sqlsrv_query($conn, $sql_seRepaircarcomplete, $params_seRepaircarcomplete);
        $result_seRepaircarcomplete = sqlsrv_fetch_array($query_seRepaircarcomplete, SQLSRV_FETCH_ASSOC);

        $carproduct1 = ($result_seRepairplan['CARPRODUCT'] == 'มีสินค้า') ? "checked=''" : "";
        $carproduct2 = ($result_seRepairplan['CARPRODUCT'] == 'ไม่มีสินค้า') ? "checked=''" : "";

        if ($result_seRepairplan['DRIVINGSTATUS'] == 'ก่อนปฏิบัติงาน') {
            $drivingstatus1 = "checked=''";
        } else if ($result_seRepairplan['DRIVINGSTATUS'] == 'ขณะปฏิบัติงาน') {
            $drivingstatus2 = "checked=''";
        } else {
            $drivingstatus3 = "checked=''";
        }

        if ($result_seRepairplan['COMPANYPAYMENT'] == 'RKR') {
            $companypayment1 = "checked=''";
        } else if ($result_seRepairplan['COMPANYPAYMENT'] == 'RKL') {
            $companypayment2 = "checked=''";
        } else if ($result_seRepairplan['COMPANYPAYMENT'] == 'RKS') {
            $companypayment3 = "checked=''";
        } else if ($result_seRepairplan['COMPANYPAYMENT'] == 'RRC') {
            $companypayment4 = "checked=''";
        } else if ($result_seRepairplan['COMPANYPAYMENT'] == 'RCC') {
            $companypayment5 = "checked=''";
        } else if ($result_seRepairplan['COMPANYPAYMENT'] == 'RATC') {
            $companypayment6 = "checked=''";
        } else {
            $companypayment7 = "checked=''";
            $companypaymentname7 = $result_seRepairplan['COMPANYPAYMENT'];
        }

        if ($result_seRepairplan['JOBTYPE'] == 'BM') {
            $request = $result_seRepairplan['DRIVEREMPLOYEE'];
        } else {
            $request = $result_seRepairplan['TECHNICIANEMPLOYEE'];
        }

        $sql_seCarinputdate = "SELECT TOP 1 RPC_INCARDATE AS 'CARINPUTDATE',RPC_INCARTIME AS 'CARINPUTTIME' FROM REPAIRCAUSE WHERE RPC_INCARDATE IS NOT NULL AND RPRQ_CODE='".$_GET['id']."'";
        $params_seCarinputdate = array();
        $query_seCarinputdate = sqlsrv_query($conn, $sql_seCarinputdate, $params_seCarinputdate);
        $result_seCarinputdate = sqlsrv_fetch_array($query_seCarinputdate, SQLSRV_FETCH_ASSOC);

        $VHCRGNB=$result_seRepairplan['VEHICLEREGISNUMBER1'];
        $sql_seCarinfo = "SELECT VEHICLEREGISNUMBER,VEHICLEGROUPCODE,VEHICLETYPECODE,VEHICLETYPEDESC,THAINAME FROM vwVEHICLEINFO WHERE VEHICLEREGISNUMBER='".$VHCRGNB."'";
        $params_seCarinfo = array();
        $query_seCarinfo = sqlsrv_query($conn, $sql_seCarinfo, $params_seCarinfo);
        $result_seCarinfo = sqlsrv_fetch_array($query_seCarinfo, SQLSRV_FETCH_ASSOC);     
        
        $sql_seEmpRepair = "SELECT PositionNameT,nameT,PositionID,
        CASE 
            WHEN (PositionNameT LIKE '%เจ้าหน้าที่%') OR (PositionNameT LIKE '%Programmer%') OR (PositionNameT LIKE '%หัวหน้างาน%') OR (PositionNameT LIKE '%ผู้จัดการ%') OR (PositionNameT LIKE '%ผู้อำนวยการ%') OR (PositionNameT LIKE '%รักษาการผู้ช่วยผู้จัดการ%') THEN 'เจ้าหน้าที่' 
            WHEN (PositionNameT LIKE '%ช่าง%') THEN 'ช่าง' 
            WHEN (PositionNameT LIKE '%พนักงาน%') OR (PositionNameT LIKE '%ผู้ฝึกสอน%') OR (PositionNameT LIKE '%Senior%') OR (PositionNameT LIKE '%Dispatcher%') OR (PositionNameT LIKE '%Controller%') THEN 'พขร.' 
        ELSE '--------------------------' END CHKPST
        FROM vwEMPLOYEE WHERE nameT = '".$request."'";
        $params_seEmpRepair = array();
        $query_seEmpRepair = sqlsrv_query($conn, $sql_seEmpRepair, $params_seEmpRepair); 
        $result_seEmpRepair = sqlsrv_fetch_array($query_seEmpRepair, SQLSRV_FETCH_ASSOC); 
        if ($result_seEmpRepair['CHKPST'] == 'ช่าง') {
            $CHKPST1 = "checked=''";
        } else if ($result_seEmpRepair['CHKPST'] == 'พขร.') {
            $CHKPST2 = "checked=''";
        } else if ($result_seEmpRepair['CHKPST'] == 'เจ้าหน้าที่') {
            $CHKPST3 = "checked=''";
        }
        
        $sql_seEmpAssign = "SELECT PositionNameT,nameT,PositionID
        FROM vwEMPLOYEE WHERE nameT = '".$result_seRepairplan['TENKOEMPLOYEE']."'";
        $params_seEmpAssign = array();
        $query_seEmpAssign = sqlsrv_query($conn, $sql_seEmpAssign, $params_seEmpAssign); 
        $result_seEmpAssign = sqlsrv_fetch_array($query_seEmpAssign, SQLSRV_FETCH_ASSOC);     

        $GETID=$_GET['id'];
        $sql_chketmrpmdrive_s1 = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE='$GETID' AND RPMD_ZONE='S1'";
        $query_chketmrpmdrive_s1 = sqlsrv_query($conn, $sql_chketmrpmdrive_s1);
        $result_chketmrpmdrive_s1 = sqlsrv_fetch_array($query_chketmrpmdrive_s1, SQLSRV_FETCH_ASSOC);
            $RPMD_CARLICENCE_s1 = $result_chketmrpmdrive_s1["RPMD_CARLICENCE"];
            $RPMD_NAME_s1 = $result_chketmrpmdrive_s1["RPMD_NAME"];
            $zone_s1 = $result_chketmrpmdrive_s1["RPMD_ZONE"];
            
            $rsdrivename_s1 = $RPMD_NAME_s1;
            $rsdrivecard_s1 = $RPMD_CARLICENCE_s1;
            
        $sql_chketmrpmdrive_s2 = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE='$GETID' AND RPMD_ZONE='S2'";
        $query_chketmrpmdrive_s2 = sqlsrv_query($conn, $sql_chketmrpmdrive_s2);
        $result_chketmrpmdrive_s2 = sqlsrv_fetch_array($query_chketmrpmdrive_s2, SQLSRV_FETCH_ASSOC);
            $RPMD_CARLICENCE_s2 = $result_chketmrpmdrive_s2["RPMD_CARLICENCE"];
            $RPMD_NAME_s2 = $result_chketmrpmdrive_s2["RPMD_NAME"];
            $zone_s2 = $result_chketmrpmdrive_s2["RPMD_ZONE"];
            
            $rsdrivename_s2 = $RPMD_NAME_s2;
            $rsdrivecard_s2 = $RPMD_CARLICENCE_s2;
    // QuerySet
    $tb_section_pm_2_01 = '<table width="100%" style="border-collapse: collapse;">
                        <tr style="border:0px solid #000;"> 
                            <td colspan="6" style="border-right:0px solid #000;padding:3px;text-align:left">    
                                <tr style="border:0px solid #000;padding:4px;">            
                                    <td colspan="6" style="padding:4px;text-align:center;font-size:13;"><b>แบบฟอร์มใบแจ้งซ่อม</b></td>
                                </tr>
                                <tr style="">
                                    <td colspan="6" style="padding:4px;text-align:left;font-size:10;"><font class="textboxcolor">&nbsp;&nbsp;&nbsp;&nbsp;<b>ส่วนนี้สำหรับผู้แจ้งซ่อม</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST1.'/> ช่าง &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST2.'/> พขร. &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST3.'/> เจ้าหน้าที่</td>
                                </tr>
                                <tr style="border:1px solid #000;"> 
                                    <td width="100%" >
                                        <table style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                            <tr> 
                                                <td style="padding:3px;text-align:left;width: 100%">
                                                    <table>
                                                        <tr>
                                                            <td style="text-align:left;width: 30%">วันที่แจ้งซ่อม : <font class="textunderline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$result_seRepairplan['CREATEDATE'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 15%">เวลา : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CREATETIME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 25%">ทะเบียนรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['VEHICLEREGISNUMBER1'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 30%">ประเภทรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seCarinfo['VEHICLETYPEDESC'].'&nbsp;&nbsp;</font></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align:left;width: 30%">วันที่ต้องการใช้รถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CARUSEDATE'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 15%">เวลา : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CARUSETIME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 25%">เบอร์รถ/ชื่อรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seCarinfo['THAINAME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 30%">เลขไมล์ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['KM'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>   
                                        </table>
                                        <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:-5px;"> 
                                            <tr> 
                                                <td style="font-size:10;padding:3px;text-align:left;">&nbsp;ลักษณะงานซ่อม : <font class="textunderline">&nbsp;&nbsp;PM เครื่องยนต์&nbsp;&nbsp;</font></td>
                                                <td style="font-size:10;padding:3px;text-align:left;">ผู้ขับรถเข้าซ่อม '.$zone_s1.' : <font class="textunderline">&nbsp;&nbsp;'.$rsdrivename_s1.'&nbsp;&nbsp;</font></td>
                                                <td style="font-size:10;padding:3px;text-align:left;">ใบขับขี่ : <font class="textunderline">&nbsp;&nbsp;'.$rsdrivecard_s1.'&nbsp;&nbsp;</font></td>
                                            </tr>  
                                        </table>
                                    </td>
                                </tr>
                            </td>
                        </tr>
                    </table>';
    $tb_section_pm_2_02 = '<table width="100%" style="border-collapse: collapse;">
                        <tr style="border:0px solid #000;"> 
                            <td colspan="6" style="border-right:0px solid #000;padding:3px;text-align:left">    
                                <tr style="border:0px solid #000;padding:4px;">            
                                    <td colspan="6" style="padding:4px;text-align:center;font-size:13;"><b>แบบฟอร์มใบแจ้งซ่อม</b></td>
                                </tr>
                                <tr style="">
                                    <td colspan="6" style="padding:4px;text-align:left;font-size:10;"><font class="textboxcolor">&nbsp;&nbsp;&nbsp;&nbsp;<b>ส่วนนี้สำหรับผู้แจ้งซ่อม</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST1.'/> ช่าง &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST2.'/> พขร. &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST3.'/> เจ้าหน้าที่</td>
                                </tr>
                                <tr style="border:1px solid #000;"> 
                                    <td width="100%" >
                                        <table style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                            <tr> 
                                                <td style="padding:3px;text-align:left;width: 100%">
                                                    <table>
                                                        <tr>
                                                            <td style="text-align:left;width: 30%">วันที่แจ้งซ่อม : <font class="textunderline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$result_seRepairplan['CREATEDATE'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 15%">เวลา : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CREATETIME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 25%">ทะเบียนรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['VEHICLEREGISNUMBER1'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 30%">ประเภทรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seCarinfo['VEHICLETYPEDESC'].'&nbsp;&nbsp;</font></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align:left;width: 30%">วันที่ต้องการใช้รถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CARUSEDATE'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 15%">เวลา : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CARUSETIME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 25%">เบอร์รถ/ชื่อรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seCarinfo['THAINAME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 30%">เลขไมล์ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['KM'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>   
                                        </table>
                                        <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:-5px;"> 
                                            <tr> 
                                                <td style="font-size:10;padding:3px;text-align:left;">&nbsp;ลักษณะงานซ่อม : <font class="textunderline">&nbsp;&nbsp;PM โครงสร้าง&nbsp;&nbsp;</font></td>
                                                <td style="font-size:10;padding:3px;text-align:left;">ผู้ขับรถเข้าซ่อม '.$zone_s1.' : <font class="textunderline">&nbsp;&nbsp;'.$rsdrivename_s1.'&nbsp;&nbsp;</font></td>
                                                <td style="font-size:10;padding:3px;text-align:left;">ใบขับขี่ : <font class="textunderline">&nbsp;&nbsp;'.$rsdrivecard_s1.'&nbsp;&nbsp;</font></td>
                                            </tr>  
                                        </table>
                                    </td>
                                </tr>
                            </td>
                        </tr>
                    </table>';
    $tb_section_pm_2_03 = '<table width="100%" style="border-collapse: collapse;">
                        <tr style="border:0px solid #000;"> 
                            <td colspan="6" style="border-right:0px solid #000;padding:3px;text-align:left">    
                                <tr style="border:0px solid #000;padding:4px;">            
                                    <td colspan="6" style="padding:4px;text-align:center;font-size:13;"><b>แบบฟอร์มใบแจ้งซ่อม</b></td>
                                </tr>
                                <tr style="">
                                    <td colspan="6" style="padding:4px;text-align:left;font-size:10;"><font class="textboxcolor">&nbsp;&nbsp;&nbsp;&nbsp;<b>ส่วนนี้สำหรับผู้แจ้งซ่อม</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST1.'/> ช่าง &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST2.'/> พขร. &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST3.'/> เจ้าหน้าที่</td>
                                </tr>
                                <tr style="border:1px solid #000;"> 
                                    <td width="100%" >
                                        <table style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                            <tr> 
                                                <td style="padding:3px;text-align:left;width: 100%">
                                                    <table>
                                                        <tr>
                                                            <td style="text-align:left;width: 30%">วันที่แจ้งซ่อม : <font class="textunderline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$result_seRepairplan['CREATEDATE'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 15%">เวลา : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CREATETIME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 25%">ทะเบียนรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['VEHICLEREGISNUMBER1'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 30%">ประเภทรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seCarinfo['VEHICLETYPEDESC'].'&nbsp;&nbsp;</font></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align:left;width: 30%">วันที่ต้องการใช้รถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CARUSEDATE'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 15%">เวลา : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CARUSETIME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 25%">เบอร์รถ/ชื่อรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seCarinfo['THAINAME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 30%">เลขไมล์ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['KM'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>   
                                        </table>
                                        <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:-5px;"> 
                                            <tr> 
                                                <td style="font-size:10;padding:3px;text-align:left;">&nbsp;ลักษณะงานซ่อม : <font class="textunderline">&nbsp;&nbsp;PM ยาง ช่วงล่าง&nbsp;&nbsp;</font></td>
                                                <td style="font-size:10;padding:3px;text-align:left;">ผู้ขับรถเข้าซ่อม '.$zone_s2.' : <font class="textunderline">&nbsp;&nbsp;'.$rsdrivename_s2.'&nbsp;&nbsp;</font></td>
                                                <td style="font-size:10;padding:3px;text-align:left;">ใบขับขี่ : <font class="textunderline">&nbsp;&nbsp;'.$rsdrivecard_s2.'&nbsp;&nbsp;</font></td>
                                            </tr>  
                                        </table>
                                    </td>
                                </tr>
                            </td>
                        </tr>
                    </table>';
    $tb_section_pm_2_04 = '<table width="100%" style="border-collapse: collapse;">
                        <tr style="border:0px solid #000;"> 
                            <td colspan="6" style="border-right:0px solid #000;padding:3px;text-align:left">    
                                <tr style="border:0px solid #000;padding:4px;">            
                                    <td colspan="6" style="padding:4px;text-align:center;font-size:13;"><b>แบบฟอร์มใบแจ้งซ่อม</b></td>
                                </tr>
                                <tr style="">
                                    <td colspan="6" style="padding:4px;text-align:left;font-size:10;"><font class="textboxcolor">&nbsp;&nbsp;&nbsp;&nbsp;<b>ส่วนนี้สำหรับผู้แจ้งซ่อม</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST1.'/> ช่าง &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST2.'/> พขร. &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST3.'/> เจ้าหน้าที่</td>
                                </tr>
                                <tr style="border:1px solid #000;"> 
                                    <td width="100%" >
                                        <table style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                            <tr> 
                                                <td style="padding:3px;text-align:left;width: 100%">
                                                    <table>
                                                        <tr>
                                                            <td style="text-align:left;width: 30%">วันที่แจ้งซ่อม : <font class="textunderline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$result_seRepairplan['CREATEDATE'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 15%">เวลา : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CREATETIME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 25%">ทะเบียนรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['VEHICLEREGISNUMBER1'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 30%">ประเภทรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seCarinfo['VEHICLETYPEDESC'].'&nbsp;&nbsp;</font></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align:left;width: 30%">วันที่ต้องการใช้รถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CARUSEDATE'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 15%">เวลา : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CARUSETIME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 25%">เบอร์รถ/ชื่อรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seCarinfo['THAINAME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 30%">เลขไมล์ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['KM'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>   
                                        </table>
                                        <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:-5px;"> 
                                            <tr> 
                                                <td style="font-size:10;padding:3px;text-align:left;">&nbsp;ลักษณะงานซ่อม : <font class="textunderline">&nbsp;&nbsp;PM ระบบไฟ&nbsp;&nbsp;</font></td>
                                                <td style="font-size:10;padding:3px;text-align:left;">ผู้ขับรถเข้าซ่อม '.$zone_s2.' : <font class="textunderline">&nbsp;&nbsp;'.$rsdrivename_s2.'&nbsp;&nbsp;</font></td>
                                                <td style="font-size:10;padding:3px;text-align:left;">ใบขับขี่ : <font class="textunderline">&nbsp;&nbsp;'.$rsdrivecard_s2.'&nbsp;&nbsp;</font></td>
                                            </tr>  
                                        </table>
                                    </td>
                                </tr>
                            </td>
                        </tr>
                    </table>';
    $tb_section_pm_3 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                        <tr style="border:1px solid #000;"> 
                            <td colspan="6" style="border-right:1px solid #000;padding:3px;text-align:left">
                                <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;">
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;width: 100%" colspan="6">ลักษณะการซ่อม : <input type="checkbox" style="font-size: 15pt;" ' . $drivingstatus1 . ' /> ก่อนวิ่งงาน  <input type="checkbox" style="font-size: 15pt;" ' . $drivingstatus2 . ' /> ขณะปฎิบัติงาน  <input type="checkbox" style="font-size: 15pt;" ' . $drivingstatus3 . ' /> หลังวิ่งงาน &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" style="font-size: 15pt;" ' . $carproduct1 . ' /> มีสินค้า <input type="checkbox" style="font-size: 15pt;" ' . $carproduct2 . ' /> ไม่มีสินค้า</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>';
    $tb_section_pm_4 = '<font size="2"><b>รายละเอียดการแจ้งซ่อม</b></font>
                        <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;">
                            <tr style="border:1px solid #000;">
                                <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 10%">ลำดับ</td>
                                <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 35%" >รายการแจ้งซ่อม</td>
                                <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 35%" colspan="3">จุด/ตำแหน่งรถ "ที่พบปัญหา"</td>
                                <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 20%" >หมายเหตุ</td>
                            </tr>';                        
                            // QuerySet  
                                $itd1 = 1;
                                $sql_seRepaircause_p01 = "SELECT RPRQ_ID 'REPAIRPLANID', RPC_SUBJECT_CON 'SUBJECT', RPC_DETAIL 'DETAIL', RPRQ_REMARK 'REMARK' FROM vwREPAIRREQUEST WHERE RPRQ_CODE='".$_GET['id']."' AND RPC_SUBJECT_CON='เครื่องยนต์'";
                                $params_seRepaircause_p01 = array();
                                $query_seRepaircause_p01 = sqlsrv_query($conn, $sql_seRepaircause_p01, $params_seRepaircause_p01);
                                while ($result_seRepaircause_p01 = sqlsrv_fetch_array($query_seRepaircause_p01, SQLSRV_FETCH_ASSOC)) {
                                    $tb_section_pm_41 = '<tr style="border:1px solid #000;">
                                                <td style="border-right:1px solid #000;padding:3px;text-align:center" >' . $itd1 . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" >' . $result_seRepaircause_p01['SUBJECT'] . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3">' . $result_seRepaircause_p01['DETAIL'] . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left" >' . $result_seRepaircause_p01['REMARK'] . '</td>
                                            </tr>';
                                    $itd1++;
                                } 
                                $itd2 = 1;
                                $sql_seRepaircause_p02 = "SELECT RPRQ_ID 'REPAIRPLANID', RPC_SUBJECT_CON 'SUBJECT', RPC_DETAIL 'DETAIL', RPRQ_REMARK 'REMARK' FROM vwREPAIRREQUEST WHERE RPRQ_CODE='".$_GET['id']."' AND RPC_SUBJECT_CON='โครงสร้าง'";
                                $params_seRepaircause_p02 = array();
                                $query_seRepaircause_p02 = sqlsrv_query($conn, $sql_seRepaircause_p02, $params_seRepaircause_p02);
                                while ($result_seRepaircause_p02 = sqlsrv_fetch_array($query_seRepaircause_p02, SQLSRV_FETCH_ASSOC)) {
                                    $tb_section_pm_42 = '<tr style="border:1px solid #000;">
                                                <td style="border-right:1px solid #000;padding:3px;text-align:center" >' . $itd2 . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" >' . $result_seRepaircause_p02['SUBJECT'] . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3">' . $result_seRepaircause_p02['DETAIL'] . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left" >' . $result_seRepaircause_p02['REMARK'] . '</td>
                                            </tr>';
                                    $itd2++;
                                }
                                $itd3 = 1;
                                $sql_seRepaircause_p03 = "SELECT RPRQ_ID 'REPAIRPLANID', RPC_SUBJECT_CON 'SUBJECT', RPC_DETAIL 'DETAIL', RPRQ_REMARK 'REMARK' FROM vwREPAIRREQUEST WHERE RPRQ_CODE='".$_GET['id']."' AND RPC_SUBJECT_CON='ยาง ช่วงล่าง'";
                                $params_seRepaircause_p03 = array();
                                $query_seRepaircause_p03 = sqlsrv_query($conn, $sql_seRepaircause_p03, $params_seRepaircause_p03);
                                while ($result_seRepaircause_p03 = sqlsrv_fetch_array($query_seRepaircause_p03, SQLSRV_FETCH_ASSOC)) {
                                    $tb_section_pm_43 = '<tr style="border:1px solid #000;">
                                                <td style="border-right:1px solid #000;padding:3px;text-align:center" >' . $itd3 . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" >' . $result_seRepaircause_p03['SUBJECT'] . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3">' . $result_seRepaircause_p03['DETAIL'] . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left" >' . $result_seRepaircause_p03['REMARK'] . '</td>
                                            </tr>';
                                    $itd3++;
                                }
                                $itd4 = 1;
                                $sql_seRepaircause_p04 = "SELECT RPRQ_ID 'REPAIRPLANID', RPC_SUBJECT_CON 'SUBJECT', RPC_DETAIL 'DETAIL', RPRQ_REMARK 'REMARK' FROM vwREPAIRREQUEST WHERE RPRQ_CODE='".$_GET['id']."' AND RPC_SUBJECT_CON='ระบบไฟ'";
                                $params_seRepaircause_p04 = array();
                                $query_seRepaircause_p04 = sqlsrv_query($conn, $sql_seRepaircause_p04, $params_seRepaircause_p04);
                                while ($result_seRepaircause_p04 = sqlsrv_fetch_array($query_seRepaircause_p04, SQLSRV_FETCH_ASSOC)) {
                                    $tb_section_pm_44 = '<tr style="border:1px solid #000;">
                                                <td style="border-right:1px solid #000;padding:3px;text-align:center" >' . $itd4 . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" >' . $result_seRepaircause_p04['SUBJECT'] . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3">' . $result_seRepaircause_p04['DETAIL'] . '</td>
                                                <td style="border-right:1px solid #000;padding:3px;text-align:left" >' . $result_seRepaircause_p04['REMARK'] . '</td>
                                            </tr>';
                                    $itd4++;
                                }                 
                            // QuerySet
                        
    $tb_section_pm_4_end = '</table>';                 
    $tb_section_pm_5 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:40px;">
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ...............'.$request.'...............ผู้แจ้งซ่อม</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ...........'.$result_seEmpAssign['nameT'].'...................เจ้าหน้าที่จ่ายงาน</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:8px;text-align:center;">(...........'.$request.'...........)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:center;">(...........'.$result_seEmpAssign['nameT'].'...................)</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">สายงาน..........'.$result_seEmpRepair["PositionNameT"].'..........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">แผนก..........'.$result_seEmpAssign["PositionNameT"].'..........</td>
                        </tr>
                    </table>';
    $tb_section_pm_6 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                        <tr>
                            <td style="border-bottom:0px solid #000;padding:4px;text-align:center;">
                                <font class="textlinevirtual">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </font>
                                <font class="textboxcolor"><b>ส่วนเฉพาะหน่วยงานซ่อมบำรุง</b></font>
                                <font class="textlinevirtual">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </font>
                            </td>
                        </tr>
                    </table>
                    <font size="2"><b>ผลการตรวจสอบและการดำเนินการ</b></font>
                    <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;">
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 10%">ลำดับ</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 35%" >ผลการตรวจประเมินงานก่อนซ่อม</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 25%" colspan="3">สาเหตุ</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 15%" >วันที่ตรวจ</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 15%" >ผู้ตรวจประเมิน</td>
                        </tr>
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center" >1</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3"></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                        </tr>
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center" >2</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3"></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                        </tr>
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center" >3</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3"></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                        </tr>
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center" >4</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3"></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                        </tr>
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center" >5</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3"></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                        </tr>
                    </table>';

    $tb_section_pm_7 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                        <tr> 
                            <td style="border:1px solid #000;padding:3px;text-align:left;width: 45%" >
                                <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:1px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> สามารถซ่อมบำรุงภายในได้</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> ไม่สามารถดำเนินการซ่อมบำรุงได้</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" /> ไม่มีอะไหล่/รออะไหล่(วันที่นัดรับ.......................................)</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> ส่งซ่อมอู่นอก (ชื่ออู่....................................................................)</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่ส่งซ่อม............................................</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:5px;text-align:left;width: 40%"><font class="textboxcolor">ประเภท RANK(ประเมินโดย SA/หน.ช่าง)</font></td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> A = หยุดรถใช้งานต้องจอดซ่อม</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> B = ใช้งานได้ชั่วคราวแต่ต้องกลับมาซ่อม</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;หรืออะไหล่ที่สั่งมาแล้วต้องนำเข้าซ่อม</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> C = สามารถใช้งานได้ ไม่ส่งผลกระทบกับตัวรถ </td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            <td style="border:0px solid #000;padding:3px;text-align:left;width: 1%" ></td>
                            <td style="border:1px solid #000;padding:3px;text-align:left;width: 54%" >            
                                <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                    <tr style="border:0px solid #000;">
                                        <td style="padding:0px;text-align:left;width: 45%">                        
                                            <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                                <tr style="border-left:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ผู้รับผิดชอบค่าใช้จ่าย</b></td>
                                                </tr>
                                                <tr style="border-left:0px solid #000;">
                                                    <td style="border-right:1px solid #000;padding:0px;text-align:left;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:0px solid #000;">
                                                    <td style="border-right:1px solid #000;padding:0px;text-align:center;">
                                                        <font class="textlinevirtual">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font><font class="textboxcolor">
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <b>หมายเหตุ</b>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        </font><font class="textlinevirtual">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
                                                    </td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;&nbsp;........................................................................&nbsp;&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;&nbsp;........................................................................&nbsp;&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;&nbsp;........................................................................&nbsp;&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;&nbsp;____________________________________&nbsp;&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">ลงชื่อ-นามสกุล</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;padding:20px;">
                                                    <td style="text-align:center;">ผู้สั่งซ่อม/ผู้รับผิดชอบ</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="padding:3px;text-align:left;width: 55%" >
                                            <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment1.'/> บริษัท ร่วมกิจรุ่งเรือง(1993) จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment3.'/> บริษัท ร่วมกิจรุ่งเรืองเซอร์วิส จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment2.'/> บริษัท ร่วมกิจรุ่งเรือง โลจิสติคส์ จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" /> บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment5.'/> บริษัท ร่วมกิจรุ่งเรือง คาร์ แคริเออร์ จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment4.'/> บริษัท ร่วมกิจ รีไซเคิล แคริเออร์ จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment6.'/> บริษัท ร่วมกิจ ออโตโมทีฟ ทรานสปอร์ต จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" /> ผู้ขับขี่............................................................</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment7.'/> อื่นๆ....................'.$companypaymentname7.'.....................</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>  
                    </table>';  
    // CheckifSet    
    if($AREA=='AMT'){
        $boxname_s1='สุริยา แน่นหนา';
        $boxname_s2='อำนวย บุญมา';
        $boxname_s3='เกียรติพงษ์ สดรัมย์';
        $boxname_s4='วิชัย ประสานดี';
        $boxname1='พงศ์ภัค มีอยู่สามเสน';
        $boxname2='บุรินชัย เบอร์ไธสง';
        $boxposition='RTD';
    }else{
        $boxname_s1='อาทิตย์ แสงสว่าง';
        $boxname_s2='อาทิตย์ แสงสว่าง';
        $boxname_s3='สำนวน พรมพา ';
        $boxname_s4='อาทิตย์ แสงสว่าง';
        $boxname1='เติมพงศ์ กลิ่นนุช';
        $boxname2='อำนาจ อาจสู้ศึก ';
        $boxposition='RTD';
    }
    // CheckifSet  
    $tb_section_pm_8_01 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:30px;">
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">ลงชื่อ...........'.$boxname_s1.'...........หัวหน้าช่าง</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ...........'.$boxname1.'...........เจ้าหน้าที่ SA</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">ลงชื่อ...........'.$boxname2.'...........หัวหน้างาน</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname_s1.'...........)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname1.'...........)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname2.'...........)</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">แก้ไขครั้งที่ : 01     มีผลบังคับใช้ : 10-06-66</td>
                        </tr>
                    </table>';
    $tb_section_pm_8_02 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:30px;">
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">ลงชื่อ...........'.$boxname_s2.'...........หัวหน้าช่าง</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ...........'.$boxname1.'...........เจ้าหน้าที่ SA</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">ลงชื่อ...........'.$boxname2.'...........หัวหน้างาน</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname_s2.'...........)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname1.'...........)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname2.'...........)</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">แก้ไขครั้งที่ : 01     มีผลบังคับใช้ : 10-06-66</td>
                        </tr>
                    </table>';
    $tb_section_pm_8_03 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:30px;">
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">ลงชื่อ...........'.$boxname_s3.'...........หัวหน้าช่าง</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ...........'.$boxname1.'...........เจ้าหน้าที่ SA</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">ลงชื่อ...........'.$boxname2.'...........หัวหน้างาน</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname_s3.'...........)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname1.'...........)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname2.'...........)</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">แก้ไขครั้งที่ : 01     มีผลบังคับใช้ : 10-06-66</td>
                        </tr>
                    </table>';
    $tb_section_pm_8_04 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:30px;">
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">ลงชื่อ...........'.$boxname_s4.'...........หัวหน้าช่าง</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ...........'.$boxname1.'...........เจ้าหน้าที่ SA</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">ลงชื่อ...........'.$boxname2.'...........หัวหน้างาน</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname_s4.'...........)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname1.'...........)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname2.'...........)</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">แก้ไขครั้งที่ : 01     มีผลบังคับใช้ : 10-06-66</td>
                        </tr>
                    </table>';

    $mpdf->WriteHTML($style_section_pm);
    $mpdf->WriteHTML($tb_section_pm_1);
    $mpdf->WriteHTML($tb_section_pm_2_01);
    $mpdf->WriteHTML($tb_section_pm_3);
    $mpdf->WriteHTML($tb_section_pm_4);
    $mpdf->WriteHTML($tb_section_pm_41);
    $mpdf->WriteHTML($tb_section_pm_4_end);
    $mpdf->WriteHTML($tb_section_pm_5);
    $mpdf->WriteHTML($tb_section_pm_6);
    $mpdf->WriteHTML($tb_section_pm_7);
    $mpdf->WriteHTML($tb_section_pm_8_01);

    $mpdf->AddPage();
    $mpdf->WriteHTML($style_section_pm);
    $mpdf->WriteHTML($tb_section_pm_1);
    $mpdf->WriteHTML($tb_section_pm_2_02);
    $mpdf->WriteHTML($tb_section_pm_3);
    $mpdf->WriteHTML($tb_section_pm_4);
    $mpdf->WriteHTML($tb_section_pm_42);
    $mpdf->WriteHTML($tb_section_pm_4_end);
    $mpdf->WriteHTML($tb_section_pm_5);
    $mpdf->WriteHTML($tb_section_pm_6);
    $mpdf->WriteHTML($tb_section_pm_7);
    $mpdf->WriteHTML($tb_section_pm_8_02);

    $mpdf->AddPage();
    $mpdf->WriteHTML($style_section_pm);
    $mpdf->WriteHTML($tb_section_pm_1);
    $mpdf->WriteHTML($tb_section_pm_2_03);
    $mpdf->WriteHTML($tb_section_pm_3);
    $mpdf->WriteHTML($tb_section_pm_4);
    $mpdf->WriteHTML($tb_section_pm_43);
    $mpdf->WriteHTML($tb_section_pm_4_end);
    $mpdf->WriteHTML($tb_section_pm_5);
    $mpdf->WriteHTML($tb_section_pm_6);
    $mpdf->WriteHTML($tb_section_pm_7);
    $mpdf->WriteHTML($tb_section_pm_8_03);

    $mpdf->AddPage();
    $mpdf->WriteHTML($style_section_pm);
    $mpdf->WriteHTML($tb_section_pm_1);
    $mpdf->WriteHTML($tb_section_pm_2_04);
    $mpdf->WriteHTML($tb_section_pm_3);
    $mpdf->WriteHTML($tb_section_pm_4);
    $mpdf->WriteHTML($tb_section_pm_44);
    $mpdf->WriteHTML($tb_section_pm_4_end);
    $mpdf->WriteHTML($tb_section_pm_5);
    $mpdf->WriteHTML($tb_section_pm_6);
    $mpdf->WriteHTML($tb_section_pm_7);
    $mpdf->WriteHTML($tb_section_pm_8_04);
    $mpdf->Output();
}else{

    $style_section_bm = '<style>
                body{
                    font-family: "Garuda";//เรียกใช้font Garuda สำหรับแสดงผล ภาษาไทย
                }
                .textboxcolor {
                    background-color: lightgrey;
                    color: black;
                }
                .textunderline {
                    text-decoration: underline;
                }
                .textlinevirtual {
                    text-decoration: line-through;
                }    
            </style>';

    $tb_section_bm_1 = '<table style="border-collapse: collapse;">
                        <tr>
                            <td style="border-bottom:3px solid #000;text-align:left;"><img src="../../img/logonew.png"></td>
                            <td style="border-bottom:3px solid #000;text-align:center;font-size:14;">&nbsp;&nbsp;&nbsp;&nbsp;<b>RUAMKIT RUNGRUENG TRUCK DETAILS CO., LTD. / บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด</b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                    </table>';

    // QuerySet
        $sql_seRepairplan = "SELECT RPRQ_WORKTYPE 'JOBTYPE',RPRQ_COMPANYCASH 'COMPANY',RPRQ_REGISHEAD 'VEHICLEREGISNUMBER1',RPRQ_REQUESTCARDATE 'CARINPUTDATE',RPRQ_USECARTIME 'CARINPUTTIME',RPRQ_USECARDATE 'CARUSEDATE',RPRQ_USECARTIME 'CARUSETIME',
            CONVERT(VARCHAR(10), RPRQ_CREATEDATE, 103) AS 'CREATEDATE',CONVERT(VARCHAR(5), RPRQ_CREATEDATE, 14) AS 'CREATETIME',
            CASE WHEN RPRQ_PRODUCTINCAR = 'ist' THEN 'มีสินค้า' WHEN RPRQ_PRODUCTINCAR = 'ost' THEN 'ไม่มีสินค้า' ELSE '' END 'CARPRODUCT',
            RPRQ_MILEAGELAST 'KM',RPRQ_REQUESTBY_SQ 'TENKOEMPLOYEE',RPRQ_REQUESTBY 'TECHNICIANEMPLOYEE',RPRQ_COMPANYCASH 'CUSTOMER',
            CASE WHEN RPRQ_NATUREREPAIR = 'bfw' THEN 'ก่อนปฏิบัติงาน' WHEN RPRQ_NATUREREPAIR = 'wwk' THEN 'ขณะปฏิบัติงาน' WHEN RPRQ_NATUREREPAIR = 'atw' THEN 'หลังปฏิบัติงาน' ELSE '' END 'DRIVINGSTATUS',
            RPRQ_COMPANYCASH 'COMPANYPAYMENT','' 'REPAIRAREADETAIL',RPRQ_REQUESTBY 'DRIVEREMPLOYEE'FROM REPAIRREQUEST WHERE RPRQ_CODE='".$_GET['id']."'";
        $params_seRepairplan = array();
        $query_seRepairplan = sqlsrv_query($conn, $sql_seRepairplan, $params_seRepairplan);
        $result_seRepairplan = sqlsrv_fetch_array($query_seRepairplan, SQLSRV_FETCH_ASSOC); 

        $sql_seRepaircarinput = "SELECT TOP 1 RPC_INCARDATE+' '+RPC_INCARTIME AS 'CARINPUTDATE' FROM REPAIRCAUSE a WHERE a.RPRQ_CODE='".$_GET['id']."'";
        $params_seRepaircarinput = array();
        $query_seRepaircarinput = sqlsrv_query($conn, $sql_seRepaircarinput, $params_seRepaircarinput);
        $result_seRepaircarinput = sqlsrv_fetch_array($query_seRepaircarinput, SQLSRV_FETCH_ASSOC);

        $sql_seRepaircarcomplete = "SELECT TOP 1 RPC_OUTCARDATE+' '+RPC_OUTCARTIME AS 'COMPLETEDDATE' FROM REPAIRCAUSE a WHERE a.RPRQ_CODE='".$_GET['id']."'";
        $params_seRepaircarcomplete = array();
        $query_seRepaircarcomplete = sqlsrv_query($conn, $sql_seRepaircarcomplete, $params_seRepaircarcomplete);
        $result_seRepaircarcomplete = sqlsrv_fetch_array($query_seRepaircarcomplete, SQLSRV_FETCH_ASSOC);

        $carproduct1 = ($result_seRepairplan['CARPRODUCT'] == 'มีสินค้า') ? "checked=''" : "";
        $carproduct2 = ($result_seRepairplan['CARPRODUCT'] == 'ไม่มีสินค้า') ? "checked=''" : "";

        if ($result_seRepairplan['DRIVINGSTATUS'] == 'ก่อนปฏิบัติงาน') {
            $drivingstatus1 = "checked=''";
        } else if ($result_seRepairplan['DRIVINGSTATUS'] == 'ขณะปฏิบัติงาน') {
            $drivingstatus2 = "checked=''";
        } else {
            $drivingstatus3 = "checked=''";
        }

        if ($result_seRepairplan['COMPANYPAYMENT'] == 'RKR') {
            $companypayment1 = "checked=''";
        } else if ($result_seRepairplan['COMPANYPAYMENT'] == 'RKL') {
            $companypayment2 = "checked=''";
        } else if ($result_seRepairplan['COMPANYPAYMENT'] == 'RKS') {
            $companypayment3 = "checked=''";
        } else if ($result_seRepairplan['COMPANYPAYMENT'] == 'RRC') {
            $companypayment4 = "checked=''";
        } else if ($result_seRepairplan['COMPANYPAYMENT'] == 'RCC') {
            $companypayment5 = "checked=''";
        } else if ($result_seRepairplan['COMPANYPAYMENT'] == 'RATC') {
            $companypayment6 = "checked=''";
        } else {
            $companypayment7 = "checked=''";
            $companypaymentname7 = $result_seRepairplan['COMPANYPAYMENT'];
        }

        if ($result_seRepairplan['JOBTYPE'] == 'BM') {
            $request = $result_seRepairplan['DRIVEREMPLOYEE'];
        } else {
            $request = $result_seRepairplan['TECHNICIANEMPLOYEE'];
        }

        $sql_seCarinputdate = "SELECT TOP 1 RPC_INCARDATE AS 'CARINPUTDATE',RPC_INCARTIME AS 'CARINPUTTIME' FROM REPAIRCAUSE WHERE RPC_INCARDATE IS NOT NULL AND RPRQ_CODE='".$_GET['id']."'";
        $params_seCarinputdate = array();
        $query_seCarinputdate = sqlsrv_query($conn, $sql_seCarinputdate, $params_seCarinputdate);
        $result_seCarinputdate = sqlsrv_fetch_array($query_seCarinputdate, SQLSRV_FETCH_ASSOC);

        $VHCRGNB=$result_seRepairplan['VEHICLEREGISNUMBER1'];
        $sql_seCarinfo = "SELECT VEHICLEREGISNUMBER,VEHICLEGROUPCODE,VEHICLETYPECODE,VEHICLETYPEDESC,THAINAME FROM vwVEHICLEINFO WHERE VEHICLEREGISNUMBER='".$VHCRGNB."'";
        $params_seCarinfo = array();
        $query_seCarinfo = sqlsrv_query($conn, $sql_seCarinfo, $params_seCarinfo);
        $result_seCarinfo = sqlsrv_fetch_array($query_seCarinfo, SQLSRV_FETCH_ASSOC);     
        
        $sql_seEmpRepair = "SELECT PositionNameT,nameT,PositionID,
        CASE 
            WHEN (PositionNameT LIKE '%เจ้าหน้าที่%') OR (PositionNameT LIKE '%Programmer%') OR (PositionNameT LIKE '%หัวหน้างาน%') OR (PositionNameT LIKE '%ผู้จัดการ%') OR (PositionNameT LIKE '%ผู้อำนวยการ%') OR (PositionNameT LIKE '%รักษาการผู้ช่วยผู้จัดการ%') THEN 'เจ้าหน้าที่' 
            WHEN (PositionNameT LIKE '%ช่าง%') THEN 'ช่าง' 
            WHEN (PositionNameT LIKE '%พนักงาน%') OR (PositionNameT LIKE '%ผู้ฝึกสอน%') OR (PositionNameT LIKE '%Senior%') OR (PositionNameT LIKE '%Dispatcher%') OR (PositionNameT LIKE '%Controller%') THEN 'พขร.' 
        ELSE '--------------------------' END CHKPST
        FROM vwEMPLOYEE WHERE nameT = '".$request."'";
        $params_seEmpRepair = array();
        $query_seEmpRepair = sqlsrv_query($conn, $sql_seEmpRepair, $params_seEmpRepair); 
        $result_seEmpRepair = sqlsrv_fetch_array($query_seEmpRepair, SQLSRV_FETCH_ASSOC); 
        if ($result_seEmpRepair['CHKPST'] == 'ช่าง') {
            $CHKPST1 = "checked=''";
        } else if ($result_seEmpRepair['CHKPST'] == 'พขร.') {
            $CHKPST2 = "checked=''";
        } else if ($result_seEmpRepair['CHKPST'] == 'เจ้าหน้าที่') {
            $CHKPST3 = "checked=''";
        }
        
        $sql_seEmpAssign = "SELECT PositionNameT,nameT,PositionID
        FROM vwEMPLOYEE WHERE nameT = '".$result_seRepairplan['TENKOEMPLOYEE']."'";
        $params_seEmpAssign = array();
        $query_seEmpAssign = sqlsrv_query($conn, $sql_seEmpAssign, $params_seEmpAssign); 
        $result_seEmpAssign = sqlsrv_fetch_array($query_seEmpAssign, SQLSRV_FETCH_ASSOC);    

        $GETID=$_GET['id'];
        $sql_chketmrpmdrive = "SELECT * FROM REPAIRMANDRIVE WHERE RPRQ_CODE='$GETID'";
        $query_chketmrpmdrive = sqlsrv_query($conn, $sql_chketmrpmdrive);
        $result_chketmrpmdrive = sqlsrv_fetch_array($query_chketmrpmdrive, SQLSRV_FETCH_ASSOC);
            $RPMD_CARLICENCE = $result_chketmrpmdrive["RPMD_CARLICENCE"];
            $RPMD_NAME = $result_chketmrpmdrive["RPMD_NAME"];
            $zone = $result_chketmrpmdrive["RPMD_ZONE"];
            
            $rsdrivename = $RPMD_NAME;
            $rsdrivecard = $RPMD_CARLICENCE;

        $sql_seRepaircause_sec2 = "SELECT (STUFF((SELECT ' + ' + [RPC_SUBJECT_CON] FROM vwREPAIRREQUEST WHERE RPRQ_CODE='".$_GET['id']."' FOR XML PATH('') ), 1, 2, '') ) AS SUBJECT";
        $params_seRepaircause_sec2 = array();
        $query_seRepaircause_sec2 = sqlsrv_query($conn, $sql_seRepaircause_sec2, $params_seRepaircause_sec2); 
        $result_seRepaircause_sec2 = sqlsrv_fetch_array($query_seRepaircause_sec2, SQLSRV_FETCH_ASSOC);
    // QuerySet
    $tb_section_bm_2 = '<table width="100%" style="border-collapse: collapse;">
                        <tr style="border:0px solid #000;"> 
                            <td colspan="6" style="border-right:0px solid #000;padding:3px;text-align:left">    
                                <tr style="border:0px solid #000;padding:4px;">            
                                    <td colspan="6" style="padding:4px;text-align:center;font-size:13;"><b>แบบฟอร์มใบแจ้งซ่อม</b></td>
                                </tr>
                                <tr style="">
                                    <td colspan="6" style="padding:4px;text-align:left;font-size:10;"><font class="textboxcolor">&nbsp;&nbsp;&nbsp;&nbsp;<b>ส่วนนี้สำหรับผู้แจ้งซ่อม</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST1.'/> ช่าง &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST2.'/> พขร. &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$CHKPST3.'/> เจ้าหน้าที่</td>
                                </tr>
                                <tr style="border:1px solid #000;"> 
                                    <td width="100%" >
                                        <table style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                            <tr> 
                                                <td style="padding:3px;text-align:left;width: 100%">
                                                    <table>
                                                        <tr>
                                                            <td style="text-align:left;width: 30%">วันที่แจ้งซ่อม : <font class="textunderline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$result_seRepairplan['CREATEDATE'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 15%">เวลา : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CREATETIME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 25%">ทะเบียนรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['VEHICLEREGISNUMBER1'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 30%">ประเภทรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seCarinfo['VEHICLETYPEDESC'].'&nbsp;&nbsp;</font></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align:left;width: 30%">วันที่ต้องการใช้รถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CARUSEDATE'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 15%">เวลา : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['CARUSETIME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 25%">เบอร์รถ/ชื่อรถ : <font class="textunderline">&nbsp;&nbsp;'.$result_seCarinfo['THAINAME'].'&nbsp;&nbsp;</font></td>
                                                            <td style="text-align:left;width: 30%">เลขไมล์ : <font class="textunderline">&nbsp;&nbsp;'.$result_seRepairplan['KM'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>   
                                        </table>
                                        <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:-5px;"> 
                                            <tr> 
                                                <td style="font-size:10;padding:3px;text-align:left;">&nbsp;ลักษณะงานซ่อม : <font class="textunderline">&nbsp;&nbsp;'. $result_seRepaircause_sec2['SUBJECT'].'&nbsp;&nbsp;</font></td>
                                                <td style="font-size:10;padding:3px;text-align:left;">ผู้ขับรถเข้าซ่อม : <font class="textunderline">&nbsp;&nbsp;'.$rsdrivename.'&nbsp;&nbsp;</font></td>
                                                <td style="font-size:10;padding:3px;text-align:left;">ใบขับขี่ : <font class="textunderline">&nbsp;&nbsp;'.$rsdrivecard.'&nbsp;&nbsp;</font></td>
                                            </tr>  
                                        </table>
                                    </td>
                                </tr>
                            </td>
                        </tr>
                    </table>';
    $tb_section_bm_3 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                        <tr style="border:1px solid #000;"> 
                            <td colspan="6" style="border-right:1px solid #000;padding:3px;text-align:left">
                                <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;">
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;width: 100%" colspan="6">ลักษณะการซ่อม : <input type="checkbox" style="font-size: 15pt;" ' . $drivingstatus1 . ' /> ก่อนวิ่งงาน  <input type="checkbox" style="font-size: 15pt;" ' . $drivingstatus2 . ' /> ขณะปฎิบัติงาน  <input type="checkbox" style="font-size: 15pt;" ' . $drivingstatus3 . ' /> หลังวิ่งงาน &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" style="font-size: 15pt;" ' . $carproduct1 . ' /> มีสินค้า <input type="checkbox" style="font-size: 15pt;" ' . $carproduct2 . ' /> ไม่มีสินค้า</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>';
    $tb_section_bm_4 = '<font size="2"><b>รายละเอียดการแจ้งซ่อม</b></font>
                        <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;">
                            <tr style="border:1px solid #000;">
                                <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 10%">ลำดับ</td>
                                <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 35%" >รายการแจ้งซ่อม</td>
                                <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 35%" colspan="3">จุด/ตำแหน่งรถ "ที่พบปัญหา"</td>
                                <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 20%" >หมายเหตุ</td>
                            </tr>';                        
                            // QuerySet                    
                                $itd1 = 1;
                                $sql_seRepaircause = "SELECT RPRQ_ID 'REPAIRPLANID', RPC_SUBJECT_CON 'SUBJECT', RPC_DETAIL 'DETAIL', RPRQ_REMARK 'REMARK' FROM vwREPAIRREQUEST WHERE RPRQ_CODE='".$_GET['id']."'";
                                $params_seRepaircause = array();
                                $query_seRepaircause = sqlsrv_query($conn, $sql_seRepaircause, $params_seRepaircause); 
                                while ($result_seRepaircause = sqlsrv_fetch_array($query_seRepaircause, SQLSRV_FETCH_ASSOC)) {
                                    $tb_section_bm_4 .= '<tr style="border:1px solid #000;">
                                                        <td style="border-right:1px solid #000;padding:3px;text-align:center" >' . $itd1 . '</td>
                                                        <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" >' . $result_seRepaircause['SUBJECT'] . '</td>
                                                        <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3">' . $result_seRepaircause['DETAIL'] . '</td>
                                                        <td style="border-right:1px solid #000;padding:3px;text-align:left" >' . $result_seRepaircause['REMARK'] . '</td>
                                                    </tr>';
                                    $itd1++;
                                }
                            // QuerySet
                        
    $tb_section_bm_4 .= '</table>';                 
    $tb_section_bm_5 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:40px;">
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ...............'.$request.'...............ผู้แจ้งซ่อม</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ...........'.$result_seEmpAssign['nameT'].'...................เจ้าหน้าที่จ่ายงาน</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:8px;text-align:center;">(............'.$request.'............)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:center;">(...........'.$result_seEmpAssign['nameT'].'...................)</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">สายงาน..........'.$result_seEmpRepair["PositionNameT"].'..........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">แผนก..........'.$result_seEmpAssign["PositionNameT"].'..........</td>
                        </tr>
                    </table>';
    $tb_section_bm_6 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                        <tr>
                            <td style="border-bottom:0px solid #000;padding:4px;text-align:center;">
                                <font class="textlinevirtual">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </font>
                                <font class="textboxcolor"><b>ส่วนเฉพาะหน่วยงานซ่อมบำรุง</b></font>
                                <font class="textlinevirtual">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </font>
                            </td>
                        </tr>
                    </table>
                    <font size="2"><b>ผลการตรวจสอบและการดำเนินการ</b></font>
                    <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;">
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 10%">ลำดับ</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 35%" >ผลการตรวจประเมินงานก่อนซ่อม</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 25%" colspan="3">สาเหตุ</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 15%" >วันที่ตรวจ</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;width: 15%" >ผู้ตรวจประเมิน</td>
                        </tr>
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center" >1</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3"></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                        </tr>
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center" >2</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3"></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                        </tr>
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center" >3</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3"></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                        </tr>
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center" >4</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3"></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                        </tr>
                        <tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center" >5</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left;width: 35%" colspan="3"></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:left" ></td>
                        </tr>
                    </table>';

    $tb_section_bm_7 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                        <tr> 
                            <td style="border:1px solid #000;padding:3px;text-align:left;width: 45%" >
                                <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:1px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> สามารถซ่อมบำรุงภายในได้</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> ไม่สามารถดำเนินการซ่อมบำรุงได้</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" style="font-size: 15pt;" /> ไม่มีอะไหล่/รออะไหล่(วันที่นัดรับ.......................................)</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> ส่งซ่อมอู่นอก (ชื่ออู่....................................................................)</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่ส่งซ่อม............................................</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:5px;text-align:left;width: 40%"><font class="textboxcolor">ประเภท RANK(ประเมินโดย SA/หน.ช่าง)</font></td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> A = หยุดรถใช้งานต้องจอดซ่อม</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> B = ใช้งานได้ชั่วคราวแต่ต้องกลับมาซ่อม</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;หรืออะไหล่ที่สั่งมาแล้วต้องนำเข้าซ่อม</td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%"><input type="checkbox" style="font-size: 15pt;" /> C = สามารถใช้งานได้ ไม่ส่งผลกระทบกับตัวรถ </td>
                                    </tr>
                                    <tr style="border:0px solid #000;">
                                        <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 40%">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            <td style="border:0px solid #000;padding:3px;text-align:left;width: 1%" ></td>
                            <td style="border:1px solid #000;padding:3px;text-align:left;width: 54%" >            
                                <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                    <tr style="border:0px solid #000;">
                                        <td style="padding:0px;text-align:left;width: 45%">                        
                                            <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                                <tr style="border-left:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ผู้รับผิดชอบค่าใช้จ่าย</b></td>
                                                </tr>
                                                <tr style="border-left:0px solid #000;">
                                                    <td style="border-right:1px solid #000;padding:0px;text-align:left;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:0px solid #000;">
                                                    <td style="border-right:1px solid #000;padding:0px;text-align:center;">
                                                        <font class="textlinevirtual">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font><font class="textboxcolor">
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <b>หมายเหตุ</b>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        </font><font class="textlinevirtual">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
                                                    </td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;&nbsp;........................................................................&nbsp;&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;&nbsp;........................................................................&nbsp;&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;&nbsp;........................................................................&nbsp;&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;&nbsp;____________________________________&nbsp;&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">&nbsp;</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;">
                                                    <td style="text-align:center;">ลงชื่อ-นามสกุล</td>
                                                </tr>
                                                <tr style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;padding:20px;">
                                                    <td style="text-align:center;">ผู้สั่งซ่อม/ผู้รับผิดชอบ</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="padding:3px;text-align:left;width: 55%" >
                                            <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:8px;">
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment1.'/> บริษัท ร่วมกิจรุ่งเรือง(1993) จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment3.'/> บริษัท ร่วมกิจรุ่งเรืองเซอร์วิส จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment2.'/> บริษัท ร่วมกิจรุ่งเรือง โลจิสติคส์ จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" /> บริษัท ร่วมกิจรุ่งเรือง ทรัค ดีเทลส์ จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment5.'/> บริษัท ร่วมกิจรุ่งเรือง คาร์ แคริเออร์ จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment4.'/> บริษัท ร่วมกิจ รีไซเคิล แคริเออร์ จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment6.'/> บริษัท ร่วมกิจ ออโตโมทีฟ ทรานสปอร์ต จำกัด</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" /> ผู้ขับขี่............................................................</td>
                                                </tr>
                                                <tr style="border:0px solid #000;">
                                                    <td style="border-right:0px solid #000;padding:0px;text-align:left;width: 60%">&nbsp;<input type="checkbox" style="font-size: 15pt;" '.$companypayment7.'/> อื่นๆ....................'.$companypaymentname7.'.....................</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>  
                    </table>';   
    // CheckifSet    
        $sql_seRepaircause_check = "SELECT RPC_SUBJECT_CON 'SUBJECT' FROM vwREPAIRREQUEST WHERE RPRQ_CODE='".$_GET['id']."'";
        $params_seRepaircause_check = array();
        $query_seRepaircause_check = sqlsrv_query($conn, $sql_seRepaircause_check, $params_seRepaircause_check); 
        $result_seRepaircause_check = sqlsrv_fetch_array($query_seRepaircause_check, SQLSRV_FETCH_ASSOC);      

        if($AREA=='AMT'){      
            if($result_seRepaircause_check['SUBJECT'] == 'เครื่องยนต์'){
                $boxname='สุริยา แน่นหนา';
            }else if($result_seRepaircause_check['SUBJECT'] == 'โครงสร้าง'){
                $boxname='อำนวย บุญมา ';
            }else if($result_seRepaircause_check['SUBJECT'] == 'ยาง ช่วงล่าง'){
                $boxname='เกียรติพงษ์ สดรัมย์';
            }else if($result_seRepaircause_check['SUBJECT'] == 'ระบบไฟ'){
                $boxname='วิชัย ประสานดี';
            }
            $boxname1='พงศ์ภัค มีอยู่สามเสน';
            $boxname2='บุรินชัย เบอร์ไธสง';
            $boxposition='RTD';
        }else{
            if($result_seRepaircause_check['SUBJECT'] == 'เครื่องยนต์'){
                $boxname='อาทิตย์ แสงสว่าง';
            }else if($result_seRepaircause_check['SUBJECT'] == 'โครงสร้าง'){
                $boxname='อาทิตย์ แสงสว่าง';
            }else if($result_seRepaircause_check['SUBJECT'] == 'ยาง ช่วงล่าง'){
                $boxname='สำนวน พรมพา ';
            }else if($result_seRepaircause_check['SUBJECT'] == 'ระบบไฟ'){
                $boxname='อาทิตย์ แสงสว่าง';
            }
            $boxname1='เติมพงศ์ กลิ่นนุช';
            $boxname2='อำนาจ อาจสู้ศึก ';
            $boxposition='RTD';
        }
    // CheckifSet                            
    $tb_section_bm_8 = '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:10;margin-top:30px;">
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">ลงชื่อ...........'.$boxname.'...........หัวหน้าช่าง</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ...........'.$boxname1.'...........เจ้าหน้าที่ SA</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:center;">ลงชื่อ...........'.$boxname2.'...........หัวหน้างาน</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname.'...........)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname1.'...........)</td>
                            <td style="border-right:0px solid #000;padding:8px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........'.$boxname2.'...........)</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แผนก...........'.$boxposition.'...........</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">&nbsp;</td>
                        </tr>
                        <tr style="border:0px solid #000;">
                            <td style="border-right:0px solid #000;padding:3px;text-align:left;">แก้ไขครั้งที่ : 01     มีผลบังคับใช้ : 10-06-66</td>
                        </tr>
                    </table>';

    $mpdf->WriteHTML($style_section_bm);
    $mpdf->WriteHTML($tb_section_bm_1);
    $mpdf->WriteHTML($tb_section_bm_2);
    $mpdf->WriteHTML($tb_section_bm_3);
    $mpdf->WriteHTML($tb_section_bm_4);
    $mpdf->WriteHTML($tb_section_bm_5);
    $mpdf->WriteHTML($tb_section_bm_6);
    $mpdf->WriteHTML($tb_section_bm_7);
    $mpdf->WriteHTML($tb_section_bm_8);
    // $mpdf->AddPage();
    $mpdf->Output();

}