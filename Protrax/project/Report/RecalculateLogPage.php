<?php
require_once("../webtrax/project/CostTracking/Modules/ModuleCostTracking.php");
require_once("project/Report/Modules/ModuleReport.php");
require_once("ConfigDB.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<!-- <script src="project/Report/lib/LibWOMapping.js"></script> -->
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Administration : Recalculate Log</li>
            </ol>
        </nav>
    </div>
</div>
<?php
$DataRecal = GET_LASTTIME_RECALCULATE($linkMACHWebTrax);
while($Data = sqlsrv_fetch_array($DataRecal))
{
    $ValTime = trim($Data['DateLog']);
    $ValTime2 = trim($Data['DateLog2']);
    $HourLog = trim($Data['HourLog']);
    
    $ArrHour = explode(":",$HourLog);
    $OnlyHour = $ArrHour[0];
    // $ValTime2 = "04/10/2022";
    echo "LAST UPDATED : $ValTime2 $HourLog";
}
$DataWO = GET_UNUPDATED_PSM_WO($ValTime2,$OnlyHour,$linkMACHWebTrax);
?>
<div class="col-md-6">
<div class="col-md-6"><strong><br><h5>LIST NEED TO UPDATE</h5></strong></div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive table-hover display" id="TableReconWO">
                    <thead>
                        <tr>
                            <th class="text-center">Quote</th>
                            <th class="text-center">ExpenseAllocation</th>
                            <th class="text-center">WO_ID</th>
                            <th class="text-center">WOChild</th>
                            <th class="text-center">WOParent</th>
                            <th class="text-center">ClosedTime</th>
                            <th class="text-center">QtyParent</th>
                            <th class="text-center">QtyQuote</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">OrderType</th>
                            <th class="text-center">PM</th>
                            <th class="text-center">TargetDone</th>
                            <th class="text-center">DateClosed</th>
                            <th class="text-center">QuoteCategory</th>
                            <th class="text-center">LocationCode</th>
                            <th class="text-center">PSM_Idx</th>
                            <th class="text-center">WOType</th>
                            <th class="text-center">LastSync</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($DataRes = sqlsrv_fetch_array($DataWO))
                        {
                            $Quote = trim($DataRes['Quote']);
                            $ExpenseAllocation = trim($DataRes['ExpenseAllocation']);
                            $Idx = trim($DataRes['Idx']);
                            $WOChild = trim($DataRes['WOChild']);
                            $WOParent = trim($DataRes['WOParent']);
                            $ClosedTime = trim($DataRes['ClosedTime']);
                            $QtyParent = trim($DataRes['QtyParent']);
                            $QtyQuote = trim($DataRes['QtyQuote']);
                            $Product = trim($DataRes['Product']);
                            $OrderType = trim($DataRes['OrderType']);
                            $PM = trim($DataRes['PM']);
                            $TargetDone = trim($DataRes['TargetDone']);
                            $DateClosed = trim($DataRes['DateClosed']);
                            $QuoteCategory = trim($DataRes['QuoteCategory']);
                            $LocationCode = trim($DataRes['LocationCode']);
                            $PSM_Idx = trim($DataRes['PSM_Idx']);
                            $WOType = trim($DataRes['WOType']);
                            $LastSync = trim($DataRes['LastSync']);
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $Quote; ?></td>
                            <td class="text-center"><?php echo $ExpenseAllocation; ?></td>
                            <td class="text-center"><?php echo $Idx; ?></td>
                            <td class="text-center"><?php echo $WOChild; ?></td>
                            <td class="text-center"><?php echo $WOParent; ?></td>
                            <td class="text-center"><?php echo $ClosedTime; ?></td>
                            <td class="text-center"><?php echo $QtyParent; ?></td>
                            <td class="text-center"><?php echo $QtyQuote; ?></td>
                            <td class="text-center"><?php echo $Product; ?></td>
                            <td class="text-center"><?php echo $OrderType; ?></td>
                            <td class="text-center"><?php echo $PM; ?></td>
                            <td class="text-center"><?php echo $TargetDone; ?></td>
                            <td class="text-center"><?php echo $DateClosed; ?></td>
                            <td class="text-center"><?php echo $QuoteCategory; ?></td>
                            <td class="text-center"><?php echo $LocationCode; ?></td>
                            <td class="text-center"><?php echo $PSM_Idx; ?></td>
                            <td class="text-center"><?php echo $WOType; ?></td>
                            <td class="text-center"><?php echo $LastSync; ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $('#TableReconWO').removeAttr('width').DataTable({
    });
</script>
