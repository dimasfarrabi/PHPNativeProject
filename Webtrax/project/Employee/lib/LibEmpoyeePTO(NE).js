$(document).ready(function () {
    GET_DETAIL_GROUP_DAYOFF();
    var BolPointerListGroup = "TRUE";
    $(".PointerListGroup").click(function () {
        if (BolPointerListGroup == "TRUE") {
            $("#ListTableGroup tr").removeClass('PointerListSelected');
            $(this).closest('.PointerListGroup').addClass("PointerListSelected");
            var ValGroup = $(this).data('roles');
            var formdata = new FormData();
            formdata.append("ValGroup", ValGroup);
            $.ajax({
                url: 'project/employee/chartemployeepto.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#ResultChart").offset().top - 20 }, "fast");
                    $('#ResultChart').html("");
                    $('#DetailChart').html("");
                    $("#ResultChart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ResultChart').html("");
                    BolPointerListGroup = "FALSE";
                },
                success: function (xaxa) {
                    $('#ResultChart').html("");
                    $('#ResultChart').hide();
                    $('#ResultChart').html(xaxa);
                    $('#ResultChart').fadeIn('fast');
                    $("#ContentLoading").remove();
                    GET_DETAIL_GROUP_DAYOFF();
                    BolPointerListGroup = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#ResultChart').html("");
                    $("#ContentLoading").remove();
                    BolPointerListGroup = "TRUE";
                }
            });
        }
        else
        {
            return false;
        }
    });
});
function GET_DETAIL_GROUP_DAYOFF()
{
    var BolPointerDescription = "TRUE";
    $(".PointerDataDetail").click(function () {
        if (BolPointerDescription == "TRUE") {
            $("#ListTableResult tr").removeClass('PointerListSelected');
            $(this).closest('.PointerDataDetail').addClass("PointerListSelected");
            var ValDetail = $(this).data('result');
            var formdata = new FormData();
            formdata.append("ValDetail", ValDetail);
            $.ajax({
                url: 'project/employee/descriptionemployeedayoffbalance.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#DetailChart").offset().top - 20 }, "fast");
                    $('#DetailChart').html("");
                    $("#DetailChart").before('<div class="col-sm-12" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#DetailChart').html("");
                    BolPointerDescription = "FALSE";
                },
                success: function (xaxa) {
                    $('#DetailChart').html("");
                    $('#DetailChart').hide();
                    $('#DetailChart').html(xaxa);
                    $('#DetailChart').fadeIn('fast');
                    $("#ContentLoading2").remove();
                    $("#TableDetail").dataTable({
                        "pagingType": "simple"
                    });
                    BolPointerDescription = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#DetailChart').html("");
                    $("#ContentLoading2").remove();
                    BolPointerDescription = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
}