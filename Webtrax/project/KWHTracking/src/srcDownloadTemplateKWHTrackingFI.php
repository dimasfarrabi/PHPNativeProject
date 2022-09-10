<?php 
session_start();
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleKWHTracking.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$Yesterday = date("d/m/Y",strtotime("-1 day"));
$TimeNow = date("H:i:s");
$ValKWH = "0.12345";
 
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "GET")
{
    $ValString = htmlspecialchars(trim($_GET['accs']), ENT_QUOTES, "UTF-8");
    if(trim($ValString) == "1")
    {
        # download template
        $filename = "TemplateImportKWHTrackingFI.csv";
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $file = fopen('php://output', 'w');
        fputcsv($file, array('DateLog','KWH'));
        $ArrayTemp = array($Yesterday,$ValKWH);
        fputcsv($file,$ArrayTemp);
        fclose($file);
        exit();
    }
    else
    {
        # download kosong
        $filename = "TemplateImportKWHTrackingFI.csv";
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $file = fopen('php://output', 'w');
        fputcsv($file, array(''));
        fclose($file);
        exit();
    }
}
else
{
    echo "";    
}
?>