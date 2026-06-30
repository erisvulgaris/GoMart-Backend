// Search functionality for both dropdown and search page
let searchTimeout;
let trendingData = null;
let currentSearchTerm = '';

document.addEventListener('DOMContentLoaded', function() {
  loadTrendingItems();

  // If on search page with query parameter, trigger search
  const urlParams = new URLSearchParams(window.location.search);
  const searchQuery = urlParams.get('q');

  if (searchQuery && document.getElementById('searchItemDiv')) {
    // Trigger search for the query
    searchProducts(searchQuery);
  }
});


async function loadTrendingItems() {
  try {
    const response = await fetch('/search/popular', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    });

    const result = await response.json();

    if (result.status === 'success') {
      trendingData = result;
      updateTrendingDisplay(result);
    }
  } catch (error) {
    console.error('Error loading trending items:', error);
  }
}


function updateTrendingDisplay(data) {
  // Update trending searches
  const searchesContainer = document.getElementById('trendingSearchesList');
  if (searchesContainer && data.searches && data.searches.length > 0) {
    searchesContainer.innerHTML = '';

    data.searches.forEach(search => {
      const searchItem = `
        <a href="/search?q=${encodeURIComponent(search)}" class="flex items-center space-x-3 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded-lg transition-colors group">
          <i class="fi fi-tr-issue-loupe text-gray-400 group-hover:text-green-600 transition-colors"></i>
          <div>
            <div class="text-gray-800 dark:text-gray-200 font-medium">${search}</div>
          </div>
        </a>
      `;
      searchesContainer.insertAdjacentHTML('beforeend', searchItem);
    });
  }

  // Update trending products
  const productsContainer = document.getElementById('trendingProductsList');
  if (productsContainer && data.products && data.products.length > 0) {
    productsContainer.innerHTML = '';

    data.products.slice(0, 4).forEach(product => {
      const productCard = createProductCard(product, data.currency_symbol, data.currency_symbol_position, true);
      productsContainer.insertAdjacentHTML('beforeend', productCard);
    });
  }
}


async function searchProducts(search) {
  currentSearchTerm = search;

  if (search.length < 1) {
    // If search is empty, show trending items
    if (trendingData) {
      resetDropdownToTrending();
      updateSearchResults(trendingData.products, trendingData.currency_symbol, trendingData.currency_symbol_position);
    }
    return;
  }

  if (search.length > 2) {
    // Show search header on search page
    const searchDiv = document.getElementById('searchDiv');
    const searchText = document.getElementById('searchText');

    if (searchDiv && searchText) {
      searchDiv.classList.remove('hidden');
      searchText.innerText = "Search for '" + search + "'";
    }

    try {
      const response = await fetch('/search', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          searchStr: search,
        }),
      });

      const result = await response.json();

      // Update search results on search page
      if (document.getElementById('searchItemDiv')) {
        updateSearchResults(result.products, result.currency_symbol, result.currency_symbol_position);
      }

      // Update dropdown results if dropdown is open
      if (document.getElementById('searchDropdown') && !document.getElementById('searchDropdown').classList.contains('hidden')) {
        updateDropdownResults(result.products, result.currency_symbol, result.currency_symbol_position, search);
      }

    } catch (error) {
      console.error('Search error:', error);
    }
  }
}


function updateSearchResults(products, currencySymbol, currencyPosition) {
  let searchItemDiv = document.getElementById('searchItemDiv');
  let searchItemEmptyDiv = document.getElementById('searchItemEmptyDiv');

  if (!searchItemDiv || !searchItemEmptyDiv) return;

  searchItemDiv.innerHTML = '';

  if (products.length > 0) {
    searchItemEmptyDiv.classList.add('hidden');

    products.forEach(product => {
      const productHtml = createProductCard(product, currencySymbol, currencyPosition, false);
      searchItemDiv.insertAdjacentHTML('beforeend', productHtml);
    });
  } else {
    searchItemEmptyDiv.classList.remove('hidden');
  }
}


function updateDropdownResults(products, currencySymbol, currencyPosition, searchTerm) {
  // Hide trending sections
  const trendingSearchesSection = document.getElementById('trendingSearchesList')?.closest('.p-6.border-b');
  const trendingProductsList = document.getElementById('trendingProductsList');
  const searchResultsContainer = document.getElementById('dropdownSearchResults');
  const dropdownTitle = document.getElementById('dropdownResultsTitle');

  // Hide trending searches section
  if (trendingSearchesSection) {
    trendingSearchesSection.classList.add('hidden');
  }

  // Hide trending products
  if (trendingProductsList) {
    trendingProductsList.classList.add('hidden');
  }

  // Show and populate search results
  if (searchResultsContainer) {
    searchResultsContainer.classList.remove('hidden');
    searchResultsContainer.innerHTML = '';

    if (products.length > 0) {
      // Show only first 4 products in dropdown
      products.slice(0, 4).forEach(product => {
        const productCard = createProductCard(product, currencySymbol, currencyPosition, true);
        searchResultsContainer.insertAdjacentHTML('beforeend', productCard);
      });
    } else {
      // Show empty state
      searchResultsContainer.innerHTML = `
        <div class="col-span-2 md:col-span-4 text-center py-8">
          <i class="fi fi-rr-search text-4xl text-gray-300 mb-3"></i>
          <p class="text-gray-500">No products found for "${searchTerm}"</p>
        </div>
      `;
    }
  }

  // Update title and "See All" link
  if (dropdownTitle) {
    dropdownTitle.textContent = 'Search Results';
  }

  // Update "See All" link to include search term
  const seeAllLink = document.querySelector('#searchDropdown a[href="/search"]');
  if (seeAllLink && searchTerm) {
    seeAllLink.href = `/search?q=${encodeURIComponent(searchTerm)}`;
  }
}


function resetDropdownToTrending() {
  const trendingSearchesSection = document.getElementById('trendingSearchesList')?.closest('.p-6.border-b');
  const trendingProductsList = document.getElementById('trendingProductsList');
  const searchResultsContainer = document.getElementById('dropdownSearchResults');
  const dropdownTitle = document.getElementById('dropdownResultsTitle');

  // Show trending sections
  if (trendingSearchesSection) {
    trendingSearchesSection.classList.remove('hidden');
  }

  if (trendingProductsList) {
    trendingProductsList.classList.remove('hidden');
  }

  // Hide search results
  if (searchResultsContainer) {
    searchResultsContainer.classList.add('hidden');
    searchResultsContainer.innerHTML = '';
  }

  // Reset title
  if (dropdownTitle) {
    dropdownTitle.textContent = 'Popular Products';
  }

  // Reset "See All" link
  const seeAllLink = document.querySelector('#searchDropdown a[href*="/search"]');
  if (seeAllLink) {
    seeAllLink.href = '/search';
  }
}


function generateStarRatingHtml(avgRating, ratingCount, productId) {
    const rating = parseFloat(avgRating) || 0;
    const count = parseInt(ratingCount) || 0;
    const fullStars = Math.floor(rating);
    const halfStar = (rating - fullStars) >= 0.5 ? 1 : 0;
    const emptyStars = 5 - fullStars - halfStar;
    let stars = '';
    for (let i = 0; i < fullStars; i++) {
        stars += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#f59e0b" class="w-3 h-3"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
    }
    if (halfStar) {
        stars += `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-3 h-3"><defs><linearGradient id="half-s-${productId}"><stop offset="50%" stop-color="#f59e0b"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><path fill="url(#half-s-${productId})" stroke="#f59e0b" stroke-width="1.5" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`;
    }
    for (let i = 0; i < emptyStars; i++) {
        stars += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" class="w-3 h-3 dark:stroke-gray-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
    }
    return `<div class="flex items-center gap-1.5"><div class="flex items-center gap-px">${stars}</div><span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 leading-none">${rating.toFixed(1)} (${count})</span></div>`;
}


function createProductCard(product, currencySymbol, currencyPosition, isDropdown = false) {
    if (!product.variants || product.variants.length === 0) {
      console.warn('Product has no variants:', product);
      return '';
    }

  const variant = product.variants[0];
  const hasDiscount = +variant.discountPercentage > 0 || +variant.discounted_price > 0;
  const discountPercentage = hasDiscount ? (variant.discountPercentage || Math.round(((variant.price - variant.discounted_price) / variant.price) * 100)) : 0;
  const activePrice = hasDiscount ? variant.discounted_price : variant.price;
  const outOfStock = variant.stock == 0 && variant.is_unlimited_stock == 0;

  const formattedPrice = currencyPosition === 'left' ? currencySymbol + activePrice : activePrice + currencySymbol;
  const originalPrice = hasDiscount ? (currencyPosition === 'left' ? currencySymbol + variant.price : variant.price + currencySymbol) : '';

  // Rating HTML
  const ratingHtml = generateStarRatingHtml(product.avg_rating, product.rating_count, product.id);

  // Delivery time (uses productDeliveryTime/productDeliveryTimeVal pattern, populated by setProductDeliveryTime in script.php)
  const _dt = (() => { try { const t = document.getElementById('proxyDeliveryTime')?.textContent?.trim(); if (t) return t; return JSON.parse(localStorage.getItem('location') || '{}').delivery_time || ''; } catch(e) { return ''; } })();
  const deliveryTimeHtml = `<span class="productDeliveryTime mt-1 whitespace-nowrap${_dt ? '' : ' hidden'}"><span class="inline-flex items-center gap-1 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 text-[10px] font-bold px-1.5 py-0.5 rounded-md leading-none border border-orange-100 dark:border-orange-800"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 24" class="h-3.5" style="width:auto;" fill="currentColor"><circle cx="9" cy="18" r="3.8" fill="none" stroke="currentColor" stroke-width="2.2"/><circle cx="9" cy="18" r="1.2"/><circle cx="29" cy="18" r="3.8" fill="none" stroke="currentColor" stroke-width="2.2"/><circle cx="29" cy="18" r="1.2"/><path d="M9,15.5 L11,10 L17,8.5 L25,9 L29,12 L29,15.5 Q19,17 9,15.5 Z"/><line x1="23" y1="8" x2="17" y2="3.5" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none"/><circle cx="15.5" cy="2.5" r="2.6"/><line x1="17" y1="4" x2="12.5" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/><line x1="35" y1="9.5" x2="40" y2="9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/><line x1="36" y1="12.5" x2="44" y2="12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/><line x1="34.5" y1="15.5" x2="42" y2="15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/></svg><span class="productDeliveryTimeVal">${_dt}</span>m</span></span>`;

  // Return product card HTML
  if (isDropdown) {
    // Smaller card for dropdown - clickable entire card
    return `
      <a href="/product/${product.slug}" class="group block">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden hover:shadow-lg transition-all hover:border-green-500">
          <div class="aspect-square bg-gray-50 dark:bg-gray-700 flex items-center justify-center p-4">
            <img src="/${product.main_img}" alt="${product.product_name}" class="w-full h-full object-contain group-hover:scale-105 transition-transform">
          </div>
          <div class="p-3">
            <h4 class="text-sm text-gray-800 dark:text-gray-200 font-medium line-clamp-2 mb-2 group-hover:text-green-600 transition-colors">
              ${product.product_name}
            </h4>
            <div class="flex items-center gap-1.5 flex-wrap min-w-0">
              <span class="text-[13px] font-extrabold text-gray-900 dark:text-white truncate">${formattedPrice}</span>
              ${hasDiscount ? `<span class="text-[10px] text-gray-400 dark:text-gray-500 line-through leading-tight truncate">${originalPrice}</span>` : ''}
            </div>
          </div>
        </div>
      </a>
    `;
  } else {
    // Cart button HTML (overlapping style matching home page)
    let cartButtonHtml = '';
    if (outOfStock) {
        cartButtonHtml = `<span class="inline-block text-[10px] font-semibold text-red-400 dark:text-red-500 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 rounded-full px-2.5 py-1 shadow-md">Unavailable</span>`;
    } else if (+product.cart_quantity > 0) {
        cartButtonHtml = `
          <div class="flex items-center rounded-xl border-2 border-green-500 dark:border-green-600 overflow-hidden bg-white dark:bg-gray-800 shadow-lg">
            <button type="button" onclick="removeFromCart(${product.id}, ${variant.id})" class="w-7 h-7 flex items-center justify-center bg-green-700 text-white hover:bg-green-600 transition-colors">
              <i class="fi fi-rr-minus-small text-base leading-none"></i>
            </button>
            <span class="w-6 text-center text-[13px] font-bold text-green-700 dark:text-green-300 ${product.slug}-qty-${variant.id}">${product.cart_quantity}</span>
            <button type="button" onclick="addToCart(${product.id}, ${variant.id})" class="w-7 h-7 flex items-center justify-center bg-green-700 text-white hover:bg-green-600 transition-colors">
              <i class="fi fi-rr-plus-small text-base leading-none"></i>
            </button>
          </div>`;
    } else {
        cartButtonHtml = `
          <button type="button" onclick="openProductVariantPopup(${product.id}, '${product.slug}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-gray-800 border-2 border-green-600 dark:border-green-500 text-green-600 dark:text-green-400 shadow-lg hover:bg-green-600 hover:text-white hover:border-green-600 active:scale-90 transition-all duration-150 ${product.slug}-${variant.id}">
            <i class="fi fi-rr-plus text-sm leading-none"></i>
          </button>`;
    }

    // Full card for search page (matching home page design)
    return `
      <div class="group" id="${product.slug}-${variant.id}">
        <div class="flex flex-col rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden" style="height:100%;">
          <div class="relative w-full flex-shrink-0">
            <a href="/product/${product.slug}" class="relative block w-full overflow-hidden" style="aspect-ratio:1/1; background:linear-gradient(135deg,#f8faf8 0%,#eef4ee 100%);">
              ${outOfStock ? `
                <div class="absolute inset-0 z-20 flex items-center justify-center bg-white/50 dark:bg-gray-900/50 backdrop-blur-[2px]">
                  <span class="text-[10px] font-bold tracking-wide uppercase text-red-500 dark:text-red-400 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 px-2.5 py-1 rounded-full shadow">Out of Stock</span>
                </div>
              ` : ''}
              ${hasDiscount ? `
                <div class="absolute top-0 left-0 z-10" style="width:54px;height:54px;overflow:hidden;">
                  <div style="position:absolute;top:8px;left:-18px;width:72px;transform:rotate(-45deg);background:linear-gradient(90deg,#ef4444,#f97316);color:#fff;font-size:9px;font-weight:800;text-align:center;padding:3px 0;letter-spacing:0.04em;box-shadow:0 2px 6px rgba(239,68,68,.35);">
                    ${discountPercentage}% OFF
                  </div>
                </div>
              ` : ''}
              <img src="/${product.main_img}" alt="${product.product_name}" class="absolute inset-0 w-full h-full object-contain p-4 group-hover:scale-[1.07] transition-transform duration-500 ease-out dark:brightness-90" />
            </a>
            <div class="absolute -bottom-4 right-2 z-30 ${product.slug}-mainbtndiv-${variant.id}">
              ${cartButtonHtml}
            </div>
          </div>
          <div class="flex flex-col flex-1 px-3 pt-5 pb-3 gap-1">
            <h3 class="text-[12.5px] font-semibold leading-snug text-gray-800 dark:text-gray-100 line-clamp-2" style="min-height:2.6em;">${product.product_name}</h3>
            ${ratingHtml}
            <div class="flex items-center justify-between gap-1">
              <span class="text-[11px] text-gray-400 dark:text-gray-500 truncate leading-none">${variant.title}</span>
              ${deliveryTimeHtml}
            </div>
            <div class="flex-1"></div>
            <div class="flex items-center gap-1.5 flex-wrap min-w-0">
              <span class="text-[13px] font-extrabold text-gray-900 dark:text-white truncate">${formattedPrice}</span>
              ${hasDiscount ? `<span class="text-[10px] text-gray-400 dark:text-gray-500 line-through leading-tight truncate">${originalPrice}</span>` : ''}
            </div>
          </div>
        </div>
      </div>
    `;
  }
}

/**
 * Handle search input with debounce
 */
function handleSearchInput(inputElement) {
  clearTimeout(searchTimeout);

  const searchTerm = inputElement.value.trim();

  // If empty, reset to trending
  if (searchTerm.length === 0) {
    currentSearchTerm = '';
    resetDropdownToTrending();

    // Also reset search page if on search page
    if (document.getElementById('searchItemDiv')) {
      const searchDiv = document.getElementById('searchDiv');
      if (searchDiv) {
        searchDiv.classList.add('hidden');
      }
      document.getElementById('searchItemDiv').innerHTML = '';
      document.getElementById('searchItemEmptyDiv').classList.remove('hidden');
    }
    return;
  }

  // Debounce search
  searchTimeout = setTimeout(() => {
    if (searchTerm.length > 2) {
      searchProducts(searchTerm);
    }
  }, 300);
}

/**
 * Navigate to search page with query
 */
function navigateToSearch(searchTerm) {
  if (searchTerm && searchTerm.trim().length > 0) {
    window.location.href = '/search?q=' + encodeURIComponent(searchTerm.trim());
  }
}
