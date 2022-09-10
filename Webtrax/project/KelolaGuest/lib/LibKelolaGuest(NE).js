$(document).ready(function () {
    $("#TableGuest").dataTable({
        "pagingType": "simple"
    });
    $(".ActActive").click(function () {
        return confirm("Are you sure to update status this data?");
    });
    $(".ActDelete").click(function () {
        return confirm("Are you sure to delete this data?");
    });
    $("#BtnUpdate").click(function () {
        var NewPassword = $("#InputPassword").val().trim();
        var InputID = $("#InputID").val().trim();
        var formdata = new FormData();
        formdata.append('ValID', InputID);
        formdata.append('ValNew', NewPassword);
        $.ajax({
            url: 'project/kelolaguest/src/srcupdatepasswordguest.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#UpdatePassword').html("");
            },
            success: function (xaxa) {
                $('#UpdatePassword').html("");
                $('#UpdatePassword').hide();
                $('#UpdatePassword').html(xaxa);
                $('#UpdatePassword').fadeIn('fast');
            },
            error: function () {
                alert("Request cannot proceed! Try Again!");
            }
        });
        $("#InputPassword").attr("disabled", true);
        $("#BtnUpdate").attr("disabled",true);
        location.reload();
    }); 
    $("#DataGuest").on('show.bs.modal', function (event) {
        var dt = $(event.relatedTarget);
        var dataid = dt.data('dataid');
        $("#InputID").val(dataid);
    });
});