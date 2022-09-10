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
    $ValClosedTime = htmlspecialchars(trim($_POST['ClosedTime']), ENT_QUOTES, "UTF-8");
    $ValFilType = htmlspecialchars(trim($_POST['FilterType']), ENT_QUOTES, "UTF-8");
    $ValKeywords = htmlspecialchars(trim($_POST['Keywords']), ENT_QUOTES, "UTF-8");
    $ValStats = htmlspecialchars(trim($_POST['UsedCL']), ENT_QUOTES, "UTF-8");
    $ValOpen = htmlspecialchars(trim($_POST['Open']), ENT_QUOTES, "UTF-8");
    // echo $ValClosedTime."||".$ValFilType."||".$ValStats."||".$ValKeywords."||".$ValOpen;

    $QData = GET_DATA_WO_MAPPING_CUSTOM($ValClosedTime,$ValFilType,$ValKeywords,$ValStats,$ValOpen,$linkMACHWebTrax);

?>
    <div class="col-md-6"><strong><h5>Data WO Mapping [<?php echo $ValClosedTime;?>]</h5></strong></div>
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive table-hover display" id="TableWOMapping">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Quote</th>
                                <th class="text-center">CostAllocation</th>
                                <th class="text-center">WOMapping_ID</th>
                                <th class="text-center">WOChild</th>
                                <th class="text-center">WOParent</th>
                                <th class="text-center">ClosedTime</th>
                                <th class="text-center">QtyParent</th>
                                <th class="text-center">QtyQuote</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">OrderType</th>
                                <th class="text-center">PM</th>
                                <th class="text-center">TargetLaborTime (Hour)</th>
                                <th class="text-center">LaborTime (Hour)</th>
                                <th class="text-center">TargetMachineTime (Hour)</th>
                                <th class="text-center">MachineTime (Hour)</th>
                                <th class="text-center">TargetMaterialCost($)</th>
                                <th class="text-center">MaterialCost($)</th>
                                <th class="text-center">EstFinishDate</th>
                                <th class="text-center">ClosedDate</th>
                                <th class="text-center">QuoteCategory</th>
                                <th class="text-center">QtyQCIn</th>
                                <th class="text-center">QtyQCOut</th>
                                <th class="text-center">MappingCode</th>
                                <th class="text-center">Location</th>
                                <th class="text-center">WOType</th>
                                <th class="text-center">PSM_Idx</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $NoLoop = 1;
                        while($RData = sqlsrv_fetch_array($QData))
                        {
                            $QtyParent = trim($RData['QtyParent']);
                            $QtyQuote = trim($RData['QtyQuote']);
                            $TargetLaborTime = trim($RData['TargetLaborTime']);
                            $TargetMachineTime = trim($RData['TargetMachineTime']);
                            $TargetMaterialCost = trim($RData['TargetMaterialCost']);
                            $LaborTime = trim($RData['LaborTime']);
                            $MachineTime = trim($RData['MachineTime']);
                            $MaterialCost = trim($RData['MaterialCost']);
                            $QtyQCIn = trim($RData['QtyQCIn']);
                            $QtyQCOut = trim($RData['QtyQCOut']);
                            $EstCostHour = trim($RData['TargetLaborTime']);

                            $QtyParent = number_format((float)$QtyParent,2,'.',',');
                            $QtyQuote = number_format((float)$QtyQuote,2,'.',',');
                            $TargetLaborTime = number_format((float)$TargetLaborTime,2,'.',',');
                            $TargetMachineTime = number_format((float)$TargetMachineTime,2,'.',',');
                            $TargetMaterialCost = number_format((float)$TargetMaterialCost,2,'.',',');
                            $LaborTime = number_format((float)$LaborTime,2,'.',',');
                            $MachineTime = number_format((float)$MachineTime,2,'.',',');
                            $MaterialCost = number_format((float)$MaterialCost,2,'.',',');
                            $QtyQCIn = number_format((float)$QtyQCIn,2,'.',',');
                            $QtyQCOut = number_format((float)$QtyQCOut,2,'.',',');
                            $EstCostHour = number_format((float)$EstCostHour,2,'.',',');
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $NoLoop; ?></td>
                                <td class="text-start"><?php echo trim($RData['Quote']); ?></td>
                                <td class="text-start"><?php echo trim($RData['ExpenseAllocation']); ?></td>
                                <td class="text-center"><?php echo trim($RData['WOMapping_ID']); ?></td>
                                <td class="text-start"><?php echo trim($RData['WOChild']); ?></td>
                                <td class="text-start"><?php echo trim($RData['WOParent']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ClosedTime']); ?></td>
                                <td class="text-center"><?php echo  $QtyParent; ?></td>
                                <td class="text-center"><?php echo $QtyQuote; ?></td>
                                <td class="text-center"><?php echo trim($RData['Product']); ?></td>
                                <td class="text-center"><?php echo trim($RData['OrderType']); ?></td>
                                <td class="text-start"><?php echo trim($RData['PM']); ?></td>
                                <td class="text-center"><?php echo $EstCostHour; ?></td>
                                <td class="text-center"><?php echo $LaborTime; ?></td>
                                <td class="text-center"><?php echo $TargetMachineTime; ?></td>
                                <td class="text-center"><?php echo $MachineTime; ?></td>
                                <td class="text-center"><?php echo $TargetMaterialCost; ?></td>
                                <td class="text-center"><?php echo $MaterialCost; ?></td>
                                <td class="text-center"><?php echo trim($RData['EstFinishDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ClosedDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QuoteCategory']); ?></td>
                                <td class="text-center"><?php echo $QtyQCIn; ?></td>
                                <td class="text-center"><?php echo $QtyQCOut; ?></td>
                                <td class="text-start"><?php echo trim($RData['MappingCode']); ?></td>
                                <td class="text-center"><?php echo trim($RData['LocationCode']); ?></td>
                                <td class="text-start"><?php echo trim($RData['WOType']); ?></td>
                                <td class="text-center"><?php echo trim($RData['PSM_Idx']); ?></td>
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
else{ echo "";}
?>