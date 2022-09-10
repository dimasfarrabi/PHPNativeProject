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
    $ValSeason = htmlspecialchars(trim($_POST['ValSeason']), ENT_QUOTES, "UTF-8");
    $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $ValQuote = htmlspecialchars(trim($_POST['ValQuote']), ENT_QUOTES, "UTF-8");
    $QListExpense = LIST_EXPENSE_BY_PARAMETERS($ValSeason,$ValQuoteCategory,$ValQuote,$linkMACHWebTrax);
    $Row = mssql_num_rows($QListExpense);
    if($Row != 0)
    {
?>
<div class="form-group">
    <label for="InputExpenseF">Expense</label>
    <select class="form-control" id="InputExpenseF"><?php 
    while($RListExpense = mssql_fetch_assoc($QListExpense))
    {
        ?>
        <option><?php echo trim($RListExpense['ExpenseAllocation']); ?></option>
        <?php
    }
    ?></select>
</div>
<div class="form-group">
    <label for="InputTypeF">Type</label>
    <select class="form-control" id="InputTypeF">
        <option>PEOPLE</option>
        <option>MACHINE</option>
        <option>MATERIAL</option>
    </select>
</div>
<div class="form-group">
    <label for="InputTargetCostF">Target Cost</label>
    <input type="text" class="form-control" id="InputTargetCostF" placeholder="0.00">
</div>
<div class="form-group">
    <label for="InputLocationF">Location</label>
    <select class="form-control" id="InputLocationF">
        <option>PSL</option>
        <option>PSM</option>
    </select>
</div>
<button class="btn btn-dark btn-labeled" id="BtnNewTarget">Add New</button>
<?php
    }
    else
    {
?>
<div class="form-group">
    <label for="InputExpenseF">Expense</label>
    <select class="form-control" id="InputExpenseF" disabled></select>
</div>
<div class="form-group">
    <label for="InputTypeF">Type</label>
    <select class="form-control" id="InputTypeF" disabled>
        <option>PEOPLE</option>
        <option>MACHINE</option>
        <option>MATERIAL</option>
    </select>
</div>
<div class="form-group">
    <label for="InputTargetCostF">Target Cost</label>
    <input type="text" class="form-control" id="InputTargetCostF" placeholder="0.00" disabled>
</div>
<div class="form-group">
    <label for="InputLocationF">Location</label>
    <select class="form-control" id="InputLocationF" disabled>
        <option>PSL</option>
        <option>PSM</option>
    </select>
</div>
<button class="btn btn-dark btn-labeled" disabled>Submit</button>
<?php        
    }
}
else
{
    echo "";    
}
?>