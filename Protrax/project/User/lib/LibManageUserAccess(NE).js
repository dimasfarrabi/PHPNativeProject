$(document).ready(function () {
    $('#TableDataUser').removeAttr('width').DataTable( {
        "iDisplayLength": 5,
        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        scrollY:        "350px",
        scrollX:        true,
        scrollCollapse: true,
        columnDefs: [
            { width: 60, targets: 1 }
        ],
        fixedColumns: true
    } );
    $(".ActDelete").click(function () {
        return confirm("Are you sure to delete this data?");
    });
    $("#DataGuest").on('show.bs.modal', function (event) {
        var dt = event.relatedTarget;
        var dataid = dt.getAttribute('data-bs-dataid');
        $("#InputID").val(dataid);
        DATA_MODAL();
    });
    $("#AddNewUser").click(function(){
        $("#NewUser").modal("show");
    });
    $("#NewUser").on("hidden.bs.modal", function () {     
        $("#InputNewGuest").val("");
        $("#InputNewUsername").val("");
        $("#InputNewPassword").val("");
        $("#InputLocationCompany")[0].selectedIndex = 0;
        $("#InputTypeUser")[0].selectedIndex = 0;
        $("#InputAccessAdmin2").prop("checked", true);
        $("#InputAccessSecurity2").prop("checked", true);
        $("#InputAccessCostTracking2").prop("checked", true);
        $("#InputAccessProduction2").prop("checked", true);
        $("#InputAccessCCTV2").prop("checked", true);
        $("#ResultMsg").html("");
    });
    $("#BtnNewUser").click(function(){
        if($("#InputNewGuest").val().trim() == "")
        {
            $("#InputNewGuest").focus();
            return false;
        }
        if($("#InputNewUsername").val().trim() == "")
        {
            $("#InputNewUsername").focus();
            return false;
        }
        if($("#InputNewPassword").val().trim() == "")
        {
            $("#InputNewPassword").focus();
            return false;
        }
        NEW_USER();
        return false;
    });
    $("#DataGuest").on("shown.bs.modal",function(){
        $("#BtnUpdateUser").on("click",function(){
            UPDATE_USER(); 
            return false;
        });
    });
    $("#DataKey").on('show.bs.modal', function (event) {
        var dt = event.relatedTarget;
        var dataid = dt.getAttribute('data-bs-dataid');
        $("#InputIDKey").val(dataid);
    });
    $("#DataKey").on("hidden.bs.modal", function () {     
        $("#InputPassword").val("");
        $("#UpdatePassword").html("");
    });
    $("#BtnUpdateKey").on("click",function(){
        KEY_MODAL();
    });
});
function NEW_USER()
{
    var Name = $("#InputNewGuest").val().trim();
    var Username = $("#InputNewUsername").val().trim();
    var Password = $("#InputNewPassword").val().trim();
    var Company = $("#InputLocationCompany").val().trim();
    var Type = $("#InputTypeUser").val().trim();
    var IsAdmin = $("input[name='InputAccessAdmin']:checked").val().trim();
    var MnSecurity = $("input[name='InputAccessSecurity']:checked").val().trim();
    var MnCostTracking = $("input[name='InputAccessCostTracking']:checked").val().trim();
    var MnProduction = $("input[name='InputAccessProduction']:checked").val().trim();
    var MnCCTV = $("input[name='InputAccessCCTV']:checked").val().trim();    
    var MnReport = $("input[name='InputAccessReport']:checked").val().trim();
    var MnPPIC = $("input[name='InputAccessPPIC']:checked").val().trim();
    var MnOprMachCNC = $("input[name='InputAccessOprMachCNC']:checked").val().trim();
    var MnOprMachManual = $("input[name='InputAccessOprMachManual']:checked").val().trim();
    var MnOprFabrication = $("input[name='InputAccessOprFabrication']:checked").val().trim();
    var MnOprFinishing = $("input[name='InputAccessOprFinishing']:checked").val().trim();
    var MnOprQA = $("input[name='InputAccessOprQA']:checked").val().trim();
    var MnOprQC = $("input[name='InputAccessOprQC']:checked").val().trim();
    var MnOprAssembly = $("input[name='InputAccessOprAssembly']:checked").val().trim();
    var MnOprCuttingMaterial = $("input[name='InputAccessOprCuttingMaterial']:checked").val().trim();
    var MnOprPacking = $("input[name='InputAccessOprPacking']:checked").val().trim();
    var MnOprInjection = $("input[name='InputAccessOprInjection']:checked").val().trim();
    var MnWarehouse = $("input[name='InputAccessWarehouse']:checked").val().trim();
    var MnExim = $("input[name='InputAccessExim']:checked").val().trim();
    var MnKAShift = $("input[name='InputAccessKAShift']:checked").val().trim();
    
    var FormDataNew = new FormData();
    FormDataNew.append('ValName', Name);
    FormDataNew.append('ValUsername', Username);
    FormDataNew.append('ValPassword', Password);
    FormDataNew.append('ValCompany', Company);
    FormDataNew.append('ValType', Type);
    FormDataNew.append('ValIsAdmin', IsAdmin);
    FormDataNew.append('ValMnSecurity', MnSecurity);
    FormDataNew.append('ValMnCostTracking', MnCostTracking);
    FormDataNew.append('ValMnProduction', MnProduction);
    FormDataNew.append('ValMnCCTV', MnCCTV);  
    FormDataNew.append('ValMnReport', MnReport);
    FormDataNew.append('ValMnPPIC', MnPPIC);
    FormDataNew.append('ValMnOprMachCNC', MnOprMachCNC);
    FormDataNew.append('ValMnOprMachManual', MnOprMachManual);
    FormDataNew.append('ValMnOprFabrication', MnOprFabrication);
    FormDataNew.append('ValMnOprFinishing', MnOprFinishing);
    FormDataNew.append('ValMnOprQA', MnOprQA);
    FormDataNew.append('ValMnOprQC', MnOprQC);
    FormDataNew.append('ValMnOprAssembly', MnOprAssembly);
    FormDataNew.append('ValMnOprCuttingMaterial', MnOprCuttingMaterial);
    FormDataNew.append('ValMnOprPacking', MnOprPacking);
    FormDataNew.append('ValMnOprInjection', MnOprInjection);
    FormDataNew.append('ValMnWarehouse', MnWarehouse);
    FormDataNew.append('ValMnExim', MnExim);
    FormDataNew.append('ValMnKAShift', MnKAShift);
    
    $.ajax({
        url: 'project/User/src/srcNewUser.php',
        data: FormDataNew,
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        beforeSend: function () {
            $("#ResultMsg").html("");
            if($("#ResultMsgInfo").length != 0)
            {
                $("#ResultMsgInfo").remove();
            }
            $("#BtnNewUser").attr("disabled",true);
            $("#BtnNewUser").after('<div class="text-center justify-content-center"><img src="images/ajax-loader1.gif" id="LoadingLogin" class="load_img"/></div>');
            $("#LoadingLogin").after('<div id="ResultMsg"></div>');
        },
        success: function (xaxa) {
            $("#ResultMsg").hide();
            $("#ResultMsg").html("");
            $("#ResultMsg").html(xaxa);
            $("#ResultMsg").fadeIn('fast');
            $("#LoadingLogin").remove();
            $("#BtnNewUser").attr("disabled",false);
        },
        error: function () {
            $("#BtnNewUser").attr("disabled",false);
            $("#LoadingLogin").remove();
            $("#ResultMsg").html('<br><div class="alert alert-danger fw-bold" id="ResultMsgInfo" role="alert">Request cannot proceed! Try Again!</div>');
        }
    });
    return false;
}
function DATA_MODAL()
{
    $("#ContentDataGuest").html('<div class="text-center justify-content-center"><img src="images/ajax-loader1.gif" id="LoadingLoad" class="load_img"/></div>');
    var DataID = $("#InputID").val();    
    var formdata = new FormData();
    formdata.append('ValID', DataID);
    $.ajax({
        url: 'project/User/ModalEditUser.php',
        data: formdata,
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        beforeSend: function () {
            $("#ContentDataGuest").html("");
            $("#ContentDataGuest").html('<div class="text-center justify-content-center"><img src="images/ajax-loader1.gif" id="LoadingLoad" class="load_img"/></div>');
        },
        success: function (xaxa) {
            $("#ContentDataGuest").hide();
            $("#ContentDataGuest").html("");
            $("#ContentDataGuest").html(xaxa);
            $("#ContentDataGuest").fadeIn('fast');
        },
        error: function () {
            $("#ContentDataGuest").html("");
            $("#ContentDataGuest").html('<div class="alert alert-danger fw-bold" role="alert">Request cannot proceed! Try Again!</div>');
        }
    });    
}
function UPDATE_USER()
{
    var Name = $("#InputNewGuestMod").val().trim();
    var Username = $("#InputNewUsernameMod").val().trim();
    var IsActive = $("input[name='InputActiveMod']:checked").val().trim();
    var Company = $("#InputLocationCompanyMod").val().trim();
    var Type = $("#InputTypeUserMod").val().trim();
    var IsAdmin = $("input[name='InputAccessAdminMod']:checked").val().trim();
    var MnSecurity = $("input[name='InputAccessSecurityMod']:checked").val().trim();
    var MnCostTracking = $("input[name='InputAccessCostTrackingMod']:checked").val().trim();
    var MnProduction = $("input[name='InputAccessProductionMod']:checked").val().trim();
    var MnCCTV = $("input[name='InputAccessCCTVMod']:checked").val().trim(); 
    var MnReport = $("input[name='InputAccessReportMod']:checked").val().trim();
    var MnPPIC = $("input[name='InputAccessPPICMod']:checked").val().trim();
    var MnOprMachCNC = $("input[name='InputAccessOprMachCNCMod']:checked").val().trim();
    var MnOprMachManual = $("input[name='InputAccessOprMachManualMod']:checked").val().trim();
    var MnOprFabrication = $("input[name='InputAccessOprFabricationMod']:checked").val().trim();
    var MnOprFinishing = $("input[name='InputAccessOprFinishingMod']:checked").val().trim();
    var MnOprQA = $("input[name='InputAccessOprQAMod']:checked").val().trim();
    var MnOprQC = $("input[name='InputAccessOprQCMod']:checked").val().trim();
    var MnOprAssembly = $("input[name='InputAccessOprAssemblyMod']:checked").val().trim();
    var MnOprCuttingMaterial = $("input[name='InputAccessOprCuttingMaterialMod']:checked").val().trim();
    var MnOprPacking = $("input[name='InputAccessOprPackingMod']:checked").val().trim();
    var MnOprInjection = $("input[name='InputAccessOprInjectionMod']:checked").val().trim();
    var MnWarehouse = $("input[name='InputAccessOprWarehouseMod']:checked").val().trim();
    var MnExim = $("input[name='InputAccessOprEximMod']:checked").val().trim();
    var MnKAShift = $("input[name='InputAccessOprKAShiftMod']:checked").val().trim();
    
    var FormDataUpdate = new FormData();
    FormDataUpdate.append('ValName', Name);
    FormDataUpdate.append('ValUsername', Username);
    FormDataUpdate.append('ValIsActive', IsActive);
    FormDataUpdate.append('ValCompany', Company);
    FormDataUpdate.append('ValType', Type);
    FormDataUpdate.append('ValIsAdmin', IsAdmin);
    FormDataUpdate.append('ValMnSecurity', MnSecurity);
    FormDataUpdate.append('ValMnCostTracking', MnCostTracking);
    FormDataUpdate.append('ValMnProduction', MnProduction);
    FormDataUpdate.append('ValMnCCTV', MnCCTV);    
    FormDataUpdate.append('ValMnReport', MnReport);
    FormDataUpdate.append('ValMnPPIC', MnPPIC);
    FormDataUpdate.append('ValMnOprMachCNC', MnOprMachCNC);
    FormDataUpdate.append('ValMnOprMachManual', MnOprMachManual);
    FormDataUpdate.append('ValMnOprFabrication', MnOprFabrication);    
    FormDataUpdate.append('ValMnOprFinishing', MnOprFinishing);
    FormDataUpdate.append('ValMnOprQA', MnOprQA);
    FormDataUpdate.append('ValMnOprQC', MnOprQC);
    FormDataUpdate.append('ValMnOprAssembly', MnOprAssembly);
    FormDataUpdate.append('ValMnOprCuttingMaterial', MnOprCuttingMaterial);    
    FormDataUpdate.append('ValMnOprPacking', MnOprPacking);
    FormDataUpdate.append('ValMnOprInjection', MnOprInjection);
    FormDataUpdate.append('ValMnWarehouse', MnWarehouse);
    FormDataUpdate.append('ValMnExim', MnExim);
    FormDataUpdate.append('ValMnKAShift', MnKAShift);
    
    $.ajax({
        url: 'project/User/src/srcUpdateUser.php',
        data: FormDataUpdate,
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        beforeSend: function () {
            $("#ResUpdateMsg").html("");
            $("#BtnUpdateUser").attr("disabled",true);
            $("#BtnUpdateUser").after('<div class="text-center justify-content-center"><img src="images/ajax-loader1.gif" id="LoadingUpdate" class="load_img"/></div>');
            $("#LoadingUpdate").after('<div id="ResUpdateMsg"></div>');
        },
        success: function (xaxa) {
            $("#ResUpdateMsg").hide();
            $("#ResUpdateMsg").html("");
            $("#ResUpdateMsg").html(xaxa);
            $("#ResUpdateMsg").fadeIn('fast');
            $("#LoadingUpdate").remove();
            $("#BtnUpdateUser").attr("disabled",false);
        },
        error: function () {
            $("#BtnUpdateUser").attr("disabled",false);
            $("#LoadingUpdate").remove();
            $("#ResUpdateMsg").html('<br><div class="alert alert-danger fw-bold" id="ResultMsgInfo" role="alert">Request cannot proceed! Try Again!</div>');
        }
    });
    return false;
}
function KEY_MODAL()
{
    var DataID = $("#InputIDKey").val();    
    var DataPassword = $("#InputPassword").val();   
    var formdata = new FormData();
    formdata.append('ValID', DataID);
    formdata.append('ValPassword', DataPassword);    
    $.ajax({
        url: 'project/User/src/srcUpdatePassword.php',
        data: formdata,
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        beforeSend: function () {
            $("#UpdatePassword").html("");
            $("#BtnUpdateKey").attr("disabled",true);
            $("#BtnUpdateKey").after('<div class="text-center justify-content-center"><img src="images/ajax-loader1.gif" id="LoadingUpdatePwd" class="load_img"/></div>');
            $("#LoadingUpdate").after('<div id="UpdatePassword"></div>');
        },
        success: function (xaxa) {
            $("#UpdatePassword").hide();
            $("#UpdatePassword").html("");
            $("#UpdatePassword").html(xaxa);
            $("#UpdatePassword").fadeIn('fast');
            $("#LoadingUpdatePwd").remove();
            $("#BtnUpdateKey").attr("disabled",false);
        },
        error: function () {
            $("#BtnUpdateKey").attr("disabled",false);
            $("#LoadingUpdatePwd").remove();
            $("#UpdatePassword").html('<br><div class="alert alert-danger fw-bold" id="ResultMsgInfo" role="alert">Request cannot proceed! Try Again!</div>');
        }
    });
}