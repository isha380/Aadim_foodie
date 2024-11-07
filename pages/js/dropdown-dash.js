function dropdown_show(clickedElement) {
    // Get all dropdown content elements
    const allDropdowns = document.querySelectorAll('.dropdown-content');

    // Close all dropdowns except the one clicked
    allDropdowns.forEach(dropdown => {
        if (dropdown !== clickedElement.nextElementSibling) {
            dropdown.style.display = 'none';
        }
    });

    // Get the dropdown content associated with the clicked element
    const dropdownContent = clickedElement.nextElementSibling;

    // Toggle the display of the clicked dropdown
    if (dropdownContent.style.display === 'block') {
        dropdownContent.style.display = 'none';
    } else {
        dropdownContent.style.display = 'block';
    }
}