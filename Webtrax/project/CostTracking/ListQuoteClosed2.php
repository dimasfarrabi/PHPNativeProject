<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValTime = htmlspecialchars(trim($_POST['ValTime']), ENT_QUOTES, "UTF-8");
    $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $ValType = htmlspecialchars(trim($_POST['ValType']), ENT_QUOTES, "UTF-8");
    $LinkOpt = $linkMACHWebTrax;
        
    ?>
<!-- <script src="project/costtracking/lib/liblistquote.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script> -->
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableList">
            <thead class="theadCustom">
                <tr>
                    <td class="text-center">Quote</td>
                </tr>
            </thead>
            <tbody><?php 
            $QListQuote = GET_LIST_QUOTE_BY_PARAM($ValTime,$ValQuoteCategory,$ValType,$LinkOpt);
            while($RListQuote = sqlsrv_fetch_array($QListQuote))
            {
                $ValQuote = $RListQuote['Quote'];
                echo '<tr class="Pointer">';
                echo '<td>'.$ValQuote.'</td>';
                echo '</tr>';
            }

            ?></tbody>
        </table>
    </div>
</div>
<script>
$(document).ready(function () {
    var BolClickListQuote = "TRUE";
    var BolClickListQuoteOpen = "TRUE";
    $(".Pointer").click(function () {
        if (BolClickListQuote == "TRUE")
        {
            var ValTitle = $("#TempLocation").text();
            $("#TableList tr").removeClass('PointerListQuoteSelected');
            $(this).closest('.Pointer').addClass("PointerListQuoteSelected");
            var QuoteName = $(this).text();
            $("#TempQuote").text(QuoteName);
            var TextTempFilter = $("#TempFilter").text();
            TextTempFilter = TextTempFilter.split("*");
            var ClosedTime = TextTempFilter[0];
            var QuoteCategory = TextTempFilter[1];
            var formdata = new FormData();
            formdata.append('ValQuoteSelected', QuoteName);
            formdata.append('ValType', ValTitle);
            formdata.append('ValClosedTime', ClosedTime);
            formdata.append('ValQuoteCategory', QuoteCategory);
            $.ajax({
                url: 'project/costtracking/listqualityreport.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                success: function (xaxa) {
                    $('#ListReport').html("");
                    $('#ListReport').hide();
                    $('#ListReport').html(xaxa);
                    $('#ListReport').fadeIn('fast');
                    $("#ContentLoading").remove();
                    $("#BtnViewQualityReport").blur();
                    $("#BtnViewQualityReport").attr('disabled', false);
                    BACK_TO_CHART_CLOSED();
                    BolClickListQuote = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#BtnViewQualityReport").blur();
                    $('#ListReport').html("");
                    $("#ContentLoading").remove();
                    $("#BtnViewQualityReport").attr('disabled', false);
                    BolClickListQuote = "TRUE";
                }
            });
        }
        else
        {
            return false;
        }

    });


});
</script>

<?php
}
else
{
    echo "";    
}
?>