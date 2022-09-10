<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleInOutPartTBZ.php");
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
$LinkPSL = $linkMACHWebTrax;
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValDateStart = htmlspecialchars(trim($_POST['ValDateStart']), ENT_QUOTES, "UTF-8");
    $ValDateEnd = htmlspecialchars(trim($_POST['ValDateEnd']), ENT_QUOTES, "UTF-8");
    $ValCategory = htmlspecialchars(trim($_POST['ValCategory']), ENT_QUOTES, "UTF-8");
    if($ValLocation == "PSL")
    {
        $Location = "PT Promanufacture Indonesia - Salatiga";
        if($ValCategory == "IN")
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_IN($ValDateStart,$ValDateEnd,$Location,$LinkPSL);
        }
        elseif($ValCategory == "OUT")
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_OUT($ValDateStart,$ValDateEnd,$Location,$LinkPSL);
        }
        else
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_ALL($ValDateStart,$ValDateEnd,$Location,$LinkPSL);
        }
    }
    if($ValLocation == "PSM")
    {
        $Location = "PT Promanufacture Indonesia - Semarang";
        if($ValCategory == "IN")
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_IN($ValDateStart,$ValDateEnd,$Location,$LinkPSL);
        }
        elseif($ValCategory == "OUT")
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_OUT($ValDateStart,$ValDateEnd,$Location,$LinkPSL);
        }
        else
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_ALL($ValDateStart,$ValDateEnd,$Location,$LinkPSL);
        }
    }
    if($ValLocation == "FOR")
    {
        $Location = "PT Formulatrix Indonesia";
        if($ValCategory == "IN")
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_IN($ValDateStart,$ValDateEnd,$Location,$LinkPSL);
        }
        elseif($ValCategory == "OUT")
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_OUT($ValDateStart,$ValDateEnd,$Location,$LinkPSL);
        }
        else
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_ALL($ValDateStart,$ValDateEnd,$Location,$LinkPSL);
        }
    }
    if($ValLocation == "ALL")
    {
        if($ValCategory == "IN")
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_IN_2($ValDateStart,$ValDateEnd,$LinkPSL);
        }
        elseif($ValCategory == "OUT")
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_OUT_2($ValDateStart,$ValDateEnd,$LinkPSL);
        }
        else
        {
            $QData = GET_DATA_MATERIAL_TRACKING_TBZ_ALL_2($ValDateStart,$ValDateEnd,$LinkPSL);
        }

    }

    if(strtotime($ValDateEnd) >= strtotime($ValDateStart))
    {
?>
<div class="col-md-12"><hr></div>
<div class="col-md-12 fw-bold header-result">Hasil Pencarian [Lokasi : <?php echo $ValLocation; ?>][<?php echo $ValDateStart; ?> - <?php echo $ValDateEnd; ?>][Kategori : <?php echo $ValCategory; ?>]</div>
<div class="col-md-12 pt-2 pb-2 text-end"><button class="btn btn-sm btn-dark buttons-csv" id="BtnDownloadCSV">Download CSV</button></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="display nowrap table table-hover" id="TableTBZ">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">PartNo</th>
                    <th class="text-center">PartDesc</th>
                    <th class="text-center">QtyReceived</th>
                    <th class="text-center">QtyUsage</th>
                    <th class="text-center">UOM</th>
                    <th class="text-center">CategoryUsage</th>
                    <th class="text-center">UnitCost($)</th>
                    <th class="text-center">TotalCost($)</th>
                    <th class="text-center">AdjustmentStatus</th>
                    <th class="text-center">ReceivedBy</th>
                    <th class="text-center">IssuedBy</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">WorkOrder</th>
                    <th class="text-center">ExpenseAllocation</th>
                    <th class="text-center">WarehouseLocation</th>
                    <th class="text-center">InputCode</th>
                    <th class="text-center">LabelIssueID</th>
                    <th class="text-center">TBZ TLI#</th>
                </tr>
            </thead>
            <tbody><?php
            $No = 1;
            while($RData = sqlsrv_fetch_array($QData))
            {
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center"><?php echo trim($RData['PartNo']); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['PartDesc'])); ?></td>
                    <td class="text-end"><?php echo number_format((float)trim($RData['QtyReceived']), 2, '.', ','); ?></td>
                    <td class="text-end"><?php echo number_format((float)trim($RData['QtyUsage']), 2, '.', ','); ?></td>
                    <td class="text-left"><?php echo trim($RData['UOM']); ?></td>
                    <td class="text-center"><?php echo trim($RData['CategoryUsage']); ?></td>
                    <td class="text-end"><?php echo number_format((float)trim($RData['UnitCost($)']), 2, '.', ','); ?></td>
                    <td class="text-end"><?php echo number_format((float)trim($RData['TotalCost($)']), 2, '.', ','); ?></td>
                    <td class="text-center"><?php echo trim($RData['AdjustmentStatus']); ?></td>
                    <td class="text-center"><?php echo trim($RData['ReceivedBy']); ?></td>
                    <td class="text-center"><?php echo trim($RData['IssuedBy']); ?></td>
                    <td class="text-center"><?php echo trim($RData['Date']); ?></td>
                    <td class="text-center"><?php echo trim($RData['WorkOrder']); ?></td>
                    <td class="text-center"><?php echo trim($RData['ExpenseAllocation']); ?></td>
                    <td class="text-center"><?php echo trim($RData['WarehouseLocation']); ?></td>
                    <td class="text-center"><?php echo trim($RData['InputCode']); ?></td>
                    <td class="text-center"><?php echo trim($RData['LabelIssue_ID']); ?></td>
                    <td class="text-center"><?php echo trim($RData['TBZ TLI#']); ?></td>
                </tr>
                <?php
                $No++;
            }
            ?></tbody>
        </table>
    </div>
</div>
<?php
    }
    else
    {
?>
<div class="col-md-12"><hr></div>
<div class="col-md-12 fw-bold header-result">Hasil Pencarian [Lokasi : <?php echo $ValLocation; ?>][<?php echo $ValDateStart; ?> - <?php echo $ValDateEnd; ?>][Kategori : <?php echo $ValCategory; ?>]</div>
<div class="col-md-12 pt-2 pb-2">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> Tanggal awal lebih besar daripada tanggal akhir.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php
    }
}
else
{
    echo "";    
}
?>