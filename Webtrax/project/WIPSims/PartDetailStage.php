<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleBarcodePart.php");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValFloat = htmlspecialchars(trim($_POST['ValFloat']), ENT_QUOTES, "UTF-8");
    $ValFloat = base64_decode(base64_decode($ValFloat));
    $ArrValFloat = explode("#",$ValFloat);
    $WOP = $ArrValFloat[0];
    $Expense = $ArrValFloat[1];
    $ClosedTime = $ArrValFloat[2];
    // echo "$WOP >> $Expense >> $ClosedTime";
    $Arr = array("NOT PRINTED YET","PRINTED","MACHINING IN","WAITING FOR QC1","QC1","WAITING FOR FINISHING","FINISHING","WAITING FOR QC2","QC2","PART FINISHED");
    $Data = GET_WOC($WOP,$Expense,$ClosedTime,$linkMACHWebTrax);
    while($DataRes = sqlsrv_fetch_array($Data))
    {
        $WOC = trim($DataRes['WOChild']);
    }
?>
<style>
    .DataChild{
    cursor: pointer;
    }
</style>
<br>
<div class="col-md-12"><h5><strong>WO Process Stage : <?php echo $WOC; ?></strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableStage">
            <thead class="theadCustom">
                <tr>
                    <th width="50" class="text-center trowCustom">No</th>
                    <th class="text-center trowCustom">WO</th>
                    <th class="text-center trowCustom">Stage</th>
                    <th class="text-center trowCustom">Barcode Qty</th>
                    <th class="text-center trowCustom">Part Qty*</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $No=1;
            foreach($Arr as $ArrStage)
            {
                $Data = GET_DATA_PER_STAGE($WOC,$ArrStage,$Expense,$ClosedTime,$linkMACHWebTrax);
                while($DataRes = sqlsrv_fetch_array($Data))
                {
                    $ValQty = trim($DataRes['SUM']);
                    $ValCount = trim($DataRes['COUNT']);
                    $ValCount = number_format((float)$ValCount, 2, '.', ',');
                    $ValQty = number_format((float)$ValQty, 2, '.', ',');
                    $ValDataRowEncrypt = base64_encode(base64_encode($WOC."#".$ArrStage."#".$ClosedTime."#".$Expense));
            ?>
                <tr class="DataChild" data-float="<?php echo $ValDataRowEncrypt; ?>">
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-left"><?php echo $WOC; ?></td>
                    <td class="text-left"><?php echo $ArrStage; ?></td>
                    <td class="text-right"><?php echo $ValCount; ?></td>
                    <td class="text-right"><?php echo $ValQty; ?></td>
                </tr>
            <?php
                    $No++;
                }
            }
            ?>
            </tbody> 
        <i>*)Qty = Qty Initial</i>
        </table>
    </div>
</div>

<?php
}
?>