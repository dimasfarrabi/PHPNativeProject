$(document).ready(function () {
    $(document).on('click', '#BtnViewQuoteOutput', function () {
        var QuoteSelected = $("#InputQuoteCategory").children("option:selected").val();
        $("#TempQuoteCategory").text(QuoteSelected);
        var formdata = new FormData();
        formdata.append('ValQuoteCategory', QuoteSelected);
        $.ajax({
            url: 'project/wipsims/wipoutputquote.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewQuoteOutput").attr('disabled', true);
                $('#ListQuote').html("");
                $("#ContentLoading").remove();
                $("#ListQuote").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuote').html("");
                $('#ResultCategory').html("");
                $('#ResultCategoryDetail').html("");
                // $('#TempQuoteCategory').html("");
                $('#TempDataFilter').html("");
                $('#TempQuote').html("");
                $("#TempDataTime").html("");
            },
            success: function (xaxa) {
                $('#ResultCategory').html("");
                $('#ResultCategoryDetail').html("");
                // $('#TempQuoteCategory').html("");
                $('#TempDataFilter').html("");
                $('#TempQuote').html("");
                $('#ListQuote').html("");
                $('#ListQuote').hide();
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewQuoteOutput").blur();
                $("#BtnViewQuoteOutput").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewQuoteOutput").blur();
                $('#ListQuote').html("");
                $('#ResultCategory').html("");
                $('#ResultCategoryDetail').html("");
                $("#ContentLoading").remove();
                $("#BtnViewQuoteOutput").attr('disabled', false);
            }
        });
    });
    var BolSelectQuote = "TRUE";
    if (BolSelectQuote == "TRUE")
    {
        $(document).on('click', '.PointerListQuoteOpen', function () {
            var QuoteCategorySelected = $("#TempQuoteCategory").text();
            var DataFilterSelected = $("#TempDataFilter").text();
            $("#ListCategory tr").removeClass('PointerListQuoteSelected');
            $(this).closest('.PointerListQuoteOpen').addClass("PointerListQuoteSelected");
            var QuoteName = $(this).text();
            $("#TempQuote").text(QuoteName);
            var formdata = new FormData();
            formdata.append('ValQuoteCategorySelected', QuoteCategorySelected);
            formdata.append('ValQuoteName', QuoteName);
            $.ajax({
                url: 'project/wipsims/wipoutputquotecontent.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolSelectQuote = "FALSE";
                    $("html, body").animate({ scrollTop: $("#ResultCategory").offset().top - 20 }, "slow");
                    $("#BtnViewQuoteOutput").attr('disabled', true);
                    $('#ResultCategory').html("");
                    $('#ResultCategoryDetail').html("");
                    $("#ContentLoading1").remove();
                    $("#ResultCategory").before('<div class="col-sm-12" id="ContentLoading1"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ResultCategory').html("");
                    $("#ListCategory tr").removeClass('PointerListQuoteOpen');
                },
                success: function (xaxa) {
                    $('#ResultCategory').html("");
                    $('#ResultCategoryDetail').html("");
                    $('#ResultCategory').hide();
                    $('#ResultCategory').html(xaxa);
                    $('#ResultCategory').fadeIn('fast');
                    $("#ContentLoading1").remove();
                    $("#BtnViewQuoteOutput").blur();
                    $("#BtnViewQuoteOutput").attr('disabled', false);
                    $("#TempDataFilter").text("Daily");
                    BolSelectQuote = "FALSE";
                    $("#ListCategory tr").addClass('PointerListQuoteOpen');
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#BtnViewQuoteOutput").blur();
                    $('#ResultCategory').html("");
                    $('#ResultCategoryDetail').html("");
                    $("#ContentLoading1").remove();
                    $("#BtnViewQuoteOutput").attr('disabled', false);
                    BolSelectQuote = "FALSE";
                }
            });
        });
    }
    else
    {
        return false;
    }
});

