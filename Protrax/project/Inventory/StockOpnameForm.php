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
/*
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
# data protrax user
$BolProdAcc = false;
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
if(mssql_num_rows($QDataUserWebtrax) > 0)
{
    $RDataUserWebtrax = sqlsrv_fetch_array($QDataUserWebtrax);
    $TypeUser = trim($RDataUserWebtrax['TypeUser']);
    $_SESSION['LoginMode'] = base64_encode($TypeUser);
    $AccessLogin = base64_decode($_SESSION['LoginMode']);   
}
else # kondisi tidak terdaftar di protrax user & akan di set sebagai employee dan hak akses ke bagian produksi saja
{
    $_SESSION['LoginMode'] = base64_encode("Employee");
    $AccessLogin = base64_decode($_SESSION['LoginMode']);
    $BolProdAcc = true;
}
*/
function GUID()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X', mt_rand(0, 65535), mt_rand(12, 65535));
}
$LinkPSL = $linkMACHWebTrax;
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $TemplateName = htmlspecialchars(trim($_POST['Input1']), ENT_QUOTES, "UTF-8");
    $Location = htmlspecialchars(trim($_POST['Input2']), ENT_QUOTES, "UTF-8");
    $FormID = GUID();
?>
<div class="col-md-12"><h6><strong>Template Name: </strong><?php echo $TemplateName; ?></h6></div>
<div class="row" style="margin-top:15px;">
    <div class="col-md-3">
        <div class="form-group">
            <label for="BinForm" class="form-label fw-bold">Bin</label>
            <input type="text" class="form-control form-control-sm" id="BinForm" value="<?php echo $Location; ?>" readonly>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="PPICForm" class="form-label fw-bold">PPIC</label>
            <input type="text" class="form-control form-control-sm" id="PPICForm" value="<?php echo $FullName; ?>" readonly>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="controls">
                <label for="txtFilterTanggal1" class="form-label fw-bold">Tanggal Stock Opname</label>
                <div class="input-group input-group-sm">
                    <input id="txtFilterTanggal1" name="txtFilterTanggal1" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal1Val" value="<?php echo $DateNow; ?>" readonly />
                    <label for="txtFilterTanggal1" class="input-group-text" id="txtFilterTanggal1Val"><span class="bi bi-calendar-date text-dark"></span></label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="FormID" class="form-label fw-bold">Form ID</label>
            <input type="text" class="form-control form-control-sm" id="FormID" value="<?php echo $FormID; ?>" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="InputPartNo" class="form-label fw-bold">Input PartNo</label>
            <input type="text" class="form-control form-control-sm" id="InputPartNo" placeholder="input the part number that will be processed">
        </div>
    </div>
    <div class="col-md-2">
        <div style="margin-top:28px;">
            <button class="btn btn-sm btn-dark" style="width:100%" id="BtnPart">Choose</button>
        </div>
    </div>
    <div class="col-md-6">
        <div style="margin-top:25px;">
            <button class="btn btn-md btn-success" style="width:40%; float:right;" id="BtnProses">Proceed</button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6" style="margin-top:20px;">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TablePartSelected">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">Part No</th>
                        <th class="text-center">Part Desc</th>
                        <th class="text-center">Stock</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no=1;
                $data = GET_DATA_WIPSIMS_GUDANGKECIL($TemplateName,$LinkPSL);
                while($RData=sqlsrv_fetch_array($data))
                {
                    $ValPartNo = trim($RData['PartNo']);
                    $ValPartDesc = trim($RData['PartDescription']);
                    $QtyStock = trim($RData['QtyStock']);
                    $check = '<input class="form-check-input checkID" type="checkbox" id="" data-id="'.$ValPartNo.'" value="'.$ValPartNo.'">';
                    if(trim($QtyStock) == ""){$QtyStock = "";} else {$QtyStock = number_format((float)$QtyStock, 2, '.', ',');}   
                ?>
                <tr>
                    <td class="text-left"><?php echo $ValPartNo; ?></td>
                    <td class="text-left"><?php echo $ValPartDesc; ?></td>
                    <td class="text-center"><?php echo $QtyStock; ?></td>
                </tr>
                <?php
                $no++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6" style="margin-top:20px;" id="TempTable">
        
    </div>
</div>
<?php
}
else { }
?>