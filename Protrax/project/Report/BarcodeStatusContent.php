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
    $ValTipeDate = htmlspecialchars(trim($_POST['ValTipeDate']), ENT_QUOTES, "UTF-8");
    $ValDataType = htmlspecialchars(trim($_POST['ValDataType']), ENT_QUOTES, "UTF-8");
    $ValUsedDate = htmlspecialchars(trim($_POST['ValUsedDate']), ENT_QUOTES, "UTF-8");
    $ValCategory = htmlspecialchars(trim($_POST['ValCategory']), ENT_QUOTES, "UTF-8");
    $ValKeywords = htmlspecialchars(trim($_POST['ValKeywords']), ENT_QUOTES, "UTF-8");
    $UsedOpen = htmlspecialchars(trim($_POST['UsedOpen']), ENT_QUOTES, "UTF-8");
    $ClosedTime = htmlspecialchars(trim($_POST['ClosedTime']), ENT_QUOTES, "UTF-8");
    // echo $ValStartDate."<br>".$ValEndDate."<br>".$ValTipeDate."<br>".
    // $ValDataType."<br>".$ValUsedDate."<br>".$ValCategory."<br>".$ValKeywords."<br>".
    // $UsedOpen."<br>".$ClosedTime;

    $QData = GET_DATA_BARCODE_STATUS_CUSTOM($ValStartDate,$ValEndDate,$ValTipeDate,$ValDataType,$ValUsedDate,$ValCategory,$ValKeywords,$UsedOpen,$ClosedTime,$linkMACHWebTrax);
    
    if($ValUsedDate == "on" && $ValKeywords != ""){
    ?>
    <div class="col-md-12"><strong><h5>Data Barcode Status [<?php echo $ValStartDate;?> - <?php echo $ValEndDate;?> | <?php echo $ValCategory;?> - <?php echo $ValKeywords;?>]</h5></strong></div>
    <?php
    } else{
    ?>
    <div class="col-md-12"><strong><h5>Data Barcode Status [<?php echo $ValStartDate;?> - <?php echo $ValEndDate;?>]</h5></strong></div>
    <?php
    }
    ?>
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive table-hover display" id="TableBarcodeStatus">
                        <thead>
                            <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">BarcodeID</th>
                            <th class="text-center">PSM_BC</th>
                            <th class="text-center">Code</th>
                            <th class="text-center">BC_ID_Material</th>
                            <th class="text-center">Notes</th>
                            <th class="text-center">CuttingDate</th>
                            <th class="text-center">PPIC</th>
                            <th class="text-center">WO</th>
                            <th class="text-center">PartNo</th>
                            <th class="text-center">QtyInitial</th>
                            <th class="text-center">QtyPassed</th>
                            <th class="text-center">FinishingCode</th>
                            <th class="text-center">DateCreate</th>
                            <th class="text-center">PrintDate</th>
                            <th class="text-center">MachiningCheckIn</th>
                            <th class="text-center">MachiningCheckOut</th>
                            <th class="text-center">StartCheckQC</th>
                            <th class="text-center">StatusQC1</th>
                            <th class="text-center">QC2CheckInDate</th>
                            <th class="text-center">QC2CheckOutDate</th>
                            <th class="text-center">FinishingChekIn</th>
                            <th class="text-center">FinishingChekOut</th>
                            <th class="text-center">PartFinishedDate</th>
                            <th class="text-center">StatusQC2</th>
                            <th class="text-center">StatusQCEngineer</th>
                            <th class="text-center">QCEngineerFinishDate</th>
                            <th class="text-center">ForcedClosed</th>
                            <th class="text-center">ClosedTime</th>
                            <th class="text-center">LocationCode</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $No = 1;
                        while($RData = sqlsrv_fetch_array($QData))
                        {
                            $RowEnc = base64_encode(trim($RData['PSL_Barcode']));
                            $ValOptForm = '<span class="bi bi-arrow-up-right-square-fill PointerList UpdateRow" aria-hidden="true" data-bs-toggle="modal" data-ecode="'.$RowEnc.'" data-bs-target="#BCDetail" title="Detail"></span>';
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $ValOptForm; ?></td>
                                <td class="text-center"><?php echo trim($RData['PSL_Barcode']); ?></td>
                                <td class="text-center"><?php echo trim($RData['PSM_Barcode']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Code']); ?></td>
                                <td class="text-center"><?php echo trim($RData['BC_ID_Material']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Notes']); ?></td>
                                <td class="text-center"><?php echo trim($RData['CuttingDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['PPIC']); ?></td>
                                <td class="text-center"><?php echo trim($RData['WO']); ?></td>
                                <td class="text-center"><?php echo trim($RData['PartNo']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QtyInitial']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QtyPassed']); ?></td>
                                <td class="text-center"><?php echo trim($RData['FinishingCode']); ?></td>
                                <td class="text-center"><?php echo trim($RData['DateCreate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['StickerPrintDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['MachiningCheckInDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['MachiningCheckOutDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QCCheckInDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['StatusQC1']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QC2CheckInDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QC2CheckOutDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['FinishingCheckInDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['FinishingCheckOutDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['PFDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['StatusQC2']); ?></td>
                                <td class="text-center"><?php echo trim($RData['StatusQCEngineer']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QCEngineerFinishDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Is_ForcedClosed']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ClosedTime']); ?></td>
                                <td class="text-center"><?php echo trim($RData['LocationCode']); ?></td>
                            </tr>
                        <?php
                        $No++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="BCDetail" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Barcode Status Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div id="DetailContent"></div>
                <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<?php

}
else { echo "error";}
?>