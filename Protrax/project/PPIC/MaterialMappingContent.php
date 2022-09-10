<?php
session_start();
require_once("../../ConfigDB.php");
// require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleNewBCPartJob.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValMachine = htmlspecialchars(trim($_POST['ValMachine']), ENT_QUOTES, "UTF-8");
    $MachineCode = htmlspecialchars(trim($_POST['MachineCode']), ENT_QUOTES, "UTF-8");
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValLocation = base64_decode(base64_decode($ValLocation));
    // echo "$ValMachine >> $MachineCode >> $ValLocation";
    if($ValLocation == 'PSL'){$Kota = "SALATIGA";}
    else {$Kota = "SEMARANG";}
    $arrayWO = array();
    $Data = GET_WO_LIST_BY_MACHINE($ValMachine,$ValLocation,"NULL",$linkMACHWebTrax);
    while($Datares=sqlsrv_fetch_array($Data))
    {
        $WO = trim($Datares['WO']);
        $Status = trim($Datares['Status']);
        $ArrayTemp = array("WorkOrder" => $WO, "LockStatus" => $Status);
        array_push($arrayWO,$ArrayTemp);
    }
    $Data = GET_WO_LIST_BY_MACHINE($ValMachine,$ValLocation,$MachineCode,$linkMACHWebTrax);
    while($Datares=sqlsrv_fetch_array($Data))
    {
        $WO = trim($Datares['WO']);
        $Status = trim($Datares['Status']);
        $ArrayTemp = array("WorkOrder" => $WO, "LockStatus" => $Status);
        array_push($arrayWO,$ArrayTemp);
    }
?>
<style>
    .cards {
    padding: 20px;
    background:#FFFFFf;
    width: 100%; 
    margin-bottom: 30px; 
    box-shadow: 0px 1px 3px #808080
    }
</style>
<div class="col-md-12">
    <div class = "cards">
        Machine Usage : <strong><?php echo $ValMachine; ?></strong>  .Location : <strong><?php echo $Kota; ?></strong>.
    </div>
</div>
<div class="col-md-12">
    <div><h6><strong>Ready To Process WO List</strong>*</h6></div>
    <div class="table-responsive"><i style="font-size:12px;">*)Based On Machine Tracking History</i>
        <table class="table table-bordered table-hover" id="TableDiRak">
            <thead class="theadCustom">
                <tr>
                    <th>Work Order</th>
                    <th>Mapping Status</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach($arrayWO as $arrayRes)
            {
                $ValWO = trim($arrayRes['WorkOrder']);
                $LockStatus = trim($arrayRes['LockStatus']);
                $RowEnc = base64_encode($ValWO."*".$ValMachine."*".$MachineCode."*".$ValLocation);
                // $ValOptForm = '<button class="btn btn-sm btn-warning" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-bs-target="#MappingForm">Manage</button>';
                // if($LockStatus == 'Unlocked')
                // {
                $ValOptForm = '<span class="PointerList UpdateRow" aria-hidden="true" data-bs-toggle="modal" data-ecode="'.$RowEnc.'" data-bs-target="#MappingForm" title="Update"><button class="btn btn-sm btn-warning">Manage</button></span>';
                // }
                // else
                // {
                // $ValOptForm = '<span class="PointerList UpdateRow" aria-hidden="true" data-bs-toggle="modal" data-ecode="'.$RowEnc.'" title="Update"><button class="btn btn-sm btn-info" disabled>Updated</button></span>';
                // }
            ?>
            <tr>
                <td class="text-left"><?php echo $ValWO; ?></td>
                <td class="text-left"><?php echo $LockStatus; ?></td>
                <td class="text-center"><?php echo $ValOptForm; ?></td>
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-12" style="margin-top:30px">
<?php
$RowEnc2 = ($ValMachine."*".$MachineCode."*".$ValLocation);
?>
<button type="button" class="btn btn-info btn-labeled block" style="width: 100%;" data-bs-target="#AddMapping" data-bs-toggle="modal" data-ecode="<?php echo $RowEnc2 ;?>">Add New Mapping</button>
</div>

<div class="modal fade" id="MappingForm" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div id="FormContent"></div>
                <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="AddMapping" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div id="FormAddMapping"></div>
				<div id="WOList"></div>
                <div class="text-center"><img src="../images/ajax-loader1.gif" id="loading" class="load_img" style="height:10px;"/></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
