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
$DateNow = date("m/d/Y");
// $DateNow = '05/20/2021'; # contoh
# get data tracking
$BtnDownloadLock = "";
$QListDataKWHTracking = GET_DATA_USAGE_BY_DATE($DateNow,$DateNow,"FI",$linkHRISWebTrax);
if(mssql_num_rows($QListDataKWHTracking) == "0")
{
    $BtnDownloadLock = " disabled";
}

?>
<div class="col-sm-12"><h5 class="TitleGroup">View Data KWH Tracking</h5></div>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <div class="controls">
                    <label for="txtFilterTanggal1" class="form-label fw-bold">Start Date</label>
                    <div class="input-group">
                        <input id="txtFilterTanggal1" name="txtFilterTanggal1" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal1Val" value="<?php echo $DateNow; ?>" readonly />
                        <label for="txtFilterTanggal1" class="input-group-text" id="txtFilterTanggal1Val"><span class="bi bi-calendar-date text-dark"></span></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="controls">
                    <label for="txtFilterTanggal2" class="form-label fw-bold">End Date</label>
                    <div class="input-group">
                        <input id="txtFilterTanggal2" name="txtFilterTanggal2" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal2Val" value="<?php echo $DateNow; ?>" readonly /><label for="txtFilterTanggal2" class="input-group-text" id="txtFilterTanggal2Val"><span class="bi bi-calendar-date text-dark"></span></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12"></div>
    </div>
</div>
<div class="col-sm-12">&nbsp;</div>
<div class="col-sm-12">
    <button type="button" id="BtnViewTable" class="btn btn-md btn-dark">View Data</button>
</div>
<div class="col-sm-12">
    <hr>
</div>
<div id="ContentResult">
    <div class="col-sm-12">
        <h5>Data Result (<?php echo $DateNow; ?> - <?php echo $DateNow; ?>)</h5>
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
</div>
<div class="col-sm-12">&nbsp;</div>