<?php
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");
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
    $Half = htmlspecialchars(trim($_POST['Half']), ENT_QUOTES, "UTF-8");
    $Category = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $LinkOpt = $linkMACHWebTrax;
    // echo "$Half >> $Category";
?>
<style>
.header {left: 0;
    background-color: #F0F0F0;
    position: sticky;
    z-index: 5;
    font-size:13px;
    border-collapse: separate; 
    border-spacing: 0;
}
.header2 {left: 0;
    background-color: #F0F0F0;
    position: sticky;
    z-index: 1;
    font-size:13px;
    border-collapse: separate; 
    border-spacing: 0;
}
.tableFixHead {
    overflow-y: auto;
    max-height: 800px;
}
.tableFixHead thead tr.first th {
    position: sticky;
    top: 0;
}
.tableFixHead thead tr.second th {
    position: sticky;
}
table {
    border-collapse: separate;
}
th {
    background: #eee;
}
</style>
<script>
$(document).ready(function() { 
    $("thead tr.second th, thead tr.second td").css("top", 35)
});
</script>
<div class="col-md-12"><h4><strong>Target Cost List</strong></h4></div>
<div class="col-md-12"><h5><strong>Season: </strong><?php echo $Half; ?>.   <strong>Category: </strong><?php echo $Category; ?></h5></div>
<div class="col-md-12">
    <div style="width:100%; overflow-x:scroll;" class="tableFixHead">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr class="first">
                    <th class="text-center header" rowspan="2"><span style="margin-left:100px; margin-right:100px;">Quote</span></th>
                    <?php
                    $arrExpense = array();
                    $QListReport = GET_REPORT_WOMAPPING_WITH_RUNNINGCOST_NEW($Half,$Category,"RI1000","",$LinkOpt);
                    while($RListReport = sqlsrv_fetch_array($QListReport))
                    {
                    $ValExpense = $RListReport['ExpenseAllocation'];
                    array_push($arrExpense,$ValExpense);
                    ?>
                    <th class="text-center" colspan="3"><?php echo $ValExpense; ?></th>
                    <?php
                    }
                    $arrCostType = array("PEOPLE","MACHINE","MATERIAL");
                    ?>
                </tr>
                <tr class="second">
                    <th>LaborCost($)</th>
                    <th>MachineCost($)</th>
                    <th>MaterialCost($)</th>
                    <th>LaborCost($)</th>
                    <th>MachineCost($)</th>
                    <th>MaterialCost($)</th>
                    <th>LaborCost($)</th>
                    <th>MachineCost($)</th>
                    <th>MaterialCost($)</th>
                    <th>LaborCost($)</th>
                    <th>MachineCost($)</th>
                    <th>MaterialCost($)</th>
                    <th>LaborCost($)</th>
                    <th>MachineCost($)</th>
                    <th>MaterialCost($)</th>
                    <th>LaborCost($)</th>
                    <th>MachineCost($)</th>
                    <th>MaterialCost($)</th>
                    <th>LaborCost($)</th>
                    <th>MachineCost($)</th>
                    <th>MaterialCost($)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $QListQuote = GET_LIST_QUOTE_BY_PARAM($Half,$Category,"",$LinkOpt);
                while($RListQuote = sqlsrv_fetch_array($QListQuote))
                {
                    $ValQuote = $RListQuote['Quote'];
                ?>
                <tr>
                    <td class="text-left header2"><?php echo $ValQuote; ?></td>
                    <?php
                    foreach($arrExpense as $Div)
                    {
                        foreach($arrCostType as $CostType)
                        {
                            $TargetCost = GET_TARGET_COST_BY_QUOTE_AND_HALF($ValQuote,$Div,$CostType,$Half,$LinkOpt);
                        ?>
                        <td class="text-right"><?php echo $TargetCost; ?></td>
                        <?php
                        }
                    }
                }
                ?>
                </tr>
            </tbody>
        </table>
    </div>   
</div>
<?php
}
?>