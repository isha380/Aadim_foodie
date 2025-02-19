<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusButtons = document.querySelectorAll('.dropdown-item[data-status="Received"]');

        statusButtons.forEach(button => {
            button.addEventListener('click', function() {
                const cartId = this.closest('tr').querySelector('td:nth-child(2)').textContent; // Assuming Cart_Id is in the second column

                if (confirm('Are you sure you want to mark this order as received?')) {
                    fetch('update_order_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ cartId: cartId, status: 'Received' }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Order status updated to Received.');
                            location.reload(); // Refresh the page to see the updated status
                        } else {
                            alert('Failed to update order status: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the status.');
                    });
                }
            });
        });
    });
</script>
