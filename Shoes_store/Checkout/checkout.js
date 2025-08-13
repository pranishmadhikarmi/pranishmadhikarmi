document.addEventListener("DOMContentLoaded", function () {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let cartSummary = document.getElementById("cart-summary");
    let totalPriceElement = document.getElementById("total-price");
    let placeOrderBtn = document.getElementById("place-order");
    let codCheckbox = document.getElementById("cod");
    let total = 0;

    if (cart.length === 0) {
        cartSummary.innerHTML = "<p>Your cart is empty.</p>";
    } else {
        cartSummary.innerHTML = cart.map(item => {
            let itemTotal = item.price * item.quantity;
            total += itemTotal;
            return `
                <div class="cart-item">
                    <img src="/image/${item.image}" alt="${item.name}" style="width:50px; height:50px;">
                    <span>${item.name} (x${item.quantity})</span>
                    <span>Rs ${itemTotal.toFixed(2)}</span>
                </div>
            `;
        }).join("");
    }

    totalPriceElement.textContent = total.toFixed(2);

    // ✅ Enable/Disable Place Order button based on COD selection
    codCheckbox.addEventListener("change", function () {
        if (codCheckbox.checked) {
            placeOrderBtn.classList.add("enabled");
            placeOrderBtn.removeAttribute("disabled");
        } else {
            placeOrderBtn.classList.remove("enabled");
            placeOrderBtn.setAttribute("disabled", "true");
        }
    });

    // ✅ Handle Checkout Submission
    document.getElementById("checkout-form").addEventListener("submit", function (e) {
        e.preventDefault();

        let name = document.getElementById("name").value;
        let email = document.getElementById("email").value;
        let address = document.getElementById("address").value;
        let phone = document.getElementById("phone").value;

        if (!name || !email || !address || !phone) {
            alert("Please fill in all details.");
            return;
        }

        if (!codCheckbox.checked) {
            alert("You must select 'Cash on Delivery' to proceed.");
            return;
        }

        let cartData = JSON.stringify(cart);

        fetch("place_order.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "cart_data=" + encodeURIComponent(cartData)
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            localStorage.removeItem("cart"); // ✅ Clear cart only after order success
            window.location.href = "index.php"; // Redirect to success page
        })
        .catch(error => console.error("Error:", error));
    });
});
