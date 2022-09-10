$(document).ready(function () {
    var BolClickCostAllocationClosed = "TRUE";
    var BolClickCostAllocationOpen = "TRUE";
    $("#TableListReport .RowCostAllocation").on("click", function () {
        if (BolClickCostAllocationClosed == "TRUE") {
            var ValCostAllocation = $(this).closest("tr").find("td:eq(1)").text();
            $("#TableListReport tr").removeClass('RowCostAllocationSelected');
            $(this).closest('.RowCostAllocation').addClass("RowCostAllocationSelected");
            var ValTitle = $("#TempLocation").text();
            var TextTempFilter = $("#TempFilter").text();
            TextTempFilter = TextTempFilter.split("*");
            var ClosedTime = TextTempFilter[0];
            var QuoteCategory = TextTempFilter[1];
            var ValQuote = $("#TempQuote").text();
            var formdata = new FormData();
            formdata.append('ValType', ValTitle);
            formdata.append('ValQuoteSelected', ValQuote);
            formdata.append('ValClosedTime', ClosedTime);
            formdata.append('ValQuoteCategory', QuoteCategory);
            formdata.append('$ValExpense', $ValExpense);          
            $.ajax({
                url: 'project/costtracking/ListDetailedClosedCost.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolClickCostAllocationClosed = "FALSE";
                    $("html, body").animate({ scrollTop: $("#TableDetailCost").offset().top }, "slow");
                    $("#BtnViewWOClosedPSL").attr('disabled', true);
                    $('#TableDetailCost').html("");
                    $("#ContentLoading").remove();
                    $("#TableDetailCost").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#TableDetailCost').html("");
                },
                success: function (xaxa) {
                    $('#TableDetailCost').html("");
                    $('#TableDetailCost').hide();
                    $('#TableDetailCost').html(xaxa);
                    $('#TableDetailCost').fadeIn('fast');
                    $("#ContentLoading").remove();
                    $("#BtnViewWOClosedPSL").blur();
                    $("#BtnViewWOClosedPSL").attr('disabled', false);
                    BolClickCostAllocationClosed = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#BtnViewWOClosedPSL").blur();
                    $('#TableDetailCost').html("");
                    $("#ContentLoading").remove();
                    $("#BtnViewWOClosedPSL").attr('disabled', false);
                    BolClickCostAllocationClosed = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });

});