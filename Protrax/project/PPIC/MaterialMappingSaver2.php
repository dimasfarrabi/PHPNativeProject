<?php
require_once("../../ConfigDB.php");
require_once("Modules/ModuleNewBCPartJob.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $Data = htmlspecialchars(trim($_POST['Data']), ENT_QUOTES, "UTF-8");
    $Machine = htmlspecialchars(trim($_POST['Machine']), ENT_QUOTES, "UTF-8");
    // echo "$Data >> $Machine";
    $arr1 = explode(",",$Data);
    $arr2 = explode("*",$Machine);
    $MachineCode = $arr2[1];
    foreach($arr1 as $ValWO)
    {
        // echo $Val1."<br>";
        $UpdateData = UPDATE_MATERIAL_MAPPING($MachineCode,$ValWO,$linkMACHWebTrax);
    }

}
?>
<script language="javascript">
        alert('Mapping Saved!');
        window.location.replace("http://localhost/protrax/home.php?link=28");
</script>