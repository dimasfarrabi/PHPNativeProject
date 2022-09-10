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
    $DataID2 = $DataID;
    $DataID = base64_decode(base64_decode($DataID));
    $ArrDataID = explode("#",$DataID);
    # delete data 
    $ResDel = DELETE_DATA_OTS_COST($ArrDataID[4],$linkMACHWebTrax);
    if($ResDel == "TRUE")
    {
        ?>
            <script>
                $(document).ready(function () {
                    var XX = $("#TableViewData tr .PointerList[data-datatoken='<?php echo $DataID2; ?>']").closest('tr');
                    $("#TableViewData").DataTable().row(XX).remove().draw();
                    $('#TemporarySpace').html("");
                });
            </script>
        <?php
    }
    else {
        ?>
            <script>
                $(document).ready(function () {
                    alert("Data gagal dihapus!");
                    $('#TemporarySpace').html("");
                });
            </script>
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
