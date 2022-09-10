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
    $datax = base64_decode(htmlspecialchars(trim($_POST['aFloat']), ENT_QUOTES, "UTF-8"));
    // echo $datax;
    $arr = explode("*",$datax);
    $WOP = $arr[0];
    $QuoteCategory = $arr[1];
    $ClosedTime = $arr[2];
?>
<div class="col-md-6">
    <h5><strong>TOP 10 Material Cost</strong></h5>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom">PartNo</th>
                    <th class="text-center trowCustom">Part Description</th>
                    <th class="text-center trowCustom">Qty Usage</th>
                    <th class="text-center trowCustom">Total Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $GetData = GET_TOP10_MATERIAL_OPEN($WOP,$QuoteCategory,$ClosedTime,$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($GetData))
                {
                    $PartNo = trim($res['PartNo']);
                    $PartDesc = trim($res['PartDescription']);
                    $QtyUsage = trim($res['Qty']);
                    $TotalCost = trim($res['Cost']);
                    $QtyUsage = number_format((float)$QtyUsage, 2, '.', ',');
                    $TotalCost = number_format((float)$TotalCost, 2, '.', ',');
                ?>
                <tr>
                    <td class="text-left"><?php echo $PartNo; ?></td>
                    <td class="text-left"><?php echo $PartDesc; ?></td>
                    <td class="text-right"><?php echo $QtyUsage; ?></td>
                    <td class="text-right"><?php echo $TotalCost; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-6">
    <h5><strong>TOP 10 Material OTS Cost</strong></h5>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom">PartNo</th>
                    <th class="text-center trowCustom">Part Description</th>
                    <th class="text-center trowCustom">Qty Usage</th>
                    <th class="text-center trowCustom">Total Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $GetData2 = GET_TOP10_OTS_OPEN($WOP,$QuoteCategory,$ClosedTime,$linkMACHWebTrax);
                while($res2=sqlsrv_fetch_array($GetData2))
                {
                    $PartNo = trim($res2['PartNo']);
                    $PartDesc = trim($res2['PartDescription']);
                    $QtyUsage = trim($res2['Qty']);
                    $TotalCost = trim($res2['Cost']);
                    $QtyUsage = number_format((float)$QtyUsage, 2, '.', ',');
                    $TotalCost = number_format((float)$TotalCost, 2, '.', ',');
                ?>
                <tr>
                    <td class="text-left"><?php echo $PartNo; ?></td>
                    <td class="text-left"><?php echo $PartDesc; ?></td>
                    <td class="text-right"><?php echo $QtyUsage; ?></td>
                    <td class="text-right"><?php echo $TotalCost; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
}
?>