<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");
require_once("Modules/ModuleTarget.php");
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
    $ValDataID = htmlspecialchars(trim($_POST['ValDataID']), ENT_QUOTES, "UTF-8");
    $ValDataID2 = str_replace("ID","",base64_decode(base64_decode($ValDataID)));
    # data target cost
    $QDataTargetCost = GET_DATA_TARGET_COST_SELECTED($ValDataID2,$linkMACHWebTrax);
    $RDataTargetCost = mssql_fetch_assoc($QDataTargetCost);
    $ValQuote = trim($RDataTargetCost['Quote']);
    $ValSeason = trim($RDataTargetCost['Half']);
    $ValExpense = trim($RDataTargetCost['ExpenseAllocation']);
    $ValCostType = trim($RDataTargetCost['CostType']);
    $ValTargetCost = trim($RDataTargetCost['TargetCost']);
    $ValID = $ValDataID;
?>
<div class="row">
    <div class="col-md-12 mb-2">
        <label for="TextQuote" class="form-label fw-bold">Quote</label>
        <input type="text" class="form-control" id="TextQuote" value="<?php echo $ValQuote; ?>" disabled>
    </div>
    <div class="col-md-12 mb-2">
        <label for="TextSeason" class="form-label fw-bold">Season</label>
        <input type="text" class="form-control" id="TextSeason" value="<?php echo $ValSeason; ?>" disabled>
    </div>
    <div class="col-md-12 mb-2">
        <label for="TextExpense" class="form-label fw-bold">Expense</label>
        <input type="text" class="form-control" id="TextExpense" value="<?php echo $ValExpense; ?>" disabled>
    </div>
    <div class="col-md-12 mb-2">
        <label for="TextCostType" class="form-label fw-bold">CostType</label>
        <input type="text" class="form-control" id="TextCostType" value="<?php echo $ValCostType; ?>" disabled>
    </div>
    <div class="col-md-12 mb-3">
        <label for="TextTargetCost" class="form-label fw-bold">TargetCost</label>
        <input type="text" class="form-control" id="TextTargetCost" value="<?php echo $ValTargetCost; ?>">
    </div>
    <div class="col-md-12 mb-2">
        <button class="btn btn-dark btn-labeled" id="BtnEditTargetCost" data-datatoken="<?php echo $ValID; ?>">Edit Target Cost</button>
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