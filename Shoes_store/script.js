const cartButton = document.getElementById('cart-icon');
const cartPopup = document.getElementById('cart-popup');

cartButton.addEventListener('click', () => {
    if (cartPopup.style.display === 'block') {
        cartPopup.style.display = 'none';
    } else {
        cartPopup.style.display = 'block';
    }
});



