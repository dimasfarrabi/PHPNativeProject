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

    // echo "Date 1:".$ValStartDate."<br>Date 2:".$ValEndDate."<br> Category:".$ValCategory."<br>Keywords:".$ValKeywords."<br>UsedDate:".$ValUsedDate."<br>ClosedTime:".$ValClosedtime."<br>UsedOpen:".$ValUsedOpen;
   
    $QData = GET_DATA_MATERIAL_TRACK_CUSTOM2($ValStartDate,$ValEndDate,$ValCategory,$ValKeywords,$ValUsedDate,$ValUsedOpen,$ValClosedtime,$linkMACHWebTrax);

?>
    <div class="col-md-6"><strong><h5>Data Material Tracking [<?php echo $ValStartDate." - ".$ValEndDate; ?>]</h5></strong></div>
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive table-hover display" id="TableMaterialTrack">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">InputCode</th>
                                <th class="text-center">DateIssue</th>
                                <th class="text-center">WOMapping_ID</th>
                                <th class="text-center">Employee</th>
                                <th class="text-center">WOChild</th>
                                <th class="text-center">ExpenseAllocation</th>
                                <th class="text-center">WOParent</th>
                                <th class="text-center">Quote</th>
                                <th class="text-center">ClosedTime</th>
                                <th class="text-center">QtyParent</th>
                                <th class="text-center">QtyQuote</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">PartNo</th>
                                <th class="text-center">PartDescription</th>
                                <th class="text-center">TransactUOM</th>
                                <th class="text-center">TransactCost</th>
                                <th class="text-center">QtyUsage</th>
                                <th class="text-center">TotalCost</th>
                                <th class="text-center">CategoryUsage</th>
                                <th class="text-center">AdjustmentStatus</th>
                                <th class="text-center">ReceivedBy</th>
                                <th class="text-center">QtyReceived</th>
                                <th class="text-center">Notes</th>
                                <th class="text-center">EstCostHour</th>
                                <th class="text-center">EstFinishDate</th>
                                <th class="text-center">IdImport</th>
                                <th class="text-center">QuoteCategory</th>
                                <th class="text-center">TLI_ID</th>
                                <th class="text-center">Location</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $NoLoop = 1;
                        while($RData = sqlsrv_fetch_array($QData))
                        {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $NoLoop; ?></td>
                                <td class="text-center"><?php echo trim($RData['InputCode']); ?></td>
                                <td class="text-center"><?php echo trim($RData['DateIssue']); ?></td>
                                <td class="text-center"><?php echo trim($RData['WOMapping_ID']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Employee']); ?></td>
                                <td class="text-center"><?php echo trim($RData['WOChild']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ExpenseAllocation']); ?></td>
                                <td class="text-center"><?php echo trim($RData['WOParent']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Quote']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ClosedTime']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QtyParent']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QtyQuote']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Product']); ?></td>
                                <td class="text-center"><?php echo trim($RData['PartNo']); ?></td>
                                <td class="text-center"><?php echo trim($RData['PartDescription']); ?></td>
                                <td class="text-center"><?php echo trim($RData['TransactUOM']); ?></td>
                                <td class="text-center"><?php echo trim($RData['TransactCost']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QtyUsage']); ?></td>
                                <td class="text-center"><?php echo trim($RData['TotalCost']); ?></td>
                                <td class="text-center"><?php echo trim($RData['CategoryUsage']); ?></td>
                                <td class="text-center"><?php echo trim($RData['AdjustmentStatus']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ReceivedBy']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QtyReceived']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Notes']); ?></td>
                                <td class="text-center"><?php echo trim($RData['EstCostHour']); ?></td>
                                <td class="text-center"><?php echo trim($RData['EstFinishDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['IdImport']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QuoteCategory']); ?></td>
                                <td class="text-center"><?php echo trim($RData['TLI_ID']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Location']); ?></td>
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