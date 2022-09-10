$(document).ready(function () {
    var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
    var formdata = new FormData();
    $.ajax({
        url: 'project/wipsims/wiplistquote.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("#BtnViewQuote").attr('disabled', true);
            $('#ListQuote').html("");
            $("#ListQuote").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ListQuote').html("");
            $('#ResultCategory').html("");
            $('#ListDetailPart').html("");
        },
        success: function (xaxa) {
            $('#ListQuote').html("");
            $('#ListQuote').hide();
            $('#ListQuote').html(xaxa);
            $('#ListQuote').fadeIn('fast');
            $("#ContentLoading").remove();
            $("#BtnViewQuote").blur();
            $("#BtnViewQuote").attr('disabled', false);
            TEMPLATE_CHECK();
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#BtnViewQuote").blur();
            $('#ListQuote').html("");
            $("#ContentLoading").remove();
            $("#BtnViewQuote").attr('disabled', false);
        }
    });
})
function TEMPLATE_CHECK()
{
    var BolClickListCategory = "TRUE";
    if (BolClickListCategory == "TRUE") {
        $(".PointerList").click(function () {
            if (BolClickListCategory == "TRUE") {
                $("#ListCategory tr").removeClass('PointerListSelected');
                $(this).closest('.PointerList').addClass("PointerListSelected");
                var QuoteName = $(this).text();
                var ProjectID = $(this).data('id');
                var Location = $(this).data('log');
                $("#TempQuote").text(QuoteName);
                var formdata = new FormData();
                formdata.append('ValQuoteName', QuoteName);
                formdata.append('ValProjectID', ProjectID);
                formdata.append('ValLocation', Location);
                $.ajax({
                    url: 'project/wipsims/wipsimscontent.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        BolClickListCategory = "FALSE";
                        $("html, body").animate({ scrollTop: $("#ResultCategory").offset().top - 20 }, "fast");
                        $('#ResultCategory').html("");
                        $('#ListDetailPart').html("");
                        $("#ContentLoading").remove();
                        $("#ResultCategory").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#ResultCategory').html("");
                    },
                    success: function (xaxa) {
                        $('#ResultCategory').html("");
                        $('#ResultCategory').hide();
                        $('#ResultCategory').html(xaxa);
                        $('#ResultCategory').fadeIn('fast');
                        $("#ContentLoading").remove();
                        BolClickListCategory = "TRUE";
                        PART_CHECK();
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $('#ResultCategory').html("");
                        $("#ContentLoading").remove();
                        BolClickListCategory = "TRUE";
                    }
                });
            }
            else {
                return false;
            }
        });
    }
}
function PART_CHECK()
{
    var BolClickPartNo = "TRUE";
    if (BolClickPartNo == "TRUE")
    {
        $("#TableParentPart .PointerList").click(function () {
            $("#TableParentPart tr").removeClass('PointerListSelected');
            $(this).closest('.PointerList').addClass("PointerListSelected");
            var PartNo = $(this).data('id');
            $("#TempFilter").text(PartNo);
            var QuoteName = $("#TempQuote").text();
            var Location = $(this).data('log');
            var formdata = new FormData();
            formdata.append('ValQuoteName', QuoteName);
            formdata.append('ValPartNo', PartNo);
            formdata.append('ValLocation', Location);
            $.ajax({
                url: 'project/wipsims/wipsimscontentpart.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolClickPartNo = "FALSE";
                    $('#ListDetailPart').html("");
                    $("#ContentLoading").remove();
                    $("#ListDetailPart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ListDetailPart').html("");
                },
                success: function (xaxa) {
                    $('#ListDetailPart').html("");
                    $('#ListDetailPart').hide();
                    $('#ListDetailPart').html(xaxa);
                    $('#ListDetailPart').fadeIn('fast');
                    $("#ContentLoading").remove();
                    BolClickPartNo = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#ListDetailPart').html("");
                    $("#ContentLoading").remove();
                    BolClickPartNo = "TRUE";
                }
            });
        });
    }
}