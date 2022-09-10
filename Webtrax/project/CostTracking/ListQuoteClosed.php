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
<?php /* <script src="project/costtracking/lib/liblistquote.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script> */ ?>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableListQuote">
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
                echo '<tr class="PointerListQuote">';
                echo '<td>'.$ValQuote.'</td>';
                echo '</tr>';
            }

            ?></tbody>
        </table>
    </div>
</div>
    <?php
}
else
{
    echo "";    
}
?>
<script>
    $(document).ready(function () {
    var BolClickListQuote = "TRUE";
    var BolClickListQuoteOpen = "TRUE";
    $(".PointerListQuote").click(function () {
        if (BolClickListQuote == "TRUE")
        {
            var ValTitle = $("#TempLocation").text();
            $("#TableListQuote tr").removeClass('PointerListQuoteSelected');
            $(this).closest('.PointerListQuote').addClass("PointerListQuoteSelected");
            var QuoteName = $(this).text();
            $("#TempQuote").text(QuoteName);
            var TextTempFilter = $("#TempFilter").text();
            TextTempFilter = TextTempFilter.split("*");
            var ClosedTime = TextTempFilter[0];
            var QuoteCategory = TextTempFilter[1];
            if(QuoteCategory == "Unquote")
            {
                VIEW_UNQUOTE_REPORT(QuoteName,ValTitle,ClosedTime,QuoteCategory);
            }
            else
            {
                var formdata = new FormData();
                formdata.append('ValQuoteSelected', QuoteName);
                formdata.append('ValType', ValTitle);
                formdata.append('ValClosedTime', ClosedTime);
                formdata.append('ValQuoteCategory', QuoteCategory);
                $.ajax({
                    url: 'project/costtracking/listreportclosed.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        BolClickListQuote = "FALSE";
                        $("html, body").animate({ scrollTop: $("#ListReport").offset().top }, "slow");
                        $("#BtnViewWOClosedPSL").attr('disabled', true);
                        $('#ListReport').html("");
                        $("#ContentLoading").remove();
                        $("#ListReport").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#ListReport').html("");
                        $('#ListOTSTop').html("");
                    },
                    success: function (xaxa) {
                        $('#ListReport').html("");
                        $('#ListReport').hide();
                        $('#ListReport').html(xaxa);
                        $('#ListReport').fadeIn('fast');
                        $("#ContentLoading").remove();
                        $("#BtnViewWOClosedPSL").blur();
                        $("#BtnViewWOClosedPSL").attr('disabled', false);
                        BACK_TO_CHART_CLOSED();
                        BolClickListQuote = "TRUE";
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $("#BtnViewWOClosedPSL").blur();
                        $('#ListReport').html("");
                        $("#ContentLoading").remove();
                        $("#BtnViewWOClosedPSL").attr('disabled', false);
                        BolClickListQuote = "TRUE";
                    }
                });
            }
        }
        else
        {
            return false;
        }
    });
    $(".PointerListQuoteOpen").click(function () {
        if (BolClickListQuoteOpen == "TRUE") {
            var ValTitle = $("#TempLocation").text();
            $("#TableListQuote tr").removeClass('PointerListQuoteOpenSelected');
            $(this).closest('.PointerListQuoteOpen').addClass("PointerListQuoteOpenSelected");
            var QuoteCategory = $("#TempFilter").text();
            var QuoteName = $(this).text();
            $("#TempQuote").text(QuoteName);
            var formdata = new FormData();
            formdata.append('ValQuoteSelected', QuoteName);
            formdata.append('ValType', ValTitle);
            formdata.append('ValQuoteCategory', QuoteCategory);
            $.ajax({
                url: 'project/costtracking/listreportopen.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolClickListQuoteOpen = "FALSE";
                    $("html, body").animate({ scrollTop: $("#ListReportOpen").offset().top }, "slow");
                    $("#BtnViewWOOpenPSM").attr('disabled', true);
                    $('#ListReportOpen').html("");
                    $("#ContentLoading").remove();
                    $("#ListReportOpen").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ListReportOpen').html("");
                    $('#ListOTSTop').html("");
                    $('#RunningWOP').hide();
                    $('#WOPCostDetail').hide();
                },
                success: function (xaxa) {
                    $('#ListReportOpen').html("");
                    $('#ListReportOpen').hide();
                    $('#ListReportOpen').html(xaxa);
                    $('#ListReportOpen').fadeIn('fast');
                    $("#ContentLoading").remove();
                    $("#BtnViewWOOpenPSM").blur();
                    $("#BtnViewWOOpenPSM").attr('disabled', false);
                    BACK_TO_CHART_OPEN();
                    BolClickListQuoteOpen = "TRUE";
                    SHOW_RUNNING_WOP(QuoteName);
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#BtnViewWOOpenPSM").blur();
                    $('#ListReportOpen').html("");
                    $("#ContentLoading").remove();
                    $("#BtnViewWOOpenPSM").attr('disabled', false);
                    BolClickListQuoteOpen = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
});
function SHOW_RUNNING_WOP(Quote)
{
    var ValQuote = Quote;
    var formdata = new FormData();
    formdata.append('ValQuote', ValQuote);
    $.ajax({
            url: 'project/CostTracking/RunningWOP.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
            $('#RunningWOP').html("");
            $('#WOPCostDetail').hide();
            },
            success: function (xaxa) {
            $('#RunningWOP').html("");
            $('#RunningWOP').hide();
            $('#RunningWOP').html(xaxa);
            $('#RunningWOP').fadeIn('fast');
            LIST_WOP();
            },
            error: function () {
            alert("Request cannot proceed!");
            $('#RunningWOP').html("");
            }
        });
}
function LIST_WOP()
{
    var BoolClick = "TRUE";
    $(".WOPList").click(function () {
        if (BoolClick == "TRUE") {
            $("#TableOpenWOP tr").removeClass('PointerListSelected');
            $(this).closest('.WOPList').addClass("PointerListSelected");
            var FloatData = $(this).data('float');
            var formdata = new FormData();
            formdata.append("ValFloat", FloatData);          
            $.ajax({
                url: 'project/CostTracking/RunningWOPDetail.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BoolClick = "FALSE";
                    $('#WOPCostDetail').html("");
                    $("#ContentLoading").remove();
                    $("#WOPCostDetail").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#WOPCostDetail').html("");
                },
                success: function (xaxa) {
                    $('#WOPCostDetail').html("");
                    $('#WOPCostDetail').hide();
                    $('#WOPCostDetail').html(xaxa);
                    $('#WOPCostDetail').fadeIn('fast');
                    $("#ContentLoading").remove();
                    BoolClick = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#StageDetail').html("");
                    $("#ContentLoading").remove();
                    BoolClick = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
}
function BACK_TO_CHART_CLOSED() {
    $(".InfoChart").click(function () {
        var DataFilterSelected = $("#TempFilter").text();
        $("#TableListQuote tr").removeClass('PointerListQuoteSelected');
        SHOW_PIE_CHART_CLOSED();
        $('#ListOTSTop').html("");
    });
}
function SHOW_PIE_CHART_CLOSED() {
    var DataFilterSelected = $("#TempFilter").text();
    var formdata = new FormData();
    formdata.append('ValFilter', DataFilterSelected);
    $.ajax({
        url: 'project/costtracking/chartwoclosed.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("html, body").animate({ scrollTop: $("#InputClosedTime").offset().top - 1}, "slow");
            $('#ListReport').html("");
            $("#ListReport").before('<div class="col-sm-12" id="ContentLoadingChart"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ListReport').html("");
        },
        success: function (xaxa) {
            $('#ListReport').html("");
            $('#ListReport').hide();
            $('#ListReport').html(xaxa);
            $('#ListReport').fadeIn('fast');
            $("#ContentLoadingChart").remove();
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#ListReport').html("");
            $("#ContentLoadingChart").remove();
        }
    });
}

function BACK_TO_CHART_OPEN() {
    $(".InfoChart").click(function () {
        var DataFilterSelected = $("#TempFilter").text();
        $("#TableListQuote tr").removeClass('PointerListQuoteOpenSelected');
        SHOW_PIE_CHART_OPEN();
        $('#ListOTSTop').html("");
    });
}
function SHOW_PIE_CHART_OPEN() {
    var DataFilterSelected = $("#TempFilter").text();
    var formdata = new FormData();
    formdata.append('ValFilter', DataFilterSelected);
    $.ajax({
        url: 'project/costtracking/chartwoopen.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("html, body").animate({ scrollTop: $("#InputQuoteCategory").offset().top - 1 }, "slow");
            $('#ListReportOpen').html("");
            $("#ListReportOpen").before('<div class="col-sm-12" id="ContentLoadingChart"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ListReportOpen').html("");
            $('#RunningWOP').hide();
            $('#WOPCostDetail').hide();
        },
        success: function (xaxa) {
            $('#ListReportOpen').html("");
            $('#ListReportOpen').hide();
            $('#ListReportOpen').html(xaxa);
            $('#ListReportOpen').fadeIn('fast');
            $("#ContentLoadingChart").remove();
            
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#ListReportOpen').html("");
            $("#ContentLoadingChart").remove();
        }
    });
}
function VIEW_UNQUOTE_REPORT(Quote,Title,Half,Category)
{
    var formdata = new FormData();
    formdata.append('ValQuoteSelected', Quote);
    formdata.append('ValType', Title);
    formdata.append('ValClosedTime', Half);
    formdata.append('ValQuoteCategory', Category);
    $.ajax({
        url: 'project/costtracking/UnquoteReportClosed.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            BolClickListQuote = "FALSE";
            $("html, body").animate({ scrollTop: $("#ListReport").offset().top }, "slow");
            $("#BtnViewWOClosedPSL").attr('disabled', true);
            $('#ListReport').html("");
            $("#ContentLoading").remove();
            $("#ListReport").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ListReport').html("");
            $('#ListOTSTop').html("");
        },
        success: function (xaxa) {
            $('#ListReport').html("");
            $('#ListReport').hide();
            $('#ListReport').html(xaxa);
            $('#ListReport').fadeIn('fast');
            $("#ContentLoading").remove();
            $("#BtnViewWOClosedPSL").blur();
            $("#BtnViewWOClosedPSL").attr('disabled', false);
            BACK_TO_CHART_CLOSED();
            BolClickListQuote = "TRUE";
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#BtnViewWOClosedPSL").blur();
            $('#ListReport').html("");
            $("#ContentLoading").remove();
            $("#BtnViewWOClosedPSL").attr('disabled', false);
            BolClickListQuote = "TRUE";
        }
    });
}
</script>