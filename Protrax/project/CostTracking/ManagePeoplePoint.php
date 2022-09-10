<?php
require_once("project/CostTracking/Modules/ModulePeoplePoint.php");
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

*/

?>
<script src="project/CostTracking/lib/LibManagePeoplePoint.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cost Tracking : Manage Discretionary People Point</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body pt-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="FilHalf" class="form-label fw-bold">Select Closed Time</label>
                        <select class="form-select form-select-sm" id="FilHalf">
                            <?php
                            $Data = GET_CLOSEDTIME($linkMACHWebTrax);
                            while($res=sqlsrv_fetch_array($Data))
                            {
                            ?>
                            <option><?php echo trim($res['ClosedTime']); ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="FilterCustom" class="form-label fw-bold">Search By:</label>
                        <select class="form-select form-select-sm" id="FilterCustom">
                            <option>Division</option>
                            <option>Employee Name</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="FilterKeywords" class="form-label fw-bold">Keywords</label>
                        <input type="text" class="form-control form-control-sm" id="FilterKeywords" placeholder="Keywords">
                    </div>
                </div>
                <div class="col-md-12 d-grid mt-2">
                    <button class="btn btn-sm btn-dark" id="btnView">View Data</button>
                </div>      
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row" id="PointContent">

        </div>
    </div>
</div>