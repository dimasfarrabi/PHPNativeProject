$(document).ready(function () {
    $("#BtnViewData").click(function(){
        var InputName = $("#FilterName").val().trim();
        var InputWOC = $("#FilterWOC").val().trim();
        if (InputName == "") {
            $("#FilterName").focus();
            return false;
        }
        if (InputName.indexOf(' - PSM') >= 0)
        {
            var Location = "PSM";
        }
        else
        {
            var Location = "PSL";
        }
        var formdata = new FormData();
        formdata.append("InputName", InputName);
        formdata.append("InputWOC", InputWOC);
        formdata.append("Location", Location);
        $.ajax({
            url: 'project/TimeTracking/ContentManageLabourHour.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewData").attr("disabled", true);
                $("#FilterName").attr("disabled", true);
                $("#FilterWOC").attr("disabled", true);
                $("html, body").animate({ scrollTop: $("#ContentPageManage").offset().top - 80 }, "fast");
                $('#ContentPageManage').html("");
                $("#ContentPageManage").before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ContentPageManage').html("");
                $('#ContentPageManage').html(xaxa);
                $('#ContentPageManage').fadeIn('fast');
                $("#LoadingLoad").remove();
                LOAD_DATA();
                $("#BtnViewData").blur();
                $("#FilterName").prop("selectedIndex", 0);
                $("#FilterWOC").val("");
                $("#FilterName").attr("disabled", false);
                $("#FilterWOC").attr("disabled", false);
                $("#BtnViewData").attr("disabled", false);
            },
            error: function () {
                $("#ContentPageManage").html("");
                $("#FilterName").attr("disabled", false);
                $("#FilterWOC").attr("disabled", false);
                $("#BtnViewData").attr("disabled", false);
            }
        });
    });
});
function LOAD_DATA()
{
    $("#TableViewData").dataTable({
        "paging": false,
        "bInfo": false,
        "columnDefs": [
            { "width": "25px", "targets": 0 },
            { "width": "200px", "targets": 4 },
            { "width": "100px", "targets": 5 },
            { "width": "50px", "targets": [2,6,7] },
            { "targets": [7], "orderable": false }
        ]
    });
    $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
    ADD_ROW();
    UPDATE_ROW();
    DELETE_ROW();
}
function ADD_ROW()
{
    $("#BtnAddData").click(function () {
        $('#TemporarySpace').html("");
        $("#TemporarySpace").html('<div class="modal fade" id="ModalAddLabourHour" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" style="color:#000;">New Labour Hour</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><span id="ContentModal" style="color:#000;"></span></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div><div class="modal fade" id="ModalWOList" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" style="color:#000;">WO List</h5><button type="button" class="btn-close"  data-bs-target="#ModalAddLabourHour" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><span id="ContentModalWO" style="color:#000;"></span></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-target="#ModalAddLabourHour" data-bs-toggle="modal" data-bs-dismiss="modal">&nbsp;Cancel&nbsp;</button></div></div></div></div>');
        var DataID = $(this).data("sess");
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        $.ajax({
            url: 'project/TimeTracking/ModalAddNewManageLabourHour.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ModalAddLabourHour").modal("show");
                $('#ContentModal').html("");
                $("#ContentModal").before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad2"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ContentModal').html("");
                $('#ContentModal').hide();
                $('#ContentModal').html(xaxa);
                $('#ContentModal').fadeIn('fast');
                $("#LoadingLoad2").remove();
                $("#TextTotal").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                LOAD_WO_LIST();
                ADD_FORM();
            },
            error: function () {
                $('#ModalAddLabourHour').on('hide.bs.modal', function () {
                    $("#TemporarySpace").html("");
                });
            }
        });

    });
}
function UPDATE_ROW()
{
    $("#TableViewData tbody .UpdateLabourHour").on("click", function () {
        var DataID = $(this).closest("td").find(".PointerList").data("datatoken");
        $('#TemporarySpace').html("");
        $("#TemporarySpace").html('<div class="modal fade" id="ModalUpdateLabourHour" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" style="color:#000;">Update Labour Hour</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><span id="ContentModal" style="color:#000;"></span></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div>');
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        $.ajax({
            url: 'project/TimeTracking/ModalUpdateManageLabourHour.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ModalUpdateLabourHour").modal("show");
                $('#ContentModal').html("");
                $("#ContentModal").before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad1"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ContentModal').html("");
                $('#ContentModal').hide();
                $('#ContentModal').html(xaxa);
                $('#ContentModal').fadeIn('fast');
                $("#LoadingLoad1").remove();
                $("#TextTotal").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                UPDATE_DATA();
            },
            error: function () {
                $('#ModalUpdateLabourHour').on('hide.bs.modal', function () {
                    $("#TemporarySpace").html("");
                });
            }
        });
    });
}
function UPDATE_DATA()
{
    $("#BtnEditLabourHour").click(function(){
        var InputTotal = $("#TextTotal").val().trim();
        var DataID = $(this).data("datatoken");
        if (InputTotal.trim() == "") {
            $("#TextTotal").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        formdata.append("InputTotal", InputTotal);
        $.ajax({
            url: 'project/TimeTracking/src/srcUpdateManageLabourHour.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnEditLabourHour").attr('disabled', true);
                $('#TempProcess').html("");
                $("#TempProcess").before('Please wait...');
            },
            success: function (xaxa) {
                $('#TempProcess').html("");
                $('#TempProcess').hide();
                $('#TempProcess').html(xaxa);
                $('#TempProcess').fadeIn('fast');
                $("#BtnEditLabourHour").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#TempProcess").html("");
                $("#BtnEditLabourHour").attr('disabled', false);
            }
        });
    });
}
function LOAD_WO_LIST()
{
    $("#BtnLoadListWO").click(function(){
        var Category = $("#TextCategory").val().trim();
        var Season = $("#TextSeason").val().trim();
        var formdata = new FormData();
        formdata.append("Category", Category);
        formdata.append("Season", Season);
        $.ajax({
            url: 'project/TimeTracking/ModalWOListManageLabourHour.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ModalWOList").modal("show");
                $("#BtnLoadListWO").attr('disabled', true);
                $('#ContentModalWO').html("");
                $("#ContentModalWO").before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad3"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ContentModalWO').html("");
                $('#ContentModalWO').hide();
                $('#ContentModalWO').html(xaxa);
                $('#ContentModalWO').fadeIn('fast');
                $("#LoadingLoad3").remove();
                $("#BtnLoadListWO").attr('disabled', false);
                $("#TableListWO").dataTable({});
                $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
                $("#TableListWO tbody .BtnSelect").on("click", function () {
                    var ValWOC = $(this).closest("tr").find("td:eq(1)").html();
                    var ValWOID = $(this).closest("tr").find("td:eq(2)").html();
                    var ValExpense = $(this).closest("tr").find("td:eq(3)").html();
                    $("#ModalAddLabourHour #TextWOID").val(ValWOID);
                    $("#ModalAddLabourHour #TextWOC").val(ValWOC);
                    $("#ModalAddLabourHour #TextExpense").val(ValExpense);
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ContentModalWO").html("");
                $("#BtnLoadListWO").attr('disabled', false);
            }
        });
    });
}
function DELETE_ROW()
{
    $("#TableViewData tbody .DeleteLabourHour").on("click", function () {
        if (confirm("Are you sure to delete this record?") == true) {
            var DataID = $(this).closest("td").find(".PointerList").data("datatoken");
            $(this).closest("tr").remove();
            var formdata = new FormData();
            formdata.append("DataID", DataID);
            $.ajax({
                url: 'project/TimeTracking/src/srcDeleteManageLabourHour.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $('#TemporarySpace').html("");
                },
                success: function (xaxa) {
                    $('#TemporarySpace').html("");
                    $('#TemporarySpace').hide();
                    $('#TemporarySpace').html(xaxa);
                    $('#TemporarySpace').fadeIn('fast');
                    $('#TemporarySpace').html("");
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#TemporarySpace").html("");
                }
            });
        }
    });
}
function ADD_FORM()
{
    $("#BtnAddLabourHour").click(function(){
        $('#TempProcess').html("");
        var DataID = $(this).data("datatoken"); 
        var DataEmployee = $("#TextEmployee").val();
        var DataCategory = $("#TextCategory").val();
        var DataSeason = $("#TextSeason").val();
        var DataWOID = $("#TextWOID").val();
        var DataWOC = $("#TextWOC").val();
        var DataExpense = $("#TextExpense").val();
        var DataTotal = $("#TextTotal").val();
        if (DataWOID == "") {
            $("#TempProcess").html('<span class="fw-bold text-danger">Input WO ID!</span>');
            return false;
        }
        if (DataWOC == "") {
            $("#TempProcess").html('<span class="fw-bold text-danger">Input WO Child!</span>');
            return false;
        }
        if (DataExpense == "") {
            $("#TempProcess").html('<span class="fw-bold text-danger">Input Expense!</span>');
            return false;
        }
        if (DataTotal == "") {
            $("#TextTotal").focus();
            return false;
        }

        if (DataEmployee.indexOf(' - PSM') >= 0) {
            var Location = "PSM";
        }
        else {
            var Location = "PSL";
        }
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        formdata.append("DataEmployee", DataEmployee);
        formdata.append("DataCategory", DataCategory);
        formdata.append("DataSeason", DataSeason);
        formdata.append("DataWOID", DataWOID);
        formdata.append("DataWOC", DataWOC);
        formdata.append("DataExpense", DataExpense);
        formdata.append("DataTotal", DataTotal);
        formdata.append("Location", Location);        
        $.ajax({
            url: 'project/TimeTracking/src/srcAddManageLabourHour.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnAddLabourHour").attr('disabled', true);
                $('#TempProcess').html("");
                $("#TempProcess").before('<div id="LoadingText">Please wait...</div>');
            },
            success: function (xaxa) {
                $('#TempProcess').html("");
                $('#TempProcess').hide();
                $('#TempProcess').html(xaxa);
                $('#TempProcess').fadeIn('fast');
                $('#LoadingText').remove("");
                $('#TempProcess').html("");
                $("#BtnAddLabourHour").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#TempProcess").html("");
                $("#BtnAddLabourHour").attr('disabled', false);
            }
        });
    });
}