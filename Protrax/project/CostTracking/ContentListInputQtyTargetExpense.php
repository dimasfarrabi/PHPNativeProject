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
    $ValSeason = htmlspecialchars(trim($_POST['ValSeason']), ENT_QUOTES, "UTF-8");
    $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $ValQuote = htmlspecialchars(trim($_POST['ValQuote']), ENT_QUOTES, "UTF-8");
    $QListExpense = LIST_EXPENSE_BY_PARAMETERS($ValSeason,$ValQuoteCategory,$ValQuote,$linkMACHWebTrax);

    $Row = mssql_num_rows($QListExpense);
    if($Row != 0)
    {
?>
<div class="col-md-12 mb-2">
    <label for="InputExpenseF" class="form-label fw-bold">Expense</label>
    <select class="form-select" id="InputExpenseF"><?php 
    while($RListExpense = mssql_fetch_assoc($QListExpense))
    {
        ?>
        <option><?php echo trim($RListExpense['ExpenseAllocation']); ?></option>
        <?php
    }
    ?></select>
</div>
<div class="col-md-12 mb-2">
    <label for="InputQtyTargetF" class="form-label fw-bold">Qty Target</label>
    <input type="text" class="form-control" id="InputQtyTargetF" placeholder="0.00">
</div>
<div class="col-md-12 mb-2">
    <label for="InputLocationF" class="form-label fw-bold">Location</label>
    <select class="form-select" id="InputLocationF">
        <option>PSL</option>
        <option>PSM</option>
    </select>
</div>
<div class="col-md-12">
    <button class="btn btn-dark btn-labeled" id="BtnNewTarget">Add New</button>
</div>
<?php
    }
    else
    {
?>
<div class="col-md-12 mb-2">
    <label for="InputExpenseF" class="form-label fw-bold">Expense</label>
    <select class="form-select" id="InputExpenseF" disabled></select>
</div>
<div class="col-md-12 mb-2">
    <label for="InputQtyTargetF" class="form-label fw-bold">Qty Target</label>
    <input type="text" class="form-control" id="InputQtyTargetF" placeholder="0.00" disabled>
</div>
<div class="col-md-12 mb-2">
    <label for="InputLocationF" class="form-label fw-bold">Location</label>
    <select class="form-select" id="InputLocationF" disabled>
        <option>PSL</option>
        <option>PSM</option>
    </select>
</div>
<div class="col-md-12">
    <button class="btn btn-dark btn-labeled" disabled>Submit</button>
</div>
<?php        
    }
}
else
{
    echo "";    
}
?>