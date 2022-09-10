<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValTemplateNameEnc = "";
    $ValQuoteName = htmlspecialchars(trim($_POST['ValQuoteName']), ENT_QUOTES, "UTF-8");
    $ValProjectID = htmlspecialchars(trim($_POST['ValProjectID']), ENT_QUOTES, "UTF-8");
    $EncValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValLocation = base64_decode(base64_decode($EncValLocation));
    $EncLocation = $EncValLocation;
    // echo $ValQuoteName."||".$ValProjectID."||".$ValLocation;
    $today = date("m/d/Y");
    
    $ArrayKit = array();
    $ArrayWIP = array();
            if($ValLocation == "PSL")
            {
                $QData = GET_DATA_WIPSIMS_TEMPLATE_BY_PROJECT($ValProjectID,$linkMACHWebTrax);
            }   
            // else
            // {
                // $QData = GET_DATA_WIPSIMS_TEMPLATE_BY_PROJECT_PSM($ValProjectID);
            // }  
                     
            while($RData = sqlsrv_fetch_array($QData))
            {
                $ValPartNo = trim($RData['PN']);
                $ValPartDesc = trim($RData['PartDescription']);
                $ValQtyInventory = trim($RData['QtyStock']);
                $ValTemplateName = trim($RData['TemplateName']);
                $ValLastModified = trim($RData['Dates']);
                // $ValTemplateNameEnc = base64_encode("TN".$ValTemplateName);

                if($ValLocation == "PSL")
                {
                    $ValMaxKit = "-";
                    $ValMaxInstrument = GET_REPORT_MAX_KIT_BUILD_BY_ID($ValTemplateName,$linkMACHWebTrax);
                    while($XData = sqlsrv_fetch_array($ValMaxInstrument))
                    $ValMaxKit = trim($XData['Max Kit Build']);
                    // if(trim($ValMaxInstrument) == ""){$ValMaxInstrument = 0;}
                }
                else
                {
                    // $ValMaxInstrument = GET_REPORT_MAX_KIT_BUILD_BY_ID_PSM($ValTemplateName);
                }
                $ValLastModified = GET_LAST_UPDATE_TEMPLATE($ValTemplateName,$linkMACHWebTrax);
                $TemporaryArray = array(
                    "PartNo" => $ValPartNo,
                    "PartDesc" => $ValPartDesc,
                    "TemplateName" => $ValTemplateName,
                    "QtyInventory" => $ValQtyInventory,
                    "LastModified" => $ValLastModified,
                    "MaxInstrument" =>  $ValMaxKit
                    );
                    array_push($ArrayKit,$TemporaryArray);
            }
            $QData2 = GET_WIP_CONSUMABLE($ValProjectID,$linkMACHWebTrax);
            while($RData2 = sqlsrv_fetch_array($QData2))
            {
                $ValPartNo = trim($RData2['PartNo']);
                $ValLocation = trim($RData2['Location']);
                $ValPartDesc = trim($RData2['PartDescription']);
                $ValQtyInventory = trim($RData2['Qty']);
                $ValLastModified = trim($RData2['DateCreated']);
                $ValType = $ValPartNo."#".$ValLocation;
                $TemporaryArray = array(
                    "PartNo" => $ValType,
                    "PartDesc" => $ValPartDesc,
                    "TemplateName" => "-",
                    "QtyInventory" => $ValQtyInventory,
                    "LastModified" => $ValLastModified,
                    "MaxInstrument" =>  "-"
                    );
                    array_push($ArrayKit,$TemporaryArray);
            }
?>
<div class="col-md-12"><h5><strong>Table WIP : <?php echo $ValQuoteName; ?></strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableParentPart">
            <thead class="theadCustom">
                <tr>
                    <th width="50" class="text-center trowCustom">No</th>
                    <th width="100" class="text-center trowCustom">Part No</th>
                    <th class="text-center trowCustom">Part Desc</th>
                    <th width="100" class="text-center trowCustom">Qty Inventory</th>
                    <th width="100" class="text-center trowCustom">Max Kit Available</th>
                    <th width="200" class="text-center trowCustom">Last Updated</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $No = 1;
            foreach($ArrayKit as $Result)
            {
                $PartNo = trim($Result['PartNo']);
                $PartDesc = trim($Result['PartDesc']);
                $TemplateName = trim($Result['TemplateName']);
                $QtyInventory = trim($Result['QtyInventory']);
                $LastModified = trim($Result['LastModified']);
                $MaxInstrument = trim($Result['MaxInstrument']);

                $ValTemplateNameEnc = base64_encode("TN".$TemplateName);

                $QtyInventory = number_format((float)$QtyInventory, 2, '.', ',');
                // $MaxInstrument = number_format((float)$MaxInstrument, 2, '.', ',');
                // $ValMaxInstrument = number_format((float)$ValMaxInstrument, 2, '.', ',');
                $ValTemplateNameEnc2 = $ValTemplateNameEnc."+".$MaxInstrument;
                ?>
                <tr data-id="<?php echo $ValTemplateNameEnc2; ?>" data-log="<?php echo $EncLocation; ?>" class="PointerList">
                <!-- <tr> -->
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center"><?php echo $PartNo; ?></td>
                    <td class="text-left"><?php echo $PartDesc; ?></td>
                    <td class="text-center"><?php echo $QtyInventory; ?></td>
                    <td class="text-center"><?php echo $MaxInstrument; ?></td>
                    <td class="text-center"><?php echo $LastModified; ?></td>
                </tr>
                <?php
                $No++; 
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php

}
else
{
    echo "";    
}
?>