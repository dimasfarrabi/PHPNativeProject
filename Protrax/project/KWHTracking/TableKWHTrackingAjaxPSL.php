<?php 
session_start();
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleKWHTracking.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValDateStart = htmlspecialchars(trim($_POST['ValDateStart']), ENT_QUOTES, "UTF-8");
    $ValDateEnd = htmlspecialchars(trim($_POST['ValDateEnd']), ENT_QUOTES, "UTF-8");
    # get data tracking
    $BtnDownloadLock = "";
    $QListDataKWHTracking = GET_DATA_USAGE_BY_DATE($ValDateStart,$ValDateEnd,"PSL",$linkHRISWebTrax);
    if(mssql_num_rows($QListDataKWHTracking) == "0")
    {
        $BtnDownloadLock = " disabled";
    }
    ?>
<div class="col-sm-12">
    <h5>Date Result (<?php echo $ValDateStart; ?> - <?php echo $ValDateEnd; ?>)</h5>
</div>
<div class="col-sm-12 text-end">
    <button type="button" id="BtnDownloadResult" class="btn btn-md btn-dark"<?php echo $BtnDownloadLock; ?>>Download</button>
</div>
<div class="col-sm-12">&nbsp;</div>
<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-responsive table-striped table-hover display" id="TableDataKWHTracking">
            <thead>
                <tr>
                    <th class="text-center" width="50">No</th>
                    <th class="text-center">Datetime</th>
                    <th class="text-center">KWH</th>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1;
            while($RListDataKWHTracking = mssql_fetch_assoc($QListDataKWHTracking))
            {
                $TimeSlave = date('m/d/Y',strtotime($RListDataKWHTracking['Log']));
                $KWH = $RListDataKWHTracking['KWH'];
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center"><?php echo $TimeSlave; ?></td>
                    <td class="text-center"><?php echo $KWH; ?></td>
                </tr>
                <?php
                $No++;
            }
            ?></tbody>
        </table>
    </div>
</div>
<div class="col-sm-12">&nbsp;</div>
    <?php
}
else
{
    echo "";    
}
?>