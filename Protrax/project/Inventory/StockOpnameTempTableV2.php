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
    $FormID = htmlspecialchars(trim($_POST['FormID']), ENT_QUOTES, "UTF-8");
    $pic = htmlspecialchars(trim($_POST['pic']), ENT_QUOTES, "UTF-8");
    $BinForm = htmlspecialchars(trim($_POST['BinForm']), ENT_QUOTES, "UTF-8");
    $SODate = htmlspecialchars(trim($_POST['SODate']), ENT_QUOTES, "UTF-8");
    $Lokasi = base64_decode(base64_decode(htmlspecialchars(trim($_POST['Lokasi']), ENT_QUOTES, "UTF-8")));
    $StockType = htmlspecialchars(trim($_POST['StockType']), ENT_QUOTES, "UTF-8");
    $arr = explode(":",$Lokasi);
    $Company = $arr[1];
    switch ($Company) {
        case 'PSL':
            $NewCompany = "PT Promanufacture Indonesia - Salatiga";
            break;
        case 'PSM':
            $NewCompany = "PT Promanufacture Indonesia - Semarang";
            break;
        case 'FOR':
            $NewCompany = "PT Formulatrix Indonesia";
            break;
        default:
            $NewCompany = "";
            break;
    }
?>
<div class="row">
    <div class="col-md-12">
        <div style="margin-top:25px;">
            <button class="btn btn-md btn-success" style="width:40%; float:right;" id="BtnProses">Proceed</button>
        </div>
    </div>
    <div class="col-md-12" style="margin-top:20px;">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="FormSO">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">Part No</th>
                        <th class="text-center" width="160">Stock Awal</th>
                        <th class="text-center" width="160">Actual Stock</th>
                        <th class="text-center">Is Adjust</div>
                        <th class="text-center">#</div>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $datax = GET_TEMP_TABLE_INFO($NewCompany,$StockType,$BinForm,$linkMACHWebTrax);
                    while($resx=sqlsrv_fetch_array($datax))
                    {
                        $PartNo = trim($resx['PartNo']);
                        $StockAwal = trim($resx['PreviousQty']);
                        $StockAwal = number_format((float)$StockAwal, 2, '.', ',');
                        $enc = $PartNo."*".$FormID;
                        $form = '<input type="text" style="direction: RTL;" class="form-control form-control-sm" value="'.$StockAwal.'" data-id="'.$PartNo.'" id="FormPart" readonly>';
                        $cek = '<input class="form-check-input checkID" type="checkbox" data-id="'.$PartNo.'">';
                        $opt = '<i class="bi bi-trash-fill PointerList" aria-hidden="true" data-bs-toggle="modal" data-ecode="'.$PartNo.'" data-bs-target="#DeleteTemp" title="Delete">';
                    ?>
                    <tr data-idrows="isx" data-erows="<?php echo $PartNo; ?>">
                        <td class="text-center"><?php echo $PartNo; ?></td>
                        <td class="text-center"><?php echo $StockAwal; ?></td>
                        <td class="text-center"><?php echo $form; ?></td>
                        <td class="text-center"><?php echo $cek; ?></td>
                        <td class="text-center"><?php echo $opt; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12" style="margin-top:20px;" id="ProceedTempTable">
    
    </div>
</div>
<div class="modal fade" id="DeleteTemp" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove from list</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div id="RecFormContent">
                <div class="row">
                        <div class="col-md-6"><button class="btn btn-md btn-danger" id="DeleteBtn2" style="width:100%">YES</button></div>
                        <div class="col-md-6"><button class="btn btn-md btn-warning" data-bs-dismiss="modal" style="width:100%">NO</button></div>
                    </div>
                </div>
                <div id="DeleteContent" style="margin-top:20px;">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<?php
}
else { }
?>
<script language="javascript">
setTimeout(myFunction, 1000);
function myFunction()
{
    $('#DangerBar').hide();
}
</script>