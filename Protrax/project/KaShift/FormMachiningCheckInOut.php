<?php
require_once("project/KaShift/Modules/ModuleCheckInOutStatus.php");
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
    if($RDataUserWebtrax['MnAdmin'] != "1" && $RDataUserWebtrax['MnKAShift'] != "1" && $RDataUserWebtrax['MnOprFabrication'] != "1" && $RDataUserWebtrax['MnOprFinishing'] != "1" && $RDataUserWebtrax['MnOprQC'] != "1" && $RDataUserWebtrax['MnWarehouse'] != "1") 
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
        if($RDataUserWebtrax['MnKAShift'] != "1" && $RDataUserWebtrax['MnOprFabrication'] != "1" && $RDataUserWebtrax['MnOprFinishing'] != "1" && $RDataUserWebtrax['MnOprQC'] != "1" && $RDataUserWebtrax['MnWarehouse'] != "1")
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
$LocationArea = "MACHINING";
$TitleMenu = "KaShift : Machining Check In Out";
if(isset($_GET['loc']))
{
    $ValLocEnc = base64_decode(base64_decode($_GET['loc']));
    if(strpos($ValLocEnc, ":") !== FALSE)   # check string loc
    {
        $ValLoc = str_replace("Loc:","",$ValLocEnc);
        $LocationArea = $ValLoc;
        $TitleMenu = ucfirst(strtolower($LocationArea))." : ".ucfirst(strtolower($LocationArea))." Check In Out";
    }
    else
    {
        $LocationArea = "MACHINING";
        $TitleMenu = "KaShift : Machining Check In Out";
    }
}

?>
<script src="project/KaShift/lib/LibMachCheckInOutStatus.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $TitleMenu; ?></li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Form <?php echo ucfirst(strtolower($LocationArea)); ?> Check In</h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterLocationIn" class="form-label fw-bold">Lokasi Kantor</label>
                                    <select class="form-select form-select-sm" id="FilterLocationIn">
                                        <option>PSL</option>
                                        <option>PSM</option>
                                    </select>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterAreaWork" class="form-label fw-bold">Lokasi Area Kerja</label>
                                    <select class="form-select form-select-sm" id="FilterAreaWork" disabled>
                                        <option <?php if($LocationArea == "MACHINING"){echo "selected";} ?>>MACHINING</option>
                                        <option <?php if($LocationArea == "FABRICATION"){echo "selected";} ?>>FABRICATION</option>
                                        <option <?php if($LocationArea == "FINISHING"){echo "selected";} ?>>FINISHING</option>
                                        <?php /*<option <?php if($LocationArea == "QC"){echo "selected";} ?>>QC</option>
                                        <option <?php if($LocationArea == "WAREHOUSE"){echo "selected";} ?>>WAREHOUSE</option> */ ?>
                                    </select>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterTypeIn" class="form-label fw-bold">Jenis Barcode</label>
                                    <select class="form-select form-select-sm" id="FilterTypeIn">
                                        <option>Material</option>
                                        <option>Part</option>
                                    </select>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="InputBarcodeIn" class="form-label fw-bold">Input Barcode</label>
                                    <input type="text" class="form-control form-control-sm text-center" id="InputBarcodeIn"> 
                                </div>  
                            </div>
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12 d-grid">
                                <button class="btn btn-sm btn-dark" id="BtnBarcodeIn">Check In</button>
                            </div>
                            <div class="col-md-12 pt-2"><div id="InfoBarcodeIn"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Form <?php echo ucfirst(strtolower($LocationArea)); ?> Check Out</h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterLocationOut" class="form-label fw-bold">Lokasi Kantor</label>
                                    <select class="form-select form-select-sm" id="FilterLocationOut">
                                        <option>PSL</option>
                                        <option>PSM</option>
                                    </select>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterAreaWorkOut" class="form-label fw-bold">Lokasi Area Kerja</label>
                                    <select class="form-select form-select-sm" id="FilterAreaWorkOut" disabled>
                                        <option <?php if($LocationArea == "MACHINING"){echo "selected";} ?>>MACHINING</option>
                                        <option <?php if($LocationArea == "FABRICATION"){echo "selected";} ?>>FABRICATION</option>
                                        <option <?php if($LocationArea == "FINISHING"){echo "selected";} ?>>FINISHING</option>
                                        <?php /*<option <?php if($LocationArea == "QC"){echo "selected";} ?>>QC</option>
                                        <option <?php if($LocationArea == "WAREHOUSE"){echo "selected";} ?>>WAREHOUSE</option> */ ?>
                                    </select>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterTypeOut" class="form-label fw-bold">Jenis Barcode</label>
                                    <select class="form-select form-select-sm" id="FilterTypeOut">
                                        <option>Material</option>
                                        <option>Part</option>
                                    </select>
                                </div>  
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="InputBarcodeOut" class="form-label fw-bold">Input Barcode</label>
                                    <input type="text" class="form-control form-control-sm text-center" id="InputBarcodeOut"> 
                                </div>  
                            </div>
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12 d-grid">
                                <button class="btn btn-sm btn-dark" id="BtnBarcodeOut">Check Out</button>
                            </div>
                            <div class="col-md-12 pt-2"><div id="InfoBarcodeOut"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 pt-4">
        <div class="row" id="LogHistoryIn">
            <div class="col-md-12">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Top 20 Log History</h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table table-responsive">
                                    <table class="table table-bordered" id="TableLogHistory">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">NIK</th>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">TglCheck</th>
                                                <th class="text-center">StatusCheck</th>
                                                <th class="text-center">BarcodeID</th>
                                                <th class="text-center">Location</th>
                                                <th class="text-center">Company</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php 
                                        $No = 1;
                                        $QLog = GET_LOG_HISTORY_CHECKINOUT_BARCODE($LocationArea,$LinkPSL);
                                        while($RLog = mssql_fetch_assoc($QLog))
                                        {
                                            $ValCompany = "PSL";
                                            if(trim($RLog['Company']) != "")
                                            {
                                                $ValCompany = trim($RLog['Company']);
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $No; ?></td>
                                                <td class="text-center"><?php echo trim($RLog['NIK2']); ?></td>
                                                <td class="text-start"><?php echo trim($RLog['EmployeeName']); ?></td>
                                                <td class="text-center"><?php echo date('m/d/Y H:i:s',strtotime(trim($RLog['DateCheck2']))); ?></td>
                                                <td class="text-center"><?php echo trim($RLog['StatusCheck']); ?></td>
                                                <td class="text-center"><?php echo trim($RLog['Barcode_ID']); ?></td>
                                                <td class="text-center"><?php echo trim($RLog['Location']); ?></td>
                                                <td class="text-center"><?php echo $ValCompany; ?></td>
                                            </tr>
                                            <?php
                                            $No++;
                                        }
                                        ?></tbody>
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
