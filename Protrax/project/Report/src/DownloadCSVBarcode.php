<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleReport.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $ValStartDate = htmlspecialchars(trim($_GET['ds']), ENT_QUOTES, "UTF-8");
    $ValEndDate = htmlspecialchars(trim($_GET['de']), ENT_QUOTES, "UTF-8");
    $ValTipeDate = htmlspecialchars(trim($_GET['typ']), ENT_QUOTES, "UTF-8");
    $ValDataType = htmlspecialchars(trim($_GET['fil']), ENT_QUOTES, "UTF-8");
    $ValUsedDate = htmlspecialchars(trim($_GET['used']), ENT_QUOTES, "UTF-8");
    $ValCategory = htmlspecialchars(trim($_GET['cat']), ENT_QUOTES, "UTF-8");
    $ValKeywords = htmlspecialchars(trim($_GET['key']), ENT_QUOTES, "UTF-8");
    $UsedOpen = htmlspecialchars(trim($_GET['op']), ENT_QUOTES, "UTF-8");
    $ClosedTime = htmlspecialchars(trim($_GET['clo']), ENT_QUOTES, "UTF-8");

    // echo $ValStartDate."<br>".$ValEndDate."<br>".$ValTipeDate."<br>".
    // $ValDataType."<br>".$ValUsedDate."<br>".$ValCategory."<br>".$ValKeywords."<br>".
    // $UsedOpen."<br>".$ClosedTime;
    
    date_default_timezone_set("Asia/Jakarta");
    $TimeNow = date('Y_m_d_H_i_s');
    $filename = "BarcodeStatus[".$ValStartDate."-".$ValEndDate."]_$TimeNow.csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array(
        'No',
        'BarcodeID',
        'PSM_BC',
        'Code',
        'BC_ID_Material',
        'Notes',
        'CuttingDate',
        'PPIC',
        'WO',
        'PartNo',
        'QtyInitial',
        'QtyPassed',
        'FinishingCode',
        'DateCreate',
        'PrintDate',
        'MachiningCheckIn',
        'MachiningCheckOut',
        'StartCheckQC',
        'StatusQC1',
        'QC2CheckInDate',
        'QC2CheckOutDate',
        'FinishingChekIn',
        'FinishingChekOut',
        'PartFinishedDate',
        'StatusQC2',
        'StatusQCEngineer',
        'QCEngineerFinishDate',
        'ForcedClosed',
        'ClosedTime',
        'LocationCode'));

        $No = 1;
        $QData = GET_DATA_BARCODE_STATUS_CUSTOM($ValStartDate,$ValEndDate,$ValTipeDate,$ValDataType,$ValUsedDate,$ValCategory,$ValKeywords,$UsedOpen,$ClosedTime,$linkMACHWebTrax);
        while($RData = sqlsrv_fetch_array($QData))
        {

            $ArrayTemp = array(
                $No,
                trim($RData['PSL_Barcode']),
                trim($RData['PSM_Barcode']),
                trim($RData['Code']),
                trim($RData['BC_ID_Material']),
                trim($RData['Notes']),
                trim($RData['CuttingDate']),
                trim($RData['PPIC']),
                trim($RData['WO']),
                trim($RData['PartNo']),
                trim($RData['QtyInitial']),
                trim($RData['QtyPassed']),
                trim($RData['FinishingCode']),
                trim($RData['DateCreate']),
                trim($RData['StickerPrintDate']),
                trim($RData['MachiningCheckInDate']),
                trim($RData['MachiningCheckOutDate']),
                trim($RData['QCCheckInDate']),
                trim($RData['StatusQC1']),
                trim($RData['QC2CheckInDate']),
                trim($RData['QC2CheckOutDate']),
                trim($RData['FinishingCheckInDate']),
                trim($RData['FinishingCheckOutDate']),
                trim($RData['PFDate']),
                trim($RData['StatusQC2']),
                trim($RData['StatusQCEngineer']),
                trim($RData['QCEngineerFinishDate']),
                trim($RData['Is_ForcedClosed']),
                trim($RData['ClosedTime']),
                trim($RData['LocationCode'])
            );
            fputcsv($file,$ArrayTemp);
            $No++;
        }


}

else{ echo "error";}

?>