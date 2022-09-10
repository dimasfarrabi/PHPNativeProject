<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php");
/*
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
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
    echo $ValTemplateName2;
	if ($Kit != "-")
    {
    ?>
<style>
    .Points{
        font-size:10px;
    }
</style>
<div class="col-md-12 text-right"><button class="btn btn-sm " id="DownloadCSV">Download CSV</button></div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-12">
    <div style="width:100%; overflow-x:scroll;">
        <table class="table table-bordered table-hover" id="TablePartSelected">
            <thead class="theadCustom">
                <tr>
                    <th width="50" class="text-center trowCustom">No</th>
                    <th width="100" class="text-center trowCustom">Part No</th>
                    <th width="500" class="text-center trowCustom">Part Desc</th>
                    <th width="100" class="text-center trowCustom">Required Qty</th>
                    <th width="100" class="text-center trowCustom">Total Qty</th>
                    <th width="100" class="text-center trowCustom">Qty Inventory PSL</th>
                    <th width="100" class="text-center trowCustom">Qty In<br>Transit</th>
                    <th width="100" class="text-center trowCustom">Qty Inventory PSM</th>
                    <th width="200" class="text-center trowCustom Points">Instrumen SN</th>
                    <th class="text-center trowCustom Points">Tgl Terakhir SO</th>
                    <th class="text-center trowCustom Points">SO PIC</th>
                    <th class="text-center trowCustom Points">Status</th>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1;
                $QData = GET_DATA_WIPSIMS_GUDANGKECIL($ValTemplateName2,$linkMACHWebTrax);
            // else
            // {
                // $QData = GET_DATA_WIPSIMS_GUDANGKECIL_PSM($ValTemplateName);
            // }
            $InfoOpname = "***";
            $DateSO = "";
            $PIC = "";
            $Status = "";
            while($RData = sqlsrv_fetch_array($QData))
            {
                $ValPartNo = trim($RData['PartNo']);
                $ValPartDesc = trim($RData['PartDescription']);
                $QtyStockPSL = trim($RData['QtyStockSLG']);
                $QtyStockPSM = trim($RData['QtyStockSMG']);
                $QtyInTransit = trim($RData['QtyStockTR']);
                $QtyStock = trim($RData['QtyStock']);
                $ValQtyTarget = trim($RData['QtyTarget']);
                $SN = trim($RData['SN']);
                if(trim($QtyStockPSL) == ""){$QtyStockPSL = "";} else {$QtyStockPSL = number_format((float)$QtyStockPSL, 2, '.', ',');}   
                if(trim($QtyStockPSM) == ""){$QtyStockPSM = "";} else {$QtyStockPSM = number_format((float)$QtyStockPSM, 2, '.', ',');}   
                if(trim($QtyInTransit) == ""){$QtyInTransit = "";} else {$QtyInTransit = number_format((float)$QtyInTransit, 2, '.', ',');}   
                $ValQtyTarget = number_format((float)$ValQtyTarget, 2, '.', ',');
                $QtyStock = number_format((float)$QtyStock, 2, '.', ',');
                $InfoOpname = GET_INFO_STOCK_OPNAME($ValPartNo,$linkMACHWebTrax);
                if($InfoOpname != ''){
                    $arr = explode("*",$InfoOpname);
                    $DateSO = $arr[0];
                    $PIC = $arr[1];
                    $Status = $arr[3];
                }
                else
                {
                    $DateSO = "";
                    $PIC = "";
                    $Status = "";
                }
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center"><?php echo $ValPartNo; ?></td>
                    <td class="text-left"><?php echo $ValPartDesc; ?></td>
                    <td class="text-right"><?php echo $ValQtyTarget; ?></td>
                    <td class="text-right"><?php echo $QtyStock; ?></td>
                    <td class="text-right"><?php echo $QtyStockPSL; ?></td>
                    <td class="text-right"><?php echo $QtyInTransit; ?></td>
                    <td class="text-right"><?php echo $QtyStockPSM; ?></td>
                    <td class="text-left Points"><?php echo $SN; ?></td>
                    <td class="text-center Points"><?php echo $DateSO; ?></td>
                    <td class="text-left Points"><?php echo $PIC; ?></td>
                    <td class="text-center Points"><?php echo $Status; ?></td>
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
<script>
$(document).ready(function () {
    $("#DownloadCSV").click(function(){
        var InputTemplate = '<?php echo $ValTemplateName2; ?>';
        window.location.href = 'project/WIPSims/_DownloadDetailTemplate.php?template='+InputTemplate;
    });
});
</script>