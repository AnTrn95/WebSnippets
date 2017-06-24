/**
 * Created by TrAn on 06.05.2017.
 */

    function selectAll()
    {
        var selectBox = document.getElementById("pizzen");

        for (var i = 0; i < selectBox.options.length; i++)
        {
            selectBox.options[i].selected = true;
        }
    }

function Pizza(name, price, quantity) {

    return {
        'name': name,
        'price': price,
        'quantity': quantity
    };
}

// toString override added to prototype of Foo class
Object.prototype.toString = function () {
    return this.quantity + "x " + this.name;
};

var pizza_list = [];

/**@function calculate the total sum/ prioe of the order: sum of all pizzas in the cart*/
function calcPrice(selected_pizza) {
    "use strict";

    var current_pizza;
    var list = document.getElementById('pizzen');
    /*calculate total price*/
    var name, price, current_price;

    current_price = parseFloat(document.getElementById('sum').textContent); //cast string to float

    price = parseFloat(selected_pizza.getAttribute('data-price')); //price of selected pizza
    name = selected_pizza.getAttribute('data-name');

    current_pizza = Pizza(name, price, 1);

    /*
    for (var i = 0; i < list.length; i++) {
    console.log(list[i].getAttribute("name")+'  '+ list[i].getAttribute("price") +'   '+list[i].getAttribute("quantity"));
    } */


    /*add current price to cart sum*/
    current_price = parseFloat(current_price + price).toFixed(2);
    document.getElementById('sum').innerText = current_price + '€';


    /*duplicate exists*/
    if (find(pizza_list, current_pizza.name) !== -1) {
        pizza_list[find(pizza_list, current_pizza.name)].quantity++;
        list.options[find(pizza_list, current_pizza.name)].innerText = pizza_list[find(pizza_list, current_pizza.name)];

    } else {
        console.log(pizza_list.indexOf(current_pizza));
        pizza_list.push(current_pizza); //fill array
        var newElem = document.createElement("option");
        newElem.setAttribute('name', current_pizza.name);
        newElem.setAttribute('price', current_pizza.price);
        newElem.setAttribute('quantity', current_pizza.quantity);
        newElem.innerHTML = current_pizza;
        newElem.value = {name: current_pizza.name, price: current_pizza.price, quantity: current_pizza.quantity};
        list.appendChild(newElem);
    }

}

/**@function fill up the cart with the selected pizzas*/
function find(list, name) {
    "use strict";

    for (var i = 0; i < list.length; i++) {
        if (list[i].name === name) return i;
    }
    return -1;
}

/** @function delete all pizzas in the cart*/
function delete_all() {
    "use strict";
    var list = document.getElementById('pizzen');

    for (var i = list.options.length - 1; i >= 0; i--) {
        list[i].remove();
        pizza_list.splice(i,1);
    }
    document.getElementById('sum').innerText = '0€';
}


/** @function delete only (the) selected pizza(s) in the cart*/
function delete_selection() {
    "use strict";
    var list = document.getElementById('pizzen');

    var quantity = 0;
    var current_price = 0;
    var name = '';
    var sum = 0;
    for (var i = list.options.length - 1; i >= 0; i--) {
        quantity = pizza_list[i].quantity;
        name = pizza_list[i].name;
        current_price = pizza_list[i].price;
        sum = parseFloat(document.getElementById('sum').innerText).toFixed(2);
        // subtract price of selected pizzas
        if (list[i].selected) {
            sum -= current_price;
            document.getElementById('sum').innerText = sum + '€';
        }

        if (list[i].selected && quantity === 1) {
            list[i].remove(); //remove if selected and pizza quantity=1
            pizza_list.splice(i, 1);
            console.log(pizza_list.length);
        }
        else if (list[i].selected) {
            pizza_list[i].quantity--;
            list.options[i].innerText = pizza_list[i]; //change the displayed quantity of the existing pizza in the cart
        }
    }
}
