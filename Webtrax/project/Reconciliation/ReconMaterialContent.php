<?php

require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");

function getStartAndEndDate($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $Start = $dto->format('m/d/Y');
    $dto->modify('+6 days');
    $End = $dto->format('m/d/Y');
    return $Start."#".$End;
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $Category = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $ValFilterType = htmlspecialchars(trim($_POST['FilterType']), ENT_QUOTES, "UTF-8");
    $ValDate = htmlspecialchars(trim($_POST['Date']), ENT_QUOTES, "UTF-8");
    $ValHalf = htmlspecialchars(trim($_POST['ClosedTime']), ENT_QUOTES, "UTF-8");

    switch ($ValFilterType) {
        case 'Daily':
            {
                $ValDateAwal = $ValDate;
                $ValDateAkhir = $ValDate;
                ?>
                <div><h5><strong>Material Tracking  (<?php echo $ValDate;?>) : <?php echo $Category;?></strong></h5></div>
                <br>
                <?php
            }
        break;
        case 'Weekly':
            {
                $ArrWeek = explode("/",$ValDate);
                $Day = $ArrWeek[0];
                $Month = $ArrWeek[1];
                $Year = $ArrWeek[2];

                $date = new DateTime($ValDate);
                $week = $date->format("W");
                $week_array = getStartAndEndDate($week,$Year);
                $ArrRangeWeek2 = explode("#",$week_array);
                $ValDateAwal = $ArrRangeWeek2[0];
                $ValDateAkhir = $ArrRangeWeek2[1];
                ?>
                <div><h5><strong>Material Tracking  (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?> )
                <br> Week <?php echo $week;?> : <?php echo $Category;?></strong></h5></div>
                <br>
                <?php

            }
        break;
        case 'Monthly':
            {
                $ArrMonth = explode("/",$ValDate);
                $Month = $ArrMonth[0];
                $Year = $ArrMonth[2];
                $ValDateAwal = $Month ."/01/". $Year;
                $ValDateAkhir = date("m/t/Y", strtotime($ValDateAwal));
                ?>
                <div><h5><strong>Material Tracking  (<?php echo $ValDateAwal;?> - <?php echo $ValDateAkhir;?> ) : <?php echo $Category;?></strong></h5></div>
                <br>
                <?php

            }
        break;
        case 'Half':
            {
                $ArrHalf = explode("-",$ValHalf);
                $Year = $ArrHalf[0];
                $Half = $ArrHalf[1];
                
                if($Half == "H1"){ $ValDateAwal="01/01/".$Year; $ValDateAkhir="06/30/".$Year;}
                elseif($Half == "H2"){ $ValDateAwal="07/01/".$Year; $ValDateAkhir="12/31/".$Year;}
                ?>
                <div><h5><strong>Material Tracking  (<?php echo $ValHalf;?>) : <?php echo $Category;?></strong></h5></div>
                <br>
                <?php

            }
        break;
    }
    /*
    $QList = GET_DATA_MATERIAL($ValDateAwal,$ValDateAkhir,$Category,$linkMACHWebTrax);
    $array1 = array();

    while($RList = sqlsrv_fetch_array($QList))
    {
        $PartNumber = trim($RList['PartNo']);
        $PartDesc = trim($RList['PartDesc']);
        $TigaQty = trim($RList['TigaQty']);
        $Qty = trim($RList['Qty']);
        $UOM = trim($RList['UOM']);
        $Category = trim($RList['CategoryUsage']);
        $arrayProd = array(
            "PartNo" => $PartNumber,
            "PartDesc" => $PartDesc,
            "TigaQty" => $TigaQty,
            "Qty" => $Qty,
            "UOM" => $UOM,
            "Category" => $Category
        );
        array_push($array1,$arrayProd);
    }
?>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableMaterial">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center trowCustom" width = "20">No</th>
                        <th class="text-center trowCustom" width = "70">Part Number</th>
                        <th class="text-center trowCustom">Part Description</th>
                        <th class="text-center trowCustom">Category</th>
                        <th class="text-center trowCustom">Qty Tiga Warehouse</th>
                        <!-- <th class="text-center trowCustom">Qty In Material Tracking</th>
                        <th class="text-center trowCustom">Qty Out Without Stock</th>
                        <th class="text-center trowCustom">Qty Out Small Warehouse</th> -->
                        <th class="text-center trowCustom">Qty Material Tracking</th>
                        <th class="text-center trowCustom">Difference</th>
                        <th class="text-center trowCustom" width = "20">UOM</th>
                        <th class="text-center trowCustom" width = "25">#</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                
                $No=1;
                foreach ($array1 as $entry) {
                    
                    $ValPartNo = trim($entry['PartNo']);
                    $ValPartDesc = trim($entry['PartDesc']);
                    $ValTigaQty = trim($entry['TigaQty']);
                    $ValQty = trim($entry['Qty']);
                    $ValCategory = trim($entry['Category']);
                    $ValUOM = trim($entry['UOM']);
                        $Diff = @($ValTigaQty - $ValQty);
                        $ValDiff = abs($Diff);
                        $ValTigaQty = number_format((float)$ValTigaQty,2,'.',',');
                        $ValQty = number_format((float)$ValQty,2,'.',',');
                        $ValDiff = number_format((float)$ValDiff,2,'.',',');
                        
                        $RowEnc = base64_encode($ValPartNo."*".$ValDateAwal."*".$ValDateAkhir."*".$ValPartDesc);
                        $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#MaterialDetail" title="Detail"></span>'
                    
                ?>
                    <tr class="RowRecon" data-cookies="<?php echo $RowEnc; ?>">
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-center"><?php echo $ValPartNo; ?></td>
                        <td class="text-left"><?php echo $ValPartDesc; ?></td>
                        <td class="text-center"><?php echo $ValCategory; ?></td>
                        <td class="text-center"><?php echo $ValTigaQty; ?></td>
                        <td class="text-center"><?php echo $ValQty; ?></td>
                        <!-- <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td> -->
                        <td class="text-center"><?php echo $ValDiff; ?></td>
                        <td class="text-center"><?php echo $ValUOM; ?></td>
                        <td class="text-center"><?php echo $ValOptForm; ?></td>
                    </tr>

                <?php
                $No++;
                    
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    */
    ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableMaterial">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center trowCustom" width = "20">No</th>
                        <th class="text-center trowCustom" width = "40">Part Number</th>
                        <th class="text-center trowCustom">Part Description</th>
                        <th class="text-center trowCustom">Category</th>
                        <th class="text-center trowCustom">Qty Out<br>Tiga Warehouse</th>
                        <th class="text-center trowCustom">Qty In<br>Material Tracking</th>
                        <th class="text-center trowCustom">Qty Out<br>Without Stock</th>
                        <th class="text-center trowCustom">Qty Out<br>Small Warehouse</th>
                        <th class="text-center trowCustom">Stock</th>
                        <th class="text-center trowCustom" width = "20">UOM</th>
                        <th class="text-center trowCustom">Warehouse Location</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $nomor = 1;
                $Data = GET_DATA_SMALL_WAREHOUSE($ValDateAwal,$ValDateAkhir,$Category,$linkMACHWebTrax);
                while($Datares = sqlsrv_fetch_array($Data))
                {
                    $PartNo = trim($Datares['PartNo']);
                    $PartDesc = trim($Datares['PartDesc']);
                    $Category = trim($Datares['Category']);
                    $Qty = trim($Datares['QtyOut']);
                    $QtyReceived = trim($Datares['QtyReceived']);
                    $OutFromSmallWarehouse = trim($Datares['OutFromSmallWarehouse']);
                    $OutTanpaStock = trim($Datares['OutTanpaStock']);
                    $UOM = trim($Datares['UOM']);
                    $SmallWareHouse = trim($Datares['SmallWarehouse']);
                    $Val1 = @($OutFromSmallWarehouse + $OutTanpaStock);
                    $Stock = @($QtyReceived - $Val1);
                    $Qty = number_format((float)$Qty,2,'.',',');
                    $QtyReceived = number_format((float)$QtyReceived,2,'.',',');
                    $OutFromSmallWarehouse = number_format((float)$OutFromSmallWarehouse,2,'.',',');
                    $OutTanpaStock = number_format((float)$OutTanpaStock,2,'.',',');
                    $Stock = number_format((float)$Stock,2,'.',',');
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $nomor; ?></td>
                        <td class="text-left"><?php echo $PartNo; ?></td>
                        <td class="text-left"><?php echo $PartDesc; ?></td>
                        <td class="text-center"><?php echo $Category; ?></td>
                        <td class="text-right"><?php echo $Qty; ?></td>
                        <td class="text-right"><?php echo $QtyReceived; ?></td>
                        <td class="text-right"><?php echo $OutTanpaStock; ?></td>
                        <td class="text-right"><?php echo $OutFromSmallWarehouse; ?></td>
                        <td class="text-right"><?php echo $Stock; ?></td>
                        <td class="text-center"><?php echo $UOM; ?></td>
                        <td class="text-left"><?php echo $SmallWareHouse; ?></td>
                    </tr>
                    <?php
                    $nomor++;
                }
                ?>
                </tbody>
            </table>
        </div>
    <div class="modal fade" id="MaterialDetail" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="width:90%">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Material Tracking Details</strong></h5><span></span></div>
                        <div class="col-xs-6 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row" id="ContentDetails">
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
<script>
$(document).ready(function () {

    $("#TableMaterial").dataTable({
		"bInfo": true
	});
    // $("#TableSmallWarehouse").dataTable({
	// 	"bInfo": true
	// });
});
</script>
<script>
$(document).ready(function () {
    $("#MaterialDetail").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/reconciliation/ModalDetailMaterial.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#ContentDetails').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#ContentDetails').hide();
                $('#ContentDetails').html(xaxa);
                $('#ContentDetails').fadeIn('fast');
                
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
});
</script>