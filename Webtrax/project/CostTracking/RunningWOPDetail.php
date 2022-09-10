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
    $ValFloat = htmlspecialchars(trim($_POST['ValFloat']), ENT_QUOTES, "UTF-8");
    $ValFloat = base64_decode(base64_decode($ValFloat));
    $ArrValFloat = explode("#",$ValFloat);
    $WOP = $ArrValFloat[0];
    $Expense = $ArrValFloat[1];
    $Quote = $ArrValFloat[2];
    $Arr = array("Labor Cost","Machine Cost","Material Cost");
?>
<br></br>
<div class="col-md-12"><h5><strong>Running Cost WO Parent : <?php echo $WOP; ?>, Expense : <?php echo $Expense; ?></strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom">Cost type</th>
                    <th class="text-center trowCustom">Running Cost ($)*</th>
                    <th class="text-center trowCustom">Target Cost ($)*</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $TotalRunCost = $TotalTargetCost = 0;
            foreach($Arr as $ArrStage)
            {
                $Data = GET_DETAIL_COST_WOP($Quote,$WOP,$Expense,$ArrStage,$linkMACHWebTrax);
                while($DataRes = sqlsrv_fetch_array($Data))
                {
                $Cost = trim($DataRes['RunningCost']);
                $TargetCost = trim($DataRes['TargetCost']);
                $Qtys = trim($DataRes['Qty']);
                if( $Qtys == 0){ $Qty = 1; } else { $Qty = $Qtys; }
                $RunCost = @($Cost/$Qty);
                $TotalRunCost = $TotalRunCost + $RunCost;
                $TotalTargetCost = $TotalTargetCost + $TargetCost;
                $RunCost = number_format((float)$RunCost, 2, '.', ',');
                $TargetCost = number_format((float)$TargetCost, 2, '.', ',');
            ?>
                <tr>
                    <td class="text-left"><?php echo $ArrStage; ?></td>
                    <td class="text-right"><?php echo $RunCost; ?></td>
                    <td class="text-right"><?php echo $TargetCost; ?></td>
                </tr>
            <?php
                }
            }
            
            $TotalRunCost = number_format((float)$TotalRunCost, 2, '.', ',');
            $TotalTargetCost = number_format((float)$TotalTargetCost, 2, '.', ',');
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $TotalRunCost; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalTargetCost; ?></strong></td>
                </tr>
            </tfoot><i>*) Per Each System</i>
        </table>
    </div>
</div>
<?php
}
?>