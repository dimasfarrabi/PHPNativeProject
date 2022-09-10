<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleReport.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");

# data session
/*$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
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
    if($RDataUserWebtrax['MnReport'] != "1")
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
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValStartDate = htmlspecialchars(trim($_POST['ValStartDate']), ENT_QUOTES, "UTF-8");
    $ValEndDate = htmlspecialchars(trim($_POST['ValEndDate']), ENT_QUOTES, "UTF-8");
    $ValSeason = htmlspecialchars(trim($_POST['ValSeason']), ENT_QUOTES, "UTF-8");
    $ValCategory = htmlspecialchars(trim($_POST['ValCategory']), ENT_QUOTES, "UTF-8");
    $ValKeywords = htmlspecialchars(trim($_POST['ValKeywords']), ENT_QUOTES, "UTF-8");
    # data
    $QData = GET_DATA_TIMETRACK_CUSTOM($ValStartDate,$ValEndDate,$ValSeason,$ValCategory,$ValKeywords,$linkMACHWebTrax);
    if(strtotime($ValEndDate) >= strtotime($ValStartDate))
    {
        $ValEncKeywords = base64_encode($ValKeywords);
        $BtnLocked = "";
        if(sqlsrv_num_rows($QData) == 0)
        {
            $BtnLocked = "";
        }
?>
    <div class="col-md-6"><strong><h5>Data Time Tracking [<?php echo $ValStartDate." - ".$ValEndDate; ?>]</h5></strong></div>
    <div class="col-md-6 text-end"><button class="btn btn-sm btn-outline-dark" title="Download CSV" data-start="<?php echo $ValStartDate; ?>" data-end="<?php echo $ValEndDate; ?>" data-season="<?php echo $ValSeason; ?>" data-category="<?php echo $ValCategory; ?>" data-keywords="<?php echo $ValEncKeywords; ?>" id="BtnDownload"<?php echo $BtnLocked; ?>><i class="bi bi-download"></i> Download CSV</button></div><span id="7841js70s"></span>
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive table-hover display" id="TableTimetrack">
                        <thead>    
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Employee</th>
                                <th class="text-center">WOMapping</th>
                                <th class="text-center">WOChild</th>
                                <th class="text-center">Quote</th>
                                <th class="text-center">ClosedTime</th>
                                <th class="text-center">QtyQuote</th>
                                <th class="text-center">Division</th>
                                <th class="text-center">ExpenseAllocation</th>
                                <th class="text-center">RealTime</th>
                                <th class="text-center">EstimateTime</th>
                                <th class="text-center">PM</th>
                                <th class="text-center">Activity</th>
                                <th class="text-center">ShiftCode</th>
                                <th class="text-center">DateSC+Name</th>
                                <th class="text-center">WOParent</th>
                                <th class="text-center">EstCostHour</th>
                                <th class="text-center">EstFinishDate</th>
                                <th class="text-center">Idx</th>
                                <th class="text-center">QuoteCategory</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">Location</th>
                            </tr>
                        </thead>
                        <tbody><?php
                        $NoLoop = 1;
                        while($RData = sqlsrv_fetch_array($QData))
                        {
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $NoLoop; ?></td>
                                <td class="text-start"><?php echo trim($RData['Date']); ?></td>
                                <td class="text-start"><?php echo trim($RData['EmployeeFullName']); ?></td>
                                <td class="text-center"><?php echo trim($RData['WOMapping_ID']); ?></td>
                                <td class="text-start"><?php echo trim($RData['WOChild']); ?></td>
                                <td class="text-start"><?php echo trim($RData['Quote']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ClosedTime']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QtyQuote']); ?></td>
                                <td class="text-start"><?php echo trim($RData['DivisionName']); ?></td>
                                <td class="text-start"><?php echo trim($RData['ExpenseAllocation']); ?></td>
                                <td class="text-center"><?php echo trim($RData['RealTime']); ?></td>
                                <td class="text-center"><?php echo trim($RData['EstimateTime']); ?></td>
                                <td class="text-start"><?php echo trim($RData['PM']); ?></td>
                                <td class="text-start"><?php echo trim($RData['Activity']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ShiftCode']); ?></td>
                                <td class="text-start"><?php echo trim($RData['DateSC+Name']); ?></td>
                                <td class="text-start"><?php echo trim($RData['WOParent']); ?></td>
                                <td class="text-center"><?php echo trim($RData['EstCostHour']); ?></td>
                                <td class="text-center"><?php echo trim($RData['EstFinishDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Idx']); ?></td>
                                <td class="text-start"><?php echo trim($RData['QuoteCategory']); ?></td>
                                <td class="text-start"><?php echo trim($RData['Product']); ?></td>
                                <td class="text-center"><?php echo trim($RData['Location']); ?></td>
                            </tr>
                            <?php
                            $NoLoop++;
                        }
                        ?></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
    }
    else
    {
        echo '<div class="col-md-6">Error filter date!</div>';
    }
}
else
{
    echo "";    
}
?>