<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleInOut.php");
/*
date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
 
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
$FullName = "LOCAL - DIMAS FARRABI";
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $Company = htmlspecialchars(trim($_POST['Company']), ENT_QUOTES, "UTF-8");
    $ProsesOUT = htmlspecialchars(trim($_POST['ProsesOUT']), ENT_QUOTES, "UTF-8");
    $ProsesIN = htmlspecialchars(trim($_POST['ProsesIN']), ENT_QUOTES, "UTF-8");
    $Barcode = htmlspecialchars(trim($_POST['Barcode']), ENT_QUOTES, "UTF-8");
    $cek = CEK_DATA($Barcode,$ProsesOUT,$linkMACHWebTrax);
    if(sqlsrv_num_rows($cek) > 0){
        $Param = "FALSE2";
        $ValPartNo = "-";
        $ValQty = "-";
    }
    else{
        $PartInfo = GET_PART_INFO_FROM_MAIN($Barcode,$linkMACHWebTrax);
        $data = GET_PART_INFO_FROM_BIN($Barcode,$ProsesOUT,$linkMACHWebTrax);
        $Param = "FALSE1";
        $ValPartNo = "-";
        $ValQty = "-";
        if($ProsesOUT == 'NEW'){
            while($res=sqlsrv_fetch_array($PartInfo))
            {
                $ValPartNo = trim($res['PartNo']);
                $ValQty = trim($res['Qty']);
                $Param = "TRUE";
            }
        }
        else{
            while($res2=sqlsrv_fetch_array($data)){
                $ValPartNo = trim($res2['PartNo']);
                $ValQty = trim($res2['Qty']);
                $Param = "TRUE";
            }
        }
        if($Param == 'TRUE'){
            $insert = INSERT_OUT_LOG($Company,$ProsesOUT,$Barcode,$ValPartNo,$ValQty,$FullName,$linkMACHWebTrax);
            $insert2 = INSERT_IN_LOG($Company,$ProsesIN,$Barcode,$ValPartNo,$ValQty,$FullName,$linkMACHWebTrax);
        }
    }
    
    echo $Param.":".$ValPartNo."*".$ValQty."*".$Barcode;
}
?>