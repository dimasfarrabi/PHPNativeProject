$(document).ready(function () {
    $("#LoadingLogin").hide();
    $("#BtnLogin").click(function(){
        LoginUser();
    });
    $("#TxtUsername").on('keypress', function (e) {
        if (e.which == 13) {
            LoginUser();
        }
    });
    $("#TxtPassword").on('keypress', function (e) {
        if (e.which == 13) {
            LoginUser();
        }
    });
    function LoginUser()
    {
        var UserName = $("#TxtUsername").val().trim();
        var PassWord = $("#TxtPassword").val().trim();
        if (UserName == "") { $("#TxtUsername").focus(); return false; }
        if (PassWord == "") { $("#TxtPassword").focus(); return false; }
        var FormDataLogin = new FormData();
        FormDataLogin.append('User', UserName);
        FormDataLogin.append('Pass', PassWord);
        $.ajax({
            url: 'src/LoginV3.php',
            data: FormDataLogin,
            dataType: 'html',
            cache: false,
            contentType: false,
            processData: false,
            type: "POST",
            beforeSend: function () {
                $("#LoadingLogin").show();
                $('#BtnLogin').blur();
                $('#BtnLogin').attr('disabled', true);
                $('#NotificationLog').html("");
            },
            success: function (xaxa) {
                $("#LoadingLogin").hide();
                $('#NotificationLog').hide();
                $('#NotificationLog').html("");
                $('#NotificationLog').html(xaxa);
                $('#NotificationLog').fadeIn('fast');               
            },
            error: function () {
                $("#LoadingLogin").hide();
                $("#NotificationLog").html('<div class="form-group"><div class="alert alert-danger alert-dismissible text-center" role="alert" ><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Request cannot proceed! Try Again!</div></div>');
                $('#BtnLogin').attr('disabled', false);
            }
        });
    }
});