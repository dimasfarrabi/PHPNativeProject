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
    $Par = $arr[0];
    $MonthOrYear = $arr[1];
    $MainArray = array();
    $FromSubject = "-";
    $data = GET_DETAIL_FREIGHT_ALL($Par,$MonthOrYear,$FromSubject,$linkMACHWebTrax);
    while($res=sqlsrv_fetch_array($data))
    {
        $TrackingID = trim($res['TrackingID']);
        $ShippedDate = trim($res['ShippedDate']);
        $Destination = trim($res['Destination']);
        $Cost = trim($res['Cost']);
        $Weight = trim($res['TotalWeight']);
        $Loc = trim($res['Loc']);
        $tempArray = array("TrackingID" => $TrackingID, "ShippedDate" => $ShippedDate, "Destination" => $Destination,
        "Cost" => $Cost, "Weight" => $Weight, "Loc" => $Loc);
        array_push($MainArray,$tempArray);
    }
    $FromSubject = "FOR";
    $data2 = GET_DETAIL_FREIGHT_ALL($Par,$MonthOrYear,$FromSubject,$linkMACHWebTrax);
    while($res2=sqlsrv_fetch_array($data2))
    {
        $TrackingID = trim($res2['TrackingID']);
        $ShippedDate = trim($res2['ShippedDate']);
        $Destination = trim($res2['Destination']);
        $Cost = trim($res2['Cost']);
        $Weight = trim($res2['TotalWeight']);
        $Loc = trim($res2['Loc']);
        $tempArray = array("TrackingID" => $TrackingID, "ShippedDate" => $ShippedDate, "Destination" => $Destination,
        "Cost" => $Cost, "Weight" => $Weight, "Loc" => $Loc);
        array_push($MainArray,$tempArray);
    }
?>
<!-- <style>
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
</style> -->
<div><h5><strong>Year: </strong><?php echo $MonthOrYear; ?>.  &nbsp;</h5></div>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="DetailALL">
        <thead class="theadCustom">
            <tr>
                <th>No</th>
                <th>Tracking ID</th>
                <th>Shipped Date</th>
                <th>From Subject</th>
                <th>Destination</th>
                <th>Final Shipping Cost ($)</th>
                <th>Weight</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no=1;
        foreach($MainArray as $Main)
        {
            $TrackingID = trim($Main['TrackingID']);
            $ShippedDate = trim($Main['ShippedDate']);
            $Destination = trim($Main['Destination']);
            $Cost = trim($Main['Cost']);
            $Weight = trim($Main['Weight']);
            $FromSubject = trim($Main['Loc']);
            $TotalCost = @($TotalCost + $Cost);
            $TotalWeight = @($TotalWeight + $Weight);
            $Cost = number_format((float)$Cost, 2, '.', ',');
            $Weight = number_format((float)$Weight, 2, '.', ',');
        ?>
        <tr>
            <td class="text-center"><?php echo $no; ?></td>
            <td class="text-left"><?php echo $TrackingID; ?></td>
            <td class="text-center"><?php echo $ShippedDate; ?></td>
            <td class="text-center"><?php echo $FromSubject; ?></td>
            <td class="text-left"><?php echo $Destination; ?></td>
            <td class="text-right"><?php echo $Cost; ?></td>
            <td class="text-right"><?php echo $Weight; ?></td>
        </tr>
        <?php
        $no++;
        }
        $TotalCost = number_format((float)$TotalCost, 2, '.', ',');
        $TotalWeight = number_format((float)$TotalWeight, 2, '.', ',');
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="text-center" colspan="5"><strong>TOTAL</strong></td>
                <td class="text-right"><strong><?php echo $TotalCost; ?></strong></td>
                <td class="text-right"><strong><?php echo $TotalWeight; ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>
<?php  
}
?>