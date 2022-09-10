$(document).ready(function () {
    LOAD_WO_LIST();
    $("#FilterClosedTime").on("change", function (e) {
        LOAD_WO_LIST();
    });
    $("#FilterQuoteCategory").on("change", function (e) {
        LOAD_WO_LIST();
    });
    $("#BtnViewData").click(function () {
        var Half = $("#FilterClosedTime").val().trim();
        var Category = $("#FilterQuoteCategory").val().trim();
        var Quote = $("#FilterQuote").val().trim();
        var formdata = new FormData();
        formdata.append("Half", Half);
        formdata.append("Category", Category);
        formdata.append("Quote", Quote);
        $.ajax({
            url: 'project/CostTracking/ContentManagePeriodicQuoteCost.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#FilterClosedTime").attr("disabled", true);
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
                $("#FilterQuoteCategory").attr("disabled", false);
                $("#FilterQuote").attr("disabled", false);
                $("#BtnViewData").attr("disabled", false);
                $("#TableViewData").dataTable({
                    "scrollX": true,
                    "scrollCollapse": true,
                    "columnDefs": [
                        { width: '100px', targets: [1, 5] },
                        { width: '50px', targets: [3, 4] },
                        { width: '150px', targets: [2] }
                    ],
                    scrollCollapse: true,
                    paging: false,
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
                $("#FilterQuoteCategory").attr("disabled", false);
                $("#FilterQuote").attr("disabled", false);
                $("#BtnViewData").attr("disabled", false);
            }
        });
    });
});
function LOAD_WO_LIST()
{
    var Half = $("#FilterClosedTime").val().trim();
    var Category = $("#FilterQuoteCategory").val().trim();
    var formdata = new FormData();
    formdata.append("Half", Half);
    formdata.append("Category", Category);
    $.ajax({
        url: 'project/CostTracking/ListWOPeriodicQuoteCost.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("#FilterClosedTime").attr("disabled", true);
            $("#FilterQuoteCategory").attr("disabled", true);
            $("#FilterQuote").attr("disabled", true);
            $("#BtnViewData").attr("disabled", true);
        },
        success: function (xaxa) {
            $('#FilterQuote').html(xaxa);
            $("#FilterClosedTime").attr("disabled", false);
            $("#FilterQuoteCategory").attr("disabled", false);
            $("#FilterQuote").attr("disabled", false);
            $("#BtnViewData").attr("disabled", false);
        },
        error: function () {
            $("#FilterClosedTime").attr("disabled", false);
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
        $("#TemporarySpace").html('<div class="modal fade" id="ModalNewPeriodicQuoteCost" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Form Tambah Data</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><div id="ContentModalNew"></div></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div>');
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        $.ajax({
            url: 'project/CostTracking/ModalAddPeriodicQuoteCost.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ModalNewPeriodicQuoteCost").modal("show");
                $("#ContentModalNew").html("");
            },
            success: function (xaxa) {
                $("#ContentModalNew").html("");
                $("#ContentModalNew").hide();
                $("#ContentModalNew").html(xaxa);
                $("#ContentModalNew").fadeIn('fast');

                $("#TextAQtyQuote").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextAQtyQuote").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextAQtyTarget").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextAQtyTarget").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextATargetPeopleCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextATargetPeopleCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextAPeopleCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextAPeopleCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextATargetMachineCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextATargetMachineCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextAMachineCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextAMachineCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextATargetMaterialCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextATargetMaterialCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextAMaterialCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextAMaterialCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextAQtyQCIn").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextAQtyQCIn").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextAQtyQCOut").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextAQtyQCOut").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextATotalTargetCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextATotalTargetCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextATotalActualCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextATotalActualCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextATotalTargetCostAndTargetQty").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextATotalTargetCostAndTargetQty").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextATotalTargetCostAndActualQty").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextATotalTargetCostAndActualQty").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextATotalActualCostAndActualQty").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextATotalActualCostAndActualQty").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#BtnAddPeriodic").click(function(){
                    var Quote = $("#TextAQuote").val().trim();
                    var PM = $("#TextAPM").val().trim();
                    var DM = $("#TextADM").val().trim();
                    var Category = $("#TextACategory").val().trim();
                    var Half = $("#TextAHalf").val().trim();
                    var SortNumber = $("#TextAExpense").val().trim();
                    var Expense = $("#TextAExpense option:selected").text().trim();
                    var QtyQuote = $("#TextAQtyQuote").val().trim();
                    var QtyTarget = $("#TextAQtyTarget").val().trim();
                    var TargetPeopleCost = $("#TextATargetPeopleCost").val().trim();
                    var PeopleCost = $("#TextAPeopleCost").val().trim();
                    var TargetMachineCost = $("#TextATargetMachineCost").val().trim();
                    var MachineCost = $("#TextAMachineCost").val().trim();
                    var TargetMaterialCost = $("#TextATargetMaterialCost").val().trim();
                    var MaterialCost = $("#TextAMaterialCost").val().trim();
                    var QtyQCIn = $("#TextAQtyQCIn").val().trim();
                    var QtyQCOut = $("#TextAQtyQCOut").val().trim();
                    var TotalTargetCost = $("#TextATotalTargetCost").val().trim();
                    var TotalActualCost = $("#TextATotalActualCost").val().trim();
                    var TotalTargetCostAndTargetQty = $("#TextATotalTargetCostAndTargetQty").val().trim();
                    var TotalTargetCostAndActualQty = $("#TextATotalTargetCostAndActualQty").val().trim();
                    var TotalActualCostAndActualQty = $("#TextATotalActualCostAndActualQty").val().trim();
                    if (PM == "-- Pilih PM --") {
                        $("#TextAPM").focus();
                        return false;
                    }
                    if (DM == "-- Pilih DM --") {
                        $("#TextADM").focus();
                        return false;
                    }
                    if (QtyQuote == "") {
                        $("#TextAQtyQuote").focus();
                        return false;
                    }
                    if (QtyTarget == "") {
                        $("#TextAQtyTarget").focus();
                        return false;
                    }
                    if (TargetPeopleCost == "") {
                        $("#TextATargetPeopleCost").focus();
                        return false;
                    }
                    if (PeopleCost == "") {
                        $("#TextAPeopleCost").focus();
                        return false;
                    }
                    if (TargetMachineCost == "") {
                        $("#TextATargetMachineCost").focus();
                        return false;
                    }
                    if (MachineCost == "") {
                        $("#TextAMachineCost").focus();
                        return false;
                    }
                    if (TargetMaterialCost == "") {
                        $("#TextATargetMaterialCost").focus();
                        return false;
                    }
                    if (MaterialCost == "") {
                        $("#TextAMaterialCost").focus();
                        return false;
                    }
                    if (QtyQCIn == "") {
                        $("#TextAQtyQCIn").focus();
                        return false;
                    }
                    if (QtyQCOut == "") {
                        $("#TextAQtyQCOut").focus();
                        return false;
                    }
                    if (TotalTargetCost == "") {
                        $("#TextATotalTargetCost").focus();
                        return false;
                    }
                    if (TotalActualCost == "") {
                        $("#TextATotalActualCost").focus();
                        return false;
                    }
                    if (TotalTargetCostAndTargetQty == "") {
                        $("#TextATotalTargetCostAndTargetQty").focus();
                        return false;
                    }
                    if (TotalTargetCostAndActualQty == "") {
                        $("#TextATotalTargetCostAndActualQty").focus();
                        return false;
                    }
                    if (TotalActualCostAndActualQty == "") {
                        $("#TextATotalActualCostAndActualQty").focus();
                        return false;
                    }
                    
                    var formdata = new FormData();
                    formdata.append("PM", PM);
                    formdata.append("DM", DM);
                    formdata.append("Quote", Quote);
                    formdata.append("Category", Category);
                    formdata.append("Half", Half);
                    formdata.append("SortNumber", SortNumber);
                    formdata.append("Expense", Expense);
                    formdata.append("QtyQuote", QtyQuote);
                    formdata.append("QtyTarget", QtyTarget);
                    formdata.append("TargetPeopleCost", TargetPeopleCost);
                    formdata.append("PeopleCost", PeopleCost);
                    formdata.append("TargetMachineCost", TargetMachineCost);
                    formdata.append("MachineCost", MachineCost);
                    formdata.append("TargetMaterialCost", TargetMaterialCost);
                    formdata.append("MaterialCost", MaterialCost);
                    formdata.append("QtyQCIn", QtyQCIn);
                    formdata.append("QtyQCOut", QtyQCOut);
                    formdata.append("TotalTargetCost", TotalTargetCost);
                    formdata.append("TotalActualCost", TotalActualCost);
                    formdata.append("TotalTargetCostAndTargetQty", TotalTargetCostAndTargetQty);
                    formdata.append("TotalTargetCostAndActualQty", TotalTargetCostAndActualQty);
                    formdata.append("TotalActualCostAndActualQty", TotalActualCostAndActualQty);
                    $.ajax({
                        url: 'project/CostTracking/src/srcAddNewPeriodicQuoteCost.php',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formdata,
                        type: 'post',
                        beforeSend: function () {
                            $("#BtnAddPeriodic").attr("disabled", true);
                            $("#ModalContentInfoAdd").html("");
                            $("#ModalContentInfoAdd").append('<div class="col-sm-12 d-flex justify-content-center" id="LoadListInfoAdd"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        },
                        success: function (xaxa) {
                            $("#ModalContentInfoAdd").html("");
                            $("#ModalContentInfoAdd").html(xaxa);
                            $("#ModalContentInfoAdd").fadeIn("fast");
                            $("#LoadListInfoAdd").remove();
                            $("#BtnAddPeriodic").attr("disabled", false);
                        },
                        error: function () {
                            $("#ModalContentInfoAdd").html("");
                            $("#LoadListInfoAdd").remove();
                            $("#BtnAddPeriodic").attr("disabled", false);
                        }
                    });
                });
            },
            error: function () {
                $("#ModalNewPeriodicQuoteCost").on('hide.bs.modal', function () {
                    $("#TemporarySpace").html("");
                });
            }
        });
    });
}
function UPDATEROW()
{
    $("#TableViewData tbody .UpdatePeriodic").on("click", function () {
        var DataID = $(this).closest("td").find(".PointerList").data("datatoken");
        $("#TemporarySpace").html("");
        $("#TemporarySpace").html('<div class="modal fade" id="ModalUpdatePeriodicQuoteCost" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" style="color:#000;">Form Update Data</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><div id="ContentModal" style="color:#000;"></div></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div>');
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        $.ajax({
            url: 'project/CostTracking/ModalUpdatePeriodicQuoteCost.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ModalUpdatePeriodicQuoteCost").modal("show");
                $("#ContentModal").html("");
                $("#ContentModal").append('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad1"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $("#ContentModal").html("");
                $("#ContentModal").hide();
                $("#ContentModal").html(xaxa);
                $("#ContentModal").fadeIn("fast");
                $("#LoadingLoad1").remove();
                $("#TextVQtyQuote").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVQtyQuote").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVQtyTarget").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVQtyTarget").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });                
                $("#TextVTargetPeopleCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVTargetPeopleCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVPeopleCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVPeopleCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVTargetMachineCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVTargetMachineCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVMachineCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVMachineCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVTargetMaterialCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVTargetMaterialCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVMaterialCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVMaterialCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVQtyQCIn").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVQtyQCIn").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVQtyQCOut").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVQtyQCOut").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVTotalTargetCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVTotalTargetCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVTotalActualCost").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVTotalActualCost").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVTotalTargetCostAndTargetQty").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVTotalTargetCostAndTargetQty").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVTotalTargetCostAndActualQty").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVTotalTargetCostAndActualQty").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#TextVTotalActualCostAndActualQty").keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TextVTotalActualCostAndActualQty").on("input", function (e) {
                    $(this).val(function (i, v) {
                        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                            e.preventDefault();
                        }
                        return v.replace(/[^\d.]|\.(?=.*\.)/g, '');
                    });
                });
                $("#BtnEditPeriodic").click(function () {
                    var DataID = $(this).data("token");
                    var PM = $("#TextVPM").val().trim();
                    var DM = $("#TextVDM").val().trim();
                    var QtyQuote = $("#TextVQtyQuote").val().trim();
                    var QtyTarget = $("#TextVQtyTarget").val().trim();
                    var TargetPeopleCost = $("#TextVTargetPeopleCost").val().trim();
                    var PeopleCost = $("#TextVPeopleCost").val().trim();
                    var TargetMachineCost = $("#TextVTargetMachineCost").val().trim();
                    var MachineCost = $("#TextVMachineCost").val().trim();
                    var TargetMaterialCost = $("#TextVTargetMaterialCost").val().trim();
                    var MaterialCost = $("#TextVMaterialCost").val().trim();
                    var QtyQCIn = $("#TextVQtyQCIn").val().trim();
                    var QtyQCOut = $("#TextVQtyQCOut").val().trim();
                    var TotalTargetCost = $("#TextVTotalTargetCost").val().trim();
                    var TotalActualCost = $("#TextVTotalActualCost").val().trim();
                    var TotalTargetCostNTargetQty = $("#TextVTotalTargetCostAndTargetQty").val().trim();
                    var TotalTargetCostNActualQty = $("#TextVTotalTargetCostAndActualQty").val().trim();
                    var TotalActualCostNActualQty = $("#TextVTotalActualCostAndActualQty").val().trim();
                    var formdata = new FormData();
                    formdata.append("DataID", DataID);
                    formdata.append("PM", PM);
                    formdata.append("DM", DM);
                    formdata.append("QtyQuote", QtyQuote);
                    formdata.append("QtyTarget", QtyTarget);
                    formdata.append("TargetPeopleCost", TargetPeopleCost);
                    formdata.append("PeopleCost", PeopleCost);
                    formdata.append("TargetMachineCost", TargetMachineCost);
                    formdata.append("MachineCost", MachineCost);
                    formdata.append("TargetMaterialCost", TargetMaterialCost);
                    formdata.append("MaterialCost", MaterialCost);
                    formdata.append("QtyQCIn", QtyQCIn);
                    formdata.append("QtyQCOut", QtyQCOut);
                    formdata.append("TotalTargetCost", TotalTargetCost);
                    formdata.append("TotalActualCost", TotalActualCost);
                    formdata.append("TotalTargetCostNTargetQty", TotalTargetCostNTargetQty);
                    formdata.append("TotalTargetCostNActualQty", TotalTargetCostNActualQty);
                    formdata.append("TotalActualCostNActualQty", TotalActualCostNActualQty);
                    $.ajax({
                        url: 'project/CostTracking/src/srcUpdateNewPeriodicQuoteCost.php',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formdata,
                        type: 'post',
                        beforeSend: function () {
                            $("#BtnEditPeriodic").attr("disabled", true);
                            $("#ModalContentInfo").html("");
                            $("#ModalContentInfo").append('<div class="col-sm-12 d-flex justify-content-center" id="LoadListInfo"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        },
                        success: function (xaxa) {
                            $("#ModalContentInfo").html("");
                            $("#ModalContentInfo").html(xaxa);
                            $("#ModalContentInfo").fadeIn("fast");
                            $("#LoadListInfo").remove();
                            $("#BtnEditPeriodic").attr("disabled", false);
                        },
                        error: function () {
                            $("#ModalContentInfo").html("");
                            $("#LoadListInfo").remove();
                            $("#BtnEditPeriodic").attr("disabled", false);
                        }
                    });
                });
            },
            error: function () {
                $("#ModalUpdatePeriodicQuoteCost").on("hide.bs.modal", function () {
                    $("#TemporarySpace").html("");
                });
            }
        });
        
    });
}
function DELETEROW()
{
    $("#TableViewData tbody .DeletePeriodic").on("click", function () {
        if (confirm("Are you sure to delete this record?") == true) {
            var DataID = $(this).closest("td").find(".PointerList").data("datatoken");
            var formdata = new FormData();
            formdata.append("DataID", DataID);
            $.ajax({
                url: 'project/CostTracking/src/srcDeleteNewPeriodicQuoteCost.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("#TemporarySpace").html("");
                },
                success: function (xaxa) {
                    $("#TemporarySpace").html("");
                    $("#TemporarySpace").hide();
                    $("#TemporarySpace").html(xaxa);
                    $("#TemporarySpace").fadeIn('fast');
                    var ValueRes = $("#TemporarySpace").text();
                    if (ValueRes == "TRUE")
                    {
                        $("#TemporarySpace").html("");
                        var $row = $("#TableViewData tr [data-datatoken='" + DataID + "']").closest('tr');
                        $($row).remove();
                    }
                    else
                    {
                        alert("Delete error!");
                        $("#TemporarySpace").html("");
                    }
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#TemporarySpace").html("");
                }
            });
        }
    });
}