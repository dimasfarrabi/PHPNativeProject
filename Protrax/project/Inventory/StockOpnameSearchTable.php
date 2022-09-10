<?php
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleStockOpname.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
$TimeNow = date("Y-m-d H:i:s");

$LinkPSL = $linkMACHWebTrax;
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $PartNo = htmlspecialchars(trim($_POST['PartNo']), ENT_QUOTES, "UTF-8");
    $StockType = htmlspecialchars(trim($_POST['StockType']), ENT_QUOTES, "UTF-8");
    $Gudang = htmlspecialchars(trim($_POST['Gudang']), ENT_QUOTES, "UTF-8");
    $LocationEnc = base64_decode(base64_decode(htmlspecialchars(trim($_POST['Lokasi']), ENT_QUOTES, "UTF-8")));
    $arr = explode(":",$LocationEnc);
    $Location = $arr[1];
    switch ($Location) {
        case 'PSL':
            $Company = "PT Promanufacture Indonesia - Salatiga";
            break;
        case 'PSM':
            $Company = "PT Promanufacture Indonesia - Semarang";
            break;
        case 'FOR':
            $Company = "PT Formulatrix Indonesia";
            break;
        default:
            $Company = "";
            break;
    }
    if($StockType == 'Bin Kitting')
    {
        $data = GET_DATA_BIN_KITTING("TOP 1000","",$Company,$PartNo,$linkMACHWebTrax);
    }
    else
    {
        $data = GET_DATA_GUDANG_KECIL("TOP 1000","",$Company,$Gudang,$PartNo,$linkMACHWebTrax);
    }
?>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="TableStock">
        <thead class="theadCustom">
            <tr>
                <th class="text-center">Part No</th>
                <th class="text-center">Part Desc</th>
                <th class="text-center">Stock</th>
                <th class="text-center">#</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while($res=sqlsrv_fetch_array($data))
        {
            $ValPartNo = trim($res['PartNo']);
            $ValPartDesc = trim($res['PartDesc']);
            $QtyStock = trim($res['QtyNow']);
            $enc = $ValPartNo."*".$QtyStock;
            if(trim($QtyStock) == ""){$QtyStock = "";} else {$QtyStock = number_format((float)$QtyStock, 2, '.', ',');}
            $opt = '<button class="btn btn-sm btn-dark BtnChoose" id="" data-ecode="'.$enc.'">choose</button>';
        ?>
        <tr>
            <td class="text-left"><?php echo $ValPartNo; ?></td>
            <td class="text-left"><?php echo $ValPartDesc; ?></td>
            <td class="text-center"><?php echo $QtyStock; ?></td>
            <td class="text-center"><?php echo $opt; ?></td>
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