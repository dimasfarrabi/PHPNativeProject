<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleShippingChart.php"); 
date_default_timezone_set("Asia/Jakarta");
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
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    // echo $ValCodeDec;
    $arr = explode("+",$ValCodeDec);
    $Month = $arr[0];
    $Year = $arr[1];
    $Loc = $arr[2];
    $BulanNama = $arr[3];
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
<div><h5><strong>Month/Year: </strong><?php echo "$BulanNama / $Year"; ?>.  <strong>Location: </strong><?php echo $Loc; ?>.</h5></div>
<div class="table-responsive tableFixHead">
    <table class="table table-bordered table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th>Tracking ID</th>
                <th>Shipped Date</th>
                <th>From Subject</th>
                <th>Final Shipping Cost</th>
                <th>Weight</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $TotalWeight = $TotalFreight = 0;
            $data = GET_INBOUND_DETAIL($Month,$Year,$Loc,$linkMACHWebTrax);
            while($res=sqlsrv_fetch_array($data))
            {
                $TrackingID = trim($res['TrackingID']);
                $ShippedDate = trim($res['ShippedDate']);
                $FromSubject = trim($res['FromSubject']);
                $Freight = trim($res['FinalShippingCost']);
                $Weight = trim($res['TotalWeight']);
                $TotalWeight = @($TotalWeight + $Weight);
                $TotalFreight = @($TotalFreight + $Freight);
                $Freight = number_format((float)$Freight, 2, '.', ',');
                $Weight = number_format((float)$Weight, 2, '.', ',');
            ?>
            <tr>
                <td class="text-left"><?php echo $TrackingID; ?></td>
                <td class="text-center"><?php echo $ShippedDate; ?></td>
                <td class="text-left"><?php echo $FromSubject; ?></td>
                <td class="text-right"><?php echo $Freight; ?></td>
                <td class="text-right"><?php echo $Weight; ?></td>
            </tr>
            <?php
            }
            $TotalFreight = number_format((float)$TotalFreight, 2, '.', ',');
            $TotalWeight = number_format((float)$TotalWeight, 2, '.', ',');
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="text-center" colspan="3">TOTAL</td>
                <td class="text-right"><?php echo $TotalFreight; ?></td>
                <td class="text-right"><?php echo $TotalWeight; ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<?php
}
?>