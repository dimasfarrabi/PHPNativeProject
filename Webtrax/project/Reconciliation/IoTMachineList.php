<?php

require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleRecon.php"); 

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValFilterType = htmlspecialchars(trim($_POST['FilterType']), ENT_QUOTES, "UTF-8");
    $ValDate = htmlspecialchars(trim($_POST['Date']), ENT_QUOTES, "UTF-8");
    // echo "$ValFilterType >> $ValDate";
?>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="ListMachine">
        <thead class="theadCustom">
            <tr>
                <th class="text-center">Select Machine</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $List = GET_MACHINE_NAME($linkMACHWebTrax);
        while($Res = sqlsrv_fetch_array($List))
        {
            $NamaMesin = trim($Res['NamaMesin']);
            $DeviceID = trim($Res['IoT_DeviceID']);
            ?>
            <tr data-id="<?php echo "$DeviceID*$NamaMesin"; ?>" class="PointerList">
                <td class="text-left" style="cursor:pointer;" value="<?php echo $DeviceID; ?>"><?php echo $NamaMesin; ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<?php
}

?>