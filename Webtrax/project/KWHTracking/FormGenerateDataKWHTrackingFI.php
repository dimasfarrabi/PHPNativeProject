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

?>
<style>.InfoGenerate{font-weight:bold;color:#ff0000;text-decoration: underline;}</style>
<div class="col-sm-12"><h4 class="TitleGroup">Generate Data KWH Tracking</h4></div>
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
    <button type="button" id="BtnGenerate" class="btn btn-md btn-dark">Generate Data</button>
</div>
<div class="col-sm-12">
    <hr>
</div>
<div class="col-sm-12"><span><i class="InfoGenerate">Instruction</i></span> :<br><span>After run import process, please use generate tools above to generate the usage of KWH Tracking, make sure the period are same with the imported data</span></div>
<div id="ContentResult"></div>