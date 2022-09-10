<?php
require_once("../../ConfigDB.php");
require_once("Modules/ModuleNewBCPartJob.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValQuote = htmlspecialchars(trim($_POST['ValQuote']), ENT_QUOTES, "UTF-8");
    $DataCode = htmlspecialchars(trim($_POST['DataCode']), ENT_QUOTES, "UTF-8");
?>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="" style="margin-top:30px">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">WorkOrder</th>
                    <th class="text-center" width="30">#</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $Data = GET_WO_LIST($ValQuote,$linkMACHWebTrax);
            while($Datares = sqlsrv_fetch_array($Data))
            {
                $ValWO = trim($Datares['WO']);
                $CheckBox = '<input  id="ad_Checkbox1" class="ads_Checkbox" type="checkbox" value="'.$ValWO.'" checked/>';
            ?>
                <tr>
                    <td class="text-right"><?php echo $ValWO; ?></td>
                    <td class="text-center"><?php echo $CheckBox; ?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-12">
    <button id="ButtonSave" type="button" class="btn btn-success btn-labeled block" style="width: 100%;" name="ButtonSave">Save</button>
</div>
<div class="col-md-12" id="saving"></div>
<?php
}
?>