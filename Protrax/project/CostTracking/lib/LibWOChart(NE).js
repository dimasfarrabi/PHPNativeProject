$(document).ready(function () {
    $("#BtnViewWOClosed").click(function(){
        var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
        $("#TempQuote").text(ValQuoteCategory);
        var formdata = new FormData();
        formdata.append('ValQuoteCategory', ValQuoteCategory);
        $.ajax({
            url: 'project/costtracking/wochartlistproject.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWOClosed").attr('disabled', true);
                $('#ListQuote').html("");
                $("#ListQuote").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuote').html("");
                $('#TempContent').html("");
                $('#ResultChart').html("");
            },
            success: function (xaxa) {
                $('#ListQuote').html("");
                $('#ListQuote').hide();
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                LISTDATAPROJECT();
                $("#ContentLoading").remove();
                $("#BtnViewWOClosed").blur();
                $("#BtnViewWOClosed").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWOClosed").blur();
                $('#ListQuote').html("");
                $('#TempContent').html("");
                $('#ResultChart').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWOClosed").attr('disabled', false);
            }
        });
    });
    $("#BtnViewWOOpen").click(function () {
        var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
        $("#TempQuote").text(ValQuoteCategory);
        var formdata = new FormData();
        formdata.append('ValQuoteCategory', ValQuoteCategory);
        $.ajax({
            url: 'project/costtracking/wochartlistproject.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWOOpen").attr('disabled', true);
                $('#ListQuote').html("");
                $("#ListQuote").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuote').html("");
                $('#TempContent').html("");
                $('#ResultChart').html("");
            },
            success: function (xaxa) {
                $('#ListQuote').html("");
                $('#ListQuote').hide();
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                LISTDATAPROJECT();
                $("#ContentLoading").remove();
                $("#BtnViewWOOpen").blur();
                $("#BtnViewWOOpen").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWOOpen").blur();
                $('#ListQuote').html("");
                $('#TempContent').html("");
                $('#ResultChart').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWOOpen").attr('disabled', false);
            }
        });
    });
});
function LISTDATAPROJECT()
{
    var BolPointerListProject = "TRUE";
    $(".PointerListProject").click(function(){
        if (BolPointerListProject == "TRUE")
        {
            $("#TableListProject tr").removeClass("PointerListSelected");
            $(this).closest(".PointerListProject").addClass("PointerListSelected");
            var ProjectName = $(this).data('split');
            var QuoteCategory = $("#TempQuote").text();
            $("#TempContent").html(ProjectName);
            var formdata = new FormData();
            formdata.append('ValCategory', QuoteCategory);
            formdata.append('ValProject', ProjectName);
            $.ajax({
                url: 'project/costtracking/wochartlistcontent.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolPointerListProject = "FALSE";
                    $("html, body").animate({ scrollTop: $("#ResultChart").offset().top - 20 }, "fast");
                    $('#ResultChart').html("");
                    $("#ResultChart").before('<div class="col-sm-12" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ResultChart').html("");
                },
                success: function (xaxa) {
                    $('#ResultChart').html("");
                    $('#ResultChart').hide();
                    $('#ResultChart').html(xaxa);
                    $('#ResultChart').fadeIn('fast');
                    $("#ContentLoading2").remove();
                    $("#btn-labeled").blur();
                    BolPointerListProject = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#btn-labeled").blur();
                    $('#ResultChart').html("");
                    $("#ContentLoading2").remove();
                    BolPointerListProject = "TRUE";
                }
            });
        }
        else
        {
            return false;
        }
    });
}
