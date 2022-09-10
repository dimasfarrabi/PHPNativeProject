<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleSyncReconV3.php"); 
date_default_timezone_set("Asia/Jakarta");
date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $Type = htmlspecialchars(trim($_GET['ty']), ENT_QUOTES, "UTF-8");
    $Params = htmlspecialchars(trim($_GET['par']), ENT_QUOTES, "UTF-8");
    $Date1 = htmlspecialchars(trim($_GET['ds']), ENT_QUOTES, "UTF-8");
    $Date2 = htmlspecialchars(trim($_GET['de']), ENT_QUOTES, "UTF-8");
    $Location = htmlspecialchars(trim($_GET['loc']), ENT_QUOTES, "UTF-8");
    // echo "$Type >> $Params >> $Date1 >> $Date2 >> $Location";
    if($Location == 'PSL'){ $ValConn = $linkMACHWebTrax;} else { $ValConn = $PSMConn; }
    switch ($Type) {
        case 'Time Tracking':
            {
                $XData = DETAIL_XY_TIMETRACK($Date1,$Date2,$Params,$Location,$ValConn);
                $ParamX = "Date";
                $ParamY = "NIK";
                $ParamZ = "Activity";
                $ParamA = "WO Parent";
                $ParamB = "Project";
            }
        break;
        case 'Material Tracking':
            {
                $XData = DETAIL_XY_MATERIALTRACK($Date1,$Date2,$Params,$Location,$ValConn);
                $ParamX = "Date Created";
                $ParamY = "Part Number";
                $ParamZ = "WO";
                $ParamA = "PartDesc";
                $ParamB = "TransactDate";
            }
        break;
    }


    date_default_timezone_set("Asia/Jakarta");
    $TimeNow = date('Y_m_d_H_i_s');
    $filename = "UnNamedList[".$Date1."-".$Date2."]_$Type.csv";
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $file = fopen('php://output', 'w');
    fputcsv($file, array('Index',$ParamX, $ParamY, $ParamZ,$ParamA,$ParamB));

    while($RData = sqlsrv_fetch_array($XData)){
        $ArrayTemp = array(
        trim($RData['Idx']),
        trim($RData['X']),
        trim($RData['Y']),
        trim($RData['Z']),
        trim($RData['A']),
        trim($RData['B'])
        );
        fputcsv($file,$ArrayTemp);
    }
    fclose($file);
    exit();
}
?>