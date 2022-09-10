<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php"); 
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
    $Category = htmlspecialchars(trim($_GET['Cat']), ENT_QUOTES, "UTF-8");
    $ValDate = htmlspecialchars(trim($_GET['Date']), ENT_QUOTES, "UTF-8");
    switch ($Category) {
        case 'Daily':
            {
                $ValDateAwal = $ValDate;
                $ValDateAkhir = $ValDate;
            }
        break;
        case 'Weekly':
            {
                $ArrWeek = explode("/",$ValDate);
                $Day = $ArrWeek[0];
                $Month = $ArrWeek[1];
                $Year = $ArrWeek[2];

                $date = new DateTime($ValDate);
                $week = $date->format("W");
                $week_array = getStartAndEndDate($week,$Year);
                $ArrRangeWeek2 = explode("#",$week_array);
                $ValDateAwal = $ArrRangeWeek2[0];
                $ValDateAkhir = $ArrRangeWeek2[1];
            }
        break;
    }

    // echo "$ValDateAwal - $ValDateAkhir";
    date_default_timezone_set("Asia/Jakarta");
    $TimeNow = date('Y/m/d');
    $filename = "SpindleReport_$ValDateAwal _ $ValDateAkhir.csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('Date',
    'Machine',
    'Shift',
    'Run Hour',
    'Stabilize (Hour)',
    'Idle Time (Hour)',
    'Work Order',));
    $Data = GET_SPINDLE_REPORT_BYDATE($ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
    while($Datares=sqlsrv_fetch_array($Data))
    {
        $Date = trim($Datares['DateTracked2']);
        $Machine = trim($Datares['Machine']);
        $Shift = trim($Datares['Shift']);
        $RunHour = trim($Datares['RunHour']);
        $Stabilize = trim($Datares['Stabilize']);
        $IdleTime = trim($Datares['IdleTime']);
        $WOP = trim($Datares['WOParent']);
        $RunHour = number_format((float)$RunHour, 2, '.', ',');
        $Stabilize = number_format((float)$Stabilize, 2, '.', ',');
        $IdleTime = number_format((float)$IdleTime, 2, '.', ',');

        $ArrayTemp = array($Date,$Machine,$Shift,$RunHour,$Stabilize,$IdleTime,$WOP);
        fputcsv($file,$ArrayTemp);
    }
    fclose($file);
}

?>