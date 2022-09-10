<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php");
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
if($_SERVER['REQUEST_METHOD'] == "GET")
{   
    $Params = htmlspecialchars(trim($_GET['Params']), ENT_QUOTES, "UTF-8");
    switch ($Params) {
        case 'Week':
            {
                $Param2 = "1 Minggu Terakhir";
            }
        break;
        case 'Month':
            {
                $Param2 = "1 Bulan Terakhir";
            }
        break;
        case 'ThreeMonth':
            {
                $Param2 = "3 Bulan Terakhir";
            }
        break;
        case 'SixMonth':
            {
                $Param2 = "6 Bulan Terakhir";
            }
        break;
    }
    date_default_timezone_set("Asia/Jakarta");
    $filename = "KittingHistory[".$Param2."].csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('BarcodeKit','WO Child',
    'TemplateName',
    'TrayKitStatus',
    'TglCreated',
    'TglTransaksiAwal',
    'TglTransaksiAkhir',
    'TglClosing',
    'TglCheckOutQC',
    'TglCheckInWH',
    'TglCheckOutWH',
    'TglCheckInAssy',
    'InstrumentSN'));
    $data = GET_KITTING_HISTORY_DATA($Params,$linkMACHWebTrax);
    while($res=sqlsrv_fetch_array($data))
    {
        $BarcodeKit = trim($res['BarcodeKit']);
        $WOChild = trim($res['WOChild']);
        $TemplateName = trim($res['TemplateName']);
        $TrayKitStatus = trim($res['TrayKitStatus']);
        $TglCreate = trim($res['TglCreate2']);
        $TglTransaksiAwal = trim($res['TglTransaksiAwal2']);
        $TglTransaksiAkhir = trim($res['TglTransaksiAkhir2']);
        $TglClosing = trim($res['TglClosing2']);
        $TglCheckOutQC = trim($res['TglCheckOutQC2']);
        $TglCheckInWH = trim($res['TglCheckInWH2']);
        $TglCheckOutWH = trim($res['TglCheckOutWH2']);
        $TglCheckInAssembly = trim($res['TglCheckInAssembly2']);
        $InstrumentSN = trim($res['InstrumentSN']);

        $ArrayTemp = array($BarcodeKit,
        $WOChild,
        $TemplateName,
        $TrayKitStatus,
        $TglCreate,
        $TglTransaksiAwal,
        $TglTransaksiAkhir,
        $TglClosing,
        $TglCheckOutQC,
        $TglCheckInWH,
        $TglCheckOutWH,
        $TglCheckInAssembly,
        $InstrumentSN);
        fputcsv($file,$ArrayTemp);
    }
    fclose($file);
    exit();
}
else
{
    echo "";    
}
?>