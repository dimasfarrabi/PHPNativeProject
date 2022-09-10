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
    $arr = explode("*",$ValCodeDec);
    $Type = $arr[0];
    $YearOrMonth = $arr[1];
?>
<div><h5><strong>Year: </strong><?php echo $YearOrMonth; ?>.</h5></div>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="InboundTableDetail">
        <thead class="theadCustom">
            <tr>
                <th>Tracking ID</th>
                <th>Shipped Date</th>
                <th>To Subject</th>
                <th>From Subject</th>
                <th>Final Shipping Cost</th>
                <th>Weight</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $TotalWeight = $TotalFreight = 0;
            $Month = "-";
            $data = GET_INBOUND_DETAIL($Type,$YearOrMonth,$linkMACHWebTrax);
            while($res=sqlsrv_fetch_array($data))
            {
                $TrackingID = trim($res['TrackingID']);
                $ShippedDate = trim($res['ShippedDate']);
                $ToSubject2 = trim($res['ToSubject2']);
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
                <td class="text-center"><?php echo $ToSubject2; ?></td>
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
                <td class="text-center" colspan="4"><strong>TOTAL</strong></td>
                <td class="text-right"><strong><?php echo $TotalFreight; ?></strong></td>
                <td class="text-right"><strong><?php echo $TotalWeight; ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>
<?php
}
?>