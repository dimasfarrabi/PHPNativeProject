<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleRecon.php");
date_default_timezone_set("Asia/Jakarta");

$lastWeek = date('m/d/Y',strtotime("-7 days"));
$yesterday = date('m/d/Y',strtotime("-1 days"));
$thisMonth = date('m/Y',strtotime("-1 days"));


if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $DataName = htmlspecialchars(trim($_POST['DataName']), ENT_QUOTES, "UTF-8");

    if($DataName != 'Employee')
    {

?>
<style>.cards {padding: 10px; box-shadow: 0px 1px 3px #888888;background:#FFFFFF;width: 100%;}</style>
<div class="col-md-12 cards">
    <br>
    <div><h5><strong><?php echo $DataName;?></strong></h5></div>
    <br>
    
        <div class="col-md-4">
            <label for="InputQuoteCategory">Choose Filter</label>
                <form id="RadioFilter">
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Daily"><strong>Daily</strong></label>
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Weekly"><strong>Weekly</strong></label>
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Monthly"><strong>Monthly</strong></label>
                    <label class="radio-inline">
                    <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter ClosedTime" value="ClosedTime"><strong>Half</strong></label>
                </form>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="DatePick">Choose Date</label>
                <div class="controls">
                    <div class="input-group"><input id="DatePick" name="txtFilterTanggal" type="text" class="date-picker form-control" value="<?php echo $yesterday;?>">
                    <label for="DatePick" class="input-group-addon btn"><span class="date-picker glyphicon glyphicon-calendar"></span></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                    <label for="InputClosedTime">Season</label>
                    <select class="form-control" id="ClosedTime" disabled="disabled">
                    <?php 
                    $QListClosedTime = GET_LIST_CLOSEDTIME_NOT_OPEN($linkMACHWebTrax);
                    while($RListClosedTime = sqlsrv_fetch_array($QListClosedTime))
                    {
                        $ClosedTime = $RListClosedTime['ClosedTime'];
                        ?>
                        <option><?php echo $ClosedTime; ?></option>
                        <?php
                    }                
                    ?></select>
            </div>
        </div>
        <div class="col-md-2">
            <label class="InvisibleText">Choose Date</label>
            <div>
            <button type="button" class="btn btn-dark btn-labeled" id="SearchBtn" onclick="Search()">Search</button>
            </div>
        </div>
</div> 

<?php
    }
    else
    { 
        ?>
        <script> Search();</script>
        <?php
    }
}
?>
<script type="text/javascript">
   $("input[name='RadioFilter']").click(function() { 
      $("#ClosedTime").prop("disabled",true);
      if($(this).hasClass('ClosedTime')) {
      $("#ClosedTime").prop("disabled",false);
      }
   });
    $(document).ready(function() {
        
        $('#DatePick').datepicker({
            format: "mm/dd/yyyy",
            autoclose: "true"
        });
    });
    function Search() {
        $('#SearchBtn').attr('disabled', true);
        $('#load_img').show();
        var Fil1 = '<?php echo $DataName; ?>';
        var Fil2 = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var Fil3 = $('#DatePick').val();
        var Fil4 = $("#ClosedTime").children("option:selected").val();
        var formdata = new FormData();
        formdata.append("ReportType", Fil1);
        formdata.append("FilterType", Fil2);
        formdata.append("Date", Fil3);
        formdata.append("ClosedTime", Fil4);
        $.ajax({
            url: 'project/reconciliation/SyncReconContentV2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#Content').html("");
                $("#Content").before('<div class="col-sm-12" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#Content').html("");
            },
            success: function(xaxa){
                $('#load_img').hide();
                $('#Content').hide();
                $('#Content').html(xaxa);
                $('#Content').fadeIn('fast');
                $('#SearchBtn').attr('disabled', false);
                $("#ContentLoadingTT").remove();
            },
            error: function() {
                alert('Request cannot proceed!');
            }
        });
    }
</script>
