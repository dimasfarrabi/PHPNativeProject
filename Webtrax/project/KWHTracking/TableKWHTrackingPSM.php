<?php 
session_start();
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleKWHTracking.php"); 
$DateNow = date("m/d/Y");
# get data tracking
$BtnDownloadLock = "";
$QListDataKWHTracking = GET_DATA_USAGE_BY_DATE($DateNow,$DateNow,"PSM",$linkHRISWebTrax);
if(mssql_num_rows($QListDataKWHTracking) == "0")
{
    $BtnDownloadLock = " disabled";
}

?>
<div class="col-sm-12"><h4 class="TitleGroup">View Data KWH Tracking</h4></div>
<div class="col-md-12">
    <div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="txtFilterTanggal1">Start Date</label>
            <div class="controls">
                <div class="input-group"><input id="txtFilterTanggal1" name="txtFilterTanggal1" type="text" class="date-picker form-control" value="<?php echo $DateNow; ?>" readonly /><label for="txtFilterTanggal1" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="txtFilterTanggal2">End Date</label>
            <div class="controls">
                <div class="input-group"><input id="txtFilterTanggal2" name="txtFilterTanggal2" type="text" class="date-picker form-control" value="<?php echo $DateNow; ?>" readonly /><label for="txtFilterTanggal2" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12"></div>
</div>
</div>
<div class="col-sm-12">
    <button type="button" id="BtnViewTable" class="btn btn-md btn-dark">View Data</button>
</div>
<div class="col-sm-12">
    <hr>
</div>
<div id="ContentResult">
<div class="col-sm-12">
    <h4>Data Result (<?php echo $DateNow; ?> - <?php echo $DateNow; ?>)</h4>
</div>
<div class="col-sm-12 text-right">
    <button type="button" id="BtnDownloadResult" class="btn btn-md btn-dark"<?php echo $BtnDownloadLock; ?>>Download</button>
</div>
<div class="col-sm-12">&nbsp;</div>
<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-responsive table-hover" id="TableDataKWHTracking">
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