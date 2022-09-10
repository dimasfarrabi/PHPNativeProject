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
?>
<div class="col-md-12">
<?php
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $Category = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $ValFilterType = htmlspecialchars(trim($_POST['FilterType']), ENT_QUOTES, "UTF-8");
    $ValDate = htmlspecialchars(trim($_POST['Date']), ENT_QUOTES, "UTF-8");
    $arrCat = explode("-",$Category);
    $Location = $arrCat[0];
    $Company = $arrCat[1];
    // echo "$Location >> $Company >> $ValFilterType >> $ValDate";
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
    }
?>
</div>
<style>
    .tableFixHead {
        overflow-y: auto;
        max-height: 450px;
      }
      .tableFixHead thead th {
        position: sticky;
        top: 0;
      }
      table {
        border-collapse: collapse;
        width: 100%;
      }
      th,
      td {
        padding: 8px 16px;
        border: 1px solid #ccc;
      }
      th {
        background: #eee;
      }
</style>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableSmallWH">
            <thead class="theadCustom">
                <tr>
                    <th width = "20">No</th>
                    <th>Date</th>
                    <th>PartNo</th>
                    <th>Part Description</th>
                    <th width = "100">Qty</th>
                    <th width = "100">UOM</th>
                    <th width = "100">#</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $nomor = 1;
            $Data = GET_SMALL_WAREHOUSE($Location,$Company,$ValDateAwal,$ValDateAkhir,$linkMACHWebTrax);
            while($Datares = sqlsrv_fetch_array($Data))
            {
               
                $PartNo = trim($Datares['PartNo']);
                $PartDesc = trim($Datares['PartDesc']);
                $Qty = trim($Datares['Qty']);
                $UOM = trim($Datares['UOM']);
                $LastModified = trim($Datares['DateCreate']);
                $Qty = number_format((float)$Qty,2,'.',',');
                $RowEnc = base64_encode($PartNo."*".$LastModified."*".$Location."*".$PartDesc);
                $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#ModalDetail" title="Update"></span>';
                ?>
                <tr>
                    <td class="text-center"><?php echo $nomor; ?></td>
                    <td class="text-center"><?php echo $LastModified; ?></td>
                    <td class="text-left"><?php echo $PartNo; ?></td>
                    <td class="text-left"><?php echo $PartDesc; ?></td>
                    <td class="text-right"><?php echo $Qty; ?></td>
                    <td class="text-left"><?php echo $UOM; ?></td>
                    <td class="text-center"><?php echo $ValOptForm; ?></td>
                </tr>
                <?php
                $nomor++;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<!-- <div class="col-md-12" id="DetailSmallWH" style="margin-top:30px">

</div> -->
<div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Material Transact Details</strong></h5><span></span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="ContentDetails"></div>
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
