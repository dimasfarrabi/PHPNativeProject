<?php
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleStockOpname.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
$TimeNow = date("Y-m-d H:i:s");
$FullName = "DIMAS RIZKY FARRABI";

$LinkPSL = $linkMACHWebTrax;
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $PartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
    // echo $PartNo;
?>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th class="text-center">Part No</th>
                <th class="text-center">Part Desc</th>
                <th class="text-center">Stock Awal</th>
                <th class="text-center">Actual Stock</th>
                <th class="text-center">Is Adjust</div>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>
<?php
}
else { }
?>