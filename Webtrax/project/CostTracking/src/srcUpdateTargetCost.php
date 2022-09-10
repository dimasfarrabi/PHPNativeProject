<?php
require_once("../../../src/Modules/ModuleLogin.php");
require("../../../../src/srcProcessFunction.php");
require_once("../Modules/ModuleTarget.php");
date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
 
session_start();
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValNewTargetCost = htmlspecialchars(trim($_POST['ValNewTargetCost']), ENT_QUOTES, "UTF-8");
    $ValDataID = htmlspecialchars(trim($_POST['ValDataID']), ENT_QUOTES, "UTF-8");
    $ValDataID2 = str_replace("ID","",base64_decode(base64_decode($ValDataID)));
    $ValNewTargetCost = sprintf('%.2f',floatval($ValNewTargetCost));
    # update data
    $ResBol = UPDATE_TARGET_COST($ValDataID2,$ValNewTargetCost,$linkMACHWebTrax);
    if($ResBol == "True")
    {
        ?>
        <script>
            $(document).ready(function () {
                var $row = $("#TableData tr .PointerList[data-datatoken='<?php echo $ValDataID; ?>']").closest('tr');
                $row.find("td:eq(5)").html('<?php echo $ValNewTargetCost; ?>');   
                $("#ModalUpdateTarget").modal("hide");             
                $('#ModalUpdateTarget').on('hide.bs.modal', function () {
                    $("#Temporary").html("");
                })
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
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();  
}
?>
