<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleLabourHour.php");

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
    $DataIDEnc = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $ValDataID = $DataIDEnc;
    $DataIDEnc = base64_decode(base64_decode($DataIDEnc));
    $DataID = str_replace("TokenID:","",$DataIDEnc);
    $ArrDataID = explode("#",$DataID);
    $Employee = trim($ArrDataID[0]);
    $ID = trim($ArrDataID[1]);    
    $InputTotal = htmlspecialchars(trim($_POST['InputTotal']), ENT_QUOTES, "UTF-8");
    $ValNewTotal = sprintf('%.3f',floatval($InputTotal));
    # update data 
    $ResBol = UPDATE_LABOUR_HOUR_BY_ID($ID,$InputTotal,$linkMACHWebTrax);
    if($ResBol == "TRUE")
    {
        ?>
        <script>
            $(document).ready(function () {
                var $row = $("#TableViewData tr .PointerList[data-datatoken='<?php echo $ValDataID; ?>']").closest('tr'); 
                $row.find("td:eq(6)").html('<?php echo $ValNewTotal; ?>');   
                $("#ModalUpdateLabourHour").modal("hide");             
                $('#ModalUpdateLabourHour').on('hide.bs.modal', function () {
                    $("#TemporarySpace").html("");
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
                $("#BtnEditLabourHour").attr('disabled', false);
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
