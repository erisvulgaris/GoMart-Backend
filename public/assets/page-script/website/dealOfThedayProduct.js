window.onload = function() {
    // Check if the product view is already set in localStorage
    const productView = localStorage.getItem('productView') || 'grid'; // Default to 'list' view
    setActiveView(productView);
};

// Function to set the active view
function setActiveView(view) {
    // Hide all views
    document.getElementById('productListView').classList.add('hidden');
    document.getElementById('productAppView').classList.add('hidden');
    document.getElementById('productGridView').classList.add('hidden');

    // Reset button classes to inactive
    document.getElementById('listViewButton').classList.replace('text-green-600', 'text-gray-600');
    document.getElementById('appViewButton').classList.replace('text-green-600', 'text-gray-600');
    document.getElementById('gridViewButton').classList.replace('text-green-600', 'text-gray-600');

    // Show the selected view and set button as active
    if (view === 'list') {
        document.getElementById('productListView').classList.remove('hidden');
        document.getElementById('listViewButton').classList.replace('text-gray-600', 'text-green-600');
    } else if (view === 'app') {
        document.getElementById('productAppView').classList.remove('hidden');
        document.getElementById('appViewButton').classList.replace('text-gray-600', 'text-green-600');
    } else if (view === 'grid') {
        document.getElementById('productGridView').classList.remove('hidden');
        document.getElementById('gridViewButton').classList.replace('text-gray-600', 'text-green-600');
    }

    // Save the selected view to localStorage
    localStorage.setItem('productView', view);
}

// Functions for each view
function setProductListView() {
    setActiveView('list');
}

function setProductAppView() {
    setActiveView('app');
}

function setProductGridView() {
    setActiveView('grid');
}

// price slider
const minRange = document.getElementById('minRange');
const maxRange = document.getElementById('maxRange');
const minPrice = document.getElementById('minPrice');
const maxPrice = document.getElementById('maxPrice');
const minHandle = document.getElementById('minHandle');
const maxHandle = document.getElementById('maxHandle');
const rangeTrack = document.getElementById('rangeTrack');

const minLimit = parseInt(minRange.min);
const maxLimit = parseInt(maxRange.max);

// Sync slider positions
function updateSlider() {
    const minVal = parseInt(minRange.value);
    const maxVal = parseInt(maxRange.value);

    // Prevent overlap
    if (maxVal - minVal <= 500) {
        if (this === minRange) minRange.value = maxVal - 500;
        if (this === maxRange) maxRange.value = minVal + 500;
    }

    const minPercent = ((minRange.value - minLimit) / (maxLimit - minLimit)) * 100;
    const maxPercent = ((maxRange.value - minLimit) / (maxLimit - minLimit)) * 100;

    // Update UI
    minHandle.style.left = `${minPercent}%`;
    maxHandle.style.left = `${maxPercent}%`;
    rangeTrack.style.left = `${minPercent}%`;
    rangeTrack.style.right = `${100 - maxPercent}%`;

    // Update text inputs
    minPrice.innerText = minRange.value;
    maxPrice.innerText = maxRange.value;
}

// Sync text inputs
function updateInputs() {
    const minVal = parseInt(minPrice.value) || minLimit;
    const maxVal = parseInt(maxPrice.value) || maxLimit;

    if (maxVal - minVal >= 500 && minVal >= minLimit && maxVal <= maxLimit) {
        minRange.value = minVal;
        maxRange.value = maxVal;
        updateSlider();
    }
}

minRange.addEventListener('input', updateSlider);
maxRange.addEventListener('input', updateSlider);
minPrice.addEventListener('input', updateInputs);
maxPrice.addEventListener('input', updateInputs);

// Initialize
updateSlider();