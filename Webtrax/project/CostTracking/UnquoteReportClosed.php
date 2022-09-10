<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValQuoteSelected = htmlspecialchars(trim($_POST['ValQuoteSelected']), ENT_QUOTES, "UTF-8");
    $ValType = htmlspecialchars(trim($_POST['ValType']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $LinkOpt = $linkMACHWebTrax;
    // echo "$ValQuoteSelected >> $ValType >> $ValClosedTime >> $ValQuoteCategory";
    $QDataRecalculate = GET_LAST_RECALCULATE_LOG($LinkOpt);
    $RowRecalculateLog = sqlsrv_num_rows($QDataRecalculate);
    if($RowRecalculateLog != "0")
    {
        while($RDataRecalculate = sqlsrv_fetch_array($QDataRecalculate))
        {
            $DateRecalculateLog = date("m/d/Y H:i A",strtotime($RDataRecalculate['DateCreated']));
        }
    }
    else
    {
        $DateRecalculateLog = "-";   
    }
    $arrayCostType = array("Labor Cost ($)","Machine Cost ($)","Material Cost ($)", "OTS Cost ($)");
    $arrayExpense = array();
?>
<style>
    .Points{font-size:13px;}
    .ColSumaryPoints{font-size:12px;}
    .ColSumaryPoints2{font-size:14px;}
    .ColSumaryPointsTotal{font-size:13px;color:#0008ff;backgtrim-color:#F0F0F0;}
    .ColResult{font-size:14px;backgtrim-color:#F0F0F0;}
    .head{backgtrim-color:#F0F0F0;}
    .RowResult{font-size:15px;}
    .TargetColumn{color:#ff0000;font-size:11px;}
    .ActualColumn{color:#0008ff;font-size:11px;}
    .TargetTotalColumn{color:#ff0000;font-size:14px;}
    .ActualTotalColumn{color:#0008ff;font-size:14px;}
    .InfoRecalculate{font-style:italic;font-weight:bold;color:#ff0000;text-decoration: underline;text-decoration-color:#fff600;}
    .InfoRecalculate2{font-size:15px;font-weight:bold;color:#ff0000;}
    .InfoChart{cursor: pointer;color: #337AB7;}
    .sticky {position: sticky; top: 0; width: 80%;z-index:100;}
    .header {padding: 5px 10px;backgtrim:#FFFFFF;color: #555;box-shadow: 0px 3px 5px #888888;}
</style>
<script src="project/costtracking/lib/liblistotspart.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="col-md-10 card ColSumaryPoints2" id="myHeader">Closed Time : <strong><?php echo $ValClosedTime; ?></strong>. Quote Category : <strong><?php echo $ValQuoteCategory; ?></strong>. Quote : <strong><?php echo $ValQuoteSelected; ?></strong>
<br>Last Quote Recalculate : <strong><?php echo $DateRecalculateLog; ?></strong></div>
<div class="col-md-2 text-right InfoChart">[ Season Chart ]</div>
<script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
</script>
<div class="col-md-12" id="TableSummary" style="margin-top: 20px;">
</div>
<div class="col-md-12" style="margin-top:20px;">
<div><h5><strong>Product Cost</strong></h5></div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="vertical-2">
            <tr>
                <th class="head trowCustom" width="220">COST ALLOCATION</th> 
                <?php
                $QListReport = UNQUOTE_EXPENSE($ValClosedTime,$ValQuoteCategory,$ValQuoteSelected,$linkMACHWebTrax);
                while($RListReport = sqlsrv_fetch_array($QListReport))
                {
                $ValExpense = $RListReport['ExpenseAllocation'];
                ?>
                <td width="70" class="text-center head trowCustom"><strong><?php echo $ValExpense; ?></strong></td>
                <?php
                array_push($arrayExpense,$ValExpense);
                }
                ?>
                <td width="70" class="text-center head trowCustom"><strong>TOTAL</strong></td>
            </tr>
            <?php
            $TotalDummy = $GrandTotal = 0;
            $arrTotal = array();
            foreach($arrayCostType as $CostType)
            {
            ?>
            <tr>
                <td class="Points"><?php echo $CostType; ?></td>
                <?php
                $Total = 0;
                foreach($arrayExpense as $Expense)
                {
                    $data = GET_UNQUOTE_COST_REPORT($ValQuoteCategory,$ValQuoteSelected,$ValClosedTime,$Expense,$linkMACHWebTrax);
                    if(sqlsrv_num_rows($data) > 0)
                    {
                        while($res=sqlsrv_fetch_array($data))
                        {
                            if($CostType == 'Labor Cost ($)')
                            {
                                $ValCost = trim($res['ManCost']);
                            }
                            elseif($CostType == 'Machine Cost ($)')
                            {
                                $ValCost = trim($res['MachCost']);
                            }
                            elseif($CostType == 'Material Cost ($)')
                            {
                                $ValCost = trim($res['MatCost']);
                            }
                            else
                            {
                                $ValCost = trim($res['OTSCost']);
                            }
                        }
                    }
                    else
                    {
                        $ValCost = 0;
                    }
                    $Total = $Total + $ValCost;
                    $ValCost = number_format((float)$ValCost,2,'.',',');
                    ?>
                    <td class="text-right"><?php echo $ValCost; ?></td>
                    <?php
                }
                $Totals = number_format((float)$Total,2,'.',',');
                array_push($arrTotal,$Total);
                ?>
                <td class="text-right"><strong><?php echo $Totals; ?></strong></td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <th class="RowResult">Actual Cost Per Division ($)</th>
                <?php
                $TotalActualCost = 0;
                foreach($arrayExpense as $Expense)
                {
                    $xdata = GET_UNQUOTE_COST_REPORT($ValQuoteCategory,$ValQuoteSelected,$ValClosedTime,$Expense,$linkMACHWebTrax);
                    if(sqlsrv_num_rows($xdata) > 0)
                    {
                        while($xres=sqlsrv_fetch_array($xdata))
                        {
                            $ActualCost = @(trim($xres['ManCost'])+trim($xres['MachCost'])+trim($xres['MatCost'])+trim($xres['OTSCost']));
                        }
                    }
                    else
                    {
                        $ActualCost = 0;
                    }
                    $TotalActualCost = $TotalActualCost + $ActualCost;
                    $ActualCost = number_format((float)$ActualCost,2,'.',',');
                    ?>
                    <td class="text-right"><?php echo $ActualCost; ?></td>
                    <?php
                }
                $TotalActualCost = number_format((float)$TotalActualCost,2,'.',',');
                ?>
                <td class="text-right"><strong><?php echo $TotalActualCost; ?></strong></td>
            </tr>
        </table>
        <span><i>Auto Recalculate at : Everyday 07:00 AM, 11:59 AM, 4:30 PM</i></span>
    </div>
</div>
<div class="col-md-12"><h5><strong>Top 10 Material Cost</strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                <th class="text-center trowCustom" width="30">No</th>
                <th class="text-center trowCustom" width="130">Part No.</th>
                <th class="text-center trowCustom">Part Description</th>
                <th class="text-center trowCustom" width="130">Qty Usage</th>
                <th class="text-center trowCustom">UOM</th>
                <th class="text-center trowCustom" width="180">Cost ($)</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $Number=1;
            $QListOTS = GET_TOP10_OVERALL_MATERIAL_COST($ValQuoteCategory,$ValQuoteSelected,$ValClosedTime,$LinkOpt);
            while($RListOTS = sqlsrv_fetch_array($QListOTS))
            {
                $ValPartNo = trim($RListOTS['PartNo']);
                $ValPartDes = trim($RListOTS['PartDescription']);
                $ValQtyUsage = trim($RListOTS['Qty']);
                $ValOTSCost = trim($RListOTS['TotalCost']);
                $UOM = trim($RListOTS['TransactUOM']);
                $ValOTSCost = number_format((float)$ValOTSCost, 2, '.', ',');

            ?>
                <tr>
                <td class="text-center"><?php echo $Number; ?></td>
                <td class="text-center"><?php echo $ValPartNo; ?></td>
                <td class="text-left"><?php echo $ValPartDes; ?></td>
                <td class="text-center"><?php echo $ValQtyUsage; ?></td>
                <td class="text-center"><?php echo $UOM; ?></td>
                <td class="text-right"><?php echo $ValOTSCost; ?></td>
                </tr>
            <?php
            $Number++;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-12"><h5><strong>Top 10 OTS Cost</strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                <th class="text-center trowCustom" width="30">No</th>
                <th class="text-center trowCustom" width="130">Part No.</th>
                <th class="text-center trowCustom" >Part Description</th>
                <th class="text-center trowCustom" width="130">Qty Usage</th>
                <th class="text-center trowCustom" width="180">Cost ($)</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $Number=1;
            $QListOTS = GET_TOP10_OVERALL_OTS_COST_PER_PRODUCT($ValQuoteCategory,$ValQuoteSelected,$ValClosedTime,$LinkOpt);
            while($RListOTS = sqlsrv_fetch_array($QListOTS))
            {
                $ValPartNo = trim($RListOTS['PartNo']);
                $ValPartDes = trim($RListOTS['PartDescription']);
                $ValQtyUsage = trim($RListOTS['QtyUsage']);
                $ValOTSCost = trim($RListOTS['TotalCost']);
                $ValOTSCost = number_format((float)$ValOTSCost, 2, '.', ',');

            ?>
                <tr>
                <td class="text-center"><?php echo $Number; ?></td>
                <td class="text-center"><?php echo $ValPartNo; ?></td>
                <td class="text-left"><?php echo $ValPartDes; ?></td>
                <td class="text-center"><?php echo $ValQtyUsage; ?></td>
                <td class="text-right"><?php echo $ValOTSCost; ?></td>
                </tr>
            <?php
            $Number++;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-12"><h5><strong>Product OTS Cost Per Division</strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableTotalOTSCostClosed">
            <thead class="theadCustom">
                <tr>
                    <td class="text-center trowCustom" width="30">No</td>
                    <td class="text-center trowCustom">Cost Allocation</td>
                    <td class="text-center trowCustom" width="80">Qty (Built)</td>
                    <td class="text-center trowCustom" width="110"><strong>OTS Cost Per Unit<br>($)</strong></td>
                </tr>
            </thead>
            <tbody>
                <?php 
            $QListOTSCost = GET_REPORT_RUNNING_COST_OTS_CLOSED($ValQuoteCategory,$ValClosedTime,$ValType,$ValQuoteSelected,$LinkOpt);
            $No2 = 1;      
            $ValTotalOTSCost = 0;     
            while($RListOTSCost = sqlsrv_fetch_array($QListOTSCost))
            {
                $ValExpenseAllocation = $RListOTSCost['ExpenseAllocation'];
                $ValOTSCost = $RListOTSCost['OTSCost'];
                $ValQtyQuoteOTS = $RListOTSCost['QtyQuote'];
                $ValQtyQuoteOTS = number_format((float)$ValQtyQuoteOTS, 0, '.', ',');
                $ValOTSCostRow = number_format((float)$ValOTSCost, 2, '.', ',');
                $ValTotalOTSCost = $ValTotalOTSCost + $ValOTSCost; 
                ?>
                <tr class="RowCostAllocation">
                    <td class="text-center"><?php echo $No2; ?></td>
                    <td class="text-left"><strong><?php echo $ValExpenseAllocation; ?></strong></td>
                    <td class="text-right"><?php echo $ValQtyQuoteOTS; ?></td>
                    <td class="text-right"><strong><?php echo $ValOTSCostRow; ?></strong></td>
                </tr>
                <?php
                $No2++;
            }     
                $ValTotalOTSCost2 = number_format((float)$ValTotalOTSCost, 2, '.', ',');       
                ?>
                <tr>
                    <td colspan="3" class="text-center"><strong>Total OTS Cost per Unit</strong></td>
                    <td class="text-right"><strong><?php echo $ValTotalOTSCost2; ?></strong></td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>
<?php
    $ValTotalPeopleCost = $arrTotal[0];
    $ValTotalMachineCost = $arrTotal[1];
    $ValTotalMaterialCost = $arrTotal[2];
    $ValTotalOTSCost = $arrTotal[3];
}
?>
<script>
    $(document).ready(function () {
        var TotalLabor = '<?php echo $ValTotalPeopleCost ?>';
        var TotalMachine = '<?php echo $ValTotalMachineCost ?>';
        var TotalMaterial = '<?php echo $ValTotalMaterialCost ?>';
        var TotalOTS = '<?php echo $ValTotalOTSCost ?>';
        var Category = '<?php echo $ValQuoteCategory ?>';
        var formdata = new FormData();
        formdata.append('TotalLabor', TotalLabor);
        formdata.append('TotalMachine', TotalMachine);
        formdata.append('TotalMaterial', TotalMaterial);
        formdata.append('TotalOTS', TotalOTS);
        formdata.append('Category', Category);
        $.ajax({
                url: 'project/CostTracking/SummaryChartQuote.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                $('#TableSummary').html("");
                $('#TableSummary').html("");
                $('#TableSummary').html("");
                $("#TableSummary").before('<div class="col-sm-12" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $("#TableSummary").html("");
                $('#TableSummary').html("");
                },
                success: function (xaxa) {
                $('#TableSummary').html("");
                $('#TableSummary').hide();
                $('#TableSummary').html(xaxa);
                $('#TableSummary').fadeIn('fast');
                $("#ContentLoadingTT").remove();
                },
                error: function () {
                alert("Request cannot proceed!");
                $('#TableSummary').html("");
                }
            });
        
    });
</script>