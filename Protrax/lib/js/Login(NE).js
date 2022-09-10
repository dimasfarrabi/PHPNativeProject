$(document).ready(function () {
    $("#BtnLogin").click(function(){
        if($("#TxtUsername").val().trim() == "")
        {
            $("#TxtUsername").focus();
            return false;
        }
        if($("#TxtPassword").val().trim() == "")
        {
            $("#TxtPassword").focus();
            return false;
        }
        LoginUser();
        return false;
    });
});
function LoginUser()
{
    var UserName = $("#TxtUsername").val().trim();
    var PassWord = $("#TxtPassword").val().trim();
    var FormDataLogin = new FormData();
    FormDataLogin.append('User', UserName);
    FormDataLogin.append('Pass', PassWord);
    $.ajax({
        url: 'src/Login.php',
        data: FormDataLogin,
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        beforeSend: function () {
            if($("#InfoLogin").length != 0)
            {
                $("#InfoLogin").remove();
            }
            $("#TxtUsername").attr("disabled",true);
            $("#TxtPassword").attr("disabled",true);
            $("#BtnLogin").attr("disabled",true);
            $("#BtnLogin").css("background-color","#525c77");
            $("#BtnLogin").after('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="LoadingLogin"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingLogin">Loading...</span ></div></div>');
            $("#LoadingLogin").after('<div id="NotificationLog"></div>');
        },
        success: function (xaxa) {
            $("#LoadingLogin").hide();
            $("#NotificationLog").hide();
            $("#NotificationLog").html("");
            $("#NotificationLog").html(xaxa);
            $("#NotificationLog").fadeIn('fast');
            $("#LoadingLogin").remove();
        },
        error: function () {
            $("#TxtUsername").attr("disabled",false);
            $("#TxtPassword").attr("disabled",false);
            $("#BtnLogin").attr("disabled",false);
            $("#LoadingLogin").remove();
            $("#NotificationLog").html('<br><div class="alert alert-danger fw-bold" id="InfoLogin" role="alert">Request cannot proceed! Try Again!</div>');
        }
    });
}