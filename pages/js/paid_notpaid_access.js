document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".order-status-dropdown").forEach(function (dropdown) {
        const statusButton = dropdown.querySelector(".edit-btn");
        const dropdownMenu = dropdown.querySelector(".dropdown-menu");
        const statusItems = dropdownMenu.querySelectorAll(".dropdown-item");
        const paidButton = dropdown.closest("tr").querySelector(".update.button a");
        
        statusButton.addEventListener("click", function () {
            dropdownMenu.classList.toggle("show");
        });

        statusItems.forEach(function (item) {
            item.addEventListener("click", function () {
                const selectedStatus = item.getAttribute("data-status");
                
                // Update the status icon and text
                statusButton.innerHTML = item.innerHTML;
                dropdownMenu.classList.remove("show");
                
                // Enable or disable the Paid button
                if (selectedStatus === "Received") {
                    paidButton.style.pointerEvents = "auto";
                    paidButton.style.opacity = "1";
                } else {
                    paidButton.style.pointerEvents = "none";
                    paidButton.style.opacity = "0.5";
                }
            });
        });
    });
});
