<?php
require_once("../../ConfigDB.php");
require_once("Modules/ModuleMonitoringMachine.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
$GetMachine = GET_MACHINE_IOT_LISTED($linkMACHWebTrax);
$arr1 = array();
while($Data=sqlsrv_fetch_array($GetMachine))
{
    $NamaMesin = trim($Data['NamaMesin']);
    $KodeMesin = trim($Data['KodeMesin']);
    $DeviceID = trim($Data['IoT_DeviceID']);
    $arrTemp = array("MachineName" => $NamaMesin, "MachineCode" => $KodeMesin, "DeviceID" => $DeviceID);
    array_push($arr1,$arrTemp);
}
?>
<div class="col-md-6">
    <div class="row">
        <div class="col-md-12 text-center fw-bold"><h5>SALATIGA</h5></div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="40">No</th>
                            <th class="text-center">MACHINE</th>
                            <th class="text-center" width="40">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no=1;
                        foreach($arr1 as $ValArr1)
                        {
                            $Machine = trim($ValArr1['MachineName']);
                            $Code = trim($ValArr1['MachineCode']);
                            $ID = trim($ValArr1['DeviceID']);
                            $InsideTable = '<div class="col-md-12">Machine Name : <strong>'.$Machine.'</strong><br>Machine Code : <strong>'.$Code.'</strong>
                            <br>IoT DeviceID : <strong>'.$ID.'</strong></div>';
                            $Datax = GET_MACHINE_STATUS($ID,$linkMACHWebTrax);
                            while($ResStatus=sqlsrv_fetch_array($Datax))
                            {
                                $Status = trim($ResStatus['Status2']);
                                $Battery = trim($ResStatus['DeviceBattery']);
                            }
                            $InsideTable2 = '<div class="col-md-12"><h6>Machine Status : <strong>['.$Status.']</strong></h6><br></div><i>Device Battery : '.$Battery.'%</i>';
                            if($Status == 'ON')
                            {
                                $BackGround = ' class="alert alert-success"';
                            }
                            else
                            {
                                $BackGround = '';
                            }
                            $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-target="#ModalDetail" title="Detail"></span>';
                        ?>
                        <tr <?php echo $BackGround; ?> >
                            <td class="text-center"><?php echo $no ;?></td>
                            <td>
                                <div>
                                    <?php echo $InsideTable; ?>
                                    <?php echo $InsideTable2; ?>
                                </div>
                            </td>
                            <td class="text-center"><?php echo $ValOptForm; ?></td>
                        </tr>
                        <?php
                        $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Details</h5>
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