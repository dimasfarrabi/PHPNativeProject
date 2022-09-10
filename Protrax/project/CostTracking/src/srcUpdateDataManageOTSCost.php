<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleOTSCost.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
 
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $DataID = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $DataQtyUsage = htmlspecialchars(trim($_POST['DataQtyUsage']), ENT_QUOTES, "UTF-8");
    $DataTotalCost = htmlspecialchars(trim($_POST['DataTotalCost']), ENT_QUOTES, "UTF-8");
    $DataID2 = $DataID;
    $DataID = base64_decode(base64_decode($DataID));
    $ArrDataID = explode("#",$DataID);
    # update data
    $ResUpdate = UPDATE_OTS_COST_BY_ID($ArrDataID[4],$DataQtyUsage,$DataTotalCost,$linkMACHWebTrax);
    if($ResUpdate == "TRUE")
    {    
        $ValQtyUsage = sprintf('%.0f',floatval(trim($DataQtyUsage)));
        $ValTotalCost = sprintf('%.2f',floatval(trim($DataTotalCost)));
        ?>
            <script>
                $(document).ready(function () {
                    var $row = $("#TableViewData tr .PointerList[data-datatoken='<?php echo $DataID2; ?>']").closest('tr');
                    $row.find("td:eq(9)").html('<?php echo $ValQtyUsage; ?>');   
                    $row.find("td:eq(10)").html('<?php echo $ValTotalCost; ?>');
                    $("#ModalUpdateOTSCost").modal("hide");
                    $("#TemporarySpace").html("");
                });
            </script>
        <?php
    }  
    else
    {
        ?>
        <div class="alert alert-danger fw-bold" role="alert">Data gagal diupdate!</div>
        <?php
    }
}
else
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();  
}
?>
