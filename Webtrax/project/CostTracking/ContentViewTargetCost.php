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
?>
<span><strong>Season</strong> : <?php echo $ValSeason; ?>.<strong>Quote Category</strong> : <?php echo $ValQuoteCategory; ?>.</span>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="TableData">
        <thead class="theadCustom">    
            <tr>
                <th class="text-center" width="10">No</th>
                <th class="text-center">Quote</th>
                <th class="text-center" width="100">Season</th>
                <th class="text-center">Expense</th>
                <th class="text-center" width="100">CostType</th>
                <th class="text-center" width="100">TargetCost</th>
                <th class="text-center" width="50">Location</th>
                <th class="text-center" width="50">#</th>
            </tr>
        </thead>
        <tbody><?php 
        $NoListDataTarget = 1;
        $QListDataTargetCost = GET_LIST_TARGET_COST($ValSeason,$ValQuoteCategory,$linkMACHWebTrax);
        while ($RListDataTargetCost = mssql_fetch_assoc($QListDataTargetCost))
        {
            $ValQuote = trim($RListDataTargetCost['Quote']);
            $ValHalf = trim($RListDataTargetCost['Half']);
            $ValExpense = trim($RListDataTargetCost['ExpenseAllocation']);
            $ValCostType = trim($RListDataTargetCost['CostType']);
            $ValQtyTarget = trim($RListDataTargetCost['TargetCost']);
            $ValQtyTarget = number_format((float)$ValQtyTarget, 2, '.', ',');
            $ValLocation = trim($RListDataTargetCost['Location']);
            $ValID = trim($RListDataTargetCost['Idx']);
            $ValToken = base64_encode(base64_encode("ID".$ValID));
            ?>    
            <tr>
                <td class="text-center"><?php echo $NoListDataTarget; ?></td>
                <td class="text-left"><?php echo $ValQuote; ?></td>
                <td class="text-center"><?php echo $ValHalf; ?></td>
                <td class="text-left"><?php echo $ValExpense; ?></td>
                <td class="text-left"><?php echo $ValCostType; ?></td>
                <td class="text-right"><?php echo $ValQtyTarget;?></td>
                <td class="text-center"><?php echo $ValLocation; ?></td>
                <td class="text-center"><span class="PointerList EditTarget" data-datatoken="<?php echo $ValToken; ?>" title="Edit Target"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></span>&nbsp;<span class="PointerList DeleteTarget" data-datatoken="<?php echo $ValToken; ?>" title="Delete Target"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span></td>
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