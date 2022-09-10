<?php
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleKWHTracking.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
$Yesterday = date("m/d/Y",strtotime("-1 day"));

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
    if($RDataUserWebtrax['MnSecurity'] != "1")
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}

?><div class="col-sm-12"><h5 class="TitleGroup">Import Data Electricity Usage</h5></div>
<div class="col-sm-12">
    <div class="row">    
        <div class="col-md-3">
            <div class="row">
                <div class="col-sm-12">[<span class="DownloadTemplate" id="DownloadTemplate">Download Template</span>]</div>
                <form method="post" action="project/KWHTracking/src/srcImportKWHTrackingPSMV2.php" id="FormImportKWHTracking" enctype="multipart/form-data">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="InputFile" class="form-label fw-bold">File input</label>
                        <input class="form-control form-control-sm" type="file" id="InputFile" name="InputFile" accept=".csv">
                        <p class="help-block"><i class="fw-bold">Format file .csv, format date <?php echo date("d/m/Y"); ?>.</i></p>
                    </div>
                </div>
                <hr>
                <div class="col-sm-5">
                    <button type="submit" id="BtnSubmit" class="btn btn-md btn-dark">Import Data</button>
                </div>
                </form>
                <div class="col-sm-7">&nbsp;</div>
                <div class="col-sm-12">&nbsp;</div>
                <div class="col-sm-12"><i>*)Please generate after import data.</i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-sm-12">
                    <label for="InputDate" class="form-label fw-bold">Date</label>
                    <div class="input-group"><input id="InputDate" name="InputDate" type="text" class="date-picker form-control" aria-describedby="InputDateVal" value="<?php echo $Yesterday; ?>" readonly /><label for="InputDate" class="input-group-text" id="InputDateVal"><span class="bi bi-calendar-date text-dark"></span></label>
                    </div>
                </div>
                 <div class="col-sm-12">
                    <label for="InputUsage" class="form-label fw-bold">Usage</label>
                    <input type="text" class="form-control form-control-custom" id="InputUsage" name="InputUsage" required>
                </div>
                <div class="col-sm-12">
                <hr>
                </div>
                <div class="col-sm-5">
                    <button id="BtnAdd" class="btn btn-md btn-dark">Add Data</button>
                </div>
                <div class="col-sm-7">&nbsp;</div>
            </div>
            <div class="row" id="ResultMsg"></div>
        </div>
        <div class="col-md-6" id="TableTopData">
            <strong>Table Top 10 Result</strong>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="TableData">
                    <thead class="theadCustom">    
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Usage</th>
                        </tr>
                    </thead>
                    <tbody><?php 
                    $QData = GET_LIST_TOP10_KWH_ADDED("PSM",$linkHRISWebTrax);
                    $No = 1;
                    while($RData = mssql_fetch_assoc($QData))
                    {
                        $ValDate = date("m/d/Y",strtotime($RData['DateLog']));
                        $ValUsage = trim($RData['KWH']);
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $No; ?></td>
                            <td class="text-center"><?php echo $ValDate; ?></td>
                            <td class="text-center"><?php echo $ValUsage; ?></td>
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


