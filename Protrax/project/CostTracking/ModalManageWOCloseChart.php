<?php
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTrackingChart.php");
date_default_timezone_set("Asia/Jakarta");

if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ArrValTokenEnc = htmlspecialchars(trim($_POST['ValDataToken']), ENT_QUOTES, "UTF-8");
    $ArrValToken = base64_decode(base64_decode($ArrValTokenEnc));
    $ArrToken = explode("#",$ArrValToken);
    $ValIdx = $ArrToken['0'];
    # data total chart
    $QDataTotalChart = GET_DETAIL_SELECTED_DATA_WO_CLOSED_CHART($ValIdx,$linkMACHWebTrax);
    $RDataTotalChart = mssql_fetch_assoc($QDataTotalChart);
    $ValSeasonHalf = trim($RDataTotalChart['TargetHalfClosed']);
    $ValTotalTargetCost = trim($RDataTotalChart['TotalTargetCost']);
    $ValTotalTargetCost = sprintf('%.2f',floatval($ValTotalTargetCost));
    $ValTotalActualCost = trim($RDataTotalChart['TotalActualCost']);
    $ValTotalActualCost = sprintf('%.2f',floatval($ValTotalActualCost));
    $ValTotalQtyBuilt = trim($RDataTotalChart['TotalQtyBuilt']);
    $ValTotalQtyBuilt = sprintf('%.2f',floatval($ValTotalQtyBuilt));
    $ValTotalOTS = trim($RDataTotalChart['TotalOTS']);
    $ValTotalOTS = sprintf('%.2f',floatval($ValTotalOTS));
    $DtStatus = trim($RDataTotalChart['IsLocked']);
    $ValStatusData = '<span class="text-danger">Enable</span>';
    if($DtStatus == "0"){$ValStatusData = '<span class="text-success">Disable</span>';}
    $ValQuote = trim($RDataTotalChart['Quote']);
    $ValTotalQtyTarget = trim($RDataTotalChart['TotalQtyTarget']);
    $ValTotalQtyTarget = sprintf('%.2f',floatval($ValTotalQtyTarget));
    
?>
<div class="row">
    <div class="col-md-12 mb-2">
        <div class="fw-bold">Auto calculation : <?php echo $ValStatusData; ?></div>
        <div class="fw-bold text-danger"><i>*) Auto calculation will be disable if data edited</i></div> 
    </div>
    <div class="col-md-12 mb-2">
        <label for="TextHalf" class="form-label fw-bold">Half</label>
        <input type="text" class="form-control" id="TextHalf" value="<?php echo $ValSeasonHalf; ?>" readonly>
    </div>
    <div class="col-md-12 mb-2">
        <label for="TextQuote" class="form-label fw-bold">Quote</label>
        <input type="text" class="form-control" id="TextQuote" value="<?php echo $ValQuote; ?>" readonly>
    </div>
    <div class="col-md-12 mb-2">
        <label for="TextTotalTargetCost" class="form-label fw-bold">Total Target Cost</label>
        <input type="text" class="form-control" id="TextTotalTargetCost" value="<?php echo $ValTotalTargetCost; ?>">
    </div>
    <div class="col-md-12 mb-2">
        <label for="TextTotalActualCost" class="form-label fw-bold">Total Actual Cost</label>
        <input type="text" class="form-control" id="TextTotalActualCost" value="<?php echo $ValTotalActualCost; ?>">
    </div>
    <div class="col-md-12 mb-3">
        <label for="TextTotalQtyBuilt" class="form-label fw-bold">Total Qty Built</label>
        <input type="text" class="form-control" id="TextTotalQtyBuilt" value="<?php echo $ValTotalQtyBuilt; ?>">
    </div>
    <div class="col-md-12 mb-3">
        <label for="TextTotalQtyTarget" class="form-label fw-bold">Total Qty Target</label>
        <input type="text" class="form-control" id="TextTotalQtyTarget" value="<?php echo $ValTotalQtyTarget; ?>">
    </div>
    <div class="col-md-12 mb-3">
        <label for="TextTotalOTS" class="form-label fw-bold">Total OTS</label>
        <input type="text" class="form-control" id="TextTotalOTS" value="<?php echo $ValTotalOTS; ?>">
    </div>
    <div class="col-md-12 mb-2">
        <button class="btn btn-sm btn-dark btn-labeled" id="BtnEditQtyTarget" data-token="<?php echo $ArrValTokenEnc; ?>">Update Data</button>
    </div>
    <div class="col-md-12 mb-2">
        <span id="TempProcess"></span>
    </div>
</div>
<?php
}
else
{
    echo "";    
}
?>