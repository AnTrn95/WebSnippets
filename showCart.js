/**
 * Created by TrAn on 22.05.2017.
 */


window.onresize = function(event)
{
    document.location.reload(true);
}

function showCart() {
    var cart_icon = document.getElementById("warenkorb-icon");
    var cart = document.getElementById("warenkorb");
    var blurry_bg = document.getElementById("blur-bg");
    var list_bg = document.getElementById("pizza-list");
    cart.style.visibility = "visible";
    cart.style.transform = "translate(0,0)";
    blurry_bg.style.display = "inline-block";
    list_bg.style.filter = "blur(5px)";
    cart_icon.style.visibility = "hidden";
    /*close modal when bg is clicked*/
    blurry_bg.onclick = function ()
    {
        cart.style.transform = "translate(0,-100%)";
        cart.style.visibility = "hidden";
        blurry_bg.style.display = "none";
        list_bg.style.filter = "blur(0)";
        cart_icon.style.visibility = "visible";
    };
}