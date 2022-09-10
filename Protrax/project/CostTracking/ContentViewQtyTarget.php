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
    $ValInputExpense = htmlspecialchars(trim($_POST['ValExpense']), ENT_QUOTES, "UTF-8");
    $ValInputExpense2 = $ValInputExpense;
    if($ValInputExpense == "All Expense")
    {
       $ValInputExpense2 = ""; 
    }
?>
<div><strong>Season</strong> : <?php echo $ValSeason; ?>. <strong>Quote Category</strong> : <?php echo $ValQuoteCategory; ?>. <strong>Expense</strong> : <?php echo $ValInputExpense; ?>.</div>
<div class="table-responsive">
    <table class="table table-hover" id="TableData">
        <thead class="theadCustom">    
            <tr>
                <th class="text-center" width="10">No</th>
                <th class="text-center">Quote</th>
                <th class="text-center" width="100">Season</th>
                <th class="text-center">Expense</th>
                <th class="text-center" width="100">QtyTarget</th>
                <th class="text-center" width="50">Location</th>
                <th class="text-center" width="50">#</th>
            </tr>
        </thead>
        <tbody><?php 
        $NoListDataTarget = 1;
        $QListDataQtyTarget = GET_LIST_TARGET_QTY_2($ValSeason,$ValQuoteCategory,$ValInputExpense2,$linkMACHWebTrax);
        while ($RListDataQtyTarget = mssql_fetch_assoc($QListDataQtyTarget))
        {
            $ValQuote = trim($RListDataQtyTarget['Quote']);
            $ValHalf = trim($RListDataQtyTarget['Half']);
            $ValExpense = trim($RListDataQtyTarget['ExpenseAllocation']);
            $ValQtyTarget = trim($RListDataQtyTarget['QtyTarget']);
            $ValQtyTarget = number_format((float)$ValQtyTarget, 2, '.', ',');
            $ValLocation = trim($RListDataQtyTarget['Location']);
            $ValID = trim($RListDataQtyTarget['Idx']);
            $ValTokenQty = base64_encode(base64_encode("ID".$ValID));
            ?>    
            <tr>
                <td class="text-center"><?php echo $NoListDataTarget; ?></td>
                <td class="text-start"><?php echo $ValQuote; ?></td>
                <td class="text-center"><?php echo $ValHalf; ?></td>
                <td class="text-start"><?php echo $ValExpense; ?></td>
                <td class="text-end"><?php echo $ValQtyTarget;?></td>
                <td class="text-center"><?php echo $ValLocation; ?></td>
                <td class="text-center"><span class="PointerList EditTarget" data-datatoken="<?php echo $ValTokenQty; ?>" title="Edit Target"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>&nbsp;<span class="PointerList DeleteTarget" data-datatoken="<?php echo $ValTokenQty; ?>" title="Delete Target"><i class="bi bi-trash-fill" aria-hidden="true"></i></span></td>
            </tr>
            <?php
            $NoListDataTarget++;
        }
        ?></tbody>
    </table>
</div>
<?php
}
else
{
    echo "";    
}
?>