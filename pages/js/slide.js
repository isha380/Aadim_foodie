// Select all dish containers and arrows
const dish_container = [...document.querySelectorAll('.dish-slide-container.menu')];
const pre_btn = [...document.querySelectorAll('.arrow.pre.menu')];
const next_btn = [...document.querySelectorAll('.arrow.next.menu')];

// Loop through each container and add event listeners to corresponding arrows
dish_container.forEach((item, i) => {
    let containerDimension = item.getBoundingClientRect();
    let containerwidth = containerDimension.width;

    // Next button scroll
    next_btn[i].addEventListener('click', () => {
        item.scrollLeft += containerwidth;
    });

    // Previous button scroll
    pre_btn[i].addEventListener('click', () => {
        item.scrollLeft -= containerwidth;
    });
});
