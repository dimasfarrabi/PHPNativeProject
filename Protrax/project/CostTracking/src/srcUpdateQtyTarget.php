<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleCostTracking.php");
require_once("../Modules/ModuleTarget.php");

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
    $ValNewQtyTarget = htmlspecialchars(trim($_POST['ValNewQtyTarget']), ENT_QUOTES, "UTF-8");
    $ValDataID = htmlspecialchars(trim($_POST['ValDataID']), ENT_QUOTES, "UTF-8");
    $ValDataID2 = str_replace("ID","",base64_decode(base64_decode($ValDataID)));
    $ValNewQtyTarget = sprintf('%.2f',floatval($ValNewQtyTarget));
    # update data
    $ResBol = UPDATE_TARGET_QTY($ValDataID2,$ValNewQtyTarget,$linkMACHWebTrax);
    if($ResBol == "True")
    {
        ?>
        <script>
            $(document).ready(function () {
                var $row = $("#TableData tr .PointerList[data-datatoken='<?php echo $ValDataID; ?>']").closest('tr');
                $row.find("td:eq(4)").html('<?php echo $ValNewQtyTarget; ?>');   
                $("#ModalUpdateTarget").modal("hide");             
                $('#ModalUpdateTarget').on('hide.bs.modal', function () {
                    $("#Temporary").html("");
                });
            });
        </script>
        <?php
    }
    else
    {
        ?>
        <script>
            $(document).ready(function () {
                $("#TempProcess").html("Update failed!");
                $("#BtnEditTargetCost").attr('disabled', false);
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
