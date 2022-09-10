<?php
require_once("../../ConfigDB.php");
require_once("Modules/ModuleNewBCPartJob.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $Fil1 = htmlspecialchars(trim($_POST['Fil1']), ENT_QUOTES, "UTF-8");
    $KodeMesin = htmlspecialchars(trim($_POST['KodeMesin']), ENT_QUOTES, "UTF-8");
    $WorkOrder = htmlspecialchars(trim($_POST['WorkOrder']), ENT_QUOTES, "UTF-8");
    $Location = htmlspecialchars(trim($_POST['Location']), ENT_QUOTES, "UTF-8");
    // echo "$Fil1,$KodeMesin,$WorkOrder,$Location";

    $UpdateData = UPDATE_MATERIAL_MAPPING($KodeMesin,$WorkOrder,$linkMACHWebTrax);
?>
    <script language="javascript">
        alert('Mapping Saved!');
        window.location.replace("http://localhost/protrax/home.php?link=28");
    </script>
<?php
}
?>