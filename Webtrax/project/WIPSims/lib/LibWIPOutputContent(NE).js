$(document).ready(function () {
    var BolChangeFilter = "TRUE";
    if (BolChangeFilter == "TRUE")
    {
        $(".RadioFilter").change(function (event) {
            // alert(this.value);
            // $('#ContentDataFilter').html("");
            var QuoteCategorySelected = $("#TempQuoteCategory").text();
            var DataFilterSelected = this.value;
            $("#TempDataFilter").text(DataFilterSelected);
            var QuoteName = $("#TempQuote").text();
            var formdata = new FormData();
            formdata.append('ValQuoteCategorySelected', QuoteCategorySelected);
            formdata.append('ValDataFilter', DataFilterSelected);
            formdata.append('ValQuoteName', QuoteName);
            $.ajax({
                url: 'project/wipsims/wipoutputquotecontent2.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolChangeFilter = "FALSE";
                    $("html, body").animate({ scrollTop: $("#ResultCategory").offset().top - 20 }, "slow");
                    $(".RadioFilter").attr('disabled', true);
                    $("#BtnViewQuoteOutput").attr('disabled', true);
                    $('#ContentDataFilter').html("");
                    $("#ContentLoading2").remove();
                    $("#ContentDataFilter").before('<div class="col-sm-12" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ContentDataFilter').html("");
                    $('#ResultCategoryDetail').html("");
                },
                success: function (xaxa) {
                    $('#ContentDataFilter').html("");
                    $('#ContentDataFilter').hide();
                    $('#ContentDataFilter').html(xaxa);
                    $('#ContentDataFilter').fadeIn('fast');
                    $("#ContentLoading2").remove();
                    $("#BtnViewQuoteOutput").blur();
                    $(".RadioFilter").attr('disabled', false);
                    $("#BtnViewQuoteOutput").attr('disabled', false);
                    BolChangeFilter = "TRUE";
                    DetailContent();
                    event.preventDefault();
                    return false;
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#BtnViewQuoteOutput").blur();
                    $('#ContentDataFilter').html("");
                    $("#ContentLoading2").remove();
                    $(".RadioFilter").attr('disabled', false);
                    $("#BtnViewQuoteOutput").attr('disabled', false);
                    BolChangeFilter = "TRUE";
                    event.preventDefault();
                }
            });
        });
    }
    else {
        return false;
    }
    DetailContent();
});
function DetailContent()
{
    $("#TableQuoteSelected tbody").on("click", ".TimeLog", function (e) {
        var ColumnHeader = e.toElement.cellIndex + 1;
        var ValColumnText = $("#TableQuoteSelected thead td:nth-child(" + ColumnHeader + ")").text();
        var ValDataFilterSelected = ValColumnText[ValColumnText.length - 2];
        // $("#TempDataTime").text(ValDataFilterSelected);
        $("#TempDataTime").text(ColumnHeader);
        var QuoteCategorySelected = $("#TempQuoteCategory").text();
        var DataFilterSelected = $("#TempDataFilter").text();
        var QuoteName = $("#TempQuote").text();
        var formdata = new FormData();
        formdata.append('ValQuoteCategorySelected', QuoteCategorySelected);
        formdata.append('ValDataFilter', DataFilterSelected);
        formdata.append('ValQuoteName', QuoteName);
        // formdata.append('ValDataTime', ValDataFilterSelected);
        formdata.append('ValDataTime', ColumnHeader);
        $.ajax({
            url: 'project/wipsims/wipoutputquotecontentdetail.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("html, body").animate({ scrollTop: $("#ResultCategoryDetail").offset().top - 20 }, "slow");
                $('#ResultCategoryDetail').html("");
                $("#ContentLoading").remove();
                $("#ResultCategoryDetail").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ResultCategoryDetail').html("");
            },
            success: function (xaxa) {
                $('#ResultCategoryDetail').html("");
                $('#ResultCategoryDetail').hide();
                $('#ResultCategoryDetail').html(xaxa);
                $('#ResultCategoryDetail').fadeIn('fast');
                $("#ContentLoading").remove();
                e.preventDefault();
                return false;
            },
            error: function () {
                alert("Request cannot proceed!");
                $('#ResultCategoryDetail').html("");
                $("#ContentLoading").remove();
                e.preventDefault();
            }
        });
    });
}

