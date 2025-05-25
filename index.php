<?php
include('connection.php');
session_start();
?>

<html>

<head>
  <title>FOOT FIND</title>
  <link rel="icon" type="image/x-icon" href="logo2.png">
  <link rel="stylesheet" href="styles.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
<section id="home" class="hero">

  <header>
    <a href="index.php">
      <img src="/image/logo.png" alt="FootFind Logo">
    </a>
    <div class="search-navbar">
      <div class="search-container">
        <input type="text" id="search" placeholder="Search shoes..." onkeyup="searchShoes()">
      </div>
    </div>

    <nav>
      <a href="#home">HOME</a>
      <a href="#product">PRODUCT</a>
      <a href="#contact">CONTACT</a>
      <a href="view_Order.php" onclick="return checkLogin()">VIEW ORDER</a>
    </nav>

    <script>
      function checkLogin() {
        let userLoggedIn = <?php echo isset($_SESSION['logged_in']) ? 'true' : 'false'; ?>;
        if (!userLoggedIn) {
          alert("Please log in first!");
          return false;
        }
        window.location.href = "view_Order.php";
        return true;
      }

      function confirmLogout() {
        return confirm("Are you sure you want to log out?");
      }
    </script>

    <div class="right-actions">
      <?php
      if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        echo "
            <div class='user'>
            $_SESSION[username] - <a href='logout.php' onclick='return confirmLogout();'>LOGOUT</a> 
           
            </div>";
            
      } else {
        echo "
            <div class='sign-in-up'>
              <button type='button' onclick=\"popup('login-popup')\">LOGIN</button>
            </div>";
      }
      ?>

      <div class="cart-icon" id="cart-icon">
        <i class="bx bx-shopping-bag"></i>
        <span id="cart-count">0</span>
      </div>
    </div>
  </header>

  <div id="shoppingcart" class="shoppingcart">
    <div class="cart-header">
      <h1>Shopping Cart</h1>
    </div>

    <div class="cart-modal" id="cart-modal">
      <div id="cart-items">
        <!-- Cart items will be loaded here -->
      </div>

      <div class="cart-total">
        <br><strong>Total: Rs <span id="total">0</span></strong>
      </div>
      <div class="cart-actions">
        <br>
        <button onclick="clearCart()" class="clear-cart">Clear</button>
        <button onclick="checkout()" class="checkout">Checkout</button>

        <script>
          function popup(popupId) {
            document.getElementById(popupId).classList.add('active');
          }

          function closePopup(popupId) {
            document.getElementById(popupId).classList.remove('active');
          }

          function checkout() {
            var isLoggedIn = <?php echo isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true ? 'true' : 'false'; ?>;
            let cart = JSON.parse(localStorage.getItem("cart")) || [];

            if (cart.length === 0) {
              alert("Your cart is empty!");
              return;
            }

            if (!isLoggedIn) {
              document.getElementById('login-popup').classList.add('active');
              return;
            }

            console.log("Checking out:", cart); 
            fetch('Checkout/place_order.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ cart })
})
.then(response => {
    return response.text().then(text => {
        try {
            return JSON.parse(text);  
        } catch (err) {
            throw new Error("Invalid JSON: " + text);  
        }
    });
})
.then(data => {
    console.log("Server Response:", data);
    if (data.success) {
        alert("Order placed successfully!");
        localStorage.removeItem("cart");
        document.getElementById("cart-items").innerHTML = "<p>Your cart is empty.</p>";
        document.getElementById("total").textContent = "0";
    } else {
        alert("Error placing order: " + data.error);
    }
})
.catch(error => console.error("Fetch Error:", error));

          }
        </script>
      </div>
    </div>
  </div>

  <script>
    document.getElementById("cart-icon").addEventListener("click", function() {
      document.getElementById("shoppingcart").classList.toggle("active");
      let cart = document.getElementById("shoppingcart");
      if (cart.style.display === "block") {
        cart.style.display = "none";
      } else {
        cart.style.display = "block";
      }
    });
  </script>

  <div class="home">
    <div class="left">
      <h1><span class="myCo">NIKE</span> <br>LUPINEK FLYKNIT <br>ACG</h1>
      <p>
        Unlock the power of unparalleled comfort, style
        and innovation.<br> Embrace your journey
        with unstoppable confidence and grace.
      </p>
      <br><br><br>
      <div class="btnn">
        Buy Now
        <script>
          document.querySelector(".btnn").addEventListener("click", function() {
            window.scrollTo({
              top: document.body.scrollHeight / 2.5,
              behavior: "smooth"
            });
          });
        </script>
      </div>
    </div>
    <img src="/image/shoe-2.png" alt="Shoe Image" class="myHomeImg">
  </div>

  <div class="popup-container" id="login-popup">
    <div class="popup">
      <form method="POST" action="login_register.php" id="loginForm">
        <h2>
          <span>USER LOGIN</span>
          <button type="reset" onclick="popup('login-popup')">X</button>
        </h2>
        <input type="text" placeholder="E-mail or Username" name="email_username" required>
        <input type="password" placeholder="Password" name="password" required>
        <div class="login">
          <button type="submit" class="login-btn" name="login">LOGIN</button>
          <br><br>
          <span style="font-weight: bolder;">Don't have an account?</span>
          <button type="button" onclick="popup('register-popup')">
            <div class="register">REGISTER</div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="popup-container" id="register-popup">
    <div class="register popup">
      <form method="POST" action="login_register.php" id="registerForm">
        <h2>
          <span>USER REGISTER</span>
          <button type="reset" onclick="popup('register-popup')">X</button>
        </h2>
        <div class="form-group">
          <input type="text" placeholder="Full Name" name="fullname" id="fullname">
        </div>
        <div class="form-group">
          <input type="text" placeholder="Username" name="username" id="username">
        </div>
        <div class="form-group">
          <input type="text" placeholder="E-mail" name="email" id="email">
        </div>
        <div class="form-group">
          <input type="password" placeholder="Password" name="password" id="password">
        </div>
        <button type="submit" class="register-btn" name="register">REGISTER</button>
      </form>
    </div>
  </div>

  <script>
    // Validation
    document.getElementById('registerForm').addEventListener('submit', function(event) {
      const fullname = document.getElementById('fullname').value;
      const username = document.getElementById('username').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;

      if (fullname.length < 5) {
        alert('Full Name must be at least 5 characters long.');
        event.preventDefault();
        return;
      }

      let usernamePattern = /^[A-Za-z]{5,}$/;

      if (!usernamePattern.test(username)) {
        alert("Username must be at least 5 characters long and contain only letters (no numbers or special characters).");
        event.preventDefault();
      }

      const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
      if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        event.preventDefault();
        return;
      }

      if (password.length < 8) {
        alert('Password must be at least 8 characters long.');
        event.preventDefault();
        return;
      }

      if (!/[A-Z]/.test(password)) {
        alert('Password must contain at least one uppercase letter.');
        event.preventDefault();
        return;
      }

      if (!/[a-z]/.test(password)) {
        alert('Password must contain at least one lowercase letter.');
        event.preventDefault();
        return;
      }

      if (!/\d/.test(password)) {
        alert('Password must contain at least one number.');
        event.preventDefault();
        return;
      }

      if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        alert('Password must contain at least one special character.');
        event.preventDefault();
        return;
      }
    });

    function popup(popup_name) {
      let get_popup = document.getElementById(popup_name);
      if (get_popup.style.display == "flex") {
        get_popup.style.display = "none";
      } else {
        get_popup.style.display = "flex";
      }
    }
  </script>

</section>

<section id="product" class="product">
  <h2 class="product-category">BEST SELLING PRODUCT</h2>
  <div class="product-container">
    <?php
    $query = "SELECT * FROM best_selling";
    $result = $con->query($query);
    ?>

    <div class="product-container">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="product-card">
          <div class="product-image">
            <span class="discount-tag"><?php echo $row['discount']; ?>% off</span>
            <img src="image/<?php echo $row['image']; ?>" class="product-thumb" alt="<?php echo $row['name']; ?>">

            <?php if ($row['quantity'] > 0): ?>
              <p class="stock">Stock Left: <?php echo $row['quantity']; ?></p>
              <button class="add-to-cart"
                data-name="<?php echo $row['name']; ?>"
                data-price="<?php echo $row['price']; ?>"
                data-image="<?php echo $row['image']; ?>">
                Add to Cart
              </button>
            <?php else: ?>
              <p class="out-of-stock" style="color:red; font-weight:bold;">Out of Stock</p>
            <?php endif; ?>
          </div>

          <div class="product-info">
            <h4 class="product-brand"><?php echo $row['name']; ?></h4>
            <span class="quantity">Stock Left: <?php echo $row['quantity']; ?></span><br>
            <span class="price">RS <?php echo $row['price']; ?></span>
            <span class="actual-price">Rs <?php echo $row['actual_price']; ?></span>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<h2 class="product-list">PRODUCT</h2>

<div id="search-results" class="search-dropdown container-grid">
  <!-- Search results will be loaded here -->
</div>
    
<div class="container-grid" id="product-container">
  <!-- Products will be loaded here -->
</div>

<div id="pagination">
  <button id="prevPage" disabled>Previous</button>
  <span id="pageNumbers"></span>
  <button id="nextPage">Next</button>
</div>

<script src="pagination.js"></script>

<section id="contact" class="contact">
  <div class="container">
    <div class="contact-text">
      <h2>Contact Us</h2>
      <p>Have questions? Reach out to us!</p>
    </div>
    <form action="/View_contact/contact.php" method="POST">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" placeholder="Your Message" required></textarea>
      <div class="sendbtn">
        <button type="submit" name="submit">Send</button>
      </div>
    </form>
  </div>
</section>

<footer>
  <div class="footer-container">
    <p>&copy 2025 FOOT FIND. All rights reserved.</p>
  </div>
</footer>

<script src="script.js"></script>
<script src="Add_to_cart.js"></script>
<script src="Search.js"></script>
</body>

</html>
