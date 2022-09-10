$(document).ready(function () {
    var BolPointerListGroup = "TRUE";
    $(".PointerListGroup").click(function () {
        if (BolPointerListGroup == "TRUE") {
            $("#ListTableGroup tr").removeClass('PointerListSelected');
            $(this).closest('.PointerListGroup').addClass("PointerListSelected");
            var ValGroup = $(this).data('roles');
            var formdata = new FormData();
            formdata.append("ValGroup", ValGroup);
            $.ajax({
                url: 'project/employee/EmployeePercentageSelected.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#ResultData").offset().top - 20 }, "fast");
                    $('#ResultData').html("");
                    $('#DetailData').html("");
                    $("#ResultData").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ResultData').html("");
                    BolPointerListGroup = "FALSE";
                },
                success: function (xaxa) {
                    $('#ResultData').html("");
                    $('#ResultData').hide();
                    $('#ResultData').html(xaxa);
                    $('#ResultData').fadeIn('fast');
                    $("#ContentLoading").remove();
                    GET_DETAIL_GROUP();
                    BolPointerListGroup = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#ResultData').html("");
                    $("#ContentLoading").remove();
                    BolPointerListGroup = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
});
function GET_DETAIL_GROUP()
{
    var BolDetailGroup = "TRUE";
    $(".DataRowSalatiga").click(function () {
        if (BolDetailGroup == "TRUE") {
            $("#ListTableResultA tr").removeClass('PointerListSelected');
            $("#ListTableResultB tr").removeClass('PointerListSelected');
            $("#ListTableResultC tr").removeClass('PointerListSelected');
            $(this).closest('.DataRowSalatiga').addClass("PointerListSelected")
            var Division = $(this).closest('tr').find("td:eq(0)").text();
            var Location = "PSL";
            var Label = $("#GroupLabel").text();
            var formdata = new FormData();
            formdata.append("ValDivision", Division);
            formdata.append("ValLocation", Location);
            formdata.append("ValLabel", Label);
            BolDetailGroup = "FALSE";
            $.ajax({
                url: 'project/employee/EmployeePercentageDetail.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#DetailData").offset().top - 20 }, "fast");
                    $('#DetailData').html("");
                    $("#DetailData").before('<div class="col-sm-12" id="ContentLoadingDetail"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#DetailData').html("");
                    BolDetailGroup = "FALSE";
                },
                success: function (xaxa) {
                    $('#DetailData').html("");
                    $('#DetailData').hide();
                    $('#DetailData').html(xaxa);
                    $('#DetailData').fadeIn('fast');
                    $("#ContentLoadingDetail").remove();
                    LOAD_PICT_EMP();
                    BolDetailGroup = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#DetailData').html("");
                    $("#ContentLoadingDetail").remove();
                    BolDetailGroup = "TRUE";
                }
            });
        }
        else
        {
            return false;
        }
    });
    $(".DataRowSemarang").click(function () {
        if (BolDetailGroup == "TRUE") {
            $("#ListTableResultA tr").removeClass('PointerListSelected');
            $("#ListTableResultB tr").removeClass('PointerListSelected');
            $("#ListTableResultC tr").removeClass('PointerListSelected');
            $(this).closest('.DataRowSemarang').addClass("PointerListSelected")
            var Division = $(this).closest('tr').find("td:eq(0)").text();
            var Location = "PSM";
            var Label = $("#GroupLabel").text();
            var formdata = new FormData();
            formdata.append("ValDivision", Division);
            formdata.append("ValLocation", Location);
            formdata.append("ValLabel", Label);
            BolDetailGroup = "FALSE";
            $.ajax({
                url: 'project/employee/EmployeePercentageDetail.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#DetailData").offset().top - 20 }, "fast");
                    $('#DetailData').html("");
                    $("#DetailData").before('<div class="col-sm-12" id="ContentLoadingDetail"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#DetailData').html("");
                    BolDetailGroup = "FALSE";
                },
                success: function (xaxa) {
                    $('#DetailData').html("");
                    $('#DetailData').hide();
                    $('#DetailData').html(xaxa);
                    $('#DetailData').fadeIn('fast');
                    $("#ContentLoadingDetail").remove();
                    LOAD_PICT_EMP();
                    BolDetailGroup = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#DetailData').html("");
                    $("#ContentLoadingDetail").remove();
                    BolDetailGroup = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
	$(".DataRowAll").click(function(){
        if (BolDetailGroup == "TRUE") {
            $("#ListTableResultA tr").removeClass('PointerListSelected');
            $("#ListTableResultB tr").removeClass('PointerListSelected');
            $("#ListTableResultC tr").removeClass('PointerListSelected');
            $(this).closest('.DataRowAll').addClass("PointerListSelected")
            var Division = $(this).closest('tr').find("td:eq(0)").text();
            var Location = "All";
            var Label = $("#GroupLabel").text();
            var formdata = new FormData();
            formdata.append("ValDivision", Division);
            formdata.append("ValLocation", Location);
            formdata.append("ValLabel", Label);
            $.ajax({
                url: 'project/employee/EmployeePercentageDetailAll.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#DetailData").offset().top - 20 }, "fast");
                    $('#DetailData').html("");
                    $("#DetailData").before('<div class="col-sm-12" id="ContentLoadingDetail"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#DetailData').html("");
                    BolDetailGroup = "FALSE";
                },
                success: function (xaxa) {
                    $('#DetailData').html("");
                    $('#DetailData').hide();
                    $('#DetailData').html(xaxa);
                    $('#DetailData').fadeIn('fast');
                    $("#ContentLoadingDetail").remove();
                    LOAD_PICT_EMP_ALL();
                    BolDetailGroup = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#DetailData').html("");
                    $("#ContentLoadingDetail").remove();
                    BolDetailGroup = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
}
function LOAD_PICT_EMP()
{
    var BolPict = "TRUE";
    $("#TableDetail .tablerow").click(function () {
        var Path = $(this).data('dataref');
        var FN = $(this).closest('tr').find("td:eq(1)").text();
        var DetailPosition = $(this).closest('tr').find("td:eq(2)").text();
        var Phone = $(this).closest('tr').find("td:eq(3)").text();
        var formdata = new FormData();
        formdata.append("ValPath", Path);
        formdata.append("ValFN", FN);
        formdata.append("ValDetailPosition", DetailPosition);
        formdata.append("ValPhone", Phone);
        $.ajax({
            url: 'project/employee/EmployeePercentagePict.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                BolPict = "FALSE";
                $("#ModalViewResult").modal("show");
                $('#ContentResultPic').html("");
                if ($('#LoadingCheck').length == "0")
                {
                    $("#ContentResultPic").before('<div class="col-sm-12" id="ContentLoadingPath"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                }
            },
            success: function (xaxa) {
                $('#ContentResultPic').html("");
                $('#ContentResultPic').hide();
                $('#ContentResultPic').html(xaxa);
                $('#ContentResultPic').fadeIn('fast');
                if ($('#PictEmp').length == "0") {
                    $("#ContentLoadingPath").remove();
                }
                else {
                    $('img#PictEmp').on('load', function () {
                        $("#ContentLoadingPath").remove();
                    });
                }
                BolPict = "TRUE";
            },
            error: function () {
                alert("Request cannot proceed!");
                $('#ContentResultPic').html("");
                $("#ContentLoadingPath").remove();
                BolPict = "TRUE";
            }
        });
    });
}
function LOAD_PICT_EMP_ALL() {
    var BolPict = "TRUE";
    $("#TableDetail .tablerow").click(function () {
        var Path = $(this).data('dataref');
        var FN = $(this).closest('tr').find("td:eq(1)").text();
        var DetailPosition = $(this).closest('tr').find("td:eq(2)").text();
        var Phone = $(this).closest('tr').find("td:eq(4)").text();
        var formdata = new FormData();
        formdata.append("ValPath", Path);
        formdata.append("ValFN", FN);
        formdata.append("ValDetailPosition", DetailPosition);
        formdata.append("ValPhone", Phone);
        $.ajax({
            url: 'project/employee/EmployeePercentagePict.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                BolPict = "FALSE";
                $("#ModalViewResult2").modal("show");
                $('#ContentResultPic2').html("");
                if ($('#LoadingCheck').length == "0")
                {
                    $("#ContentResultPic2").before('<div class="col-sm-12" id="ContentLoadingPath2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                }
            },
            success: function (xaxa) {
                $('#ContentResultPic2').html("");
                $('#ContentResultPic2').hide();
                $('#ContentResultPic2').html(xaxa);
                $('#ContentResultPic2').fadeIn('fast');
                if ($('#PictEmp').length == "0") {
                    $("#ContentLoadingPath2").remove();
                }
                else {
                    $('img#PictEmp').on('load', function () {
                        $("#ContentLoadingPath2").remove();
                    });
                }
                BolPict = "TRUE";
            },
            error: function () {
                alert("Request cannot proceed!");
                $('#ContentResultPic2').html("");
                $("#ContentLoadingPath2").remove();
                BolPict = "TRUE";
            }
        });
    });
}