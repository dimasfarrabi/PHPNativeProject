<?php
require_once("project/Inventory/Modules/ModuleStockOpname.php");
require_once("project/Inventory/Modules/ModuleInOutpartTBZ.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
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
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkMACHWebTrax);
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

if((trim($AccessLogin) != "Manager"))
{
    if($RDataUserWebtrax['MnAdmin'] != "1" && $RDataUserWebtrax['MnInventory'] != "1")  
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
else
{
    if($RDataUserWebtrax['MnAdmin'] != "1")
    {
        if($RDataUserWebtrax['MnInventory'] != "1")
        {
            ?>
            <script language="javascript">
                window.location.replace("https://protrax.formulatrix.com/");
            </script>
            <?php
            exit();
        }
    }
}*/
$FullName = "DIMAS RIZKY FARRABI";
# data karyawan
$QData = GET_DETAIL_EMPLOYEE_BY_NAME($FullName,$linkMACHWebTrax);
while($RData = sqlsrv_fetch_array($QData))
{
    if(isset($RData['NIK'])){$UserNIK = trim($RData['NIK']);}else{$UserNIK = "";}
    if(isset($RData['FullName'])){$UserFN = trim($RData['FullName']);}else{$UserFN = "";}
    if(isset($RData['DivisionName'])){$UserDivName = trim($RData['DivisionName']);}else{$UserDivName = "";}
    if(isset($RData['CompanyCode'])){$UserCompanyCode = trim($RData['CompanyCode']);}else{$UserCompanyCode = "";}
}

$ValQuoteCategory = "";
$ArrListQuote = array();
$QListCategory = GET_LIST_QUOTE_BY_PARAM($ValQuoteCategory,$linkMACHWebTrax);
while($RListCategory = sqlsrv_fetch_array($QListCategory))
{
    $TempArray = array(
        "Quote" => trim($RListCategory['Quote']),
        "Location" => "PSL",
        "ProjectID" => trim($RListCategory['ProjectID'])
    );
    array_push($ArrListQuote,$TempArray);
}
asort($ArrListQuote);
?>
<script src="project/Inventory/lib/LibStockOpname.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inventory : Stock Opname Form V2</li>
            </ol>
        </nav>
    </div>
</div>  
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <h6 class="card-header text-white bg-secondary">Choose Filter</h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <label for="InputCompany" class="input-group-text fw-bold">Lokasi</label>
                            <select class="form-select form-select-sm" id="InputCompany">
                                <option value="Pilih Company">-- Pilih Company --</option>
                                <?php
                                $QListLocation = GET_LIST_COMPANY($linkMACHWebTrax);
                                $x = "X";
                                while($RListLocation = sqlsrv_fetch_array($QListLocation))
                                {
                                    ?>
                                    <option value="<?php echo base64_encode(base64_encode($x.":".trim($RListLocation['CompanyCode']))); ?>"><?php echo trim($RListLocation['CompanyCode']); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <label for="StockType" class="input-group-text fw-bold">Stock</label>
                            <select class="form-select form-select-sm" id="StockType">
                                <option>-- Pilih Stock --</option>
                                <option>Bin Kitting</option>
                                <option>Gudang Kecil</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12" id="ListGudang">
                        
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-3">
                            <button class="btn btn-md btn-dark" style="width:100%" id="ButtonOK"><strong>OK</strong></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row" id="OpnameContent">
            
        </div>
        <div class="row" id="FormContent">

        </div>
    </div>
</div>