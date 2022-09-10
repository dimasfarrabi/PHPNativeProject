<?php
require_once("project/_TemplateProject/Modules/ModuleTemplate.php");
require_once("project/MachiningCNC/Modules/ModuleSingleTimeTracking.php");
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
}
*/
# data karyawan
$FullName = "DIMAS RIZKY FARRABI";
$QData = GET_DETAIL_EMPLOYEE_BY_NAME($FullName,$linkHRISWebTrax);
$RData = sqlsrv_fetch_array($QData);
if(isset($RData['NIK'])){$UserNIK = trim($RData['NIK']);}else{$UserNIK = "";}
if(isset($RData['FullName'])){$UserFN = trim($RData['FullName']);}else{$UserFN = "";}
if(isset($RData['DivisionName'])){$UserDivName = trim($RData['DivisionName']);}else{$UserDivName = "";}
if(isset($RData['CompanyCode'])){$UserCompanyCode = trim($RData['CompanyCode']);}else{$UserCompanyCode = "";}


/**
 * rombak ulang susunan + koneksi
 * 
 * 
 * 
 * 
 */



?>
<script src="lib/datetimepicker-master/jquery.datetimepicker.full.min.js"></script>
<script src="lib/DataTables-v1.11.3/DataTables/Buttons-2.1.1/js/dataTables.buttons.min.js"></script>
<script src="lib/DataTables-v1.11.3/DataTables/Buttons-2.1.1/js/buttons.html5.min.js"></script>
<script src="lib/DataTables-v1.11.3/DataTables/Buttons-2.1.1/js/buttons.print.min.js"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<script src="project/Inventory/lib/LibInOutPartTBZWithList.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inventory : Proses In/Out Part TBZ dengan List</li>
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
                                <div class="input-group input-group-sm">
                                    <label for="InputLocation" class="input-group-text fw-bold">Lokasi</label>
                                    <select class="form-select form-select-sm" id="InputLocation">
                                        <option >PSL</option>
                                        <option  <?php if($UserCompanyCode == "PSM"){echo " selected";} ?>>PSM</option>
                                    </select>
                                </div>                                
                            </div>
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="InputScan" id="LabelScan" class="form-label fw-bold">Scan BC Label Item</label>
                                    <input type="text" class="form-control form-control-sm text-center" id="InputScan">
                                </div>
                            </div>
                            <div class="col-md-12 pt-3"><div id="InfoScan"></div></div>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="col-md-12 pb-2">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Penerima Barang</h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="InfoNIK" class="form-label fw-bold">NIK</label>
                                <input type="text" class="form-control form-control-sm text-left" id="InfoNIK" value="<?php echo $UserNIK; ?>"  readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="InfoFN" class="form-label fw-bold">Nama</label>
                                <input type="text" class="form-control form-control-sm text-left" id="InfoFN" value="<?php echo $UserFN; ?>" readonly>
                            </div>
                            <div class="col-md-12">
                                <label for="InfoDivision" class="form-label fw-bold">Divisi</label>
                                <input type="text" class="form-control form-control-sm text-left" id="InfoDivision" value="<?php echo $UserDivName; ?>" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoWH" class="form-label fw-bold">Gudang</label>
                                <select class="form-select form-select-sm" id="InfoWH">
                                    <option <?php if($UserDivName == "ASSEMBLY"){ echo "selected";}?>>ASSEMBLY</option>
                                    <option <?php if($UserDivName == "ELECTRONICS"){ echo "selected";}?>>ELECTRONICS</option>                                    
                                    <option <?php if($UserDivName == "MACHINING"){ echo "selected";}?>>MACHINING</option>
                                    <option <?php if($UserDivName == "QUALITY ASSURANCE"){ echo "selected";}?>>QUALITY ASSURANCE</option>
                                    <option <?php if($UserDivName == "INJECTION"){ echo "selected";}?>>INJECTION</option>
                                    <option <?php if($UserDivName == "MECHANICAL ENGINEERING"){ echo "selected";}?>>MECHANICAL ENGINEERING</option>
                                    <option <?php if($UserDivName == "INFORMATION TECHNOLOGY"){ echo "selected";}?>>INFORMATION TECHNOLOGY</option>
                                    <option <?php if($UserDivName == "PROCESS ENGINEERING"){ echo "selected";}?>>PROCESS ENGINEERING</option>
                                    <option <?php if($UserDivName == "MAINTENANCE"){ echo "selected";}?>>MAINTENANCE</option>
                                    <option <?php if($UserDivName == "WAREHOUSE"){ echo "selected";}?>>WAREHOUSE</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="InfoStockType" class="form-label fw-bold">Jenis Stock</label>
                                <select class="form-select form-select-sm" id="InfoStockType" disabled>
                                    <option selected>-- Jenis Stock --</option>
                                    <option>Masuk Stock</option>
                                    <option>Tanpa Stock</option>
                                    <option>Tanpa Stock RM/Tools</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pb-2">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Identitas Group Batch Import</h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="InfoIDBatchCode" class="form-label fw-bold">Import Batch Code</label>
                                <input type="text" class="form-control form-control-sm text-left" id="InfoIDBatchCode" readonly>
                            </div>
                            <div class="col-md-12">
                                <label for="InfoTotalItemLine" class="form-label fw-bold">Total Item Line</label>
                                <input type="text" class="form-control form-control-sm text-left" id="InfoTotalItemLine" readonly> 
                            </div>
                            <div class="col-md-12 pt-2">
                                <button class="btn btn-sm btn-dark">Lihat Detail</button>
                            </div>
                            <div class="col-md-12">
                                <label for="InfoTotalVarWOTBZ" class="form-label fw-bold">Total Variasi WO TBZ</label>
                                <input type="text" class="form-control form-control-sm text-left" id="InfoTotalVarWOTBZ" readonly> 
                            </div>
                            <div class="col-md-12">
                                <label for="InfoListVarWOTBZ" class="form-label fw-bold">List Variasi WO TBZ</label>
                                <select class="form-select" size="3" id="InfoListVarWOTBZ" readonly>
                                    <!-- <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option> -->
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="InfoWOTBZ" class="form-label fw-bold">WO TBZ yg di proses</label>
                                <input type="text" class="form-control form-control-sm text-left" id="InfoWOTBZ" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoTotalVarWOTBZ" class="form-label fw-bold">Job ID<br></label>
                                <input type="text" class="form-control form-control-sm text-left" id="InfoJobID" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoTotalVarWOTBZ" class="form-label fw-bold">WOC</label>
                                <input type="text" class="form-control form-control-sm text-left" id="InfoWOChild" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoExpense" class="form-label fw-bold">Expense</label>
                                <input type="text" class="form-control form-control-sm text-left" id="InfoExpense" readonly> 
                            </div>
                            <div class="col-md-6">
                                <label for="InfoProduct" class="form-label fw-bold">Produk</label>
                                <input type="text" class="form-control form-control-sm text-left" id="InfoProduct" readonly> 
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
                    <h6 class="card-header text-white bg-secondary">Filter Pencarian Data</h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <label for="FilterLocation" class="input-group-text fw-bold">Lokasi</label>
                                            <select class="form-select form-select-sm" id="FilterLocation">
                                                <option >PSL</option>
                                                <option <?php if($UserCompanyCode == "PSM"){echo " selected";} ?>>PSM</option>
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
          
<!-- <div class="modal fade" id="ModalUpdate" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="ContentTemplate"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div> -->