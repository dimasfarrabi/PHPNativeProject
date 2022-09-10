<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php"); 
require_once("Modules/ModuleTarget.php"); 
date_default_timezone_set("Asia/Jakarta");

if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValDataID = htmlspecialchars(trim($_POST['ValDataID']), ENT_QUOTES, "UTF-8");
    $ValDataID2 = str_replace("ID","",base64_decode(base64_decode($ValDataID)));
    // # data target cost
    $QDataTargetCost = GET_DATA_TARGET_QTY_SELECTED($ValDataID2,$linkMACHWebTrax);
    $RDataTargetCost = mssql_fetch_assoc($QDataTargetCost);
    $ValQuote = trim($RDataTargetCost['Quote']);
    $ValSeason = trim($RDataTargetCost['Half']);
    $ValExpense = trim($RDataTargetCost['ExpenseAllocation']);
    $ValQtyTarget = trim($RDataTargetCost['QtyTarget']);
    $ValID = $ValDataID;
?>
<div>
    <div class="form-group">
        <label for="TextQuote">Quote</label>
        <input type="text" class="form-control" id="TextQuote" value="<?php echo $ValQuote; ?>" disabled>
    </div>
    <div class="form-group">
        <label for="TextSeason">Season</label>
        <input type="text" class="form-control" id="TextSeason" value="<?php echo $ValSeason; ?>" disabled>
    </div>
    <div class="form-group">
        <label for="TextExpense">Expense</label>
        <input type="text" class="form-control" id="TextExpense" value="<?php echo $ValExpense; ?>" disabled>
    </div>
    <div class="form-group">
        <label for="TextQtyTarget">Qty Target</label>
        <input type="text" class="form-control" id="TextQtyTarget" value="<?php echo $ValQtyTarget; ?>">
    </div>
    <button class="btn btn-dark btn-labeled" id="BtnEditQtyTarget" data-datatoken="<?php echo $ValID; ?>">Edit Qty Target</button>
    <span id="TempProcess"></span>
</div>
<?php
}
else
{
    echo "";    
}
?>