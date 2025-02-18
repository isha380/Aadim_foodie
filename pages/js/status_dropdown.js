
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-btn").forEach(function (button) {
        button.addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent click from propagating to body

            let dropdown = this.nextElementSibling;
            document.querySelectorAll(".dropdown-menu").forEach((menu) => {
                if (menu !== dropdown) menu.classList.remove("show");
            });
            dropdown.classList.toggle("show");
        });
    });

    // Handle status change when an item in the dropdown is clicked
    document.querySelectorAll(".dropdown-item").forEach(function (item) {
        item.addEventListener("click", function () {
            const newStatus = this.dataset.status;

            // Find the closest status icon related to this dropdown
            let statusIcon = this.closest(".order-status-dropdown").querySelector(".status-icon");

            // Update the icon for the clicked row
            if (newStatus === "Pending") {
                statusIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                        <g>
                            <path fill="currentColor" d="M7 3H17V7.2L12 12L7 7.2V3Z">
                                <animate id="eosIconsHourglass0" fill="freeze" attributeName="opacity" begin="0;eosIconsHourglass1.end" dur="2s" from="1" to="0"/>
                            </path>
                            <path fill="currentColor" d="M17 21H7V16.8L12 12L17 16.8V21Z">
                                <animate fill="freeze" attributeName="opacity" begin="0;eosIconsHourglass1.end" dur="2s" from="0" to="1"/>
                            </path>
                            <path fill="currentColor" d="M6 2V8H6.01L6 8.01L10 12L6 16L6.01 16.01H6V22H18V16.01H17.99L18 16L14 12L18 8.01L17.99 8H18V2H6ZM16 16.5V20H8V16.5L12 12.5L16 16.5ZM12 11.5L8 7.5V4H16V7.5L12 11.5Z"/>
                            <animateTransform id="eosIconsHourglass1" attributeName="transform" attributeType="XML" begin="eosIconsHourglass0.end" dur="0.5s" from="0 12 12" to="180 12 12" type="rotate"/>
                        </g>
                    </svg>`;
            } else if (newStatus === "Received") {
                statusIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                        <path fill="#48cb0d" fill-rule="evenodd" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m-.232-5.36l5-6l-1.536-1.28l-4.3 5.159l-2.225-2.226l-1.414 1.414l3 3l.774.774z" clip-rule="evenodd"/>
                    </svg>`;
            }

            // Close dropdown after selecting an option
            let dropdown = this.closest(".dropdown-menu");
            dropdown.classList.remove("show");
        });
    });

    // Hide dropdown when clicking outside
    document.addEventListener("click", function (event) {
        document.querySelectorAll(".dropdown-menu").forEach((menu) => {
            if (!menu.contains(event.target)) {
                menu.classList.remove("show");
            }
        });
    });
  
    //     const statusButton = dropdown.querySelector(".edit-btn");
    //     const dropdownMenu = dropdown.querySelector(".dropdown-menu");
    //     const statusItems = dropdownMenu.querySelectorAll(".dropdown-item");
    //     const paidButton = dropdown.closest("tr").querySelector(".update.button a");
        
    //     statusButton.addEventListener("click", function () {
    //         dropdownMenu.classList.toggle("show");
    //     });

    //     statusItems.forEach(function (item) {
    //         item.addEventListener("click", function () {
    //             const selectedStatus = item.getAttribute("data-status");
                
    //             // Update the status icon and text
    //             statusButton.innerHTML = item.innerHTML;
    //             dropdownMenu.classList.remove("show");
                
    //             // Enable or disable the Paid button
    //             if (selectedStatus === "Received") {
    //                 paidButton.style.pointerEvents = "auto";
    //                 paidButton.style.opacity = "1";
    //             } else {
    //                 paidButton.style.pointerEvents = "none";
    //                 paidButton.style.opacity = "0.5";
    //             }
    //         });
    //     });
    // });
});


