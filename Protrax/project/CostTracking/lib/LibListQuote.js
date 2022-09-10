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