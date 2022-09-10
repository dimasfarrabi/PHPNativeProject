<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePointAchievement.php");
date_default_timezone_set("Asia/Jakarta");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
	$ArrListPMDM = array();
    $QListPMDM = GET_LIST_PM_DM_NAME($linkMACHWebTrax);
    while($RListPMDM = sqlsrv_fetch_array($QListPMDM))
    {
        array_push($ArrListPMDM,array("FullName"=>trim($RListPMDM['FullName']),"Role"=>trim($RListPMDM['Role'])));
    }
?>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="ListTablePM">
        <thead class="theadCustom">
            <tr>
                <th class="text-center">Name</th>
            </tr>
        </thead>
        <tbody><?php 
        
        foreach($ArrListPMDM as $DataListPMDM)
        {
            $ValName = trim($DataListPMDM['FullName']);
            $ValRole = trim($DataListPMDM['Role']);
            $ValEncrypt = base64_encode(base64_encode($ValName."#".$ValRole));
        ?>
            <tr class="PointerListPM" data-roles="<?php echo $ValEncrypt; ?>">
                <td class="text-left"><?php echo $ValName; ?></td>
            </tr><?php
        }
        ?></tbody>
    </table>
</div>
<?php
}
else
{
    echo "";    
}
?>
