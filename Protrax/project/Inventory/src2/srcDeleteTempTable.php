<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleStockOpname.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = (htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $PartNo = $ValCodeDec;
    $Delete = DELETE_TEMP_TABLE($PartNo,$linkMACHWebTrax);
    if($Delete == 'FALSE')
    {
        echo '<div class="alert alert-danger fw-bold" id="DangerBar2" role="alert">Delete Failed</div>';
    }
    else
    { }
}
?>
<script language="javascript">
setTimeout(myFunction, 300);
function myFunction()
{
    var XX = '<?php echo $PartNo; ?>';
    $("#FormSO tr[data-erows='" + XX + "']").remove();
    $('#DeleteTemp').modal('hide');
}
</script>