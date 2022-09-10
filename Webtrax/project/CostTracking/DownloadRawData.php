<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $Cat = htmlspecialchars(trim($_GET['Cat']), ENT_QUOTES, "UTF-8");
    $Quote = htmlspecialchars(trim($_GET['Quote']), ENT_QUOTES, "UTF-8");
    $Half = htmlspecialchars(trim($_GET['Half']), ENT_QUOTES, "UTF-8");
    $Tipe = htmlspecialchars(trim($_GET['Tipe']), ENT_QUOTES, "UTF-8");
    $Expense = htmlspecialchars(trim($_GET['Expense']), ENT_QUOTES, "UTF-8");

    // echo "$Cat >> $Quote >> $Half >> $Tipe >> $Expense";
    // exit();
    if($Tipe == 'Labor')
    {
        $Data=GET_RAW_LABOR_COST($Quote,$Cat,$Half,$Expense,$linkMACHWebTrax);
        date_default_timezone_set("Asia/Jakarta");
        $TimeNow = date('Y_m_d_H_i_s');
        $filename = "LaborCost[".$Quote."-".$Half."]Expense$Expense.csv";
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $file = fopen('php://output', 'w');
        fputcsv($file, array('Date',
        'EmployeeNIK',
        'EmployeeFullName',
        'Product',
        'Activity',
        'Duration',
        'Notes',
        'DivisionName',
        'DurationHours',
        'WOChild',
        'WOParent',
        'Quote',
        'ClosedTime',
        'StartTime',
        'EndTime',
        'ExpenseAllocation',
        'ShiftCode',
        'Stabilize',
        'WOMapping_ID',
        'LocationCode'));

        while($RData = sqlsrv_fetch_array($Data)){
            $ArrayTemp = array(
                trim($RData['Date']),
                trim($RData['EmployeeNIK']),
                trim($RData['EmployeeFullName']),
                trim($RData['Product']),
                trim($RData['Activity']),
                trim($RData['Duration']),
                trim($RData['Notes']),
                trim($RData['DivisionName']),
                trim($RData['DurationHours']),
                trim($RData['WOChild']),
                trim($RData['WOParent']),
                trim($RData['Quote']),
                trim($RData['ClosedTime']),
                trim($RData['StartTime']),
                trim($RData['EndTime']),
                trim($RData['ExpenseAllocation']),
                trim($RData['ShiftCode']),
                trim($RData['Stabilize']),
                trim($RData['WOMapping_ID']),
                trim($RData['LocationCode'])
            );
            fputcsv($file,$ArrayTemp);
        }
        fclose($file);
        exit();
    }
    elseif($Tipe == 'Machine')
    {
        $Data=GET_RAW_MACHINE_COST($Quote,$Cat,$Half,$Expense,$linkMACHWebTrax);
        date_default_timezone_set("Asia/Jakarta");
        $TimeNow = date('Y_m_d_H_i_s');
        $filename = "MachineCost[".$Quote."-".$Half."]Expense$Expense.csv";
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $file = fopen('php://output', 'w');
        fputcsv($file, array('DateCreated',
        'Operator',
        'PartNo',
        'Product',
        'Qty',
        'MachineName',
        'Duration',
        'Barcode_ID',
        'OrderType',
        'WO',
        'ExpenseAllocation',
        'WOParent',
        'Quote',
        'QtyParent',
        'ClosedTime',
        'FullStartTime',
        'FullEndTime',
        'DurationHours',
        'ShiftCode',
        'Stabilize',
        'WOMapping_ID',
        'LocationCode'));

        while($RData = sqlsrv_fetch_array($Data)){
            $ArrayTemp = array(
                trim($RData['DateCreated']),
                trim($RData['Operator']),
                trim($RData['PartNo']),
                trim($RData['Product']),
                trim($RData['Qty']),
                trim($RData['MachineName']),
                trim($RData['Duration']),
                trim($RData['Barcode_ID']),
                trim($RData['OrderType']),
                trim($RData['WO']),
                trim($RData['ExpenseAllocation']),
                trim($RData['WOParent']),
                trim($RData['Quote']),
                trim($RData['QtyParent']),
                trim($RData['ClosedTime']),
                trim($RData['FullStartTime']),
                trim($RData['FullEndTime']),
                trim($RData['DurationHours']),
                trim($RData['ShiftCode']),
                trim($RData['Stabilize']),
                trim($RData['WOMapping_ID']),
                trim($RData['LocationCode'])
            );
            fputcsv($file,$ArrayTemp);
        }
        fclose($file);
        exit();
    }
    else
    {
        $Data=GET_RAW_MATERIAL_COST($Quote,$Cat,$Half,$Expense,$linkMACHWebTrax);
        date_default_timezone_set("Asia/Jakarta");
        $TimeNow = date('Y_m_d_H_i_s');
        $filename = "MaterialCost[".$Quote."-".$Half."]Expense$Expense.csv";
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $file = fopen('php://output', 'w');
        fputcsv($file, array('InputCode',
        'DateIssue',
        'Employee',
        'WOMapping_ID',
        'WOChild',
        'ExpenseAllocation',
        'WOParent',
        'ClosedTime',
        'QtyParent',
        'QtyQuote',
        'Product',
        'PartNo',
        'PartDescription',
        'TransactUOM',
        'TransactCost',
        'QtyUsage',
        'TotalCost',
        'CategoryUsage',
        'IdImport',
        'Location'));

        while($RData = sqlsrv_fetch_array($Data)){
            $ArrayTemp = array(
                trim($RData['InputCode']),
                trim($RData['DateIssue']),
                trim($RData['Employee']),
                trim($RData['WOMapping_ID']),
                trim($RData['WOChild']),
                trim($RData['ExpenseAllocation']),
                trim($RData['WOParent']),
                trim($RData['ClosedTime']),
                trim($RData['QtyParent']),
                trim($RData['QtyQuote']),
                trim($RData['Product']),
                trim($RData['PartNo']),
                trim($RData['PartDescription']),
                trim($RData['TransactUOM']),
                trim($RData['TransactCost']),
                trim($RData['QtyUsage']),
                trim($RData['TotalCost']),
                trim($RData['CategoryUsage']),
                trim($RData['IdImport']),
                trim($RData['Location'])
            );
            fputcsv($file,$ArrayTemp);
        }
        fclose($file);
        exit();
    }
}
?>