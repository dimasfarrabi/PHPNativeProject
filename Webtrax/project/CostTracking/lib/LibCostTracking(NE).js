$(document).ready(function () {
    $("#BtnViewWOClosedPSL").click(function(){
        var ValTime = $("#InputClosedTime").children("option:selected").val();
        var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
        $("#TempLocation").text("");                                                // *) sementara utk ganti lokasi yg disembunyikan
        var ValTitle = "";                                                          // *) sementara utk ganti lokasi yg disembunyikan
        $("#TempFilter").text(ValTime + "*" + ValQuoteCategory);
        var formdata = new FormData();
        formdata.append('ValTime', ValTime);
        formdata.append('ValQuoteCategory', ValQuoteCategory);
        formdata.append('ValType', ValTitle);
        if(ValQuoteCategory != "All"){
        $.ajax({
            url: 'project/costtracking/listquoteclosed.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWOClosedPSL").attr('disabled', true);
                $('#ListQuote').html("");
                $("#ListQuote").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuote').html("");
                $('#ListReport').html("");
                $('#ListOTSTop').html("");
            },
            success: function (xaxa) {
                $('#ListQuote').html("");
                $('#ListQuote').hide();
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewWOClosedPSL").blur();
                $("#BtnViewWOClosedPSL").attr('disabled', false);
                SHOW_PIE_CHART_CLOSED()
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWOClosedPSL").blur();
                $('#ListQuote').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWOClosedPSL").attr('disabled', false);
            }
        
        });
    }
    else{}
    });


    $("#BtnViewWOOpenPSL").click(function () {
        var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
        $("#TempLocation").text("");                                                // *) sementara utk ganti lokasi yg disembunyikan
        var ValTitle = "";                                                          // *) sementara utk ganti lokasi yg disembunyikan
        $("#TempFilter").text(ValQuoteCategory);
        var formdata = new FormData();
        formdata.append('ValQuoteCategory', ValQuoteCategory);
        formdata.append('ValType', ValTitle);
        $.ajax({
            url: 'project/costtracking/listquoteopen.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWOOpenPSL").attr('disabled', true);
                $('#ListQuoteOpen').html("");
                ListReportOpen
                $("#ListQuoteOpen").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuoteOpen').html("");
                $('#ListReportOpen').html("");
                $('#ListOTSTop').html("");
            },
            success: function (xaxa) {
                $('#ListQuoteOpen').html("");
                $('#ListQuoteOpen').hide();
                $('#ListQuoteOpen').html(xaxa);
                $('#ListQuoteOpen').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewWOOpenPSL").blur();
                $("#BtnViewWOOpenPSL").attr('disabled', false);
                SHOW_PIE_CHART_OPEN();
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWOOpenPSL").blur();
                $('#ListQuoteOpen').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWOOpenPSL").attr('disabled', false);
            }
        });
    });
});


function SHOW_PIE_CHART_CLOSED()
{
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
function SHOW_PIE_CHART_OPEN()
{
    var DataFilterSelected = $("#TempFilter").text();
    if(DataFilterSelected == "Quote")
    {
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
    else
    {
        
    }
}