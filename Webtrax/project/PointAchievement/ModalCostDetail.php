<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePointAchievement.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $NewValCodeEnc = base64_encode($ValCodeDec);
    $ArrCodeDec = explode("*",$ValCodeDec);
    $Quote = $ArrCodeDec[0];
    $ClosedTime = $ArrCodeDec[1];
    $Role = $ArrCodeDec[2];
    $Name = $ArrCodeDec[3];
    $Expense = $ArrCodeDec[4];
    // echo "$Quote >> $ClosedTime >> $Expense";
?>
<style>
    .Target{color:#ff0000;}
    .Actual{color:#0008ff;}
</style>
<h5>Quote : <strong><?php echo $Quote; ?></strong>.</h5>
<h5>Closed Time : <strong><?php echo $ClosedTime; ?></strong>.</h5>
<div class="table-resposive">
    <table class="table table-responsive table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th class="text-center">Expense Allocation</th>
                <th class="text-center">Target Labor Cost ($)</th>
                <th class="text-center">Actual Labor Cost ($)</th>
                <th class="text-center">Target Machine Cost ($)</th>
                <th class="text-center">Actual Machine Cost ($)</th>
                <th class="text-center">Target Material Cost ($)</th>
                <th class="text-center">Actual Material Cost ($)</th>
                <th class="text-center">Total Target Cost($)</th>
                <th class="text-center">Total Actual Cost($)</th>
                <th class="text-center">Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $a=1;
            $TotalTarget = $TotalActual = 0;
            $data = GET_DETAIL_QUOTE_COST($Quote,$ClosedTime,$Role,$Name,$Expense,$linkMACHWebTrax);
            while($Datares=sqlsrv_fetch_array($data))
            {
                $Expense = trim($Datares['ExpenseAllocation']);
                $TargetLabor = trim($Datares['TargetPeopleCost']);
                $LaborCost = trim($Datares['PeopleCost']);
                $TargetMachine = trim($Datares['TargetMachineCost']);
                $MachineCost = trim($Datares['MachineCost']);
                $targetMaterial = trim($Datares['TargetMaterialCost']);
                $MaterialCost = trim($Datares['MaterialCost']);
                $Qty = trim($Datares['QtyQuote']);
                $TotalTargetCost = @($TargetLabor+$TargetMachine+$targetMaterial);
                $TotalActualCost = @($LaborCost+$MachineCost+$MaterialCost);
                $TotalTarget = $TotalTarget + $TotalTargetCost;
                $TotalActual = $TotalActual + $TotalActualCost;

                $TargetLabor = number_format((float)$TargetLabor,2,'.',',');
                $LaborCost = number_format((float)$LaborCost,2,'.',',');
                $TargetMachine = number_format((float)$TargetMachine,2,'.',',');
                $MachineCost = number_format((float)$MachineCost,2,'.',',');
                $targetMaterial = number_format((float)$targetMaterial,2,'.',',');
                $MaterialCost = number_format((float)$MaterialCost,2,'.',',');
                $TotalTargetCost = number_format((float)$TotalTargetCost,2,'.',',');
                $TotalActualCost = number_format((float)$TotalActualCost,2,'.',',');

            ?>
            <tr>
                <td class="text-left"><?php echo $Expense; ?></td>
                <td class="text-right Target"><span><?php echo $TargetLabor; ?></span></td>
                <td class="text-right Actual"><span onclick="GET_RAW_DATA('<?php echo $Expense; ?>','Labor')" style="cursor:pointer;"><?php echo $LaborCost; ?></span></td>
                <td class="text-right Target"><span><?php echo $TargetMachine; ?></span></td>
                <td class="text-right Actual"><span onclick="GET_RAW_DATA('<?php echo $Expense; ?>','Machine')" style="cursor:pointer;"><?php echo $MachineCost; ?></span></td>
                <td class="text-right Target"><span><?php echo $targetMaterial; ?></span></td>
                <td class="text-right Actual"><span onclick="GET_RAW_DATA('<?php echo $Expense; ?>','Material')" style="cursor:pointer;"><?php echo $MaterialCost; ?></span></td>
                <td class="text-right Target"><span><?php echo $TotalTargetCost; ?></span></td>
                <td class="text-right Actual"><span><?php echo $TotalActualCost; ?></span></td>
                <td class="text-right Actual"><span><?php echo $Qty; ?></span></td>
            </tr>
            <?php
            $a++;
            }
            $TotalTarget = number_format((float)$TotalTarget,2,'.',',');
            $TotalActual = number_format((float)$TotalActual,2,'.',',');
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="text-center" colspan="7"><strong>TOTAL</strong></td>
                <td class="text-right Target"><strong><?php echo $TotalTarget; ?></strong></td>
                <td class="text-right Actual"><strong><?php echo $TotalActual; ?></strong></td>
                <td class="text-right Actual"><strong></td>
            </tr>
        </tfoot>
    </table>
</div>
<?php
}
?>
<script>
    $(document).ready(function () {
        
    });
    function GET_RAW_DATA(Data1,Data2)
    {
        var Cat = 'Quote';
        var Quote = '<?php echo $Quote; ?>';
        var Half = '<?php echo $ClosedTime; ?>';
        var Tipe = Data2;
        var Expense =  Data1;
        window.location.href = 'project/CostTracking/DownloadRawData.php?Cat='+Cat+'&&Quote='+Quote+'&&Half='+Half+'&&Tipe='+Tipe+'&&Expense='+Expense;
    }
</script>