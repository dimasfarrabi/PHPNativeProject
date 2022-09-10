<?php  
require_once("project/Security/Modules/ModuleSecurity.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
if($AccessLogin != "Reguler")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}
$Yesterday = date("m/d/Y",strtotime("-1 day"));

$QDataGuest = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
$RDataGuest = mssql_fetch_assoc($QDataGuest);
$LocCompany = $RDataGuest['Company'];
# check data before
$QCheckData1 = GET_DATA_KWH_TRACKING_BEFORE($Yesterday,$LocCompany,$linkHRISWebTrax);
$QCheckData2 = GET_DATA_KWH_TRACKING_BEFORE_SECURITY($Yesterday,$LocCompany,$linkHRISWebTrax);
$RowCheck1 = mssql_num_rows($QCheckData1);
$RowCheck2 = mssql_num_rows($QCheckData2);
$TotalCheckRow = $RowCheck1 + $RowCheck2;
if($TotalCheckRow == 0)
{
    $FormInput = '';
    $BtnAdd = '';
}
else
{
    if($RowCheck1 != "0")
    {
        $RCheckData1 = mssql_fetch_assoc($QCheckData1);
        $ValKWH = trim($RCheckData1['KWH']);
        $FormInput = ' value="'.$ValKWH.'" disabled';
        $BtnAdd = ' disabled';
    }
    if($RowCheck2 != "0")
    {
        $RCheckData2 = mssql_fetch_assoc($QCheckData2);
        $ValKWH = trim($RCheckData2['Usage']);
        $FormInput = ' value="'.$ValKWH.'" disabled';
        $BtnAdd = ' disabled';
    }
}

?><script src="project/Security/lib/LibSecurity.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=23">Electricy Usage : Input KWH</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-inline">
                    <div class="form-group">
                        <div class="controls"><label for="InputDate">Date</label>
                            <div class="input-group"><input id="InputDate" name="InputDate" type="text" class="date-picker form-control" value="<?php echo $Yesterday; ?>" readonly /><label for="InputDate" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="col-sm-12" id="ContentResult">
                <label>Date Input : <?php echo $Yesterday; ?></label>
                <div class="form-group">
                    <label for="InputUsage">Usage</label>
                    <input type="text" class="form-control form-control-custom" id="InputUsage" name="InputUsage"<?php echo $FormInput; ?> required>
                </div>                        
                <button id="BtnAdd" class="btn btn-md btn-dark"<?php echo $BtnAdd; ?>>Add Data</button>
            </div>
        </div>
        <div class="row" id="ResultMsg"></div><span id="TempData" class="InvisibleText"></span><span id="InputLocation" class="InvisibleText"><?php echo base64_encode(base64_encode("Location#".$LocCompany.""));?></span>
    </div>
    <div class="col-md-9">
        <div id="TableTopData">
            <strong>Table Top 10 Result</strong>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="TableData">
                    <thead class="theadCustom">    
                        <tr>
                            <th class="text-center" width="10">No</th>
                            <th class="text-center" width="100">Date</th>
                            <th class="text-center">Usage</th>
                            <th class="text-center">Location</th>
                            <th class="text-center">Name</th>
                        </tr>
                    </thead>
                    <tbody><?php 
                    $QData = GET_HISTORY_TOP10_SECURITY_KWH_TRACKING_LOG($LocCompany,$linkHRISWebTrax);
                    $No = 1;
                    while($RData = mssql_fetch_assoc($QData))
                    {
                        $ValDate = date("m/d/Y",strtotime($RData['DateTracking']));
                        $ValUsage = trim($RData['Usage']);
                        $ValLocation = trim($RData['Location']);
                        $ValUser = trim($RData['FullName']);
                        switch ($ValLocation) {
                            case 'FI':
                                $ResLocation = "Formulatrix Indonesia - Salatiga";
                                break;
                            case 'PSL':
                                $ResLocation = "Promanufacture Indonesia - Salatiga";
                                break;
                            case 'PSM':
                                $ResLocation = "Promanufacture Indonesia - Semarang";
                                break;
                            default:
                                $ResLocation = "-";
                                break;
                        }

                        ?>
                        <tr>
                            <td class="text-center"><?php echo $No; ?></td>
                            <td class="text-center"><?php echo $ValDate; ?></td>
                            <td class="text-center"><?php echo $ValUsage; ?></td>
                            <td class="text-center"><?php echo $ResLocation; ?></td>
                            <td class="text-center"><?php echo $ValUser; ?></td>
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