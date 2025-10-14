<?php
        $RPSPP_LASTSPARE="22/12/2023";
        $convertdate=str_replace('/', '-', $RPSPP_LASTSPARE);
        $convert_last=date('d/m/Y', strtotime($convertdate));
        $convert_1years = date('d-m-Y', strtotime($convertdate."+1 years"));
        $convert_1month = date('d-m-Y', strtotime($convertdate."+1 month"));
        $convert_1yearsmonth = date('d-m-Y', strtotime($convertdate."+1 years"."+6 month"));
        // echo "วันนี้ ".$convert_last;
        // echo "<br/>";
        // echo "บวก 1 ปี คือวันที่ ".$convert_1years;
        // echo "<br/>";
        // echo "บวก 1 เดือน คือวันที่ ".$convert_1month;
        // echo "<br/>";
        // echo "บวก 1 ปีเดือน คือวันที่ ".$convert_1yearsmonth;
        // echo "<br/>";
        
        // $var = '20/04/2012';
        // $date = str_replace('/', '-', $var);
        // echo date('Y-m-d', strtotime($date));
        // $now = date("Y-m-d");
        // $date_1years = date('Y-m-d',strtotime($now . "+1 years"));
        // echo "วันนี้ ".$now;
        // echo "<br/>";
        // echo "บวก 1 ปี คือวันที่ ".$date_1years;
?>