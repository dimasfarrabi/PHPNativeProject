<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWOMapping.php");
require_once("../../Project/Report/Modules/ModuleReport.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
$UserNameSession = "dimas.farrabi@m2.formulatrix.com";
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkMACHWebTrax);
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
*/
while($res=sqlsrv_fetch_array($QDataUserWebtrax))
{
    $MnWOMapping = trim($res['MnWOMapping']);
    $MnClosedWO = trim($res['MnClosedWO']);
}
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValLocation = htmlspecialchars(trim($_POST['Location']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ClosedTime']), ENT_QUOTES, "UTF-8");
    $ValOpen = htmlspecialchars(trim($_POST['Open']), ENT_QUOTES, "UTF-8");
    $ValUsedCL = htmlspecialchars(trim($_POST['UsedCL']), ENT_QUOTES, "UTF-8");
    $ValFilterType = htmlspecialchars(trim($_POST['FilterType']), ENT_QUOTES, "UTF-8");
    $ValKeywords = htmlspecialchars(trim($_POST['Keywords']), ENT_QUOTES, "UTF-8");
    //  echo "$ValLocation >> $ValClosedTime >> $ValOpen >> $ValUsedCL >> $ValFilterType >> $ValKeywords";
     $QData = GET_LIST_MANAGE_WO_MAPPING($ValClosedTime,$ValFilterType,$ValKeywords,$ValUsedCL,$ValOpen,$linkMACHWebTrax);
    // if($ValLocation == "PSL")
    // {
    //     $QData = GET_LIST_MANAGE_WO_MAPPING($ValClosedTime,$ValFilterType,$ValKeywords,$ValUsedCL,$ValOpen,$linkMACHWebTrax);
    // }
    // if($ValLocation == "PSM")
    // {
    //     $QData = GET_LIST_MANAGE_WO_MAPPING_PSM($ValClosedTime,$ValFilterType,$ValKeywords,$ValUsedCL,$ValOpen);
    // }
    
    ?>
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <h6 class="card-header text-white bg-secondary">Filter Pencarian - WO Mapping [<?php echo $ValClosedTime;?>][<?php echo $ValLocation; ?>]</h6>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive table-hover display" id="TableWOMapping">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">#</th>
                                <th class="text-center">ClosedTime</th>
                                <th class="text-center">Quote</th>
                                <th class="text-center">ExpenseAllocation</th>
                                <th class="text-center">WOMapping_ID</th>
                                <th class="text-center">WOChild</th>
                                <th class="text-center">WOParent</th>
                                <th class="text-center">QtyParent</th>
                                <th class="text-center">QtyQuote</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">OrderType</th>
                                <th class="text-center">PM</th>
                                <th class="text-center">CO PM</th>
                                <th class="text-center">DM</th>
                                <th class="text-center">TargetLaborTime (Hour)</th>
                                <th class="text-center">LaborTime (Hour)</th>
                                <th class="text-center">TargetMachineTime (Hour)</th>
                                <th class="text-center">MachineTime (Hour)</th>
                                <th class="text-center">TargetMaterialCost($)</th>
                                <th class="text-center">MaterialCost($)</th>
                                <th class="text-center">EstFinishDate</th>
                                <th class="text-center">ClosedDate</th>
                                <th class="text-center">QuoteCategory</th>
                                <th class="text-center">QtyQCIn</th>
                                <th class="text-center">QtyQCOut</th>
                                <th class="text-center">MappingCode</th>
                                <th class="text-center">Location</th>
                                <th class="text-center">WOType</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $NoLoop = 1;
                        while($RData = sqlsrv_fetch_array($QData))
                        {
                            $QtyParent = trim($RData['QtyParent']);
                            $QtyQuote = trim($RData['QtyQuote']);
                            $TargetLaborTime = trim($RData['TargetLaborTime']);
                            $TargetMachineTime = trim($RData['TargetMachineTime']);
                            $TargetMaterialCost = trim($RData['TargetMaterialCost']);
                            $LaborTime = trim($RData['LaborTime']);
                            $MachineTime = trim($RData['MachineTime']);
                            $MaterialCost = trim($RData['MaterialCost']);
                            $QtyQCIn = trim($RData['QtyQCIn']);
                            $QtyQCOut = trim($RData['QtyQCOut']);

                            $QtyParent = number_format((float)$QtyParent,0,'.',',');
                            $QtyQuote = number_format((float)$QtyQuote,0,'.',',');
                            $TargetLaborTime = number_format((float)$TargetLaborTime,2,'.',',');
                            $TargetMachineTime = number_format((float)$TargetMachineTime,2,'.',',');
                            $TargetMaterialCost = number_format((float)$TargetMaterialCost,2,'.',',');
                            $LaborTime = number_format((float)$LaborTime,2,'.',',');
                            $MachineTime = number_format((float)$MachineTime,2,'.',',');
                            $MaterialCost = number_format((float)$MaterialCost,2,'.',',');
                            $QtyQCIn = number_format((float)$QtyQCIn,0,'.',',');
                            $QtyQCOut = number_format((float)$QtyQCOut,0,'.',',');

                            if(trim($RData['Location']) == "" || trim($RData['Location']) == "NULL"){$ValLoc = $ValLocation;}else{$ValLoc = trim($RData['Location']);}

                            $DataRow = base64_encode(base64_encode(trim($RData['WOMapping_ID'])."#".$ValLoc));
                            if($MnClosedWO == '1' && $MnWOMapping == '0')
                            {
                                $opt = '<i class="bi bi-key-fill UpdateCT" title="Update Closed Time"></i>';
                            }
                            elseif($MnClosedWO == '0' && $MnWOMapping == '1')
                            {
                                $opt = '<i class="bi bi-gear-fill OptionUpdate" title="Update WO"></i>&nbsp;&nbsp;&#10072;&nbsp;&nbsp;<i class="bi bi-trash-fill DeleteWO" title="Delete WO Mapping"></i>';
                            }
                            elseif($MnClosedWO == '1' && $MnWOMapping == '1')
                            {
                                $opt = '<i class="bi bi-gear-fill OptionUpdate" title="Update WO"></i>&nbsp;&nbsp;&#10072;&nbsp;&nbsp;<i class="bi bi-key-fill UpdateCT" title="Update Closed Time"></i>&nbsp;&nbsp;&#10072;&nbsp;&nbsp;<i class="bi bi-trash-fill DeleteWO" title="Delete WO Mapping"></i>';
                            }
                            else
                            {
                                $opt = '';
                            }
                        ?>
                            <tr data-idrows="<?php echo $DataRow; ?>">
                                <td class="text-center"><?php echo $NoLoop; ?></td>
                                <td class="text-center"><?php echo $opt; ?></td>
                                <td class="text-center"><?php echo trim($RData['ClosedTime']); ?></td>
                                <td class="text-start"><?php echo trim($RData['Quote']); ?></td>
                                <td class="text-start"><?php echo trim($RData['ExpenseAllocation']); ?></td>
                                <td class="text-center"><?php echo trim($RData['WOMapping_ID']); ?></td>
                                <td class="text-start"><?php echo trim($RData['WOChild']); ?></td>
                                <td class="text-start"><?php echo trim($RData['WOParent']); ?></td>
                                <td class="text-end"><?php echo  $QtyParent; ?></td>
                                <td class="text-end"><?php echo $QtyQuote; ?></td>
                                <td class="text-center"><?php echo trim($RData['Product']); ?></td>
                                <td class="text-center"><?php echo trim($RData['OrderType']); ?></td>
                                <td class="text-start"><?php echo trim($RData['PM']); ?></td>
                                <td class="text-start"><?php echo trim($RData['CO_PM']); ?></td>
                                <td class="text-start"><?php echo trim($RData['DM']); ?></td>
                                <td class="text-end"><?php echo $TargetLaborTime; ?></td>
                                <td class="text-end"><?php echo $LaborTime; ?></td>
                                <td class="text-end"><?php echo $TargetMachineTime; ?></td>
                                <td class="text-end"><?php echo $MachineTime ?></td>
                                <td class="text-end"><?php echo $TargetMaterialCost; ?></td>
                                <td class="text-end"><?php echo $MaterialCost; ?></td>
                                <td class="text-center"><?php echo trim($RData['EstFinishDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['ClosedDate']); ?></td>
                                <td class="text-center"><?php echo trim($RData['QuoteCategory']); ?></td>
                                <td class="text-end"><?php echo $QtyQCIn; ?></td>
                                <td class="text-end"><?php echo $QtyQCOut ?></td>
                                <td class="text-start"><?php echo trim($RData['MappingCode']); ?></td>
                                <td class="text-center"><?php echo $ValLoc; ?></td>
                                <td class="text-start"><?php echo trim($RData['WOType']); ?></td>
                            </tr>
                        <?php
                        $NoLoop++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>                    
            </div>  
        </div>  
    </div>  
</div> 
    <?php
}
else
{
    echo "";    
}
?>