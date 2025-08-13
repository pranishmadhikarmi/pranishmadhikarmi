function searchShoes() {
    let query = document.getElementById("search").value.trim();
    let productDiv = document.getElementById("product-container");
    let resultsDiv = document.getElementById("search-results");

    if (query === "") {
        resultsDiv.style.display = "none"; // Hide search results
        productDiv.style.display = "grid"; // Show default products
        return;
    }

    fetch('search_shoes.php?q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            resultsDiv.innerHTML = ""; // Clear previous results
            productDiv.style.display = "none"; // Hide default products
            resultsDiv.style.display = "grid"; // Show search results

            if (data.length > 0) {
                data.forEach(item => {
                    let gridContainer = document.createElement("div");
                    gridContainer.classList.add("product-grid"); // Parent div for each product

                    let productCard = document.createElement("div");
                    productCard.classList.add("product_card"); // Individual product card

                    productCard.innerHTML = `
                        <img src="${item.image}" alt="${item.name}" class="product-image" 
                             onerror="this.onerror=null; this.src='../uploads/default.jpg';">
                        <h3 class="product-name">${item.name}</h3>
                        <p class="price">Rs ${item.price}</p>
                        <p class="stock">Stock Left: ${item.quantity}</p>
                        <button class="add-to-cart" 
                                    data-id="${item.id}" 
                                    data-name="${item.name}" 
                                    data-price="${item.price}" 
                                    data-image="${item.image}">
                                    Add to Cart
                        </button>
                    `;

                    gridContainer.appendChild(productCard); // Append product card inside its parent
                    resultsDiv.appendChild(gridContainer); // Append parent container to results div
                });
            } else {
                resultsDiv.innerHTML = `<p class="no-product-found">No product found</p>`; // Style like default UI
            }
        })
        .catch(error => console.error("Error fetching search results:", error));
}

document.addEventListener("DOMContentLoaded", function () {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    updateCartUI();


    document.querySelector("#search-results").addEventListener("click", function (event) {
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
