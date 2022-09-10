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
    // echo $Season."<br>".$ValOpen."<br>";

    date_default_timezone_set("Asia/Jakarta");
    $TimeNow = date('Y_m_d_H_i_s');
    $filename = "DataMaterialTrack[".$Season."]_$TimeNow.csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('No','Date','InputCode','WOMapping_ID','Employee','WOChild','ExpenseAllocation',
    'WOParent','Quote','ClosedTime','QtyParent','QtyQuote','Product','PartNumber','PartDescription','TransactUOM',
    'TransactCost','QtyUsage','TotalCost','CategoryUsage','AdjustmenStatus','QtyReceived','ReceivedBy','Notes','EstCostHour',
    'EstFinishDate','ID_Import','QuoteCategory','TLI_ID','Location'));

    $NoLoop = 1;
    $QData = GET_DATA_MATERIAL_TRACK_SEASON($Season,$ValOpen,$linkMACHWebTrax);
    while($RData = sqlsrv_fetch_array($QData)){

        $ArrayTemp = array($NoLoop,
        trim($RData['DateIssue']),
        trim($RData['InputCode']),
        trim($RData['WOMapping_ID']),
        trim($RData['Employee']),
        trim($RData['WOChild']),
        trim($RData['ExpenseAllocation']),
        trim($RData['WOParent']),
        trim($RData['Quote']),
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
        trim($RData['AdjustmentStatus']),
        trim($RData['QtyReceived']),
        trim($RData['ReceivedBy']),
        trim($RData['Notes']),
        trim($RData['EstCostHour']),
        trim($RData['EstFinishDate']),
        trim($RData['IdImport']),
        trim($RData['QuoteCategory']),
        trim($RData['TLI_ID']),
        trim($RData['Location'])
        );
        fputcsv($file,$ArrayTemp);
        $NoLoop++;
    }
    fclose($file);
    exit();
}
?>