$(document).ready(function () {
    $('#txtFilterTanggal1').datetimepicker({
        lang: 'en',
        timepicker: false,
        format: 'm/d/Y',
        formatDate: 'm/d/Y',
        theme: 'dark'
    });
    $('#txtFilterTanggal2').datetimepicker({
        lang: 'en',
        timepicker: false,
        format: 'm/d/Y',
        formatDate: 'm/d/Y',
        theme: 'dark'
    });
    $("#DownloadTemplate").click(function(){
        window.location.href = 'project/CostTracking/src/srcDownloadTemplateShiftCodeMachine.php';
    });
    $("#TableShiftCode").dataTable({});
    $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
    DELETE_ROW();
    VIEW_DATA();
});
function DELETE_ROW()
{
    $("#TableShiftCode tbody").on("click",".DeleteRow", function () {
        if (confirm("Are you sure to delete this record?") == true) {
            var DataID = $(this).closest("td").find(".PointerList").data("token");
            var Location = $(this).closest("td").find(".PointerList").data("location");
            var formdata = new FormData();
            formdata.append("DataID", DataID);
            formdata.append("Location", Location);
            $.ajax({
                url: 'project/CostTracking/src/srcDeleteDataShiftCodeMachine.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $('#ResultPage').html("");
                },
                success: function (xaxa) {
                    $('#ResultPage').html("");
                    $('#ResultPage').hide();
                    $('#ResultPage').html(xaxa);
                    $('#ResultPage').fadeIn('fast');
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#ResultPage").html("");
                }
            });
        }
    });
}
function VIEW_DATA()
{
    $("#BtnViewData").click(function(){
        var SD = $("#txtFilterTanggal1").val().trim();
        var ED = $("#txtFilterTanggal2").val().trim();
        var Machine = $("#OptMachine").val().trim();
        var Location = $("#OptMachine").find(':selected').attr('data-location');
        if ($("#UseDate").is(":checked")) { 
            var BolChecked = "TRUE";
        }
        else {
            var BolChecked = "FALSE";
        }
        var formdata = new FormData();
        formdata.append("SD", SD);
        formdata.append("ED", ED);
        formdata.append("Machine", Machine);
        formdata.append("Location", Location);
        formdata.append("BolChecked", BolChecked);
        $.ajax({
            url: 'project/CostTracking/ManageShiftCodeMachineAjax.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewData").attr('disabled', true);
                $("#ContentSearchData").html("");
                $("#ContentSearchData").append('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $("#ContentSearchData").html("");
                $("#ContentSearchData").html(xaxa);
                $("#ContentSearchData").fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewData").blur();
                $("#BtnViewData").attr('disabled', false);
                $("#TableShiftCode").dataTable({});
                $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
                DELETE_ROW();
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewData").blur();
                $("#ContentSearchData").html("");
                $("#ContentLoading").remove();
                $("#BtnViewData").attr('disabled', false);
            }
        });
    });
}