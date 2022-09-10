<?php
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePointAchievement.php");
date_default_timezone_set("Asia/Jakarta");
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
    $Name = htmlspecialchars(trim($_POST['Name']), ENT_QUOTES, "UTF-8");
    $Role = htmlspecialchars(trim($_POST['Role']), ENT_QUOTES, "UTF-8");
    $ClosedTime = htmlspecialchars(trim($_POST['ClosedTime']), ENT_QUOTES, "UTF-8");

    // echo "$Name >> $Role >> $ClosedTime";
    $DataRes = GET_COST_DETAIL_LEADER($Name, $Role, $ClosedTime, $linkMACHWebTrax);
?>
<style>
    .Target{color:#ff0000;}
    .Actual{color:#0008ff;}
</style>
<br></br>
<div><h5><strong>Cost Total System (Without OTS)</strong></h5></div></div>
<i>***) Total cost target - Total Cost actual</i>
<div class="table-responsive">
        <table class="table table-responsive table-hover" id="TabelCost">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center" width="20">No</th>
                    <th class="text-center">Quote</th>
                    <?php if($Role == 'DM'){
                        ?>
                        <th class="text-center">Expense</th>
                    <?php
                    } else {}
                    ?>
                    <th class="text-center">Total Actual Labor<br>Cost ($)</th>
                    <th class="text-center">Total Actual Machine<br>Cost ($)</th>
                    <th class="text-center">Total Actual Material<br>Cost ($)</th>
                    <th class="text-center">Total Target Cost ($)</th>
                    <th class="text-center">Total Actual Cost ($)</th>
                    <th class="text-center">Total Saving ($)***</th>
                    <th class="text-center">#</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $No = 1;
            $TotalTCAQ = $TotalACAQ = $TotalSaving = 0;
            while($Data = sqlsrv_fetch_array($DataRes))
            {
                $ValQuote = trim($Data['Quote']);
                $ValLabor = trim($Data['PeopleCost']);
                $ValMach = trim($Data['MachineCost']);
                $ValMaterial = trim($Data['MaterialCost']);
                $TotalCost = trim($Data['TotalActualCost']);
                $TotalTargetCost = trim($Data['TotalTargetCost']);
                if($Role == 'DM'){ $Expense = trim($Data['ExpenseAllocation']); $span = 6;} else {$Expense = ""; $span = 5;}
                $Saving = @($TotalTargetCost - $TotalCost);
                $TotalTCAQ = $TotalTCAQ + $TotalTargetCost;
                $TotalACAQ = $TotalACAQ + $TotalCost;
                $TotalSaving = $TotalSaving + $Saving;
                // $Qty = trim($Data['Qty']);
                $ValLabor = number_format((float)$ValLabor,2,'.',',');
                $ValMach = number_format((float)$ValMach,2,'.',',');
                $ValMaterial = number_format((float)$ValMaterial,2,'.',',');
                $TotalTargetCost = number_format((float)$TotalTargetCost,2,'.',',');
                $TotalCost = number_format((float)$TotalCost,2,'.',',');
                $Saving = number_format((float)$Saving,2,'.',',');
                // $Qty = number_format((float)$Qty,2,'.',',');
                if($Saving < 0){ $class = 'class="text-right Actual"'; }
                else { $class = 'class="text-right Target"'; }
                $RowEnc = base64_encode($ValQuote."*".$ClosedTime."*".$Role."*".$Name."*".$Expense);
                $ValOptForm = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$RowEnc.'" data-target="#ModalCostDetail" title="Details"></span>'
                
            ?>
            <!-- <tr> -->
            <tr class="RowCost" data-cookies="<?php echo $RowEnc; ?>">
                <td class="text-center"><?php echo $No; ?></td>
                <td class="text-left"><?php echo $ValQuote; ?></td>
                <?php if($Role == 'DM'){
                        ?>
                        <td class="text-left"><?php echo $Expense; ?></td>
                    <?php
                    } else {}
                ?>
                <td class="text-right"><?php echo $ValLabor; ?></td>
                <td class="text-right"><?php echo $ValMach; ?></td>
                <td class="text-right"><?php echo $ValMaterial; ?></td>
                <td class="text-right"><?php echo $TotalTargetCost; ?></td>
                <td class="text-right"><?php echo $TotalCost; ?></td>
                <td class="text-right"><strong><?php echo $Saving; ?></strong></td>
                <td class="text-center"><?php echo $ValOptForm; ?></td>
            </tr>
            <?php
            $No++;
            }
            $TotalTCAQ = number_format((float)$TotalTCAQ,2,'.',',');
            $TotalACAQ = number_format((float)$TotalACAQ,2,'.',',');
            $TotalSaving = number_format((float)$TotalSaving,2,'.',',');

            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="<?php echo $span; ?>"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $TotalTCAQ; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalACAQ; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalSaving; ?></strong></td>
                    <td class="text-right"><strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="modal fade" id="ModalCostDetail" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width:70%">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>System Cost Detail (Without OTS)</strong></h5><span></span></div>
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
else {}
?>
<script>
$(document).ready(function () {
    $("#TabelCost").removeAttr('width').DataTable({
	});
    $("#ModalCostDetail").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/PointAchievement/ModalCostDetail.php',
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
    $(".status")
        .filter(function () {
        return $(this).html() < 0;
    })
        .parent().css('background-color', 'red');
    
});
</script>