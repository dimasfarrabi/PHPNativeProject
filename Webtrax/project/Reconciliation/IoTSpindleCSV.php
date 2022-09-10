<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");

function getStartAndEndDate($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $Start = $dto->format('m/d/Y');
    $dto->modify('+6 days');
    $End = $dto->format('m/d/Y');
    return $Start."#".$End;
}
if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $aFloat = base64_decode(htmlspecialchars(trim($_GET['afloat']), ENT_QUOTES, "UTF-8"));
    $arr = explode("*",$aFloat);
    $DeviceID = $arr[0];
    $ValDateAwal = $arr[1];
    $ValDateAkhir = $arr[2];
    // echo "$ValDateAwal - $ValDateAkhir";
    date_default_timezone_set("Asia/Jakarta");
    $TimeNow = date('Y/m/d');
    $filename = "SpindleReport(IOT)_$ValDateAwal _ $ValDateAkhir.csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('No',
    'Date',
    'Machine Name',
    'Device ID',
    'Start Time',
    'End Time',
    'Duration (Minute)',
    'Device Battery (%)'));
    $no=1;
    $Data = GET_SPINDLE_IOT($ValDateAwal,$ValDateAkhir,$DeviceID,$linkMACHWebTrax);
    while($Datares=sqlsrv_fetch_array($Data))
    {
        $Date = trim($Datares['DateRecord']);
        $ID = trim($Datares['DeviceID']);
        $FullStart = trim($Datares['FullStart']);
        $FullEnd = trim($Datares['FullEnd']);
        $RunMinute = trim($Datares['RunMinute']);
        $DeviceBatt = trim($Datares['DeviceBatt']);
        $Machine = trim($Datares['Machine']);

        $ArrayTemp = array($no,$Date,$Machine,$ID,$FullStart,$FullEnd,$RunMinute,$DeviceBatt);
        fputcsv($file,$ArrayTemp);
        $no++;
    }
    fclose($file);
}

?>