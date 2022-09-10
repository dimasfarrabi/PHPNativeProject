$(document).ready(function () {
    $('#PointerCopy').on("click", function () {
        var TxtValue = $("#TxtPwd").text();
        var TxtValue2 = $("<input>");
        $("body").append(TxtValue2);
        TxtValue2.val(TxtValue).select();
        document.execCommand("copy");
        TxtValue2.remove();
    })
    $('#PointerCopyUser').on("click", function () {
        var TxtValue = $("#TxtUsr").text();
        var TxtValue2 = $("<input>");
        $("body").append(TxtValue2);
        TxtValue2.val(TxtValue).select();
        document.execCommand("copy");
        TxtValue2.remove();
    })
    $("#DownloadPlugin").on("click",function(){
        window.open('https://sik.formulatrix.com/resources/repository/LocalServiceComponents.exe', '_blank');
    });
})