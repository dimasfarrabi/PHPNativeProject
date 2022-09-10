<?php
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleShippingChart.php");
/*
if(!session_is_registered("UIDWebTrax"))
{
  ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $EncData = htmlspecialchars(trim($_POST['EncData']), ENT_QUOTES, "UTF-8");
    $Data = base64_decode($EncData);
    $arr = explode("+",$Data);
    $Country = $arr[0];
    $Year = $arr[1];
    $CountryExp = $arr[2];
    $Yesterday = "01/12/".$Year;
    $ArrMonth = array();
    for ($i = 0; $i <= 11; $i++) 
    {
    $months =array("Month" => date("Y-m", strtotime( $Yesterday." +$i months")), 
    "Month2" => date("M,Y", strtotime($Yesterday." +$i months")));
    array_push($ArrMonth,$months);
    }
    asort($ArrMonth);
    
?>
<div class="col-md-12"><h4>Destination: <strong><?php echo $Country; ?></strong>.  Year: <strong><?php echo $Year; ?></strong>.</h4></div>
<div class="col-md-4">
    <h5>Company: <strong>PSL</strong>.</h5>
    <div class="table-responsive">
        <table class="table table-responsive table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-left">Month</th>
                    <th class="text-right">Freight ($)</th>
                    <th class="text-right">Qty Shipment</th>
                    <th class="text-center">#</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $TotalFreight = 0;
                    $TotalQty = 0;
                    foreach($ArrMonth as $Bulan)
                    {
                        $ValMonth = trim($Bulan['Month']);
                        $ValMonth2 = trim($Bulan['Month2']);
                        
                        $datax = SUBJECT_FREIGHT_PER_MONTH($ValMonth,$Country,"PSL",$CountryExp,$linkMACHWebTrax);
                        while($resx=sqlsrv_fetch_array($datax))
                        {
                            $TotFreight = trim($resx['Freight']);
                            $TotQty = trim($resx['Qty']);
                            $TotalFreight = @($TotalFreight + $TotFreight);
                            $TotalQty = @($TotalQty + $TotQty);
                            if(trim($TotFreight) == ""){$TotFreight = "";} else {$TotFreight = number_format((float)$TotFreight, 2, '.', ',');}
                            if(trim($TotQty) == ""){$TotQty = "";} else {$TotQty = number_format((float)$TotQty, 2, '.', ',');}
                            $RowEncPSL = base64_encode($ValMonth.":PSL:".$Country.":".$ValMonth2.":".$CountryExp);
                            $OptPSL = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEncPSL.'" data-target="#ModalShipment" title="Detail"></span>'
            
                        ?>
                        <tr>
                            <td class="text-left"><?php echo $ValMonth2; ?></td>
                            <td class="text-right"><?php echo $TotFreight; ?></td>
                            <td class="text-right"><?php echo $TotQty; ?></td>
                            <td class="text-center"><?php echo $OptPSL; ?></td>
                        </tr>
                        <?php
                        }
                    }
                    $TotalFreight = number_format((float)$TotalFreight, 2, '.', ',');
                    $TotalQty = number_format((float)$TotalQty, 2, '.', ',');
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $TotalFreight; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalQty; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="col-md-4">
    <h5>Company: <strong>FOR</strong>.</h5>
    <div class="table-responsive">
        <table class="table table-responsive table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-left">Month</th>
                    <th class="text-right">Freight ($)</th>
                    <th class="text-right">Qty Shipment</th>
                    <th class="text-center">#</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $TotalFreight = 0;
                $TotalQty = 0;
                    foreach($ArrMonth as $Bulan)
                    {
                        $ValMonth = trim($Bulan['Month']);
                        $ValMonth2 = trim($Bulan['Month2']);
                        $datax = SUBJECT_FREIGHT_PER_MONTH($ValMonth,$Country,"FOR",$CountryExp,$linkMACHWebTrax);
                        while($resx=sqlsrv_fetch_array($datax))
                        {
                            $TotFreight = trim($resx['Freight']);
                            $TotQty = trim($resx['Qty']);
                            $TotalFreight = @($TotalFreight + $TotFreight);
                            $TotalQty = @($TotalQty + $TotQty);
                            if(trim($TotFreight) == ""){$TotFreight = "";} else {$TotFreight = number_format((float)$TotFreight, 2, '.', ',');}
                            if(trim($TotQty) == ""){$TotQty = "";} else {$TotQty = number_format((float)$TotQty, 2, '.', ',');}
                            $RowEncFOR = base64_encode($ValMonth.":FOR:".$Country.":".$ValMonth2.":".$CountryExp);
                            $OptFOR = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEncFOR.'" data-target="#ModalShipment" title="Detail"></span>'
            
                        ?>
                        <tr>
                            <td class="text-left"><?php echo $ValMonth2; ?></td>
                            <td class="text-right"><?php echo $TotFreight; ?></td>
                            <td class="text-right"><?php echo $TotQty; ?></td>
                            <td class="text-center"><?php echo $OptFOR; ?></td>
                        </tr>
                        <?php
                        }
                    }
                    $TotalFreight = number_format((float)$TotalFreight, 2, '.', ',');
                    $TotalQty = number_format((float)$TotalQty, 2, '.', ',');
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $TotalFreight; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalQty; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="col-md-4">
    <h5>Company: <strong>PSM</strong>.</h5>
    <div class="table-responsive">
        <table class="table table-responsive table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-left">Month</th>
                    <th class="text-right">Freight ($)</th>
                    <th class="text-right">Qty Shipment</th>
                    <th class="text-center">#</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $TotalFreight = 0;
                $TotalQty = 0;
                    foreach($ArrMonth as $Bulan)
                    {
                        $ValMonth = trim($Bulan['Month']);
                        $ValMonth2 = trim($Bulan['Month2']);
                        $datax = SUBJECT_FREIGHT_PER_MONTH($ValMonth,$Country,"PSM",$CountryExp,$linkMACHWebTrax);
                        while($resx=sqlsrv_fetch_array($datax))
                        {
                            $TotFreight = trim($resx['Freight']);
                            $TotQty = trim($resx['Qty']);
                            $TotalFreight = @($TotalFreight + $TotFreight);
                            $TotalQty = @($TotalQty + $TotQty);
                            if(trim($TotFreight) == ""){$TotFreight = "";} else {$TotFreight = number_format((float)$TotFreight, 2, '.', ',');}
                            if(trim($TotQty) == ""){$TotQty = "";} else {$TotQty = number_format((float)$TotQty, 2, '.', ',');}
                            $RowEncPSM = base64_encode($ValMonth.":PSM:".$Country.":".$ValMonth2.":".$CountryExp);
                            $OptPSM = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEncPSM.'" data-target="#ModalShipment" title="Detail"></span>'
            
                        ?>
                        <tr>
                            <td class="text-left"><?php echo $ValMonth2; ?></td>
                            <td class="text-right"><?php echo $TotFreight; ?></td>
                            <td class="text-right"><?php echo $TotQty; ?></td>
                            <td class="text-center"><?php echo $OptPSM; ?></td>
                        </tr>
                        <?php
                        }
                    }
                    $TotalFreight = number_format((float)$TotalFreight, 2, '.', ',');
                    $TotalQty = number_format((float)$TotalQty, 2, '.', ',');
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $TotalFreight; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalQty; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="modal fade" id="ModalShipment" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="width:50%">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Detail Shipment</strong></h5><span></span></div>
                        <div class="col-xs-6 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="ContentModal">
                    </div>
                    <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">&nbsp;Close&nbsp;</button>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>