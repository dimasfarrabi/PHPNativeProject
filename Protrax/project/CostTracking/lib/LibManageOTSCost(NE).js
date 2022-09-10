$(document).ready(function () {
    LOAD_WO_LIST();
    $("#FilterClosedTime").on("change", function (e) {
        LOAD_WO_LIST();
    });
    $("#FilterQuoteCategory").on("change", function (e) {
        LOAD_WO_LIST();
    });
    $("#BtnViewData").click(function(){
        var Half = $("#FilterClosedTime").val().trim();
        var Expense = $("#FilterExpense").val().trim();
        var Category = $("#FilterQuoteCategory").val().trim();
        var Quote = $("#FilterQuote").val().trim();
        var formdata = new FormData();
        formdata.append("Half", Half);
        formdata.append("Expense", Expense);
        formdata.append("Category", Category);
        formdata.append("Quote", Quote);
        $.ajax({
            url: 'project/CostTracking/ContentManageOTSCost.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#FilterClosedTime").attr("disabled", true);
                $("#FilterExpense").attr("disabled", true);
                $("#FilterQuoteCategory").attr("disabled", true);
                $("#FilterQuote").attr("disabled", true);
                $("#BtnViewData").attr("disabled", true);
                $("#ContentSearchData").html("");
                $("#ContentSearchData").append('<div class="col-sm-12 d-flex justify-content-center" id="LoadListQuote"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $("#ContentSearchData").html(xaxa);
                $("#ContentSearchData").fadeIn('fast');
                $("#LoadListQuote").remove();
                $("#FilterClosedTime").attr("disabled", false);
                $("#FilterExpense").attr("disabled", false);
                $("#FilterQuoteCategory").attr("disabled", false);
                $("#FilterQuote").attr("disabled", false);
                $("#BtnViewData").attr("disabled", false);
                $("#TableViewData").dataTable({
                    "scrollCollapse": true,
                    "columnDefs": [
                        { width: '70px', targets: [1] },
                        { width: '150px', targets: [3] }
                    ],
                    fixedColumns: true,
                    "bInfo": false
                });
                $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
                $("#TableViewData tbody").css("font-size", "12px");
                NEWROW();
                UPDATEROW();
                DELETEROW();
            },
            error: function () {
                $("#ContentSearchData").html("");
                $("#LoadListQuote").remove();
                $("#FilterClosedTime").attr("disabled", false);
                $("#FilterExpense").attr("disabled", false);
                $("#FilterQuoteCategory").attr("disabled", false);
                $("#FilterQuote").attr("disabled", false);
                $("#BtnViewData").attr("disabled", false);
            }
        });
    });
});
function LOAD_WO_LIST() {
    var Half = $("#FilterClosedTime").val().trim();
    var Category = $("#FilterQuoteCategory").val().trim();
    var formdata = new FormData();
    formdata.append("Half", Half);
    formdata.append("Category", Category);
    $.ajax({
        url: 'project/CostTracking/ListWOManageOTSCost.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("#FilterQuoteCategory").attr("disabled", true);
            $("#FilterQuote").attr("disabled", true);
            $("#BtnViewData").attr("disabled", true);
        },
        success: function (xaxa) {
            $('#FilterQuote').html(xaxa);
            $("#FilterQuoteCategory").attr("disabled", false);
            $("#FilterQuote").attr("disabled", false);
            $("#BtnViewData").attr("disabled", false);
        },
        error: function () {
            $("#FilterQuoteCategory").attr("disabled", false);
            $("#FilterQuote").attr("disabled", false);
            $("#BtnViewData").attr("disabled", false);
        }
    });
}
function NEWROW()
{
    $("#BtnAddData").click(function () {
        var DataID = $(this).data("datatoken");
        $("#TemporarySpace").html("");
        $("#TemporarySpace").html('<div class="modal fade" id="ModalNewManageOTSCost" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Form Tambah Data</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><div id="ContentModalNew"></div></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div>');
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        $.ajax({
            url: 'project/CostTracking/ModalAddManageOTSCost.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ModalNewManageOTSCost").modal("show");
                $("#ContentModalNew").html("");
            },
            success: function (xaxa) {
                $("#ContentModalNew").html("");
                $("#ContentModalNew").html(xaxa);
                $("#ContentModalNew").fadeIn('fast');
                $("#TextVPartDesc").css("resize", "none");
                $("#TextVUnitCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVUnitCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVQtyUsage").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVQtyUsage").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVTotalCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVTotalCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#BtnFindPartNo").click(function () {
                    $("#ModalPartNoList").modal("show");
                    FIND_PARTNO();
                });
                ADD_DATA();
            },
            error: function () {
                $("#ModalNewManageOTSCost").on('hide.bs.modal', function () {
                    $("#TemporarySpace").html("");
                });
            }
        });
    });
}
function FIND_PARTNO()
{
    $("#BtnSearchPartNo").click(function () {
        $("#ContentModalPartNo").html("");
        var Category = $("#FilterTypeCategoryFind").val().trim();
        var Keywords = $("#FilterInputKeywordPartno").val().trim();
        if (Keywords == "") {
            $("#FilterInputKeywordPartno").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append("Category", Category);
        formdata.append("Keywords", Keywords);
        $.ajax({
            url: 'project/PPIC/ModalFindPartNo.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnSearchPartNo").attr('disabled', true);
                $("#ContentModalPartNo").html("");
                $("#ContentModalPartNo").append('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoadPartNo"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $("#ContentModalPartNo").html("");
                $("#ContentModalPartNo").html(xaxa);
                $("#ContentModalPartNo").fadeIn('fast');
                $("#LoadingLoadPartNo").remove();
                $("#TablePartNo").dataTable();
                $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
                $("#TablePartNo > tbody").css("font-size", "12px");
                $("#BtnSearchPartNo").attr('disabled', false);
                $("#TablePartNo").on("click", "tbody .BtnSelect", function () {
                    var ValPartNo = $(this).closest("tr").find("td:eq(1)").html();
                    var ValDesc = $(this).closest("tr").find("td:eq(2)").html();
                    var ValUnitCost = $(this).closest("tr").find("td:eq(5)").html();
                    ValUnitCost = parseFloat(ValUnitCost).toFixed(2);
                    $("#TextVPartNo").val(ValPartNo);
                    $("#TextVPartDesc").val(ValDesc);
                    $("#TextVUnitCost").val(ValUnitCost);
                    $("#TextVQtyUsage").val("0");
                    var TotalCost = 0 * ValUnitCost;
                    $("#TextVTotalCost").val(TotalCost);
                    $("#FilterTypeCategoryFind").prop("selectedIndex", 0);
                    $("#FilterInputKeywordPartno").val("");
                    $("#ContentModalPartNo").html("");
                    $("#ModalContentInfo").html("");
                    $("#BtnClose").click();
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ContentModalPartNo").html("");
                $("#BtnSearchPartNo").attr('disabled', false);
            }
        });
    });
    $('#ModalPartNoList').on('hide.bs.modal', function () {
        $("#FilterTypeCategoryFind").prop("selectedIndex", 0);
        $("#FilterInputKeywordPartno").val("");
        $("#ContentModalPartNo").html("");
    });
}
function ADD_DATA()
{
    $("#TextVQtyUsage").on("focus", function (){
        $("#TextVQtyUsage").select();
    });
    $("#TextVQtyUsage").keyup(function (e) {
        $("#TextVQtyUsage").val().trim();
        var DataQtyUsage = $("#TextVQtyUsage").val().trim();
        var DataUnitCost = $("#TextVUnitCost").val().trim();
        if (DataUnitCost != "")
        {
            var TotalCost = parseInt(DataQtyUsage) * DataUnitCost;
            TotalCost = parseFloat(TotalCost).toFixed(2);
            if (DataQtyUsage == "") {
                $("#TextVTotalCost").val("0");
            }
            else {
                $("#TextVTotalCost").val(TotalCost);
            }
        }
    });
    $("#BtnNewOTSCost").click(function(){
        var DataID = $(this).data("token");
        var DataQuote = $("#TextVQuote").val().trim();
        var DataCategory = $("#TextVCategory").val().trim();
        var DataHalf = $("#TextVHalf").val().trim();
        var DataExpense = $("#TextVExpense").val().trim();
        var DataPartNo = $("#TextVPartNo").val().trim();
        var DataPartDesc = $("#TextVPartDesc").val().trim();
        var DataUnitCost = $("#TextVUnitCost").val().trim();
        var DataQtyUsage = $("#TextVQtyUsage").val().trim();
        var DataTotalCost = $("#TextVTotalCost").val().trim();
        if (DataQtyUsage == "") {
            $("#TextVQtyUsage").focus();
            return false;
        }
        if (DataPartNo == "") {
            $("#TextVPartNo").focus();
            return false;
        }
        if (DataUnitCost == "") {
            $("#TextVUnitCost").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        formdata.append("DataQuote", DataQuote);
        formdata.append("DataCategory", DataCategory);
        formdata.append("DataHalf", DataHalf);
        formdata.append("DataExpense", DataExpense);
        formdata.append("DataPartNo", DataPartNo);
        formdata.append("DataPartDesc", DataPartDesc);
        formdata.append("DataUnitCost", DataUnitCost);
        formdata.append("DataQtyUsage", DataQtyUsage);
        formdata.append("DataTotalCost", DataTotalCost);
        $.ajax({
            url: 'project/CostTracking/src/srcAddNewDataManageOTSCost.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnNewOTSCost").attr('disabled', true);
                $('#ModalContentInfo').html("");
            },
            success: function (xaxa) {
                $('#ModalContentInfo').html("");
                $('#ModalContentInfo').html(xaxa);
                $('#ModalContentInfo').fadeIn('fast');
                $('#ModalContentInfo').html("");
                $("#BtnNewOTSCost").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ModalContentInfo").html("");
                $("#BtnNewOTSCost").attr('disabled', false);
            }
        });
    });
}
function UPDATEROW()
{
    $("#TableViewData tbody .UpdateOTSCost").on("click", function () {
        var DataID = $(this).closest("td").find(".PointerList").data("datatoken");
        $("#TemporarySpace").html("");
        $("#TemporarySpace").html('<div class="modal fade" id="ModalUpdateOTSCost" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Update OTS Cost</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><div id="ContentModal"></div></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div>');
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        $.ajax({
            url: 'project/CostTracking/ModalUpdateManageOTSCost.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ModalUpdateOTSCost").modal("show");
                $('#ContentModal').html("");
                $("#ContentModal").before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ContentModal').html("");
                $('#ContentModal').hide();
                $('#ContentModal').html(xaxa);
                $('#ContentModal').fadeIn('fast');
                $("#LoadingLoad").remove();
                $("#TextRQtyUsage").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextRQtyUsage").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextRPartDesc").css("resize", "none");
                $("#TextRQtyUsage").keyup(function (e) {
                    $("#TextRQtyUsage").val().trim();
                    var DataQtyUsage = $("#TextRQtyUsage").val().trim();
                    var DataUnitCost = $("#TextRUnitCost").val().trim();
                    if (DataUnitCost != "") {
                        var TotalCost = parseInt(DataQtyUsage) * DataUnitCost;
                        TotalCost = parseFloat(TotalCost).toFixed(2);
                        if (DataQtyUsage == "") {
                            $("#TextRTotalCost").val("0");
                        }
                        else {
                            $("#TextRTotalCost").val(TotalCost);
                        }
                    }
                });
                UPDATE_ROW_ACT();
            },
            error: function () {
                $('#ModalUpdateOTSCost').on('hide.bs.modal', function () {
                    $("#TemporarySpace").html("");
                });
            }
        });
    });
}
function UPDATE_ROW_ACT()
{
    $("#BtnUpdateOTSCost").click(function () {
        var DataID = $(this).data("token");
        var DataQtyUsage = $("#TextRQtyUsage").val().trim();
        var DataTotalCost = $("#TextRTotalCost").val().trim();
        if (DataQtyUsage.trim() == "") {
            $("#TextRQtyUsage").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        formdata.append("DataQtyUsage", DataQtyUsage);
        formdata.append("DataTotalCost", DataTotalCost);
        $.ajax({
            url: 'project/CostTracking/src/srcUpdateDataManageOTSCost.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnUpdateOTSCost").attr('disabled', true);
                $('#ModalContentInfoUpdate').html("");
                $("#ModalContentInfoUpdate").append('Please wait...');
            },
            success: function (xaxa) {
                $('#ModalContentInfoUpdate').html("");
                $('#ModalContentInfoUpdate').html(xaxa);
                $('#ModalContentInfoUpdate').fadeIn('fast');
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ModalContentInfoUpdate").html("");
                $("#BtnUpdateOTSCost").attr('disabled', false);
            }
        });
    });
}
function DELETEROW()
{
    $("#TableViewData tbody .DeleteOTSCost").on("click", function () {
        if (confirm("Are you sure to delete this record?") == true) {
            var DataID = $(this).closest("td").find(".PointerList").data("datatoken");
            var formdata = new FormData();
            formdata.append("DataID", DataID);
            $.ajax({
                url: 'project/CostTracking/src/srcDeleteDataManageOTSCost.php',
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