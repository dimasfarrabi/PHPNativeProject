<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleReport.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
/* 
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}*/
if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $Season = htmlspecialchars(trim($_GET['sea']), ENT_QUOTES, "UTF-8");
    $ValOpen = htmlspecialchars(trim($_GET['op']), ENT_QUOTES, "UTF-8");
    echo $Season."<br>".$ValOpen."<br>";

    date_default_timezone_set("Asia/Jakarta");
    $TimeNow = date('Y_m_d_H_i_s');
    $filename = "DataMachineTrack[".$Season."]_$TimeNow.csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('DateCreated',
    'MachineName',
    'Operator',
    'Product',
    'Barcode_ID',
    'OrderType',
    'WO',
    'PartNo',
    'ExpenseAllocation',
    'WOParent',
    'Quote',
    'QuoteCategory',
    'QtyParent',
    'QtyQuote',
    'Qty',
    'StartTime',
    'EndTime',
    'Duration',
    'Stabilize',
    'FullStartTime',
    'FullEndTime',
    'DurationHours',
    'Side',
    'ShiftCode',
    'WOMapping_ID',
    'ClosedTime',
    'LocationCode'));

    $NoLoop = 1;

    $QData = GET_DATA_MACHINE_TRACK_SEASON($Season,$ValOpen,$linkMACHWebTrax);

    while($RData = sqlsrv_fetch_array($QData)){
        $ArrayTemp = array(trim($RData['DateCreated']),
        trim($RData['MachineName']),
        trim($RData['Operator']),
        trim($RData['Product']),
        trim($RData['Barcode_ID']),
        trim($RData['OrderType']),
        trim($RData['WO']),
        trim($RData['PartNo']),
        trim($RData['ExpenseAllocation']),
        trim($RData['WOParent']),
        trim($RData['Quote']),
        trim($RData['QuoteCategory']),
        trim($RData['QtyParent']),
        trim($RData['QtyQuote']),
        trim($RData['Qty']),
        trim($RData['StartTime']),
        trim($RData['EndTime']),
        trim($RData['Duration']),
        trim($RData['Stabilize']),
        trim($RData['FullStartTime']),
        trim($RData['FullEndTime']),
        trim($RData['DurationHours']),
        trim($RData['Side']),
        trim($RData['ShiftCode']),
        trim($RData['WOMapping_ID']),
        trim($RData['ClosedTime']),
        trim($RData['LocationCode'])
        );
        fputcsv($file,$ArrayTemp);
        $NoLoop++;
    }
    fclose($file);
    exit();
}

?>
<!-- <script>
    alert("Download Is On Process");
</script> -->