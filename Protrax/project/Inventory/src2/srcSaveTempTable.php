<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleStockOpname.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $Header = (htmlspecialchars(trim($_POST['Header']), ENT_QUOTES, "UTF-8"));
    $Body = (htmlspecialchars(trim($_POST['arr']), ENT_QUOTES, "UTF-8"));
    $arr1 = explode("*",$Header);
    $LocEnc = base64_decode(base64_decode($arr1[0]));
    $arrLoc = explode(":",$LocEnc);
    $Company = $arrLoc[1];
    $Stock = $arr1[1];
    $Gudang = $arr1[2];
    $PIC = $arr1[3];
    $DateOpname = $arr1[4];
    $FormID = $arr1[5];
    $arr2 = explode(",",$Body);
    $arrMain = array();
    foreach($arr2 as $arrTemp)
    {
        $arr3 = explode("*",$arrTemp);
        $PartNo = $arr3[0];
        $ActualQty = $arr3[1];
        $IsAdjust = $arr3[2];
        $Temporary = array("Company" => $Company, "Stock" => $Stock, "Gudang" => $Gudang, "PIC" => $PIC, "DateOpname" => $DateOpname, "FormID" => $FormID,
        "PartNo" => $PartNo,"ActualQty" => $ActualQty, "IsAdjust" => $IsAdjust);
        array_push($arrMain,$Temporary);
    }
    foreach($arrMain as $main)
    {
        $ValCompany = trim($main['Company']);
        $ValStock = trim($main['Stock']);
        $ValGudang = trim($main['Gudang']);
        $ValPIC = trim($main['PIC']);
        $ValDate = trim($main['DateOpname']);
        $ValForm = trim($main['FormID']);
        $ValPartNo = trim($main['PartNo']);
        $ValActualQty = trim($main['ActualQty']);
        $ValAdjust = trim($main['IsAdjust']);
        if($ValAdjust == 'on')
        {
            $adj = "adj";
        }
        else
        {
            $adj = "";
        }
        $UpdateSO = UPDATE_SO_BY_PART($ValPartNo,$ValForm,$ValActualQty,$adj,$ValStock,$linkMACHWebTrax);
    }
    if($UpdateSO == 'TRUE')
    {
        echo '<div class="alert alert-success fw-bold" id="SuccessBar" role="alert">Success</div>';
        $ValComp = base64_encode($ValCompany);
        $ValBatch = base64_encode($ValForm);
        ?>
        <script language="javascript">
            var Par1 = '<?php echo $ValComp; ?>';
            var Par2 = '<?php echo $ValBatch; ?>';
            window.location.href = 'project/Inventory/src2/DownloadStockOpname.php?Loc='+Par1+'&&Batch='+Par2;
        </script>
        <?php
    }
    else
    {
        echo '<div class="alert alert-danger fw-bold" id="dangerBar" role="alert">Failed to save!</div>';
    }
    
}
    
?>
<script language="javascript">
setTimeout(myFunction, 400);
function myFunction()
{
    $("#SuccessBar").hide();
    $("#dangerBar").hide();
    window.location.replace("http://localhost/protrax/home.php?link=41");
}
</script>