<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPProcess.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
$FullName = "Local-Dimas Farrabi";

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $Tipe = (htmlspecialchars(trim($_POST['Tipe']), ENT_QUOTES, "UTF-8"));
?>
<div class="col-md-12" style="margin-top:25px;">
    <button class="btn btn-sm btn-success" id="BtnPrintBC" style="float:right; width:30%;">Proceed</button>
</div>
<h2><br></h2>
<div class="col-md-12" style="margin-top:25px;">
    <table class="table table-bordered table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th class="text-center">Production PartNo</th>
                <th class="text-center">Barcode</th>
                <th class="text-center">#</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data = GET_READY_TO_PRINT($Tipe,$linkMACHWebTrax);
            while($res=sqlsrv_fetch_array($data))
            {
                $PartNo = trim($res['ProductionPartNo']);
                $BC = trim($res['Idx']);
                $del = '<i class="bi bi-trash-fill PointerList" data-ecode="'.$PartNo.'"  title="Delete">';
                ?>
                <tr>
                    <td class="text-left"><?php echo $PartNo; ?></td>
                    <td class="text-center"><?php echo $BC; ?></td>
                    <td class="text-center"><?php echo $del; ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php
}
?>