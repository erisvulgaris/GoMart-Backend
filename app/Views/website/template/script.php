<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?= base_url('/assets/website/js/custom.js') ?>"></script>

<script>
	function toggleDark() {
		const html = document.documentElement;
		if (html.classList.contains('dark')) {
			html.classList.remove('dark');
			localStorage.setItem('theme', 'light');
			document.getElementById('theme-icon').textContent = '🌙';
		} else {
			html.classList.add('dark');
			localStorage.setItem('theme', 'dark');
			document.getElementById('theme-icon').textContent = '☀️';
		}
	}

	// sync icon on load
	if (localStorage.getItem('theme') === 'dark') {
		document.getElementById('theme-icon').textContent = '☀️';
	}
</script>

<script>
	function hideLoader() {
		const loader = document.getElementById('pageLoader');
		if (loader) {
			loader.classList.add('fade-out');
			loader.addEventListener('animationend', () => loader.remove());
		}
	}

	function simulateProgress() {
		const progressBar = document.querySelector('.progress-bar');
		let width = 0;
		const interval = setInterval(() => {
			if (width >= 100) {
				clearInterval(interval);
			} else {
				width += Math.random() * 10;
				progressBar.style.width = Math.min(width, 100) + '%';
			}
		}, 300);
	}

	window.addEventListener('load', () => {
		simulateProgress();
		setTimeout(hideLoader, 1500);
	});

	setTimeout(hideLoader, 10000);
</script>


<script>
	const dropdownUserLink = document.getElementById('dropdownUserLink');
	const dropdownUser = document.getElementById('dropdownUser');

	// Toggle dropdown visibility
	if (dropdownUserLink) {
		dropdownUserLink.addEventListener('click', function(event) {
			event.preventDefault();
			dropdownUser.classList.toggle('hidden');
		});
	}


	// Close dropdown when clicking outside
	document.addEventListener('click', function(event) {
		if (dropdownUserLink) {
			const isClickInside = dropdownUserLink.contains(event.target);
			if (!isClickInside) {
				dropdownUser.classList.add('hidden');
			}
		}
	});

	async function toggleShoppingCart() {
		const miniShoppingCart = document.getElementById('mini-shopping-cartRight');
		if (miniShoppingCart.classList.contains('show')) {
			miniShoppingCart.classList.remove('show');
			const backdropDiv = document.querySelector('.mini-shopping-cart-backdrop');
			if (backdropDiv) {
				backdropDiv.remove();
			}
		} else {
			miniShoppingCart.classList.add('show');
			const backdropDiv = document.createElement('div'); // Create the div element
			backdropDiv.className = 'mini-shopping-cart-backdrop fade show'; // Add the class names
			document.body.appendChild(backdropDiv); // Append the div to the body
		}

		let guest_id = localStorage.getItem('guest_id');

		try {
			const response = await fetch('/cartItemList', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					guest_id,
				}),
			});

			const result = await response.json();

			if (result.status === 'success') {
				if (!result.sellers && !result.productItems) {
					document.getElementById('emptyCartDiv').classList.add('block');
				}

				// Check for sellers
				document.querySelector('.mini-shopping-cart-footer').innerHTML = '';
				if (result.sellers) {
					Array.from(document.getElementsByClassName('cartsHeading')).forEach((element) => {
						element.textContent = 'Your Carts (' + result.sellers.length + ')'
					});
					const sellerList = document.getElementById('mini-seller-list');
					sellerList.innerHTML = ''; // Clear existing seller list

					result.sellers.forEach((seller) => {
						const sellerHtml = `
							<li class="py-3 border-b border-gray-300 dark:border-gray-700">
								<div class="flex items-center justify-between">
									<!-- Left Section -->
									<div class="flex items-center gap-3">
										<img src="${seller.logo}" alt="${seller.store_name}" class="w-12 h-12 rounded-full border dark:border-gray-600" />
										<div>
											<span class="text-base font-medium block dark:text-white">${seller.store_name}</span>
											<span class="text-sm text-gray-500 dark:text-gray-400 block">Total items: ${seller.item_count}</span>
										</div>
									</div>
									<!-- Right Section -->
									<div class="flex flex-col items-end">
										<a href="/cart/${seller.seller_id}" class="flex flex-col text-center bg-green-600 text-white text-sm px-4 py-1 rounded-lg shadow-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
											<?php echo lang('website.view_cart'); ?>
											<span class="text-sm mt-1">Items: ${seller.item_count}</span>
										</a>
									</div>
								</div>
							</li>
						`;
						sellerList.insertAdjacentHTML('beforeend', sellerHtml);
					});


					return; // Exit function if sellers are returned
				}

				// If product items exist, execute the toggleShoppingCart logic
				if (result.productItems) {
					Array.from(document.getElementsByClassName('cartsHeading')).forEach((element) => {
						element.textContent = 'Your Carts (' + result.productItems.length + ')'
					});

					const cartItemList = document.getElementById('mini-shop-cart-item-list');
					cartItemList.innerHTML = ''; // Clear existing items

					const currency_symbol = result.currency_symbol;
					const currency_symbol_position = result.currency_symbol_position;

					const formatPrice = (price) => {
						return currency_symbol_position === 'left' ?
							`${currency_symbol}${price}` :
							`${price}${currency_symbol}`;
					};

					result.productItems.forEach((product) => {
						let priceHtml = product.discounted_price > 0 ?
							`
							<div class="flex gap-2">
								<span class="font-bold text-gray-800 dark:text-gray-100">${formatPrice(product.discounted_price)}</span>
								<div class="line-through text-gray-500 dark:text-gray-400 text-sm self-end">${formatPrice(product.price)}</div>
							</div>
						` :
							`
							<div class="flex gap-2">
								<span class="font-bold text-gray-800 dark:text-gray-100">${formatPrice(product.price)}</span>
							</div>
						`;

						let newHtml = `
						<li class="py-2 pl-2 pr-4 border-gray-300 border-b py-3 border-gray-200 dark:border-gray-700 ${product.slug}-maindiv-${product.product_variant_id}">
							<div class="flex gap-5">
								<img src="${product.main_img}" alt="${product.product_name}" class="w-28 h-28 border border-gray-300 dark:border-gray-600 rounded-lg" />
								<div class="flex flex-col gap-1 w-full">
									<div>
										<a href="#" class="text-base font-semibold dark:text-white">
											<h6 class="">${product.product_name}</h6>
										</a>
										<span class="text-gray-500 dark:text-gray-400 text-sm">${product.variant_title}</span>
									</div>
									${priceHtml}
									<div class="flex items-center justify-between">
										<div class="${product.slug}-mainbtndiv-${product.product_variant_id}">
											<div class="flex items-center gap-1 p-1 rounded-lg bg-green-700 border border-green-700 shadow-md">
												<button type="button" onclick="removeFromCart(${product.product_id}, ${product.product_variant_id})" class="text-lg leading-none hover:text-primary ${product.slug}-removebtn-${product.product_variant_id}">
													<i class="fi fi-rr-minus-small text-white"></i>
												</button>
												<span class="text-center h-5 text-sm font-medium text-white ${product.slug}-qty-${product.product_variant_id}">${product.quantity}</span>
												<button type="button" onclick="addToCart(${product.product_id}, ${product.product_variant_id})" class="text-lg leading-none hover:text-primary ${product.slug}-addbtn-${product.product_variant_id}">
													<i class="fi fi-rr-plus-small text-white"></i>
												</button>
											</div>
										</div>
										<div class="text-sm bg-red-100 dark:bg-red-900/30 p-1 rounded-lg shadow">
											<button class="text-red-900 dark:text-red-400 flex gap-1" onclick="removeItem(${product.product_id}, ${product.product_variant_id})">
												<span class="align-text-bottom">
													<i class="fi fi-tr-trash-xmark text-xs"></i>
												</span>
												<span class="text-gray-500 dark:text-gray-400 text-xs text-red-600 dark:text-red-400"><?php echo lang('website.remove'); ?></span>
											</button>
										</div>
									</div>
								</div>
							</div>
						</li>
					`;
						cartItemList.insertAdjacentHTML('beforeend', newHtml);
					});


					let cartFooterHtml = `
					<div class="p-4 w-full border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 bottom-0 shadow-lg">
						<div class="flex flex-col space-y-3">
							<div class="grid gap-2">
								<a href="/checkout" class="flex justify-between items-center bg-green-600 text-white rounded-lg p-3 shadow-md hover:bg-green-700 hover:border-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 active:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
									<span class="text-lg font-medium"><?php echo lang('website.go_to_checkout'); ?></span>
									<span class="font-bold subtotal text-white">0</span>
								</a>
							</div>
							<p class="text-center text-sm text-gray-600 dark:text-gray-400">
							<?php echo lang('website.delivery_Taxes_&_Discounts_calculated_at_checkout'); ?>
							</p> 
						</div>
					</div>
					`;

					// Add the cartFooterHtml to the DOM
					document.querySelector('.mini-shopping-cart-footer').innerHTML = cartFooterHtml;

					// Update the subtotal once the footer is loaded
					Array.from(document.getElementsByClassName('subtotal')).forEach((element) => {
						element.textContent = formatPrice(result.subtotal);
					});

					<?php if (!$settings['seller_only_one_seller_cart']): ?>
						discountedPricesavingHtmlManipulate(result.discountedPricesaving, result.currency_symbol, result.currency_symbol_position)
					<?php endif; ?>
				}


			} else {
				// Handle error
			}
		} catch (error) {
			console.log(error);
		}
	}


	//common model product varient popup code
	const closeModalButton = document.getElementById('closeModalButton');
	const modalOverlay = document.getElementById('modalOverlay');
	const modal = document.getElementById('modal');

	function openProductVariantPopup(product_id, slug) {
		// Fetch product and variant details
		fetch(`/product/variants/${product_id}`)
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					// Assuming you have a modal where product data should be displayed
					const product = data.product;
					const variants = data.variants;

					const currency_symbol = data.currency_symbol;
					const currency_symbol_position = data.currency_symbol_position;

					// Example: Set product name in the modal
					document.getElementById('modalProductName').textContent = product.product_name;

					// Assuming the variantsContainer is your #productVariantData element
					const variantsContainer = document.getElementById('productVarientData');

					// Clear previous variants (optional)
					variantsContainer.innerHTML = '';

					// Function to format price with currency symbol based on its position
					const formatPrice = (price) => {
						return currency_symbol_position === 'left' ?
							`${currency_symbol}${price}` :
							`${price}${currency_symbol}`;
					};

					if (variants.length > 1) {
						variants.forEach(variant => {
							const variantElement = document.createElement('div');
							variantElement.classList.add('flex', 'justify-between', 'items-center', 'mb-2');

							const formattedPrice = variant.discounted_price > 0 ?
								formatPrice(variant.discounted_price) :
								formatPrice(variant.price);

							// Calculate discount percentage
							const discountPercent = variant.discounted_price > 0 && variant.discounted_price < variant.price ?
								Math.round((1 - variant.discounted_price / variant.price) * 100) :
								0;
							const hasDiscount = discountPercent > 0;
							const discountedPrice = formatPrice(variant.discounted_price);
							const originalPrice = formatPrice(variant.price);

							variantElement.innerHTML = `
								<div class="flex-1">
    <div style="font-size: 15px" class="dark:text-white">${variant.title}</div>
    <div class="flex items-baseline gap-2">
        ${hasDiscount ? `
            <span class="font-bold text-gray-800 dark:text-white">${discountedPrice}</span>
            <span class="line-through text-gray-500 text-sm dark:text-gray-400">${originalPrice}</span>
            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-0.5 rounded-lg dark:bg-green-900 dark:text-green-300">${discountPercent}% off</span>
        ` : `
            <span class="font-bold text-gray-800 dark:text-white">${originalPrice}</span>
        `}
    </div>
</div>
<div class="${slug}-mainbtndiv-${variant.id}">
    <button type="button" onclick="addToCart(${product_id}, ${variant.id})" 
        class="text-sm px-2 py-1 rounded-lg items-center gap-x-1 bg-green-700 text-white border-green-700 disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-green-700 hover:border-green-700 dark:bg-green-600 dark:border-green-600 dark:text-white dark:hover:bg-green-500 dark:hover:border-green-500 btn-sm ${slug}-${variant.id}">
        <i class="fi fi-rr-shopping-cart"></i>
        <span><?php echo lang('website.add'); ?></span>
    </button>
</div>
							`;

							// Append this variant to the container
							variantsContainer.appendChild(variantElement);
						});

						// Show the modal
						document.getElementById('modal').classList.remove('hidden');
						document.getElementById('modalOverlay').classList.remove('hidden');
					} else {
						addToCart(product_id, variants[0].id)
					}
				} else {
					console.error('Product not found');
				}
			})
			.catch(error => console.error('Error fetching product data:', error));
	}

	function discountedPricesavingHtmlManipulate(discountedPricesaving, currency_symbol, currency_symbol_position) {
		document.querySelector('.discountedPricesaving').innerHTML = '';
		if (+discountedPricesaving > 0) {
			let discountedPricesavingHtml = `<div class="flex items-center justify-between bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-lg shadow-lg p-4 w-full">
                    <div class="flex items-center gap-4">
                        <div class="bg-emerald-400 dark:bg-emerald-600 text-white font-bold rounded-full w-12 h-12 flex items-center justify-center shadow-lg">
                            <i class="fi fi-rr-piggy-bank text-lg"></i>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-emerald-800 dark:text-emerald-300"><?php echo lang('website.congratulations'); ?>!</p>
                            <p class="text-sm text-emerald-600 dark:text-emerald-400"><?php echo lang('website.youre_saving'); ?> <span class="font-bold text-emerald-800 dark:text-emerald-300 discountedPricesavingAmt"></span> <?php echo lang('website.on_this_purchase'); ?>!</p>
                        </div>
                    </div>
                </div>`;

			document.querySelector('.discountedPricesaving').innerHTML = discountedPricesavingHtml;

			// Format amount based on currency position
			let formattedAmount = currency_symbol_position === 'left' ?
				`${currency_symbol}${discountedPricesaving}` :
				`${discountedPricesaving}${currency_symbol}`;

			Array.from(document.getElementsByClassName('discountedPricesavingAmt')).forEach((element) => {
				element.textContent = formattedAmount;
			});
		}
	}

	async function addToCart(product_id, variant_id) {
		let guest_id = localStorage.getItem('guest_id');

		localStorage.setItem('wallet', JSON.stringify({
			wallet_applied: 0,
			remaining_wallet_balance: 0
		}));

		localStorage.setItem('wallet', JSON.stringify({
			coupon_id: 0,
			coupon_code: '',
			coupon_amount: 0,
			coupon_minOrderAmount: 0,
			coupon_type: 0
		}));

		try {
			const response = await fetch('/addToCart', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					product_id,
					variant_id,
					guest_id
				}),
			});

			const result = await response.json(); // Await here to parse the JSON response

			if (result.status === 'success') {
				const currency_symbol = result.currency_symbol;
				const currency_symbol_position = result.currency_symbol_position;

				const formatPrice = (price) => {
					return currency_symbol_position === 'left' ?
						`${currency_symbol}${price}` :
						`${price}${currency_symbol}`;
				};

				// Handle success
				let mainAddBtn = document.getElementsByClassName(`${result.slug}-${variant_id}`)
				while (mainAddBtn.length > 0) {
					mainAddBtn[0].parentNode.removeChild(mainAddBtn[0]);
				}

				let mainbtndiv = document.getElementsByClassName(`${result.slug}-mainbtndiv-${variant_id}`)
				let cartQuantity = result.quantity;

				// Generate HTML string to insert
				let newHtml = `
					<div class="inline-flex items-center gap-1 p-1 rounded-lg bg-green-700 border border-green-700 shadow-md">
						<button type="button" onclick="removeFromCart(${product_id}, ${variant_id})"
							class="text-lg leading-none hover:text-primary ${result.slug}-removebtn-${variant_id}">
							<i class="fi fi-rr-minus-small text-white"></i>
						</button>
						<span class="text-center h-5 text-sm font-medium text-white ${result.slug}-qty-${variant_id}">${cartQuantity}</span>
						<button type="button" onclick="addToCart(${product_id}, ${variant_id})"
							class="text-lg leading-none hover:text-primary ${result.slug}-addbtn-${variant_id}">
							<i class="fi fi-rr-plus-small text-white"></i>
						</button>
					</div>
				`;

				// Insert the new HTML into each matching element
				for (let i = 0; i < mainbtndiv.length; i++) {
					mainbtndiv[i].innerHTML = newHtml;
				}

				let cartCount = document.getElementById('cartCount');
				cartCount.innerText = result.itemCount
				updateCartBar(result.itemCount, result.itemImages);

				let subtotalElements = document.getElementsByClassName('subtotal');
				for (let i = 0; i < subtotalElements.length; i++) {
					subtotalElements[i].textContent = formatPrice(result.subtotal);
				}

				discountedPricesavingHtmlManipulate(result.discountedPricesaving, result.currency_symbol, result.currency_symbol_position)

				showToast(result.message, "success");

			} else {
				// Handle error
				showToast(result.message, "danger");

			}
		} catch (error) {
			console.log(error);
		}
	}

	async function removeFromCart(product_id, variant_id) {
		let guest_id = localStorage.getItem('guest_id');

		localStorage.setItem('wallet', JSON.stringify({
			wallet_applied: 0,
			remaining_wallet_balance: 0
		}));

		localStorage.setItem('wallet', JSON.stringify({
			coupon_id: 0,
			coupon_code: '',
			coupon_amount: 0,
			coupon_minOrderAmount: 0,
			coupon_type: 0
		}));

		try {
			const response = await fetch('/removeFromCart', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					product_id,
					variant_id,
					guest_id
				}),
			});

			const result = await response.json();
			console.log(result)
			if (result.status === 'success') {
				let cartQuantity = result.quantity;
				let qtySpans = document.getElementsByClassName(`${result.slug}-qty-${variant_id}`)

				for (let i = 0; i < qtySpans.length; i++) {
					qtySpans[i].textContent = cartQuantity;
				}

				const currency_symbol = result.currency_symbol;
				const currency_symbol_position = result.currency_symbol_position;

				const formatPrice = (price) => {
					return currency_symbol_position === 'left' ?
						`${currency_symbol}${price}` :
						`${price}${currency_symbol}`;
				};

				let cartCount = document.getElementById('cartCount');
				cartCount.innerText = result.itemCount
				updateCartBar(result.itemCount, result.itemImages);

				let subtotalElements = document.getElementsByClassName('subtotal');
				for (let i = 0; i < subtotalElements.length; i++) {
					subtotalElements[i].textContent = formatPrice(result.subtotal);
				}
				showToast(result.message, "success");

				discountedPricesavingHtmlManipulate(result.discountedPricesaving, result.currency_symbol, result.currency_symbol_position)


			} else {
				let mainAddBtn = document.getElementsByClassName(`${result.slug}-${variant_id}`)
				showToast(result.message, "danger");

			}
		} catch (error) {
			console.log(error)
		}
	}

	async function removeItem(product_id, variant_id) {
		let guest_id = localStorage.getItem('guest_id');

		localStorage.setItem('wallet', JSON.stringify({
			wallet_applied: 0,
			remaining_wallet_balance: 0
		}));

		localStorage.setItem('wallet', JSON.stringify({
			coupon_id: 0,
			coupon_code: '',
			coupon_amount: 0,
			coupon_minOrderAmount: 0,
			coupon_type: 0
		}));

		try {
			const response = await fetch('/removeItem', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					product_id,
					variant_id,
					guest_id
				}),
			});

			const result = await response.json();
			console.log(result)
			if (result.status === 'success') {
				let removeItems = document.getElementsByClassName(`${result.slug}-maindiv-${variant_id}`);
				Array.from(removeItems).forEach((removeItem) => {
					removeItem.remove();
				});

				let cartCount = document.getElementById('cartCount');
				cartCount.innerText = result.itemCount
				updateCartBar(result.itemCount, result.itemImages);

				const currency_symbol = result.currency_symbol;
				const currency_symbol_position = result.currency_symbol_position;

				const formatPrice = (price) => {
					return currency_symbol_position === 'left' ?
						`${currency_symbol}${price}` :
						`${price}${currency_symbol}`;
				};

				let subtotalElements = document.getElementsByClassName('subtotal');
				for (let i = 0; i < subtotalElements.length; i++) {
					subtotalElements[i].textContent = formatPrice(result.subtotal);
				}
				showToast(result.message, "success");
				discountedPricesavingHtmlManipulate(result.discountedPricesaving, result.currency_symbol, result.currency_symbol_position)


			} else {
				showToast(result.message, "danger");

			}
		} catch (error) {
			console.log(error)
		}
	}

	if (closeModalButton) {
		closeModalButton.addEventListener('click', () => {
			modal.classList.add('hidden');
			modalOverlay.classList.add('hidden');
		});
	}

	// Hide modal when clicking outside modal content
	if (modalOverlay) {
		modalOverlay.addEventListener('click', () => {
			modal.classList.add('hidden');
			modalOverlay.classList.add('hidden');
		});
	}
</script>

<!-- /this script for productDetails -->
<script>
	// Function to show the relevant tab pane
	function showTab(element) {
		// Get all tab buttons and remove 'active-tab' class from them
		const allTabs = document.querySelectorAll('.nav-link');
		allTabs.forEach(tab => tab.classList.remove('active-tab'));

		// Add 'active-tab' class to the clicked button
		element.classList.add('active-tab');

		// Get all tab panes and hide them
		const allTabPanes = document.querySelectorAll('.tab-pane');
		allTabPanes.forEach(pane => pane.classList.add('hidden'));

		// Get the target tab-pane from the clicked button
		const targetPaneId = element.getAttribute('data-bs-target');
		const targetPane = document.querySelector(targetPaneId);

		// Show the target tab pane
		targetPane.classList.remove('hidden');
		targetPane.classList.add('block');
	}

	function zoom(f) {
		var t = f.currentTarget;
		offsetX = f.offsetX || f.touches[0].pageX, f.offsetY ? offsetY = f.offsetY : offsetX = f.touches[0].pageX, x = offsetX / t.offsetWidth * 100, y = offsetY / t.offsetHeight * 100, t.style.backgroundPosition = x + "% " + y + "%"
	}

	// Initialize Swiper
	const mainSwiper = new Swiper('#productSwiper', {
		slidesPerView: 1,
		spaceBetween: 10,
		on: {
			slideChange: updateActiveThumbnail // Update active thumbnail on slide change
		}
	});

	// Get all thumbnail elements
	const thumbnails = document.querySelectorAll('#productThumbnails .thumbnails-img');

	// Function to set the active thumbnail
	function updateActiveThumbnail() {
		// Remove active class from all thumbnails
		thumbnails.forEach(thumbnail => thumbnail.classList.remove('active-thumbnail'));

		// Add active class to the current thumbnail
		const activeIndex = mainSwiper.activeIndex;
		if (thumbnails[activeIndex]) {
			thumbnails[activeIndex].classList.add('active-thumbnail');
		}
	}

	// Add click event to each thumbnail
	thumbnails.forEach((thumbnail, index) => {
		thumbnail.addEventListener('click', () => {
			mainSwiper.slideTo(index); // Slide to the clicked thumbnail index
		});
	});

	// Set the initial active thumbnail
	updateActiveThumbnail();

	function copyLink() {
		const url = window.location.href;
		navigator.clipboard.writeText(url)
			.then(() => alert('Link copied to clipboard!'))
			.catch(err => console.error('Error copying text: ', err));
	}

	// Check if the shareButton exists in the DOM
	const shareButton = document.getElementById('shareButton');
	if (shareButton) {
		// Add event listener only if shareButton is available
		shareButton.addEventListener('click', async () => {
			if (navigator.share) {
				try {
					await navigator.share({
						title: 'Check out this page!',
						url: '<?= current_url(); ?>',
					});
					showToast('Successfully shared', 'success');
				} catch (error) {
					console.error('Error sharing:', error);
				}
			} else {
				showToast('Web Share API not supported in your browser.', 'error');
			}
		});
	} else {
		console.log('shareButton element is not available in the DOM.');
	}
</script>

<!-- /from get location code start -->
<script>
	(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__gu__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]);for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.googleapis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once."):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
		key: "<?= $settings['map_api_key'] ?>",
		v: "weekly"
	});
</script>
<script>
	const locationData = {
		city: '',
		state: '',
		country: '',
		postalCode: '',
		lat: '',
		lng: '',
		area: '',
		landmark: '',
		formatted_address: '',
		city_id: 0,
		deliverable_area_id: 0
	};

	const locationModal = document.getElementById('locationModal');
	const cityNotFoundMsg = document.getElementById('cityNotFoundMsg');
	const suggestionsContainer = document.getElementById('citySuggestions');

	let locationMap = null;
	let mapGeocoder = null;
	let mapDragTimeout = null;
	let pendingMapLocationData = null;

	function setGuestId() {
		if (!localStorage.getItem('guest_id')) {
			const randomGuestId = Math.floor(100000 + Math.random() * 900000);
			localStorage.setItem('guest_id', randomGuestId);
		}
	}

	function openLocationModel() {
		locationModal.classList.remove('hidden');
		document.body.classList.add('modal-open');
		// Always show step 1 when opening
		showLocationStep(1);
	}

	function closeLocationModal() {
		// Only allow close if a location is already saved
		const saved = JSON.parse(localStorage.getItem('location') || '{}');
		if (saved && saved.city) {
			locationModal.classList.add('hidden');
			document.body.classList.remove('modal-open');
		}
	}

	function showLocationStep(step) {
		const step1 = document.getElementById('locationStep1');
		const step2 = document.getElementById('locationStep2');
		if (step === 1) {
			step1.classList.remove('hidden');
			step2.classList.add('hidden');
			document.getElementById('locationModalTitle').textContent = '<?php echo lang('website.delivery_to');?>';
		} else {
			step1.classList.add('hidden');
			step2.classList.remove('hidden');
			document.getElementById('locationModalTitle').textContent = 'Adjust Pin';
		}
	}

	function goBackToSearch() {
		showLocationStep(1);
		document.getElementById('citySearch').value = '';
		suggestionsContainer.classList.add('hidden');
		cityNotFoundMsg.classList.add('hidden');
	}

	// ── Map Picker ──
	async function showMapStep(lat, lng, locData) {
		pendingMapLocationData = locData || Object.assign({}, locationData);
		pendingMapLocationData.lat = lat;
		pendingMapLocationData.lng = lng;

		// Show step 2 FIRST so the map container has dimensions
		showLocationStep(2);

		const center = { lat: parseFloat(lat), lng: parseFloat(lng) };

		// Ensure maps & geocoding libraries are loaded
		await google.maps.importLibrary("maps");
		await google.maps.importLibrary("geocoding");

		if (!locationMap) {
			// Use google.maps.Map directly (same pattern as address page)
			locationMap = new google.maps.Map(document.getElementById('locationMap'), {
				center: center,
				zoom: 16,
				disableDefaultUI: true,
				zoomControl: true,
				gestureHandling: 'greedy',
			});

			mapGeocoder = new google.maps.Geocoder();

			// Reverse-geocode when map stops moving
			locationMap.addListener('idle', function() {
				clearTimeout(mapDragTimeout);
				mapDragTimeout = setTimeout(function() {
					reverseGeocodeMapCenter();
				}, 400);
			});
		} else {
			locationMap.setCenter(center);
		}

		// Initial reverse-geocode
		setTimeout(function() { reverseGeocodeMapCenter(); }, 500);
	}

	function reverseGeocodeMapCenter() {
		if (!locationMap || !mapGeocoder) return;
		const center = locationMap.getCenter();
		const overlay = document.getElementById('mapLoadingOverlay');
		overlay.classList.remove('hidden');

		mapGeocoder.geocode({ location: { lat: center.lat(), lng: center.lng() } }, function(results, status) {
			overlay.classList.add('hidden');
			if (status === 'OK' && results[0]) {
				const r = results[0];
				const comps = r.address_components;
				let area = '', city = '', state = '', country = '', postalCode = '';
				comps.forEach(function(c) {
					if (c.types.includes('sublocality') || c.types.includes('neighborhood')) area = c.long_name;
					else if (c.types.includes('locality')) city = c.long_name;
					else if (c.types.includes('administrative_area_level_1')) state = c.long_name;
					else if (c.types.includes('country')) country = c.long_name;
					else if (c.types.includes('postal_code')) postalCode = c.long_name;
				});

				pendingMapLocationData.lat = center.lat();
				pendingMapLocationData.lng = center.lng();
				pendingMapLocationData.city = city;
				pendingMapLocationData.state = state;
				pendingMapLocationData.country = country;
				pendingMapLocationData.postalCode = postalCode;
				pendingMapLocationData.area = area;
				pendingMapLocationData.formatted_address = r.formatted_address || '';

				document.getElementById('mapSelectedArea').textContent = area || city || 'Selected Location';
				document.getElementById('mapSelectedAddress').textContent = r.formatted_address || (city + ', ' + state);
			}
		});
	}

	function confirmMapLocation() {
		if (!pendingMapLocationData || !pendingMapLocationData.city) return;

		var btn = document.getElementById('confirmLocationBtn');
		btn.disabled = true;
		btn.textContent = 'Confirming...';

		fetch('/fetchDeliverableAreaByLatLong', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				name: pendingMapLocationData.city,
				lat: pendingMapLocationData.lat,
				lng: pendingMapLocationData.lng,
				guest_id: localStorage.getItem('guest_id')
			}),
		})
		.then(function(res) { return res.json(); })
		.then(function(data) {
			if (data.id > 0) {
				pendingMapLocationData.city_id = data.id;
				pendingMapLocationData.deliverable_area_id = data.deliverable_area_id;
				if (data.delivery_time) {
					document.getElementById("proxyDeliveryTime").textContent = data.delivery_time;
					document.getElementById("proxyDeliveryTimeWrapper").classList.remove('hidden');
					document.getElementById("deliveryLabelDefault").classList.add('hidden');
					document.getElementById("deliveryLabelWithTime").classList.remove('hidden');
					document.getElementById("deliveryLabelWithTime").classList.add('flex');
					setProductDeliveryTime(data.delivery_time, data.distance_km);
				}
				localStorage.setItem('location', JSON.stringify(pendingMapLocationData));
				locationModal.classList.add('hidden');
				document.body.classList.remove('modal-open');
				setLocationBar();
				location.reload();
			} else {
				btn.disabled = false;
				btn.textContent = 'Confirm Location';
				// Go back to step 1 and show not-found message
				showLocationStep(1);
				cityNotFoundMsg.classList.remove('hidden');
			}
		})
		.catch(function() {
			btn.disabled = false;
			btn.textContent = 'Confirm Location';
		});
	}

	// ── Autocomplete (New Places API) ──
	let searchDebounceTimer = null;

	async function initAutocomplete() {
		// Pre-load the places library so it's ready when user starts typing
		await google.maps.importLibrary("places");
	}

	function searchCity(query) {
		if (query.length < 3) {
			suggestionsContainer.classList.add('hidden');
			return;
		}
		// Debounce to avoid excessive API calls
		clearTimeout(searchDebounceTimer);
		searchDebounceTimer = setTimeout(function() {
			fetchSuggestions(query);
		}, 300);
	}

	async function fetchSuggestions(query) {
		try {
			const { AutocompleteSuggestion } = await google.maps.importLibrary("places");
			const request = { input: query };
			const { suggestions } = await AutocompleteSuggestion.fetchAutocompleteSuggestions(request);

			if (suggestions && suggestions.length > 0) {
				displaySuggestions(suggestions);
			} else {
				suggestionsContainer.classList.add('hidden');
			}
		} catch (e) {
			console.error('Autocomplete error:', e);
			suggestionsContainer.classList.add('hidden');
		}
	}

	function displaySuggestions(suggestions) {
		cityNotFoundMsg.classList.add('hidden');
		suggestionsContainer.innerHTML = '';
		suggestionsContainer.classList.remove('hidden');

		suggestions.forEach(function(suggestion) {
			var prediction = suggestion.placePrediction;
			var mainText = prediction.mainText ? prediction.mainText.toString() : '';
			var secondaryText = prediction.secondaryText ? prediction.secondaryText.toString() : '';

			var item = document.createElement('div');
			item.className = 'flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-50 dark:border-gray-700 last:border-0 transition-colors';
			item.innerHTML =
				'<div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-600 flex items-center justify-center flex-shrink-0">' +
					'<i class="fi fi-rr-marker text-gray-500 dark:text-gray-300 text-xs"></i>' +
				'</div>' +
				'<div class="min-w-0 flex-1">' +
					'<p class="text-sm text-gray-800 dark:text-white truncate font-medium">' + mainText + '</p>' +
					'<p class="text-xs text-gray-500 dark:text-gray-400 truncate">' + secondaryText + '</p>' +
				'</div>' +
				'<i class="fi fi-rr-arrow-up-left text-gray-300 text-xs flex-shrink-0"></i>';

			item.onclick = async function() {
				try {
					const { Place } = await google.maps.importLibrary("places");
					const place = new Place({ id: prediction.placeId });
					await place.fetchFields({ fields: ['location', 'displayName', 'formattedAddress', 'addressComponents'] });

					var locData = {
						city: '', state: '', country: '', postalCode: '',
						lat: place.location.lat(),
						lng: place.location.lng(),
						area: '', landmark: place.displayName || '',
						formatted_address: place.formattedAddress || '',
						city_id: 0, deliverable_area_id: 0
					};

					if (place.addressComponents) {
						place.addressComponents.forEach(function(component) {
							var types = component.types;
							if (types.includes("locality")) locData.city = component.longText;
							else if (types.includes("administrative_area_level_1")) locData.state = component.longText;
							else if (types.includes("country")) locData.country = component.longText;
							else if (types.includes("postal_code")) locData.postalCode = component.longText;
							else if (types.includes("sublocality") || types.includes("neighborhood")) locData.area = component.longText;
						});
					}

					// Go to map step to let user adjust pin
					showMapStep(locData.lat, locData.lng, locData);
				} catch (e) {
					console.error('Place details error:', e);
				}
			};
			suggestionsContainer.appendChild(item);
		});
	}

	async function fetchCartItemCount() {
		let cartCount = document.getElementById('cartCount');
		let guest_id = localStorage.getItem('guest_id');

		try {
			const response = await fetch('/fetchCartItemCount', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					guest_id
				}),
			});
			const result = await response.json();

			if (result.status === 'success') {
				cartCount.innerText = result.itemCount
				updateCartBar(result.itemCount, result.itemImages);
			} else {}
		} catch (error) {
			console.log(error);
		}

	}

	function updateCartBar(count, itemImages = []) {
		count = parseInt(count) || 0;
		const isMobile = window.innerWidth < 768;
		const label = count === 1 ? '1 item' : count + ' items';
		const images = Array.isArray(itemImages) ? itemImages.slice(0, 3) : [];

		function buildImagesHtml(imgs) {
			if (!imgs.length) return '<i class="fi fi-tr-cart-shopping-fast text-xl leading-none opacity-80"></i>';
			return imgs.map((src, i) =>
				`<img src="${src}" class="w-9 h-9 rounded-full object-contain bg-white border-2 border-green-600" style="margin-left:${i > 0 ? '-20px' : '0'};z-index:${imgs.length - i}" />`
			).join('');
		}

		// Mobile bar
		const mobileBar = document.getElementById('mobileViewCartBar');
		const mobileCount = document.getElementById('mobileCartBarCount');
		const mobileImgs = document.getElementById('mobileCartImages');
		if (mobileBar && mobileCount) {
			mobileCount.textContent = label;
			if (mobileImgs) mobileImgs.innerHTML = buildImagesHtml(images);
			mobileBar.classList.toggle('hidden', !(count > 0 && isMobile));
		}

		// Desktop button
		const desktopBtn = document.getElementById('desktopViewCartBtn');
		const desktopCount = document.getElementById('desktopCartBarCount');
		const desktopImgs = document.getElementById('desktopCartImages');
		if (desktopBtn && desktopCount) {
			if (desktopImgs) desktopImgs.innerHTML = buildImagesHtml(images);
			desktopCount.textContent = count > 0 ? 'View cart · ' + label : 'View cart';
			desktopBtn.style.display = (count > 0 && !isMobile) ? 'flex' : 'none';
		}
	}

	function setProductDeliveryTime(time, distanceKm) {
		if (!time) return;
		try {
			const loc = JSON.parse(localStorage.getItem('location') || '{}');
			loc.delivery_time = time;
			if (distanceKm) loc.distance_km = distanceKm;
			localStorage.setItem('location', JSON.stringify(loc));
		} catch(e) {}
		document.querySelectorAll('.productDeliveryTime').forEach(function(el) {
			el.querySelector('.productDeliveryTimeVal').textContent = time;
			el.classList.remove('hidden');
		});
		if (typeof fetchProductList === 'function') {
			fetchProductList();
		}
	}

	window.onload = function() {
		setGuestId()
		initAutocomplete();
		setLocationBar()
		fetchCartItemCount();
		let locationData = JSON.parse(localStorage.getItem('location'));



		if (locationData && locationData.city) {
			fetch('/fetchDeliverableAreaByLatLong', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify({
						name: locationData.city,
						lat: locationData.lat,
						lng: locationData.lng,
						guest_id: localStorage.getItem('guest_id'),
					}), // Send city name in the request
				})
				.then(response => {
					if (!response.ok) {
						throw new Error('City not found');
					}
					return response.json();
				})
				.then(data => {
					if (data.id > 0) {
						if (data.delivery_time) {
							document.getElementById("proxyDeliveryTime").textContent = data.delivery_time;
							document.getElementById("proxyDeliveryTimeWrapper").classList.remove('hidden');
							document.getElementById("deliveryLabelDefault").classList.add('hidden');
							document.getElementById("deliveryLabelWithTime").classList.remove('hidden');
							document.getElementById("deliveryLabelWithTime").classList.add('flex');
							setProductDeliveryTime(data.delivery_time, data.distance_km);
						}
					} else {
						// Area/city was deleted from DB — clear stale localStorage to prevent
						// infinite reload loop on loader.php
						localStorage.removeItem('location');
						locationModal.classList.remove('hidden');
						document.body.classList.add('modal-open');
					}
				})
				.catch(error => {
					console.error('Error fetching city ID:', error);
				});
		} else {
			locationModal.classList.remove('hidden');
			document.body.classList.add('modal-open');
		}
	};

	function setLocationBar() {
		var loc = JSON.parse(localStorage.getItem('location') || '{}');
		var el = document.getElementById('locationBarSubtitle');
		if (loc && loc.city) {
			// Show full address if available, otherwise fall back to city, state, country
			var display = loc.formatted_address || (loc.city + ', ' + loc.state + ', ' + loc.country);
			el.innerText = display;
		} else {
			el.innerText = 'Choose Location';
		}
	}

	// Get user's current location via GPS, then open map step
	function useMyLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
		} else {
			alert("Geolocation is not supported by this browser.");
		}
	}

	function successCallback(position) {
		// Open map step directly with GPS coordinates — user can adjust pin there
		showMapStep(position.coords.latitude, position.coords.longitude, null);
	}

	function errorCallback(error) {
		console.error(error);
		alert("Unable to retrieve your location");
	}
</script>

<script>
	async function uploadUserProfilePic(event) {
		const file = event.target.files[0];

		const formData = new FormData();
		formData.append('file', file);

		try {
			const response = await fetch('/uploadUserProfilePic', {
				method: 'POST',
				body: formData,
			});

			const result = await response.json();

			if (response.ok) {

				if (result.status == 'success') {
					showToast(result.message, "success")

					location.reload(); // Reload to reflect changes
				} else {
					showToast(result.message, "error")
				}

			} else {
				showToast(result.message, "error")
			}
		} catch (error) {
			alert('File upload failed. Please try again.');
			console.error(error);
		}
	}
</script>
<?php
if ($settings['twak_live_chat_status']) {
	echo $settings['twak_live_chat_widget_code'];
}
?>