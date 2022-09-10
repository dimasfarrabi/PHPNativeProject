<?php
require_once("project/PPIC/Modules/ModuleNewBCPartJob.php");
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

if((trim($AccessLogin) != "Manager"))
{
    if($RDataUserWebtrax['MnAdmin'] != "1")  
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
    if($RDataUserWebtrax['MnPPIC'] != "1")
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
$DateNow = date("m/d/Y");



?>
<script src="project/PPIC/lib/LibNewBCPartJob.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">PPIC : Buat BC Part</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-3 pb-4" id="FormInput">
        <div class="card">
            <h6 class="card-header">Inisialisasi WO Part No</h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-1"><button class="btn btn-sm btn-dark" id="BtnMappingWO">Cari Data Mapping WO</button></div>
                    <div class="col-md-4">
                        <label for="FilterWOMapID" class="form-label mb-0 fw-bold">WO Map ID</label>
                        <input type="text" class="form-control form-control-sm" id="FilterWOMapID" readonly>
                    </div>
                    <div class="col-md-8">
                        <label for="FilterCompany" class="form-label mb-0 fw-bold">Company</label>
                        <input type="text" class="form-control form-control-sm" id="FilterCompany" value="PT Promanufacture Indonesia - Salatiga" readonly>
                    </div>
                    <div class="col-md-12">
                        <label for="FilterWOChild" class="form-label mb-0 fw-bold">WO Child</label>
                        <input type="text" class="form-control form-control-sm" id="FilterChild" readonly>
                    </div>
                    <div class="col-md-12">
                        <label for="FilterWOParent" class="form-label mb-0 fw-bold">WO Parent</label>
                        <input type="text" class="form-control form-control-sm" id="FilterParent" readonly>
                    </div>
                    <div class="col-md-12">
                        <label for="FilterQuote" class="form-label mb-0 fw-bold">Quote</label>
                        <input type="text" class="form-control form-control-sm" id="FilterQuote" readonly>
                    </div>
                    <div class="col-md-12">
                        <label for="FilterProduct" class="form-label mb-0 fw-bold">Product</label>
                        <input type="text" class="form-control form-control-sm" id="FilterProduct" readonly>
                    </div>
                    <div class="col-md-12">
                        <label for="FilterOrderType" class="form-label mb-0 fw-bold">OrderType</label>
                        <input type="text" class="form-control form-control-sm" id="FilterOrderType" readonly>
                    </div>
                    <div class="col-md-12">
                        <label for="FilterExpense" class="form-label mb-0 fw-bold">Expense</label>
                        <input type="text" class="form-control form-control-sm" id="FilterExpense" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="FilterPartNo" class="form-label mb-0 fw-bold">Part No</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="FilterPartNo" readonly>
                            <button class="btn btn-sm btn-secondary" type="button" id="BtnFindPartNo" disabled>Cari</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="FilterQty" class="form-label mb-0 fw-bold">Qty</label>
                        <input type="text" class="form-control form-control-sm" id="FilterQty" readonly>
                    </div>
                    <div class="col-md-12">
                        <label for="FilterPartStatus" class="form-label mb-0 fw-bold">Part Status</label>
                        <input type="text" class="form-control form-control-sm" id="FilterPartStatus" value="NEW" readonly>
                        <div>(Default status is "New")</div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-check pt-2">
                        <input class="form-check-input" type="checkbox" id="OptCheckFinishing" disabled>
                        <label class="form-check-label" for="OptCheckFinishing">
                            Ada Finishing
                        </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="FilterTypeFinishing" class="form-label mb-0 fw-bold">Jenis Finishing</label>
                        <select class="form-select form-select-sm" aria-label="Select Type Finishing" id="FilterTypeFinishing" disabled>
                            <option value="#">-- Pilih Jenis --</option>
                            <?php 
                            $QListFinishing = LIST_FINISHING_CODE($linkMACHWebTrax);
                            while($RListFinishing = mssql_fetch_assoc($QListFinishing))
                            {
                                $DescriptionList = trim($RListFinishing['Description']);
                                $CodeList = trim($RListFinishing['Code']);
                                ?>
                            <option value="<?php echo $CodeList; ?>"><?php echo $DescriptionList; ?></option>
                                <?php
                            }    
                            ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <hr>
                    </div>
                    <div class="col-md-12 text-end">
                        <button class="btn btn-sm btn-dark w-100" id="BtnNewData" disabled>Simpan Data Barcode</button>
                    </div>
                    <div class="col-md-12 mt-1">
                        <div id="InfoError"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row" id="FormFindData">
            <div class="col-md-12">
                <div class="card">
                    <h6 class="card-header">Pencarian Data</h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="FilterPPIC" class="form-label mb-0 fw-bold">PPIC</label>
                                <select class="form-select form-select-sm" aria-label="Select PPIC" id="FilterPPIC">
                                    <?php 
                                    /*$QListPPIC = LIST_PPIC($linkMACHWebTrax);
                                    while($RListPPIC = mssql_fetch_assoc($QListPPIC))
                                    {
                                        $PPICName = trim($RListPPIC['PPICName']);
                                        if($PPICName == "<ALL DATA>")
                                        {
                                            $PPICVal = "";   
                                            $PPICName = "ALL DATA";  
                                        ?>
                                    <option value="<?php echo $PPICVal; ?>"><?php echo $PPICName; ?></option>
                                        <?php
                                        }
                                        else
                                        {
                                            $PPICVal = $PPICName;    
                                        ?>
                                    <option value="<?php echo $PPICVal; ?>"><?php echo $PPICName; ?></option>
                                        <?php
                                        } 
                                    }*/   
                                    ?>
                                    <option value="<?php echo $FullName; ?>"><?php echo $FullName; ?></option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="FilterTypeData" class="form-label mb-0 fw-bold">Jenis Data</label>
                                <select class="form-select form-select-sm" aria-label="Select Type" id="FilterTypeData">
                                    <option>Barcode Part</option>
                                    <option>Part No</option>
                                    <option>WO Child</option>
                                    <option>Company</option>
                                </select>
                            </div>
                    <div class="col-md-3">
                        <label for="FilterKeywords" class="form-label mb-0 fw-bold">Kata Kunci</label>
                        <input type="text" class="form-control form-control-sm" id="FilterKeywords">
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="FilterYear" class="form-label mb-0 fw-bold">Thn Buat</label>
                                <input type="text" class="form-control form-control-sm" id="FilterYear" value="<?php echo date("Y"); ?>" readonly>
                            </div>
                            <div class="col-md-8 pt-3 mt-1">
                                <button class="btn btn-sm btn-dark" id="BtnSearch">Cari Data</button>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4" id="ContentResult">
            <div class="col-md-12">
                <div class="card">
                    <h6 class="card-header">Hasil Pencarian</h6>
                    <div class="card-body">
                        <div class="row" id="ContentResult2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 pb-5"></div>
    <div class="col-md-12" id="Space"></div>
</div>


<div class="modal fade" id="ModalLoadMappingWO" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header"><h6 class="modal-title">Form Pencarian WO Mapping</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><div id="ContentModal">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h6 class="card-header">Pencarian Data WO</h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="FilterTypeSearchData" class="form-label mb-0 fw-bold">Jenis Pencarian</label>
                        <select class="form-select form-select-sm" aria-label="Select Type" id="FilterTypeSearchData">
                            <option>WO Child</option>
                            <option>WO Parent</option>
                            <option>Quote</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="FilterDivision" class="form-label mb-0 fw-bold">Divisi</label>
                        <select class="form-select form-select-sm" aria-label="Select Division" id="FilterDivision"><?php 
                        $QListDivModal = LIST_DIVISION_MODAL($linkMACHWebTrax);
                        while($RListDivModal = mssql_fetch_assoc($QListDivModal))
                        {
                            echo '<option>'.trim($RListDivModal['DivisionName']).'</option>';
                        }
                        ?></select>
                    </div>
                    <div class="col-md-3">
                        <label for="FilterInputKeywords" class="form-label mb-0 fw-bold">Kata Kunci</label>
                        <input type="text" class="form-control form-control-sm" id="FilterInputKeywords">
                    </div>
                    <div class="col-md-3 pt-3 mt-1">
                        <button class="btn btn-sm btn-dark" id="BtnSearchWOMapping">Cari Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>            
    <div class="col-md-12 pt-4">
        <div class="card">
            <h6 class="card-header">Data WO</h6>
            <div class="card-body">
                <div class="row" id="ContentResultModal"></div>
            </div>
        </div>
    </div>
</div>
</div></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div>

<div class="modal fade" id="ModalLoadPartNo" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header"><h6 class="modal-title">Form Pencarian WO Mapping</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><div id="ContentModalFilterPartNo">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h6 class="card-header">Form Pencarian Part No</h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="FilterTypeCategoryFind" class="form-label mb-0 fw-bold">Jenis Pencarian</label>
                        <select class="form-select form-select-sm" aria-label="Select Type" id="FilterTypeCategoryFind">
                            <option>Part No</option>
                            <option>Part Description</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="FilterInputKeywordPartno" class="form-label mb-0 fw-bold">Kata Kunci</label>
                        <input type="text" class="form-control form-control-sm" id="FilterInputKeywordPartno">
                    </div>
                    <div class="col-md-3 pt-3 mt-1">
                        <button class="btn btn-sm btn-dark" id="BtnSearchPartNo">Cari Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>            
    <div class="col-md-12 pt-4">
        <div class="card">
            <h6 class="card-header">Tabel Item Master (Tanpa Konversi)</h6>
            <div class="card-body">
                <div class="row" id="ContentModalPartNo"></div>
            </div>
        </div>
    </div>
</div>
</div></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div>