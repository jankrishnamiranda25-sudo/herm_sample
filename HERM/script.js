// Add to cart using AJAX and update cart count (if desired)
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-to-cart').forEach(function(button) {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            fetch('cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + encodeURIComponent(id)
            })
            .then(response => response.json())
            .then(data => {
                alert('Added to cart!');
            });
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    // Attach click listeners to all remove buttons
    document.querySelectorAll('.remove-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.getAttribute('data-id');
            fetch('cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'remove=' + encodeURIComponent(id)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the row from the table
                    var row = btn.closest('tr');
                    row.style.opacity = 0;
                    setTimeout(function () {
                        row.remove();
                        // If cart is empty, show empty message
                        if (Object.keys(data.cart).length === 0) {
                            document.getElementById('cart-content').innerHTML = '<p>Your cart is empty.</p>';
                        }
                    }, 250);
                }
            });
        });
    });
});

