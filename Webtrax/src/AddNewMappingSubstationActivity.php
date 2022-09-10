<?php
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
require("../../src/srcConnect.php");
require("../../src/srcProcessFunction.php");
require("../../src/srcFunction.php");
require("Modules/ModuleLogin.php");
require("Modules/ModuleMappingSubstation.php");
date_default_timezone_set("Asia/Jakarta");
# data session
$EmployeeID = base64_decode(base64_decode($_SESSION['UIDWebTrax']));

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValProduct = htmlspecialchars(trim($_POST['ValProduct']), ENT_QUOTES, "UTF-8");
    $ValSubstation = htmlspecialchars(trim($_POST['ValSubstation']), ENT_QUOTES, "UTF-8");
    $ValAction = htmlspecialchars(trim($_POST['ValAction']), ENT_QUOTES, "UTF-8");
    $ValAction = strtolower($ValAction);
    if($ValAction == "simpan")
    {
        $EncValSubstation = base64_decode(base64_decode($ValSubstation));
        $ArraySubstation = explode("*",$EncValSubstation);
        $MappingSubstationActivityID = $ArraySubstation[0];
        $ValProductRes = $ArraySubstation[1];
        $ValSubstationRes = $ArraySubstation[2];
        $ValSubstationActivityRes = $ArraySubstation[3];
        # simpan data
        ADD_NEW_USER_MAPPING_SUBSTATION($EmployeeID,$MappingSubstationActivityID,$linkMACHWebTrax);
        ?>
        <script>
        $(document).ready(function () {
            $('#TableDataMappingSubstation').find('tr:eq(2)').find('td:eq(2)').html("<?php echo $ValProductRes; ?>");
            $('#TableDataMappingSubstation').find('tr:eq(3)').find('td:eq(2)').html("<?php echo $ValSubstationRes; ?>");
            $('#TableDataMappingSubstation').find('tr:eq(4)').find('td:eq(2)').html("<?php echo $ValSubstationActivityRes; ?>");
            $("#BtnSimpanMappingSubstation").text('Update');
        });
        </script>
        <?php
    }
    if($ValAction == "update")
    {
        $EncValSubstation = base64_decode(base64_decode($ValSubstation));
        $ArraySubstation = explode("*",$EncValSubstation);
        $MappingSubstationActivityID = $ArraySubstation[0];
        $ValProductRes = $ArraySubstation[1];
        $ValSubstationRes = $ArraySubstation[2];
        $ValSubstationActivityRes = $ArraySubstation[3];
        # close data
        DISABLE_USER_MAPPING_SUBSTATION($EmployeeID,$linkMACHWebTrax);
        # simpan data
        ADD_NEW_USER_MAPPING_SUBSTATION($EmployeeID,$MappingSubstationActivityID,$linkMACHWebTrax);
        ?>
        <script>
        $(document).ready(function () {
            $('#TableDataMappingSubstation').find('tr:eq(2)').find('td:eq(2)').html("<?php echo $ValProductRes; ?>");
            $('#TableDataMappingSubstation').find('tr:eq(3)').find('td:eq(2)').html("<?php echo $ValSubstationRes; ?>");
            $('#TableDataMappingSubstation').find('tr:eq(4)').find('td:eq(2)').html("<?php echo $ValSubstationActivityRes; ?>");
            $("#BtnSimpanMappingSubstation").text('Update');
        });
        </script>
        <?php
    }
}
else
{
    echo "Anda tidak mempunyai hak akses!";
}
?>
