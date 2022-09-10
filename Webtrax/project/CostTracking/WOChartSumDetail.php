<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTrackingChart.php");
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
    $ValCategory = htmlspecialchars(trim($_POST['ValCategory']), ENT_QUOTES, "UTF-8");
    $ValYear = htmlspecialchars(trim($_POST['ValYear']), ENT_QUOTES, "UTF-8");
    // echo "$ValCategory >> $ValYear";
?>
<style>
    .tableFixHead {
        overflow-y: auto;
        max-height: 500px;
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
<h4>Year : <strong><?php echo $ValYear; ?></strong></h4>
<div class="table-responsive tableFixHead">
    <table class="table table-bordered table-hover" id="TableProject">
        <thead class="theadCustom">
            <tr>
                <th>Project</th>
                <th>Total Actual Expense Cost ($)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $TotalExpenseCost = 0;
            $data = GET_DETAIL_CHART_BY_YEAR($ValYear,$ValCategory,$linkMACHWebTrax);
            while($res=sqlsrv_fetch_array($data))
            {
                $ValProject = trim($res['Project']);
                $ValCost = trim($res['Total']);
                $TotalExpenseCost = $TotalExpenseCost + $ValCost;
                $ValCost = number_format((float)$ValCost, 2, '.', ',');
                $enc = $ValProject."*".$ValYear."*".$ValCategory;
                // $opt = '<span class="glyphicon glyphicon-new-window PointerList UpdateRow" aria-hidden="true" data-toggle="modal" data-ecode="'.$enc.'" data-target="#ModalDetail" title="Detail"></span>';
                ?>
                <tr class="DataParent" data-float="<?php echo $enc; ?>">
                    <td class="text-left"><?php echo $ValProject; ?></td>
                    <td class="text-right"><?php echo $ValCost; ?></td>
                </tr>
                <?php
            }
            $TotalExpenseCost = number_format((float)$TotalExpenseCost, 2, '.', ',');
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="text-center"><strong>TOTAL</strong></td>
                <td class="text-right"><strong><?php echo $TotalExpenseCost; ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Total Expense Details</strong></h5><span></span></div>
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