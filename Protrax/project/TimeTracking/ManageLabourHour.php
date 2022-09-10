<?php
require_once("project/TimeTracking/Modules/ModuleLabourHour.php");
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

if($RDataUserWebtrax['MnAdmin'] != "1")  
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}


$ArrayEmployee = array();
$ArrayEmployee2 = array();
# list employee salatiga
$QListEmployeePSL = GET_LIST_EMPLOYEE_PRODUCTION($linkMACHWebTrax);
while($RListEmployeePSL = mssql_fetch_assoc($QListEmployeePSL))
{
    $ValEmployee = trim($RListEmployeePSL['FullName']);
    $ValDetailPosition = trim($RListEmployeePSL['DetailPosition']);
    $TemporaryArray = array(
        "FullName" => $ValEmployee,
        "Location" => "SALATIGA",
        "Position" => $ValDetailPosition
    );
    array_push($ArrayEmployee,$TemporaryArray);
}
$ArrListPMDM = array();
$QListPMDM = GET_LIST_PM_DM_NAME($linkMACHWebTrax);
while($RListPMDM = mssql_fetch_assoc($QListPMDM))
{
    array_push($ArrListPMDM,array("FullName"=>trim($RListPMDM['FullName']),"Role"=>trim($RListPMDM['Role'])));
}
# list employee semarang
$QListEmployeePSM = GET_LIST_EMPLOYEE_PRODUCTION_PSM();
while($RListEmployeePSM = mssql_fetch_assoc($QListEmployeePSM))
{
    $ValEmployee = trim($RListEmployeePSM['FullName']);
    $ValDetailPosition = trim($RListEmployeePSM['DetailPosition']);
    $TemporaryArray = array(
        "FullName" => $ValEmployee." - PSM",
        "Location" => "SEMARANG",
        "Position" => $ValDetailPosition
    );
    array_push($ArrayEmployee,$TemporaryArray);
}
foreach($ArrayEmployee as $ArrayEmployee1)
{
    array_push($ArrayEmployee2,array("FullName" => trim($ArrayEmployee1['FullName'])));
}
foreach($ArrListPMDM as $ArrListPMDM1)
{
    array_push($ArrayEmployee2,array("FullName" => trim($ArrListPMDM1['FullName'])));
}
sort($ArrayEmployee2);

?>
<script src="project/TimeTracking/lib/LibManageLabourHour.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Administration : Manage Labour Hour</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-5">
                <label for="FilterName" class="form-label fw-bold">Employee</label>
                <select class="form-select form-select-sm" aria-label="Select Employee" id="FilterName">
                    <?php
                    foreach($ArrayEmployee2 as $DtEmployee)
                    {
                        ?>
                        <option><?php echo trim($DtEmployee['FullName']); ?></option>
                        <?php
                    }       
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="FilterWOC" class="form-label fw-bold">WO Child</label>
                <input type="text" class="form-control form-control-sm" id="FilterWOC">
            </div>
            <div class="col-md-3 mt-4 pt-1">
                <button type="button" id="BtnViewData" class="btn btn-sm btn-dark">View Data</button>
            </div>
        </div>
    </div>
    <div class="col-md-6"></div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <div class="row" id="ContentPageManage"></div>
    </div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12">&nbsp;</div>
</div>
