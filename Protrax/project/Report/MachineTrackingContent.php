<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleReport.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValStartDate = htmlspecialchars(trim($_POST['ValStartDate']), ENT_QUOTES, "UTF-8");
    $ValEndDate = htmlspecialchars(trim($_POST['ValEndDate']), ENT_QUOTES, "UTF-8");
    $ValCategory = htmlspecialchars(trim($_POST['ValCategory']), ENT_QUOTES, "UTF-8");
    $ValKeywords = htmlspecialchars(trim($_POST['ValKeywords']), ENT_QUOTES, "UTF-8");
    $ValUsedDate = htmlspecialchars(trim($_POST['Used']), ENT_QUOTES, "UTF-8");
    $ValUsedOpen = htmlspecialchars(trim($_POST['Open']), ENT_QUOTES, "UTF-8");
    $ValClosedtime = htmlspecialchars(trim($_POST['Half']), ENT_QUOTES, "UTF-8");

    // echo $ValStartDate."<br>".$ValEndDate."<br>".$ValCategory."<br>".$ValKeywords."<br>".$ValUsedDate."<br>".$ValClosedtime."<br>".$ValUsedOpen;
    
    $QData = GET_DATA_MACHINE_TRACK_CUSTOM($ValStartDate,$ValEndDate,$ValCategory,$ValKeywords,$ValUsedDate,$ValUsedOpen,$ValClosedtime,$linkMACHWebTrax);
  
?>
    <div class="col-md-6"><strong><h5>Data Machine Tracking [<?php echo $ValStartDate." - ".$ValEndDate; ?>]</h5></strong></div>
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive table-hover display" id="TableMachineTrack">
                        <thead>
                            <tr>
                                <th class="text-center">DateCreated</th>
                                <th class="text-center">MachineName</th>
                                <th class="text-center">Operator</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">Barcode_ID</th>
                                <th class="text-center">OrderType</th>
                                <th class="text-center">WO</th>
                                <th class="text-center">PartNo</th>
                                <th class="text-center">ExpenseAllocation</th>
                                <th class="text-center">WOParent</th>
                                <th class="text-center">Quote</th>
                                <th class="text-center">QuoteCategory</th>
                                <th class="text-center">QtyParent</th>
                                <th class="text-center">QtyQuote</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">StartTime</th>
                                <th class="text-center">EndTime</th>
                                <th class="text-center">Duration</th>
                                <th class="text-center">Stabilize</th>
                                <th class="text-center">FullStartTime</th>
                                <th class="text-center">FullEndTime</th>
                                <th class="text-center">DurationHours</th>
                                <th class="text-center">Side</th>
                                <th class="text-center">ShiftCode</th>
                                <th class="text-center">WOMapping_ID</th>
                                <th class="text-center">ClosedTime</th>
                                <th class="text-center">LocationCode</th>
                            </tr>
                        </thead>
                        <tbody>
                         <?php
                         $NoLoop = 1;
                         while($RData = sqlsrv_fetch_array($QData))
                         {
                         ?>
                            <tr>
                                <td class="text-center"><?php echo trim($RData['DateCreated']); ?></td>
                                <td class="text-center"><?php echo trim($RData['MachineName']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Operator']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Product']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Barcode_ID']); ?></td>
                                <td class="text-center"><?php echo trim($RData['OrderType']); ?></td>
                                <td class="text-center"><?php echo trim($RData['WO']); ?></td>
                                <td class="text-center"><?php echo trim($RData['PartNo']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ExpenseAllocation']); ?></td>
                                <td class="text-center"><?php echo trim($RData['WOParent']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Quote']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QuoteCategory']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QtyParent']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QtyQuote']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Qty']); ?></td>
                                <td class="text-center"><?php echo trim($RData['StartTime']); ?></td>
                                <td class="text-center"><?php echo trim($RData['EndTime']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Duration']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Stabilize']); ?></td>
                                <td class="text-center"><?php echo trim($RData['FullStartTime']); ?></td>
                                <td class="text-center"><?php echo trim($RData['FullEndTime']); ?></td>
                                <td class="text-center"><?php echo trim($RData['DurationHours']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Side']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ShiftCode']); ?></td>
                                <td class="text-center"><?php echo trim($RData['WOMapping_ID']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ClosedTime']); ?></td>
                                <td class="text-center"><?php echo trim($RData['LocationCode']); ?></td>
                            </tr>   
                         <?php
                         $NoLoop++;
                         }
                         ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php
}
?>