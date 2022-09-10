<?php
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTrackingChart.php");
date_default_timezone_set("Asia/Jakarta");
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $NewValCodeEnc = base64_encode($ValCodeDec);
    $ArrCodeDec = explode("*",$ValCodeDec);
    // echo $ValCodeDec;
    $QuoteID = $ArrCodeDec[0];
    $Year = $ArrCodeDec[1];
    $Category = $ArrCodeDec[2];
?>
<style>
    .tableFixHead {
        overflow-y: auto;
        max-height: 550px;
      }
      .tableFixHead thead th {
        position: sticky;
        top: 0;
      }
      table {
        border-collapse: collapse;
        width: 100%;
      }
      th,
      td {
        padding: 8px 16px;
        border: 1px solid #ccc;
      }
      th {
        background: #eee;
      }
</style>
<h5>Project : <strong><?php echo $QuoteID; ?></strong></h5>
<h5>Year : <strong><?php echo $Year; ?></strong></h5>
    <i>*)Labor Cost + Machine Cost + Material Cost + OTS Cost</i>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th>Quote</th>
                    <th>Half Closed</th>
                    <th>Labor Cost ($)</th>
                    <th>Machine Cost ($)</th>
                    <th>Material Cost ($)</th>
                    <th>OTS Cost ($)</th>
                    <th>Total Actual Cost ($)*</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $AllCost = $TotalAllCost = 0;
                $data = GET_DETAIL_MODAL_CHART_UNQUOTE($Year,$Category,$QuoteID,$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($data))
                {
                    $ValQuote = trim($res['Quote']);
                    $ValHalf = trim($res['ClosedTime']);
                    $ManCost = trim($res['ManCost']);
                    $MachCost = trim($res['MachCost']);
                    $MatCost = trim($res['MatCost']);
                    $TotalActualCost = trim($res['TotalActualCost']);
                    $TotalOTS = trim($res['OTSCost']);
                    $TotalAllCost = @($TotalAllCost + $TotalActualCost);
                    if($ManCost == ''){$ManCost = 0;}
                    if($MachCost == ''){$MachCost = 0;}
                    if($MatCost == ''){$MatCost = 0;}
                    if($TotalActualCost == ''){$TotalActualCost = 0;}
                    if($TotalOTS == ''){$TotalOTS = 0;}
                    $ManCost = number_format((float)$ManCost, 2, '.', ',');
                    $MachCost = number_format((float)$MachCost, 2, '.', ',');
                    $MatCost = number_format((float)$MatCost, 2, '.', ',');
                    $TotalActualCost = number_format((float)$TotalActualCost, 2, '.', ',');
                    $TotalOTS = number_format((float)$TotalOTS, 2, '.', ',');
                ?>
                <tr>
                    <td class="text-left"><?php echo $ValQuote; ?></td>
                    <td class="text-center"><?php echo $ValHalf; ?></td>
                    <td class="text-right"><?php echo $ManCost; ?></td>
                    <td class="text-right"><?php echo $MachCost; ?></td>
                    <td class="text-right"><?php echo $MatCost; ?></td>
                    <td class="text-right"><?php echo $TotalOTS; ?></td>
                    <td class="text-right"><?php echo $TotalActualCost; ?></td>
                </tr>
                <?php
                }
                $TotalAllCost = number_format((float)$TotalAllCost, 2, '.', ',');
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="6"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $TotalAllCost; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php
}

?>