$(document).ready(function () {
    var BolClickCostAllocationClosed = "TRUE";
    var BolClickCostAllocationOpen = "TRUE";
    $("#TableTotalOTSCostClosed .RowCostAllocation").on("click", function () {
        if (BolClickCostAllocationClosed == "TRUE") {
            var ValCostAllocation = $(this).closest("tr").find("td:eq(1)").text();
            $("#TableTotalOTSCostClosed tr").removeClass('RowCostAllocationSelected');
            $(this).closest('.RowCostAllocation').addClass("RowCostAllocationSelected");
            var ValTitle = $("#TempLocation").text();
            var TextTempFilter = $("#TempFilter").text();
            TextTempFilter = TextTempFilter.split("*");
            var ClosedTime = TextTempFilter[0];
            var QuoteCategory = TextTempFilter[1];
            var ValQuote = $("#TempQuote").text();
            // alert("Title : " + ValTitle + ". ClosedTime : " + ClosedTime + ". QuoteCategory : " + QuoteCategory + ". Quote : " + ValQuote + ". CostAllocation : " + ValCostAllocation);
            
            var formdata = new FormData();
            formdata.append('ValType', ValTitle);
            formdata.append('ValQuoteSelected', ValQuote);
            formdata.append('ValClosedTime', ClosedTime);
            formdata.append('ValQuoteCategory', QuoteCategory);
            formdata.append('ValCostAllocation', ValCostAllocation);            
            $.ajax({
                url: 'project/costtracking/listreportotsclosed.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolClickCostAllocationClosed = "FALSE";
                    $("html, body").animate({ scrollTop: $("#ListOTSTop").offset().top }, "slow");
                    $("#BtnViewWOClosedPSL").attr('disabled', true);
                    $('#ListOTSTop').html("");
                    $("#ContentLoading").remove();
                    $("#ListOTSTop").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ListOTSTop').html("");
                },
                success: function (xaxa) {
                    $('#ListOTSTop').html("");
                    $('#ListOTSTop').hide();
                    $('#ListOTSTop').html(xaxa);
                    $('#ListOTSTop').fadeIn('fast');
                    $("#ContentLoading").remove();
                    $("#BtnViewWOClosedPSL").blur();
                    $("#BtnViewWOClosedPSL").attr('disabled', false);
                    BolClickCostAllocationClosed = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#BtnViewWOClosedPSL").blur();
                    $('#ListOTSTop').html("");
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
    $("#TableTotalOTSCostOpen .RowCostAllocationOpen").on("click", function () {
        if (BolClickCostAllocationOpen == "TRUE") {
            var ValCostAllocation = $(this).closest("tr").find("td:eq(1)").text();
            $("#TableTotalOTSCostOpen tr").removeClass('RowCostAllocationSelected');
            $(this).closest('.RowCostAllocationOpen').addClass("RowCostAllocationSelected");
            var ValTitle = $("#TempLocation").text();
            var QuoteCategory = $("#TempFilter").text();
            var ClosedTime = "OPEN";
            var ValQuote = $("#TempQuote").text();
            // alert("Title : " + ValTitle + ". ClosedTime : " + ClosedTime + ". QuoteCategory : " + QuoteCategory + ". Quote : " + ValQuote + ". CostAllocation : " + ValCostAllocation);
            var formdata = new FormData();
            formdata.append('ValType', ValTitle);
            formdata.append('ValQuoteSelected', ValQuote);
            formdata.append('ValClosedTime', ClosedTime);
            formdata.append('ValQuoteCategory', QuoteCategory);
            formdata.append('ValCostAllocation', ValCostAllocation);
            $.ajax({
                url: 'project/costtracking/listreportotsopen.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolClickCostAllocationOpen = "FALSE";
                    $("html, body").animate({ scrollTop: $("#ListOTSTop").offset().top }, "slow");
                    $("#BtnViewWOOpenPSL").attr('disabled', true);
                    $('#ListOTSTop').html("");
                    $("#ContentLoading").remove();
                    $("#ListOTSTop").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ListOTSTop').html("");
                },
                success: function (xaxa) {
                    $('#ListOTSTop').html("");
                    $('#ListOTSTop').hide();
                    $('#ListOTSTop').html(xaxa);
                    $('#ListOTSTop').fadeIn('fast');
                    $("#ContentLoading").remove();
                    $("#BtnViewWOOpenPSL").blur();
                    $("#BtnViewWOOpenPSL").attr('disabled', false);
                    BolClickCostAllocationOpen = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#BtnViewWOOpenPSL").blur();
                    $('#ListOTSTop').html("");
                    $("#ContentLoading").remove();
                    $("#BtnViewWOOpenPSL").attr('disabled', false);
                    BolClickCostAllocationOpen = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
});