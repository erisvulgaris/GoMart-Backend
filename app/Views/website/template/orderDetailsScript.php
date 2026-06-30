<script>
    const cancelOrderModal = document.getElementById('cancelOrderModal');

    function openCancelOrderPopup() {
        if (!cancelOrderModal) return;
        cancelOrderModal.classList.remove('hidden');
        document.body.classList.add('modal-open');
    }

    function closeCancelOrderPopup() {
        if (!cancelOrderModal) return;
        cancelOrderModal.classList.add('hidden');
        document.body.classList.remove('modal-open');
    }

    const cancelOrderForm = document.querySelector('form.cancelOrderForm');

    if (cancelOrderForm) {
        cancelOrderForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const note = document.getElementById('note').value.trim();

            try {
                const response = await fetch('/cancelOrder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        note,
                        order_id: <?= $order['id'] ?>
                    }),
                });

                const result = await response.json();

                // Handle success or error response
                if (result.status === 'success') {
                    event.target.reset();
                    closeCancelOrderPopup()

                    document.getElementById('orderTrackingDiv').classList.add('hidden')
                    document.getElementById('openCancelOrderPopup').classList.add('hidden')
                    showToast(result.message, "success");

                } else {
                    showToast(result.message, "danger");
                }
            } catch (error) {

            }
        })
    }

    const returningItemModal = document.getElementById('returningItemModal');

    function openReturningItemPopup(order_id, order_product_id) {
        returningItemModal.classList.remove('hidden');
        document.body.classList.add('modal-open');

        document.getElementById('ri_order_id').value = order_id
        document.getElementById('ri_order_product_id').value = order_product_id
    }

    function closeReturningItemPopup() {
        returningItemModal.classList.add('hidden');
        document.body.classList.remove('modal-open');
    }

    const returningItemForm = document.querySelector('form.returningItemForm');
    if (returningItemForm) returningItemForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const note = document.getElementById('note').value.trim();
        const order_id = document.getElementById('ri_order_id').value.trim();
        const order_product_id = document.getElementById('ri_order_product_id').value.trim();

        try {
            const response = await fetch('/returningItemRequest', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    note,
                    order_id,
                    order_product_id
                }),
            });

            const result = await response.json();

            // Handle success or error response
            if (result.status === 'success') {
                event.target.reset();
                closeReturningItemPopup()

                document.getElementById('returningItem_' + order_id + '_' + order_product_id).innerHTML = '<span class="font-medium text-yellow-800 bg-yellow-200 px-2 py-1 rounded text-xs">Pending</span>';

                showToast(result.message, "success");

            } else {
                showToast(result.message, "danger");
            }
        } catch (error) {

        }

    });

    // ── Live Delivery Tracking ──
    (function () {
        const trackingCard = document.getElementById('liveTrackingCard');
        if (!trackingCard) return; // only runs on status-4 orders

        const orderId = <?= $order['id'] ?>;
        const mapsApiKey = '<?= esc($settings['map_api_key'] ?? '') ?>';
        const badge = document.getElementById('trackingStatusBadge');

        let map = null;
        let marker = null;
        let googleLoaded = false;
        let pollInterval = null;

        function initMap(lat, lng) {
            if (map) return;
            const pos = { lat, lng };
            map = new google.maps.Map(document.getElementById('deliveryMap'), {
                center: pos,
                zoom: 15,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
            });
            marker = new google.maps.Marker({
                position: pos,
                map,
                title: 'Delivery Boy',
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                },
            });
        }

        function updateMarker(lat, lng) {
            const pos = { lat, lng };
            if (!map) {
                initMap(lat, lng);
            } else {
                marker.setPosition(pos);
                map.panTo(pos);
            }
        }

        async function fetchTracking() {
            try {
                const res = await fetch('/fetchLiveDeliveryTracking', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_id: orderId }),
                });
                const data = await res.json();

                if (data.status === 'success') {
                    const t = data.liveTracking;
                    const lat = parseFloat(t.latitude);
                    const lng = parseFloat(t.longitude);
                    document.getElementById('mapPlaceholder')?.remove();
                    badge.textContent = 'Live';
                    badge.className = 'text-xs font-medium px-2.5 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
                    updateMarker(lat, lng);
                } else {
                    badge.textContent = 'Not started';
                    badge.className = 'text-xs font-medium px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
                }
            } catch (e) {
                // silent fail – keep polling
            }
        }

        function loadGoogleMaps() {
            if (googleLoaded || !mapsApiKey) return;
            googleLoaded = true;
            const script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key=' + mapsApiKey;
            script.async = true;
            script.defer = true;
            script.onload = function () {
                fetchTracking();
                pollInterval = setInterval(fetchTracking, 10000);
            };
            document.head.appendChild(script);
        }

        if (mapsApiKey) {
            loadGoogleMaps();
        } else {
            badge.textContent = 'Map key missing';
            badge.className = 'text-xs font-medium px-2.5 py-1 rounded-full bg-red-100 text-red-700';
            // Still poll & show coordinates as text fallback
            async function fetchTrackingNoMap() {
                try {
                    const res = await fetch('/fetchLiveDeliveryTracking', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ order_id: orderId }),
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        const t = data.liveTracking;
                        const ph = document.getElementById('mapPlaceholder');
                        if (ph) ph.innerHTML = `<i class="fi fi-rr-map-marker text-3xl leading-none text-blue-400"></i><p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Lat: ${t.latitude}, Lng: ${t.longitude}</p>`;
                        badge.textContent = 'Live';
                        badge.className = 'text-xs font-medium px-2.5 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
                    }
                } catch (e) {}
            }
            fetchTrackingNoMap();
            setInterval(fetchTrackingNoMap, 10000);
        }
    })();

    async function downloadInvoice(order_id, buttonElement) {
        try {
            // Disable the button and change text
            const originalContent = buttonElement.innerHTML;
            buttonElement.innerHTML = `
            <i class="fi fi-rr-cloud-download-alt"></i>
            <span class="text-sm font-medium capitalize whitespace-nowrap">Downloading...</span>
        `;
            buttonElement.disabled = true;

            // Fetch the PDF file
            const response = await fetch('/downloadInvoice', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    order_id
                }),
            });

            if (!response.ok) {
                throw new Error('Failed to download invoice.');
            }

            // Convert response to a blob
            const blob = await response.blob();

            // Create a download link for the PDF
            const link = document.createElement('a');
            const url = window.URL.createObjectURL(blob);
            link.href = url;
            link.download = `order_invoice_${order_id}.pdf`;
            document.body.appendChild(link);
            link.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(link);


            buttonElement.disabled = false;
            buttonElement.innerHTML = originalContent;

        } catch (error) {
            alert('An error occurred while downloading the invoice. Please try again.');
            console.error(error);
        }
    }
</script>