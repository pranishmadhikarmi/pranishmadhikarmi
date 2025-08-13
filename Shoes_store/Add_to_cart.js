document.addEventListener("DOMContentLoaded", function () {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    updateCartUI();

    // ✅ Event Delegation: Attach event listener to the parent container
    document.querySelector(".product-container").addEventListener("click", function (event) {
        if (event.target.classList.contains("add-to-cart")) {
            addToCart(event.target);
        }
    });
    document.querySelector("#product-container").addEventListener("click", function (event) {
        if (event.target.classList.contains("add-to-cart")) {
            addToCart(event.target);
        }
    });
});

function addToCart(button) {
    let name = button.getAttribute("data-name");
    let price = parseFloat(button.getAttribute("data-price"));
    let image = button.getAttribute("data-image");

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    
    let existingItem = cart.find(item => item.name === name);
    if (existingItem) {
        existingItem.quantity += 1; 
    } else {
        cart.push({ name, price, image, quantity: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartUI();
}

function updateCartUI() {
    let cartContainer = document.getElementById("cart-items");
    let cartCount = document.getElementById("cart-count");
    let totalAmount = document.getElementById("total");

    cartContainer.innerHTML = "";
    let total = 0;
    let totalItems = 0;

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    if (cart.length === 0) {
        cartContainer.innerHTML = "<br><p>Your cart is empty.</p>";
        totalAmount.textContent = "0";
        cartCount.textContent = "0";
        return;
    }

    cart.forEach((item, index) => {
        let itemTotal = item.price * item.quantity;
        total += itemTotal;
        totalItems += item.quantity;

        cartContainer.innerHTML += `
            <div class="cart-item">
                <img src="/image/${item.image}" alt="${item.name}" style="width:50px;height:50px;">
                <span>${item.name}</span>
                <span>Rs ${item.price.toFixed(2)}</span>
                <span>Qty: ${item.quantity}</span>
                <button onclick="decreaseQuantity(${index})">➖</button>
                <button onclick="removeFromCart(${index})">❌</button>
            </div>
        `;
    });

    cartCount.textContent = totalItems;  
    totalAmount.textContent = total.toFixed(2);
}

function decreaseQuantity(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    
    if (cart[index].quantity > 1) {
        cart[index].quantity -= 1;
    } else {
        cart.splice(index, 1); 
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartUI();
}

function removeFromCart(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartUI();
}

function clearCart() {
    localStorage.removeItem("cart");
    updateCartUI();
}
