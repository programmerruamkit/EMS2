<!--JS Window8-->
<script type="text/javascript" src="<?=$path;?>js/modern/dropdown.js"></script>
<?php
  $AD_ROLE_NAME=$_SESSION['AD_ROLE_NAME'];
  $AD_RU_IMG=$_SESSION['AD_RU_IMG'];
  
  $useragent = $_SERVER['HTTP_USER_AGENT'];
  if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
  { 
    $width0 = "0%";
    $width1 = "0%";
    $width2 = "0%";
    $width3 = "0%";
    $width4 = "0%";
    $width5 = "0%";
    $width6 = "0%";
  }else{
    $width0 = "1%";
    $width1 = "3%";
    $width2 = "28%";
    $width3 = "40%";
    $width4 = "2%";
    $width5 = "20%";
    $width6 = "5%";
  }
?>
<table class="comtop fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr" width="100%" border="0"  cellpadding="0" cellspacing="0">
 <tr>
    <td width="<?=$width0;?>">&nbsp;</td>
    <td width="<?=$width1;?>"><img src="../images/car_repair.png" width="48" height="48" /></td>
    <td width="<?=$width2;?>">&nbsp;<strong><font size="3px"><?=$title;?> - <?php print $_SESSION['AD_AREA']; ?></font></strong></td>
    <td width="<?=$width3;?>">&nbsp;</td>
    <td width="<?=$width4;?>"><img src="<?=$AD_RU_IMG;?>" width="35" height="35" /></td>
    <td width="<?=$width5;?>">
      <div class="dropdown">&nbsp;&nbsp;<strong><?php print $_SESSION['AD_NAMETHAI']; ?></strong> | 
        <a type="button" data-bs-toggle="dropdown" style="cursor:pointer"><strong><?php print $_SESSION['AD_ROLE_NAME']; ?></strong><img src="../images/dropdrown_new.png" width="15px"></a>
        <ul class="dropdown-menu">              
          <?php
            $sql_login = "SELECT * FROM vwUSERLOGIN WHERE RA_USERNAME = ? AND RA_PASSWORD = ? AND ROACTIVE = 'Y' AND ROACACTIVE = 'Y' AND AREA = ? ORDER BY RU_NAME ASC";
            $parameters = [$_SESSION['AD_ROLEACCOUNT_USERNAME'],$_SESSION['AD_ROLEACCOUNT_PASSWORD'],$_SESSION["AD_AREA"]];
            $query_login = sqlsrv_query($conn, $sql_login, $parameters);
            $no=1;
            while($result_login = sqlsrv_fetch_array($query_login, SQLSRV_FETCH_ASSOC)){
                $AD_RA_ID = $result_login["RA_ID"];
                $AD_ROLE_ID = $result_login["RU_ID"];
                $AD_ROLE_NAME = $result_login["RU_NAME"];
                $AD_AREA = $result_login["AREA"];	                    
          ?>
            <a href="javascript:void(0);" onclick="role_session('<?=$_SESSION['AD_ROLEACCOUNT_USERNAME'];?>','<?=$_SESSION['AD_ROLEACCOUNT_PASSWORD'];?>',<?=$AD_ROLE_ID;?>,<?=$AD_RA_ID;?>)">
              <li>
                <font color="black" size="2px"><b><i class="gg-user"></i>&nbsp;&nbsp;&nbsp;<?=$AD_ROLE_NAME?> - <?=$AD_AREA?></b></font>
              </li>
              <div class="dropdown-divider"></div>
            </a>
          <?php $no++; } ?>
        </ul>
      </div>
    </td>
    <td width="<?=$width6;?>">
    <a href="<?=$path;?>main/main_menu.php?menu_id=14" style="cursor:pointer"><img src="../images/cog.png" width="20px"></a>
      <!-- <div class="dropdown">
        <a type="button" data-bs-toggle="dropdown" style="cursor:pointer"><img src="../images/cog-dropdrown.png"
            width="30px"></a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="javascript:void(0);" onclick="log_outsession('<?=$_SESSION['AD_PERSONCODE'];?>','LA2')">
              <h4>
                <font color="black"><i class="gg-log-out"></i>&nbsp;&nbsp;&nbsp;ออกจากระบบ</font>
              </h4>
            </a></li>
          <div class="dropdown-divider"></div>
          <li><?php 
                $sql_repass = "SELECT * FROM MENU WHERE MN_CODE = 'MN_SETTING'";
                $query_repass = sqlsrv_query($conn, $sql_repass);
                while($result_repass = sqlsrv_fetch_array($query_repass, SQLSRV_FETCH_ASSOC)){ 
              ?>
              <a class="dropdown-item" href="<?=$path;?>main/main_menu.php?menu_id=<?=$result_repass["MN_ID"]?>">
                <h4>
                  <font color="black"><i class="gg-sync"></i>&nbsp;&nbsp;&nbsp;เปลี่ยนรหัสผ่าน</font>
                </h4>
              </a>
              <?php } ?>
          </li>
        </ul>
      </div> -->
    </td>
  </tr>
</table> 
<div class="topnav bg-color-grayDark" id="myTopnav">
<div id="container">
  <div id="left">
      <a href="<?=$path;?>manage/dashboard.php?menu_id=dashboard" style=" <?=($_GET["menu_id"]=='dashboard')?'color:#0CF;':'' /*text-decoration:underline;*/?>">Dashboard</a>
      <span class="divider"></span>
      <?php 
        $SS_AREA=$_SESSION["AD_AREA"];
        $SS_ROLENAME=$_SESSION["AD_ROLE_NAME"];
        $sql_sidemenu = "SELECT * FROM vwMENU  WHERE MN_LEVEL = '1' AND MN_STATUS = 'Y' AND RM_STATUS = 'Y' AND AREA = '$SS_AREA' AND RU_NAME = '$SS_ROLENAME' ORDER BY MN_SORT ASC";
        $query_sidemenu = sqlsrv_query($conn, $sql_sidemenu);
        while($result_sidemenu = sqlsrv_fetch_array($query_sidemenu, SQLSRV_FETCH_ASSOC)){ 
      ?>
        <a href="<?=$path;?>main/main_menu.php?menu_id=<?=$result_sidemenu["MN_ID"]?>" style="<?=($_GET["menu_id"]==$result_sidemenu["MN_ID"])?'color:#0CF;':''?>"><?=$result_sidemenu["MN_NAME"]?>
          <!-- AMT -->
          <?php 
            if($result_sidemenu["MN_ID"]===26){ 
              echo '<span id="checksidebmpm"></span>'; 
            } 
            if($result_sidemenu["MN_ID"]===28){ 
              echo '<span id="checksideassign"></span>'; 
            }
            if($result_sidemenu["MN_ID"]===32){ 
              echo '<span id="checksidework"></span>';
            } 
          ?>
          <!-- GW -->
          <?php 
            if($result_sidemenu["MN_ID"]===62){ 
              echo '<span id="checksidebmpm"></span>';
            } 
            if($result_sidemenu["MN_ID"]===64){ 
              echo '<span id="checksideassign"></span>';
            }
            if($result_sidemenu["MN_ID"]===68){ 
              echo '<span id="checksidework"></span>';
            } 
          ?>
        </a>   
        <span class="divider"></span>
      <?php } ?>
    </div>
      <a href="javascript:void(0);" class="icon" onclick="topmenu()"><b><img src="../images/menu2.png" width="25px"></b></a>
    <div id="right">
      <a href="javascript:void(0);" onclick="log_outsession('<?=$_SESSION['AD_PERSONCODE'];?>','LA2')">ออกจากระบบ</a>
    </div>
</div>
</div>
<style>
  #container {
      width:100%;
      text-align:center;
  }
  #left {
      float:left;
      width:90%;
      /* height: 20px; */
      /* background: #ff0000; */
  }
  #right {
      float:right;
      width:10%;
      /* height: 20px; */
      /* background: #0000ff; */
  }
  /* log-out */
    .gg-log-out {
      box-sizing: border-box;
      position: absolute;
      display: block;
      width: 4px;
      height: 14px;
      text-align: center;
      border: 2px solid;
      transform: scale(var(--ggs, 1));
      border-right: 0;
      border-top-left-radius: 2px;
      border-bottom-left-radius: 2px;
      margin-left: -10px
    }
    .gg-log-out::after,
    .gg-log-out::before {
      content: "";
      display: block;
      box-sizing: border-box;
      position: absolute
    }
    .gg-log-out::after {
      border-top: 2px solid;
      border-left: 2px solid;
      transform: rotate(-45deg);
      width: 8px;
      height: 8px;
      left: 4px;
      bottom: 2px
    }
    .gg-log-out::before {
      border-radius: 3px;
      width: 10px;
      height: 2px;
      background: currentColor;
      left: 5px;
      bottom: 5px
    }

  /* sync */
    .gg-sync {
      box-sizing: border-box;
      position: absolute;
      display: block;
      width: 14px;
      height: 14px;
      text-align: center;
      border: 2px solid;
      border-radius: 40px;
      transform: scale(var(--ggs, 1));
      border-left-color: transparent;
      border-right-color: transparent;
      margin-left: -10px
    }
    .gg-sync::after,
    .gg-sync::before {
      content: "";
      display: block;
      box-sizing: border-box;
      position: absolute;
      width: 0;
      height: 0;
      border-top: 4px solid transparent;
      border-bottom: 4px solid transparent;
      transform: rotate(-45deg)
    }
    .gg-sync::before {
      border-left: 6px solid;
      bottom: -1px;
      right: -3px
    }
    .gg-sync::after {
      border-right: 6px solid;
      top: -1px;
      left: -3px
    }
  /* user */
    .gg-user {
      box-sizing: border-box;
      position: absolute;
      display: block;
      width: 14px;
      height: 14px;
      text-align: center;
      margin-left: -10px
    }

    .gg-user::after,
    .gg-user::before {
    content: "";
    display: block;
    box-sizing: border-box;
    position: absolute;
    border: 2px solid
    }

    .gg-user::before {
    width: 8px;
    height: 8px;
    border-radius: 30px;
    top: 0;
    left: 2px
    }

    .gg-user::after {
    width: 12px;
    height: 9px;
    border-bottom: 0;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    top: 9px
    } 
</style>
