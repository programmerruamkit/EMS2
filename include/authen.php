<?php 
    require 'connect.php' ; 
    if( !isset($_SESSION['AD_ID'] ) ){
        echo "<script type='text/javascript'>";
        // echo "alert('กรุณาเข้าสู่ระบบด้วยค่ะ');";
        echo "window.location = '../login'; ";
        echo "</script>"; 
        // header('Location: ../../login.php'); 
    }
?>