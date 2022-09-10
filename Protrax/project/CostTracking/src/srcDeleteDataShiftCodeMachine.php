<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleShiftCodeMachine.php");

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
    $Location = htmlspecialchars(trim($_POST['Location']), ENT_QUOTES, "UTF-8");
    $DataID2 = $DataID;
    $DataID = base64_decode(base64_decode($DataID));
    $DataID = str_replace("DataToken","",$DataID);
    
    if($Location == "SEMARANG")
    {
        $ResDel = DELETE_SHIFTCODE_MACHINE_PSM($DataID);
        if($ResDel == "TRUE")
        {
            ?>
                <script>
                    $(document).ready(function () {
                        var XX = $("#TableShiftCode tr .PointerList[data-token='<?php echo $DataID2; ?>']").closest('tr');
                        $("#TableShiftCode").DataTable().row(XX).remove().draw();
                        $('#ResultPage').html("");
                    });
                </script>
            <?php
        }
        else
        {
            ?>
                <script>
                    $(document).ready(function () {
                        alert("Data gagal dihapus!");
                        $('#ResultPage').html("");
                    });
                </script>
            <?php
        }
    }
    else
    {
        $ResDel = DELETE_SHIFTCODE_MACHINE($DataID,$linkMACHWebTrax);
        if($ResDel == "TRUE")
        {
            ?>
                <script>
                    $(document).ready(function () {
                        var XX = $("#TableShiftCode tr .PointerList[data-token='<?php echo $DataID2; ?>']").closest('tr');
                        $("#TableShiftCode").DataTable().row(XX).remove().draw();
                        $('#ResultPage').html("");
                    });
                </script>
            <?php
        }
        else
        {
            ?>
                <script>
                    $(document).ready(function () {
                        alert("Data gagal dihapus!");
                        $('#ResultPage').html("");
                    });
                </script>
            <?php
        }
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
