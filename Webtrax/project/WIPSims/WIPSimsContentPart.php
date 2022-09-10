<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValQuoteName = htmlspecialchars(trim($_POST['ValQuoteName']), ENT_QUOTES, "UTF-8");
    $ValTemplateName = htmlspecialchars(trim($_POST['ValPartNo']), ENT_QUOTES, "UTF-8");
    $arrCode = explode("+",$ValTemplateName);
    $ValTemplateName2 = base64_decode($arrCode[0]);
    $Kit = $arrCode[1];
    $ValTemplateName2 = str_replace("TN","",$ValTemplateName2);
    $EncValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValLocation = base64_decode(base64_decode($EncValLocation));

    // echo $ValQuoteName."-".$ValTemplateName2."-".$Kit;
    if ($Kit != "-")
    {
    ?>
<div class="col-md-12 text-right"><button class="btn btn-sm " id="DownloadCSV">Download CSV</button></div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TablePartSelected">
            <thead class="theadCustom">
                <tr>
                    <th width="50" class="text-center trowCustom">No</th>
                    <th width="100" class="text-center trowCustom">Part No</th>
                    <th class="text-center trowCustom">Part Desc</th>
                    <th width="100" class="text-center trowCustom">Required Qty</th>
                    <th width="100" class="text-center trowCustom">Qty Inventory</th>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1;
            if($ValLocation == "PSL")
            {
                $QData = GET_DATA_WIPSIMS_GUDANGKECIL($ValTemplateName2,$linkMACHWebTrax);
            }
            // else
            // {
                // $QData = GET_DATA_WIPSIMS_GUDANGKECIL_PSM($ValTemplateName);
            // }
            while($RData = sqlsrv_fetch_array($QData))
            {
                $ValPartNo = trim($RData['PartNo']);
                $ValPartDesc = trim($RData['PartDescription']);
                $ValQtyInventory = trim($RData['QtyStock']);
                $ValQtyInventory = number_format((float)$ValQtyInventory, 2, '.', ',');
                $ValQtyTarget = trim($RData['QtyTarget']);
                $ValQtyTarget = number_format((float)$ValQtyTarget, 2, '.', ',');

                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center"><?php echo $ValPartNo; ?></td>
                    <td class="text-left"><?php echo $ValPartDesc; ?></td>
                    <td class="text-center"><?php echo $ValQtyTarget; ?></td>
                    <td class="text-center"><?php echo $ValQtyInventory; ?></td>
                </tr>
                <?php
                $No++; 
            }
            ?></tbody>
        </table>
    </div>
</div>
    <?php
    }
    else {}
}
else
{
    echo "";    
}
?>