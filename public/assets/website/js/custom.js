function initializeSwiperCarousels() {
    document.querySelectorAll(".swiper-container").forEach(container => {
        const options = {
            speed: parseInt(container.getAttribute("data-speed") || 400),
            spaceBetween: parseInt(container.getAttribute("data-space-between") || 20),
            breakpoints: JSON.parse(container.getAttribute("data-breakpoints") || '{}'),
            effect: container.getAttribute("data-effect") || "slide",
            autoplay: container.getAttribute("data-autoplay") === "true" ? { delay: parseInt(container.getAttribute("data-autoplay-delay") || 3000) } : false,
            pagination: container.getAttribute("data-pagination") === "true" ? {
                el: container.querySelector(".swiper-pagination"),
                type: container.getAttribute("data-pagination-type") || "bullets",
                dynamicBullets: true,
                clickable: true
            } : false,
            navigation: container.getAttribute("data-navigation") === "true" ? {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            } : false
        };

        new Swiper(container, options);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initializeSwiperCarousels();
});

let icon = {
    success: '<i class="fi fi-rr-check-circle"></i>',
    danger: '<i class="fi fi-rr-times-hexagon"></i>',
    warning: '<i class="fi fi-rr-triangle-warning"></i>',
    info: '<i class="fi fi-rr-info"></i>',
};

const showToast = (message = "", toastType = "", duration = 5000) => {
    if (!Object.keys(icon).includes(toastType)) toastType = "info";

    // Create the toast element
    let box = document.createElement("div");
    box.classList.add("toast", `toast-${toastType}`, 'z-[999]');
    box.innerHTML = `
        <div class="toast-content-wrapper">
            <div class="toast-icon">${icon[toastType]}</div>
            <div class="toast-message">${message}</div>
            <div class="toast-progress"></div>
        </div>`;
    duration = duration || 5000;
    box.querySelector(".toast-progress").style.animationDuration = `${duration / 1000}s`;

    // Remove any existing toast before adding a new one
    let toastAlready = document.body.querySelector(".toast");
    if (toastAlready) {
        toastAlready.remove();
    }

    // Append the toast to the body
    document.body.appendChild(box);

    // Automatically remove the toast after the duration ends
    setTimeout(() => {
        box.classList.add("hidden"); // Add a hide class for fading out (optional)
        box.addEventListener("transitionend", () => {
            box.remove(); // Remove the toast completely after transition
        });
    }, 4000);
};
