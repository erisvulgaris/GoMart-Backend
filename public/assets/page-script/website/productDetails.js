async function switchVarient(productId, variantId, productSlug) {
    // Remove active classes from all variants
    document.querySelectorAll(`[id^="variant-"]`).forEach(element => {
        element.classList.remove("border-green-700", "bg-[#F7FFF9]", "shadow-md", "active");
        element.classList.add("bg-white");
    });

    // Apply active classes to the currently clicked variant
    const selectedVariant = document.getElementById(`variant-${variantId}`);
    if (selectedVariant) {
        selectedVariant.classList.add("border-green-700", "bg-[#F7FFF9]", "shadow-md", "active");
        selectedVariant.classList.remove("bg-white");
    }

    try {
        const response = await fetch('/switchVarient', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                productId,
                variantId
            }),
        });

        const result = await response.json();
        console.log(result);

        if (result.status === 'success') {
            // Update swiper gallery with variant images
            if (result.images && result.images.length > 0) {
                // Rebuild swiper slides
                const swiperWrapper = document.querySelector('#productSwiper .swiper-wrapper');
                if (swiperWrapper) {
                    swiperWrapper.innerHTML = result.images.map((img, index) => `
                        <div class="swiper-slide zoom-container" data-index="${index}">
                            <div class="image-wrapper bg-white dark:bg-gray-800" style="position: relative; overflow: hidden;">
                                <img src="${img.image}"
                                    alt="Product Image ${index + 1}"
                                    class="main-product-image w-full h-auto object-contain"
                                    style="display: block; max-width: 100%; height: auto;" />
                            </div>
                        </div>
                    `).join('');
                }

                // Rebuild thumbnails
                const thumbnailsContainer = document.getElementById('productThumbnails');
                if (thumbnailsContainer) {
                    thumbnailsContainer.innerHTML = result.images.map((img, index) => `
                        <div class="thumbnail-wrapper w-1/4">
                            <div class="thumbnails-img cursor-pointer border-2 border-transparent dark:border-gray-700 rounded-lg overflow-hidden transition-all duration-300 hover:border-gray-300 dark:hover:border-gray-500"
                                data-index="${index}">
                                <img src="${img.image}"
                                    alt="Thumbnail ${index + 1}"
                                    class="thumbnail-image w-full h-auto object-cover rounded-lg" />
                            </div>
                        </div>
                    `).join('');
                }

                // Reinitialize swiper and re-attach zoom + thumbnail listeners
                if (typeof swiper !== 'undefined' && swiper) {
                    swiper.update();
                    swiper.slideTo(0);
                } else if (typeof initSwiper === 'function') {
                    initSwiper();
                }
                if (typeof setupEventListeners === 'function') setupEventListeners();
                if (typeof setupThumbnailNavigation === 'function') setupThumbnailNavigation();
            }

            // Remove all child elements from non-selected variants
            document.querySelectorAll(`.${productSlug}-mainbtndiv`).forEach((div) => {
                if (!div.classList.contains(`${productSlug}-mainbtndiv-${variantId}`)) {
                    while (div.firstChild) {
                        div.removeChild(div.firstChild);
                    }
                }
            });

            // Get the main container (select the first container with the class)
            let mainbtndivs = document.getElementsByClassName(`${productSlug}-mainbtndiv`);

            if (mainbtndivs.length > 0) {
                // Select the first main container (or modify if needed)
                let mainbtndiv = mainbtndivs[0];

                // Create a new div for the variant
                const newDiv = document.createElement("div");

                // Add the dynamic class to the new div
                newDiv.classList.add(`${productSlug}-mainbtndiv-${variantId}`);

                // Set the inner HTML depending on the quantity
                if (result.quantity > 0) {
                    newDiv.innerHTML = `
                        <div class="inline-flex items-center gap-1 p-1 rounded-lg bg-green-700 border border-green-700 shadow-md">
                            <button type="button" onclick="removeFromCart(${productId}, ${variantId})"
                                class="text-lg leading-none hover:text-primary ${productSlug}-removebtn-${variantId}">
                                <i class="fi fi-rr-minus-small text-white"></i>
                            </button>
                            <span class="text-center h-5 text-sm font-medium text-white ${productSlug}-qty-${variantId}">
                                ${result.quantity}
                            </span>
                            <button type="button" onclick="addToCart(${productId}, ${variantId})"
                                class="text-lg leading-none hover:text-primary ${productSlug}-addbtn-${variantId}">
                                <i class="fi fi-rr-plus-small text-white"></i>
                            </button>
                        </div>
                    `;
                                    } else {
                                        newDiv.innerHTML = `
                        <button type="button" onclick="addToCart(${productId}, ${variantId})"
                            class="text-sm px-2 py-1 rounded-lg items-center gap-x-1 bg-green-700 text-white border-green-700 disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-green-700 hover:border-green-700 btn-sm">
                            <i class="fi fi-rr-shopping-cart"></i>
                            <span>Add</span>
                        </button>
                    `;
                }

                // Append the new div to the mainbtndiv
                mainbtndiv.appendChild(newDiv);
            }

        } else {
            console.log("Failed to switch variant:", result.message);
        }
    } catch (error) {
        console.log(error);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const stars = document.querySelectorAll("#starRating i");
    const ratingInput = document.getElementById("rating");

    stars.forEach((star, index) => {
        star.addEventListener("click", () => {
            // Set the value of the hidden input
            ratingInput.value = index + 1;

            // Update the stars' appearance
            stars.forEach((s, i) => {
                if (i <= index) {
                    s.classList.remove("fi-rr-star-exclamation", "text-gray-400");
                    s.classList.add("fi-sc-star", "text-yellow-500");
                } else {
                    s.classList.remove("fi-sc-star", "text-yellow-500");
                    s.classList.add("fi-rr-star-exclamation", "text-gray-400");
                }
            });
        });
    });
});


const writeReviewModal = document.getElementById('writeReviewModal');

function openWriteReviewPopup(productId) {
    writeReviewModal.classList.remove('hidden');
    document.body.classList.add('modal-open');

    document.getElementById('product_id').value = productId
}

function closeWriteReviewPopup() {
    writeReviewModal.classList.add('hidden');
    document.body.classList.remove('modal-open');
}

document.querySelector('form.writeReviewForm').addEventListener('submit', async (event) => {
    event.preventDefault();

    // Input fields
    const review = document.getElementById('review').value.trim();
    const title = document.getElementById('title').value.trim();
    const rating = parseInt(document.getElementById('rating').value.trim());
    const productId = document.getElementById('product_id').value.trim();

    let hasError = false;

    // Helper function to show errors
    const showError = (fieldId, message) => {
        const errorElement = document.getElementById(`${fieldId}Error`);
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    };

    const clearError = (fieldId) => {
        const errorElement = document.getElementById(`${fieldId}Error`);
        errorElement.textContent = '';
        errorElement.classList.add('hidden');
    };

    // Validation
    if (!rating || rating <= 0) {
        showError('rating', 'Please select a rating.');
        hasError = true;
    } else {
        clearError('rating');
    }

    if (!title || title.length < 3) {
        showError('title', 'Please enter a valid title (at least 3 characters).');
        hasError = true;
    } else {
        clearError('title');
    }

    if (!review || review.length < 10) {
        showError('review', 'Please write a review (at least 10 characters).');
        hasError = true;
    } else {
        clearError('review');
    }

    if (hasError) return;

    try {
        // Send data to the server
        const response = await fetch('/writeReview', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                productId,
                rate: rating,
                title,
                review,
            }),
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            event.target.reset(); // Clear the form
            showToast(result.message, 'success'); // Show success notification
            closeWriteReviewPopup(); // Close modal
        } else {
            showToast(result.message || 'Failed to submit the review.', 'danger');
        }
    } catch (error) {
        console.error('Error submitting review:', error);
        showToast('An unexpected error occurred. Please try again.', 'danger');
    }
});