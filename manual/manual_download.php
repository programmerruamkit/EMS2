<?php
	session_start ();
	$path='../';
	require($path."include/head.php");		
	require($path."include/script.php"); 
?>

<body>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="INVENDATA no-border">
    <tr class="TOP">
      <td class="LEFT"></td>
      <td class="CENTER">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="24" valign="middle" class=""><span class="Normaltxtbold"><img src="<?=$path?>images/pdf-icon-150x150.png" width="35" height="35"></span></td>
            <td valign="bottom" class="">
              <h4>&nbsp;ดาวน์โหลดคู่มือผู้ใช้</h4>
            </td>
            <td width="80%" align="right" valign="bottom" class="" nowrap>
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
        <form id="form1" name="form1" method="post" action="#">
          <table width="100%" cellpadding="0" cellspacing="0" border="0" class="striped hover pointer display" id="datatable">
            <thead>
              <tr height="30">
                <!-- <th width="50">ลำดับ</th>
                <th width="250">ชื่อระบบ</th>
                <th width="250">ชื่อโครงการ</th> -->
                <th width="200">สิทธิ์การเข้าใช้งาน</th>
                <th width="200">ดาวโหลดคู่มือ PC</th>
                <th width="200">ดาวโหลดคู่มือ MOBILE</th>
              </tr>
            </thead>
            <tbody>
              <!-- <tr height="50px" align="center">
                <td align="center">1</td>
                <td align="right">ระบบแจ้งซ่อม</td>
                <td align="right">E-Maintenance Phase 2</td>
                <td align="right">ADMIN</td>
                <td align="center"><span>&emsp;อยู่ระหว่างจัดทำ</span></td>
                <td align="center"></td>
              </tr> -->
              <tr height="50px" align="center">
                <!-- <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td> -->
                <td align="right">MAINTENANCE(SA)</td>
                <td align="center">
                  <a href="./document/คู่มือผู้ใช้สิทธิ์ SA.pdf" target="_blank">
                    <span>
                      <img title="pdf" src="<?=$path?>images/pdf-icon-150x150.png" width="35" height="35">
                    </span><span>&emsp;(อัพเดทล่าสุด 24/08/2567)</span>
                  </a>
                </td>
                <td align="center"></td>
              </tr>
              <tr height="50px" align="center">
                <!-- <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td> -->
                <td align="right">PLANNING</td>
                <td align="center">
                  <a href="./document/คู่มือผู้ใช้สิทธิ์ PLANNING.pdf" target="_blank">
                    <span>
                      <img title="pdf" src="<?=$path?>images/pdf-icon-150x150.png" width="35" height="35">
                    </span><span>&emsp;(อัพเดทล่าสุด 24/08/2567)</span>
                  </a>
                </td>
                <td align="center"></td>
              </tr>
              <tr height="50px" align="center">
                <!-- <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td> -->
                <td align="right">TENKO</td>
                <td align="center">
                  <a href="./document/คู่มือผู้ใช้สิทธิ์ TENKO.pdf" target="_blank">
                    <span>
                      <img title="pdf" src="<?=$path?>images/pdf-icon-150x150.png" width="35" height="35">
                    </span><span>&emsp;(อัพเดทล่าสุด 24/08/2567)</span>
                  </a>
                </td>
                <td align="center"></td>
              </tr>
              <tr height="50px" align="center">
                <!-- <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td> -->
                <td align="right">DRIVER</td>
                <td align="center">
                  <a href="./document/คู่มือผู้ใช้สิทธิ์ DRIVER PC.pdf" target="_blank">
                    <span>
                      <img title="pdf" src="<?=$path?>images/pdf-icon-150x150.png" width="35" height="35">
                    </span><span>&emsp;(อัพเดทล่าสุด 24/08/2567)</span>
                  </a>
                </td>
                <td align="center">
                  <a href="./document/คู่มือผู้ใช้สิทธิ์ DRIVER MOBILE&TABLET.pdf" target="_blank">
                    <span>
                      <img title="pdf" src="<?=$path?>images/pdf-icon-150x150.png" width="35" height="35">
                    </span><span>&emsp;(อัพเดทล่าสุด 04/09/2567)</span>
                  </a>
                </td>
              </tr>
              <tr height="50px" align="center">
                <!-- <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td> -->
                <td align="right">ช่างซ่อมบำรุง</td>
                <td align="center">
                  <a href="./document/คู่มือผู้ใช้สิทธิ์ ช่างซ่อมบำรุง PC.pdf" target="_blank">
                    <span>
                      <img title="pdf" src="<?=$path?>images/pdf-icon-150x150.png" width="35" height="35">
                    </span><span>&emsp;(อัพเดทล่าสุด 24/08/2567)</span>
                  </a>
                </td>
                <td align="center">
                  <a href="./document/คู่มือผู้ใช้สิทธิ์ ช่างซ่อมบำรุง MOBILE&TABLET.pdf" target="_blank">
                    <span>
                      <img title="pdf" src="<?=$path?>images/pdf-icon-150x150.png" width="35" height="35">
                    </span><span>&emsp;(อัพเดทล่าสุด 24/08/2567)</span>
                  </a>
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
      <td class="CENTER">
        <div class="row input-control"><br><br>
          <button  title="Excel" type="button" class="bg-color-red big" onclick="javascript:window.close('','_parent','');"><font color="white" size="4">ปิดหน้าต่าง</font></button>
        </div>
      </td>
      <td class="RIGHT">&nbsp;</td>
    </tr>
  </table>
  <style>
    img {
      vertical-align: middle;
      display: table-cell;
    }
    span {
      vertical-align: middle;
      display: table-cell;
      }
  </style>
</body>
</html>