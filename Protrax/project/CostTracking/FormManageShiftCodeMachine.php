<?php
require_once("project/CostTracking/Modules/ModuleShiftCodeMachine.php");
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
    if($RDataUserWebtrax['MnAdmin'] != "1" && $RDataUserWebtrax['MnCostTracking'] != "1")  
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
        if($RDataUserWebtrax['MnCostTracking'] != "1")
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
if(isset($_SESSION['InfoUploadShiftCodeMachine']))
{
    echo $_SESSION['InfoUploadShiftCodeMachine'];
    unset($_SESSION['InfoUploadShiftCodeMachine']);
}

# list machine PSM
$ArrListMachine = array();
$ArrListMachineHours = array();
$QListMachinePSM = LOAD_LIST_MACHINE_PSM();
while($RListMachinePSM = mssql_fetch_assoc($QListMachinePSM))
{
    $TempArray = array(
        "Machine" => trim($RListMachinePSM['Machine']),
        "Location" => "SEMARANG"
    );
    array_push($ArrListMachine,$TempArray);
}
# list shiftcode machine list PSM
$QListMachinePSM = LOAD_SHIFTCODE_MACHINE_LIST_PSM($DateNow,$DateNow);
while($RListMachinePSM = mssql_fetch_assoc($QListMachinePSM))
{
    $Machine = trim($RListMachinePSM['Machine']);
    $ValDate = date('m/d/Y',strtotime($RListMachinePSM['Date']));
    $UtilizeHour = (int)trim($RListMachinePSM['UtilizeHours']);
    $DataToken = base64_encode(base64_encode(trim($RListMachinePSM['Idx'])."DataToken"));
    $ArrTemp = array(
        "Machine" => $Machine,
        "Date" => $ValDate,
        "UtilizeHour" => $UtilizeHour,
        "DataToken" => $DataToken,
        "Location" => "SEMARANG"
    );
    array_push($ArrListMachineHours,$ArrTemp);
}

# list machine PSL
$QListMachinePSL = LOAD_LIST_MACHINE($linkMACHWebTrax);
while($RListMachinePSL = mssql_fetch_assoc($QListMachinePSL))
{
    $TempArray = array(
        "Machine" => trim($RListMachinePSL['Machine']),
        "Location" => "SALATIGA"
    );
    array_push($ArrListMachine,$TempArray);
}
# list shiftcode machine list PSL
$QListMachinePSL = LOAD_SHIFTCODE_MACHINE_LIST($DateNow,$DateNow,$linkMACHWebTrax);
while($RListMachinePSL = mssql_fetch_assoc($QListMachinePSL))
{
    $Machine = trim($RListMachinePSL['Machine']);
    $ValDate = date('m/d/Y',strtotime($RListMachinePSL['Date']));
    $UtilizeHour = (int)trim($RListMachinePSL['UtilizeHours']);
    $DataToken = base64_encode(base64_encode(trim($RListMachinePSL['Idx'])."DataToken"));
    $ArrTemp = array(
        "Machine" => $Machine,
        "Date" => $ValDate,
        "UtilizeHour" => $UtilizeHour,
        "DataToken" => $DataToken,
        "Location" => "SALATIGA"
    );
    array_push($ArrListMachineHours,$ArrTemp);
}
asort($ArrListMachine);
asort($ArrListMachineHours);



?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<script src="project/CostTracking/lib/LibManageShiftCodeMachine.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cost Tracking : Manage Shiftcode Machine</li>
            </ol>
        </nav>
    </div>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12"><h5>Form Upload</h5></div>
            <div class="col-md-12"><hr class="mt-0"></div>
            <div class="col-md-12 text-end DownloadTemplate" id="DownloadTemplate">[Download Template]</div>
            <form action="project\CostTracking\src\srcUploadShiftCodeMachine.php" method="post" enctype="multipart/form-data">
            <div class="col-md-12 mb-2">
                <label for="UploadFileShiftCode" class="form-label fw-bold">Upload File</label>
                <input class="form-control form-control-sm" name="UploadFileShiftCode" id="UploadFileShiftCode" type="file" accept=".csv">
            </div>
            <div class="col-md-12 d-grid mb-2">
                <button class="btn btn-sm btn-dark submit" id="BtnUpload">Upload</button>
            </div>
            </form>
            <div class="col-md-12 mb-2"><i>*) Format Date : mm/dd/yyyy</i></div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12"><h5>Pencarian Data</h5></div>
            <div class="col-md-12"><hr class="mt-0"></div>
            <div class="col-md-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="UseDate">
                    <label class="form-check-label fw-bold" for="UseDate">Pakai Tanggal</label>
                </div>    
            </div>
            <div class="col-3">
                <div class="row">
                    <label for="txtFilterTanggal1" class="col-md-3 col-form-label col-form-label fw-bold pt-1">Mulai</label>
                    <div class="col-md-9">
                        <div class="input-group input-group-sm">
                            <input id="txtFilterTanggal1" name="txtFilterTanggal1" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal1Val" value="<?php echo $DateNow; ?>" readonly />
                            <label for="txtFilterTanggal1" class="input-group-text" id="txtFilterTanggal1Val"><span class="bi bi-calendar-date text-dark"></span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="row">
                    <label for="txtFilterTanggal2" class="col-md-3 col-form-label col-form-label fw-bold pt-1">Sampai</label>
                    <div class="col-md-9">
                        <div class="input-group input-group-sm">
                            <input id="txtFilterTanggal2" name="txtFilterTanggal2" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal2Val" value="<?php echo $DateNow; ?>" readonly />
                            <label for="txtFilterTanggal2" class="input-group-text" id="txtFilterTanggal2Val"><span class="bi bi-calendar-date text-dark"></span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row">
                    <label for="OptMachine" class="col-md-2 col-form-label col-form-label fw-bold pt-1">Mesin</label>
                    <div class="col-md-10">
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example" id="OptMachine"><?php 
                        foreach ($ArrListMachine as $ListMachine)
                        {
                            $MachID = trim($ListMachine['Location']);
                            ?>
                            <option data-location="<?php echo $MachID;?>"><?php echo trim($ListMachine['Machine']); ?></option>
                            <?php
                        }
                        ?></select>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <button class="btn btn-sm btn-dark submit" id="BtnViewData">Lihat Data</button>
            </div>
            <div class="col-md-12"><hr class="mt-2"></div>
            <div class="col-md-12"><div class="row" id="ContentSearchData">
                <div class="col-12"><h5>Daftar Mesin Tgl <?php echo $DateNow ; ?></h5></div>
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="TableShiftCode">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Location</th>
                                    <th class="text-center">Machine</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Utilize Hours</th>
                                    <th class="text-center">#</th>
                                </tr>
                            </thead>
                            <tbody><?php 
                            $No = 1;
                            foreach ($ArrListMachineHours as $ListMachineHours) 
                            {
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $No; ?></td>
                                    <td class="text-center"><?php echo $ListMachineHours['Location']; ?></td>
                                    <td class="text-start"><?php echo $ListMachineHours['Machine']; ?></td>
                                    <td class="text-center"><?php echo $ListMachineHours['Date']; ?></td>
                                    <td class="text-center"><?php echo $ListMachineHours['UtilizeHour']; ?></td>
                                    <td class="text-center"><span class="PointerList DeleteRow" data-token="<?php echo $ListMachineHours['DataToken']; ?>" data-location="<?php echo $ListMachineHours['Location']; ?>">Delete</span></td>
                                </tr>
                                <?php
                                $No++; 
                            }
                            ?></tbody>
                        </table>
                    </div>
                </div>
            </div></div>
        </div>
    </div>
    <div class="col-md-12 mb-5" id="ResultPage"></div>
    <div class="col-md-12 mb-5">&nbsp;</div>
</div>






