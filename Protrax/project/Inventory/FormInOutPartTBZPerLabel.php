<?php
require_once("project/MachiningCNC/Modules/ModuleSingleTimeTracking.php");
require_once("project/Inventory/Modules/ModuleInOutPartTBZ.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");

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
    $RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
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

/*if((trim($AccessLogin) != "Manager"))
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

# data karyawan
$QData = GET_DETAIL_EMPLOYEE_BY_NAME($FullName,$linkHRISWebTrax);
$RData = mssql_fetch_assoc($QData);
if(isset($RData['NIK'])){$UserNIK = trim($RData['NIK']);}else{$UserNIK = "";}
if(isset($RData['FullName'])){$UserFN = trim($RData['FullName']);}else{$UserFN = "";}
if(isset($RData['DivisionName'])){$UserDivName = trim($RData['DivisionName']);}else{$UserDivName = "";}
if(isset($RData['CompanyCode'])){$UserCompanyCode = trim($RData['CompanyCode']);}else{$UserCompanyCode = "";}

?>
<script src="lib/datetimepicker-master/jquery.datetimepicker.full.min.js"></script>
<script src="lib/DataTables-v1.11.3/DataTables/Buttons-2.1.1/js/dataTables.buttons.min.js"></script>
<script src="lib/DataTables-v1.11.3/DataTables/Buttons-2.1.1/js/buttons.html5.min.js"></script>
<script src="lib/DataTables-v1.11.3/DataTables/Buttons-2.1.1/js/buttons.print.min.js"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<script src="project/Inventory/lib/LibInOutPartTBZ.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inventory : Proses In/Out Part TBZ Per Label</li>
            </ol>
        </nav>
    </div>
</div>  
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12 pb-2">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Form Scan Data</h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group input-group-sm mb-3">
                                    <label for="InputLocation" class="input-group-text fw-bold">Lokasi</label>
                                    <select class="form-select form-select-sm" id="InputLocation">
                                        <option value="<?php echo base64_encode(base64_encode("Id:0")); ?>">-- Pilih Lokasi --</option>
                                        <?php
                                        $QListLocation = GET_LIST_WAREHOUSE_LOCATION($LinkPSL);
                                        while($RListLocation = mssql_fetch_assoc($QListLocation))
                                        {
                                            ?>
                                        <option value="<?php echo base64_encode(base64_encode("Id:".trim($RListLocation['Idx']))); ?>"><?php echo trim($RListLocation['WarehouseAlias']); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <label for="InputScan" id="LabelScan" class="form-label fw-bold">Scan BC Label Item</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm text-center" id="InputScan">
                                    <button class="btn btn-sm btn-secondary" type="button" id="BtnScan"></button>
                                </div>
                            </div>
                            <div class="col-md-12 pt-3"><div id="InfoScan"></div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pb-2">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Data User</h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="InfoNIK" class="form-label fw-bold">NIK</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoNIK" value="<?php echo $UserNIK; ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="InfoFN" class="form-label fw-bold">Nama</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoFN" value="<?php echo $UserFN; ?>" readonly>
                            </div>
                            <div class="col-md-12">
                                <label for="InfoDivision" class="form-label fw-bold">Divisi</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoDivision" value="<?php echo $UserDivName; ?>" readonly> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pb-2">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Identifikasi Item P/N</h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="InfoLocation" class="form-label fw-bold">Location*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoLocation" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="InfoLabelID" class="form-label fw-bold">Label ID*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoLabelID" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="InfoStockType" class="form-label fw-bold">Jenis Stock*</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm text-center" id="InfoStockType" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="InfoStockType" class="form-label fw-bold">Jenis Stock Default</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm text-center" id="InfoStockTypeDefault" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="InfoIDLineItemTBZ" class="form-label fw-bold">ID Transact Line Item TBZ*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoIDLineItemTBZ" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="InfoPartNo" class="form-label fw-bold">PartNo*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoPartNo" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoCategory" class="form-label fw-bold">Kategori*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoCategory" readonly>
                            </div>
                            <div class="col-md-12">
                                <label for="InfoPartDesc" class="form-label fw-bold">Part Description*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoPartDesc" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoWOTBZ" class="form-label fw-bold">WO TBZ*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoWOTBZ" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoJobID" class="form-label fw-bold">Job ID*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoJobID" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoWOC" class="form-label fw-bold">WO Child*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoWOC" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoWOID" class="form-label fw-bold">WO ID*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoWOID" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoProduct" class="form-label fw-bold">Produk</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoProduct" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoExpense" class="form-label fw-bold">Expense</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoExpense" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoQty" class="form-label fw-bold">Qty*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoQty" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoUOM" class="form-label fw-bold">UOM*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoUOM" readonly> 
                            </div>
                            <div class="col-md-12">
                                <label for="InfoUnitCost" class="form-label fw-bold">Unit Cost*</label>
                                <input type="text" class="form-control form-control-sm text-center" id="InfoUnitCost" readonly> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12 pb-2">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Filter Pencarian Hasil Data</h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <label for="FilterLocation" class="input-group-text fw-bold">Lokasi</label>
                                            <select class="form-select form-select-sm" id="FilterLocation">
                                                <option>ALL</option>
                                                <option>PSL</option>
                                                <option>PSM</option>
                                                <option>FOR</option>
                                            </select>
                                        </div>  
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text fw-bold" for="txtFilterDateIN1">Awal</span>
                                            <input id="txtFilterDateIN1" name="txtFilterDateIN1" type="text" class="date-picker form-control" aria-describedby="txtFilterDateIN1Val" value="<?php echo $DateNow; ?>" readonly />
                                            <label for="txtFilterDateIN1" class="input-group-text" id="txtFilterDateIN1Val"><span class="bi bi-calendar-date text-dark"></span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text fw-bold" for="txtFilterDateOUT1">Akhir</span>
                                            <input id="txtFilterDateOUT1" name="txtFilterDateOUT1" type="text" class="date-picker form-control" aria-describedby="txtFilterDateOUT1Val" value="<?php echo $DateNow; ?>" readonly />
                                            <label for="txtFilterDateOUT1" class="input-group-text" id="txtFilterDateOUT1Val"><span class="bi bi-calendar-date text-dark"></span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <label class="input-group-text fw-bold" for="FilterOpt">Kategori</label>
                                            <select class="form-select" id="FilterOpt">
                                                <option>IN</option>
                                                <option>OUT</option>
                                                <option>ALL</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-dark" id="BtnHistory">Lihat Data</button>
                            </div>
                            <div class="col-md-12 pt-2">
                                <div class="row" id="ContentTableInOut">
                                    <div class="col-md-12"><hr></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pb-2">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Log Aktivitas</h6>
                    <div class="card-body">
                        <div class="row">
                            <div id="InfoLog">
                                <div class="col-md-12">
                                    <textarea id="TextInfoLog" class="form-control" readonly></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12">&nbsp;</div>
</div>


<div class="modal fade" id="ModalWO" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"  aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header"><h6 class="modal-title">Form Pencarian WO</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body">
                <div id="ContentModalFilterWO">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <h6 class="card-header">Filter Pencarian</h6>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="FilterTypeCategoryFindWO" class="form-label mb-0 fw-bold">Jenis Pencarian</label>
                                            <select class="form-select form-select-sm" aria-label="Select Type" id="FilterTypeCategoryFindWO">
                                                <option>WO ID</option>
                                                <option>WO Child</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="FilterInputKeywordWO" class="form-label mb-0 fw-bold">Kata Kunci</label>
                                            <input type="text" class="form-control form-control-sm" id="FilterInputKeywordWO">
                                        </div>
                                        <div class="col-md-3 pt-3 mt-1">
                                            <button class="btn btn-sm btn-dark" id="BtnSearchWO">Cari Data</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <div class="col-md-12 pt-4">
                            <div class="card">
                                <h6 class="card-header">Hasil Pencarian WO</h6>
                                <div class="card-body">
                                    <div class="row" id="ContentModalWO"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="ModalConfirmation" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"  aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header"><h6 class="modal-title">Konfirmasi</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body">
                <div id="ContentModalFilterWO">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <h6 class="card-header">Form Input</h6>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="ConfirmNIK" class="form-label fw-bold">NIK</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmNIK" readonly> 
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="ConfirmName" class="form-label fw-bold">Nama</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmName" readonly> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="ConfirmDivision" class="form-label fw-bold">Divisi</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmDivision" readonly> 
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="ConfirmCompany" class="form-label fw-bold">Company</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmCompany" readonly> 
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="ConfirmGudang" class="form-label fw-bold">Gudang</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmGudang" readonly> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12"><hr></div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="ConfirmStockType" class="form-label fw-bold">Jenis Stock</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmStockType" readonly> 
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="ConfirmLabelID" class="form-label fw-bold">Label ID</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmLabelID" readonly> 
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="ConfirmIDTBZ" class="form-label fw-bold">ID Transact Line Item TBZ</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmIDTBZ" readonly> 
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="ConfirmWOTBZ" class="form-label fw-bold">WO TBZ</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmWOTBZ" readonly> 
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="ConfirmProduct" class="form-label fw-bold">Produk</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmProduct" readonly> 
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="ConfirmJobID" class="form-label fw-bold">Job ID</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmJobID" readonly> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="ConfirmPartNo" class="form-label fw-bold">PartNo</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmPartNo" readonly> 
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="ConfirmCategory" class="form-label fw-bold">Kategori</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmCategory" readonly> 
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="ConfirmPartDesc" class="form-label fw-bold">Part Description</label>
                                                    <textarea class="form-control form-control-sm text-start" id="ConfirmPartDesc" readonly rows="2"></textarea>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="ConfirmWOChild" class="form-label fw-bold">WO Mapping ID</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmWOID" readonly> 
                                                </div>
                                                <div class="col-md-9">
                                                    <label for="ConfirmWOChild" class="form-label fw-bold">WO Child</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmWOChild" readonly> 
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="ConfirmExpense" class="form-label fw-bold">Expense</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmExpense" readonly> 
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="ConfirmQty" class="form-label fw-bold">Qty</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmQty" readonly> 
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="ConfirmUOM" class="form-label fw-bold">UOM</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmUOM" readonly> 
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="ConfirmUnitCost" class="form-label fw-bold">Unit Cost</label>
                                                    <input type="text" class="form-control form-control-sm text-center" id="ConfirmUnitCost" readonly> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12"><hr></div>
                                        <div class="col-md-12 d-grid pb-2"><button class="btn btn-dark btn-sm" id="BtnSubmit">Simpan Data</button></div>
                                        <div class="col-md-12"><div id="InfoSubmit"></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalTypeStocks" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"  aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header"><h6 class="modal-title">Form Pemilihan Jenis Stock</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body">
                <div id="ContentModalFilterWO">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <h6 class="card-header">Jenis Stock</h6>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-border table-hover" id="TableTypeStock">
                                                    <thead class="theadCustom">
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th class="text-center">ID</th>
                                                            <th class="text-center">Jenis Stock</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center"><button class="btn btn-sm btn-dark BtnSelect">Gunakan</button></td>
                                                            <td class="text-center">MS</td>
                                                            <td class="text-center">Masuk Stock</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center"><button class="btn btn-sm btn-dark BtnSelect">Gunakan</button></td>
                                                            <td class="text-center">TS</td>
                                                            <td class="text-center">Tanpa Stock</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center"><button class="btn btn-sm btn-dark BtnSelect">Gunakan</button></td>
                                                            <td class="text-center">TS-IN</td>
                                                            <td class="text-center">Tanpa Stock RM/Tools</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

