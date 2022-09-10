
$(document).ready(function () {
    $("#closebtn").click(function(){
        closeNav();
    });
    $("#menuNav").click(function(){
        openNav();
    });
});
function openNav() {
    document.getElementById("menuSide").style.width = "300px";
    document.getElementById("menuNav").style.display = "none";
    document.getElementById("menuNav").style.transition = "1s";
    document.getElementById("menuSide").style.overflowY = "visible";
}

function closeNav() {
    document.getElementById("menuSide").style.width = "0";
    document.getElementById("menuNav").style.display = "inline";
    document.getElementById("menuNav").style.transition = "1.5s";
    document.getElementById("menuSide").style.overflowY = "hidden";
}
window.addEventListener('click', function(e){
    if(document.getElementById("menuSide").style.width != "0"){
        if (!document.getElementById('menuNav').contains(e.target)){
            closeNav();
        }
   }    
})

//-----------------------

$(document).ready(function () {
    $("#closebtn").click(function(){
        closeNav();
    });
    $("#menuNav").click(function(){
        openNav();
    });
});
function openNav() {
    document.getElementById("menuSide").style.width = "300px";
    document.getElementById("menuNav").style.display = "none";
    document.getElementById("menuNav").style.transition = "1s";
    document.getElementById("menuSide").style.overflowY = "visible";
}

function closeNav() {
    document.getElementById("menuSide").style.width = "0";
    document.getElementById("menuNav").style.display = "inline";
    document.getElementById("menuNav").style.transition = "1.5s";
    document.getElementById("menuSide").style.overflowY = "hidden";
}
window.addEventListener('click', function(e){
    if(document.getElementById("menuSide").style.width != "0"){
        if (!document.getElementById('menuNav').contains(e.target)){
            closeNav();
        }
    }    
})