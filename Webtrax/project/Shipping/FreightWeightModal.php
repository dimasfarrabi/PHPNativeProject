<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleShippingChart.php"); 
date_default_timezone_set("Asia/Jakarta");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $NewValCodeEnc = base64_encode($ValCodeDec);
    $ArrCodeDec = explode("*",$ValCodeDec);
    $Val1 = $ArrCodeDec[0];
    $Val2 = $ArrCodeDec[1];
    $Val3 = $ArrCodeDec[2];
    if($Val3 != 'All')
    {
        $arr = explode("-",$Val2);
        $Month = date('M',mktime(0, 0, 0, $arr[0], 10));
        $Month2 = $arr[0];
        $Year = $arr[1];
    }
    else
    {
        $Month2 = "";
        $Year = $Val2;
    }
    // echo "$Val1 >> $Val2 >> $Val3";
    if($Val3 == 'All')
    {
    ?>
    <div><h5><strong>Year: </strong><?php echo $Val2; ?>.  <strong>Location: </strong><?php echo $Val1; ?>.</h5></div>
    <?php
    }
    else
    {
    ?>
    <div><h5><strong>Month/Year: </strong><?php echo "$Month / $Year"; ?>.  <strong>Location: </strong><?php echo $Val1; ?>.</h5></div>
    <?php
    }
    ?>
<style>
    .tableFixHead {
        overflow-y: auto;
        max-height: 500px;
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
    <div class="table-responsive tableFixHead">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th>Tracking ID</th>
                    <th>Shipped Date</th>
                    <th>To Customer</th>
                    <th>Country</th>
                    <th>Final Shipping Cost</th>
                    <th>Total Weight</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no=1;
                $TotalCost = $TotalWeight = 0;
                $Loc = $Val1;
                $Data = GET_DETAIL_FREIGHT($Month2,$Year,$Loc,$Val3,$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($Data))
                {
                    $ValDate = trim($res['Date']);
                    $ToSubject = trim($res['ToSubject']);
                    $AccShippingCountry = trim($res['AccShippingCountry']);
                    $FinalShippingCost = trim($res['FinalShippingCost']);
                    $TrackingID = trim($res['TrackingID']);
                    $Weight = trim($res['TotalWeight']);
                    $TotalCost = @($TotalCost + $FinalShippingCost);
                    $TotalWeight = @($TotalWeight + $Weight);
                    $FinalShippingCost = number_format(sprintf('%.2f',floatval($FinalShippingCost)),2,'.',',');
                    $Weight = number_format(sprintf('%.2f',floatval($Weight)),2,'.',',');
                ?>
                <tr>
                    <td class="text-left"><?php echo $TrackingID; ?></td>
                    <td class="text-center"><?php echo $ValDate; ?></td>
                    <td class="text-left"><?php echo $ToSubject; ?></td>
                    <td class="text-left"><?php echo $AccShippingCountry; ?></td>
                    <td class="text-right"><?php echo $FinalShippingCost; ?></td>
                    <td class="text-right"><?php echo $Weight; ?></td>
                </tr>
                <?php
                $no++;
                }
                $TotalCost = number_format(sprintf('%.2f',floatval($TotalCost)),2,'.',',');
                $TotalWeight = number_format(sprintf('%.2f',floatval($TotalWeight)),2,'.',',');
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center theadCustom" colspan="4"><strong>TOTAL</strong></td>
                    <td class="text-right theadCustom"><strong><?php echo $TotalCost; ?></strong></td>
                    <td class="text-right theadCustom"><strong><?php echo $TotalWeight; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php
}
?>