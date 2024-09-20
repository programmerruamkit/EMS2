<?php 
$path='../';
require($path."include/connect.php");
require($path."include/authen.php"); 
require($path."include/head.php");		
require($path."include/script.php"); 
// print_r ($_SESSION);
?>

<body>
    <div class="bg-image"></div>
    <div class="bg-text">        
        <div class="login">
            <?php
                $sql_login = "SELECT * FROM vwUSERLOGIN WHERE RA_USERNAME = ? AND RA_PASSWORD = ? AND ROACTIVE = 'Y' AND ROACACTIVE = 'Y' AND AREA = ?";
                $parameters = [$_SESSION['AD_ROLEACCOUNT_USERNAME'],$_SESSION['AD_ROLEACCOUNT_PASSWORD'],$_SESSION["AD_AREA"]];
                $query_login = sqlsrv_query($conn, $sql_login, $parameters);
                $no=1;
                while($result_login = sqlsrv_fetch_array($query_login, SQLSRV_FETCH_ASSOC)){
                    $AD_RA_ID = $result_login["RA_ID"];
                    $AD_ROLE_ID = $result_login["RU_ID"];
                    $AD_ROLE_NAME = $result_login["RU_NAME"];	                    
            ?>
            <a href="javascript:void(0);" onclick="role_session('<?=$_SESSION['AD_ROLEACCOUNT_USERNAME'];?>','<?=$_SESSION['AD_ROLEACCOUNT_PASSWORD'];?>',<?=$AD_ROLE_ID;?>,<?=$AD_RA_ID;?>)">
                <div class="box" style="cursor:pointer">
                    <div class="ribbon"><span>&nbsp;</span></div>
                    <h1><?=$AD_ROLE_NAME?></h1>
                </div>
            </a>
            <?php $no++; } ?>
        </div>
    </div>
    <!-- <br> -->
    <b><font color="red" class="textdest" size="4px">&nbsp;&nbsp;&nbsp;&nbsp;หมายเหตุ: ให้คลิกเลือกสิทธิ์การใช้งาน เพื่อเข้าใช้งานตามสิทธิ์ที่เลือก เช่น Driver, Admin, Manager เป็นต้น</font></b>
    
    <style>
        .bg-image {
            background-image: url("https://img2.pic.in.th/pic/bgrole.jpeg");
            filter: blur(20px);
            -webkit-filter: blur(20px);
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .bg-text {
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/opacity/see-through */
            color: white;
            font-weight: bold;
            border: 0px solid #f1f1f1;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            padding: 20px;
            text-align: center;
        }
        .textdest {
            color: white;
            font-weight: bold;
            position: absolute;
            top: 90%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            /* height: 100%; */
            /* padding: 300px; */
            text-align: center;
            text-shadow: 1px 1px 2px black, 0 0 25px red, 0 0 5px red;
        }
        @import url(https://fonts.googleapis.com/css?family=Lato:700);
        .box {
            width: 480px;
            height: 120px;
            position: relative;
            background-image: url("https://img2.pic.in.th/pic/1636744759075.png");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            box-shadow: 0 0 15px rgba(0, 0, 0, .1);
            border-radius: 30px;
            float: left;
            margin: 20px;
            word-wrap: break-word;
        }        
        .ribbon {
            position: absolute;
            right: -5px;
            top: -5px;
            z-index: 1;
            overflow: hidden;
            width: 100px;
            height: 100px;
            text-align: right;
        }
        .ribbon span {
            font-size: 0.8rem;
            color: #fff;
            text-transform: uppercase;
            text-align: center;
            font-weight: bold;
            line-height: 32px;
            transform: rotate(45deg);
            width: 125px;
            display: block;
            background: #ffff00;
            background: linear-gradient(#ffff56 0%, #ffff00 100%);
            box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
            position: absolute;
            top: 17px; // change this, if no border
            right: -29px; // change this, if no border
        }
        .ribbon span::before {
            content: '';
            position: absolute; 
            left: 0px; top: 100%;
            z-index: -1;
            border-left: 3px solid #FFFF00;
            border-right: 3px solid transparent;
            border-bottom: 3px solid transparent;
            border-top: 3px solid #FFFF00;
        }
        .ribbon span::after {
            content: '';
            position: absolute; 
            right: 0%; top: 100%;
            z-index: -1;
            border-right: 3px solid #FFFF00;
            border-left: 3px solid transparent;
            border-bottom: 3px solid transparent;
            border-top: 3px solid #FFFF00;
        }
        h1 {
            padding: 50px 0;
            margin-left: auto;
            margin-right: auto;
            color: white;
            font-weight: bold;
            text-align: center;
            /* word-wrap: break-word; */
            font-size: 400%;
        }
    </style>