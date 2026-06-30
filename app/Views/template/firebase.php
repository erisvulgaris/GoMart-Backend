<script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-messaging.js"></script>
<!-- FCM Order Notification Modal -->
<div class="modal fade" id="fcmModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden; border: none;">

            <div style="height: 4px; background: linear-gradient(90deg, #fd7e14, #ffc107);"></div>

            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center">
                    <span class="badge badge-warning p-2 mr-2" style="border-radius:8px; font-size:18px;">
                        <i class="fas fa-shopping-bag"></i>
                    </span>
                    <h5 id="fcm-title" class="modal-title font-weight-bold text-dark mb-0"></h5>
                </div>
                <button type="button" class="close" onclick="closeFcmPopup()">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body pt-2">
                <p id="fcm-body" class="text-muted mb-0" style="font-size:14px;"></p>
            </div>

            <div class="modal-footer border-0 pt-0">
                <a href="/admin/orders" class="btn btn-warning text-white font-weight-bold">
                    <i class="fas fa-eye mr-1"></i> View Order
                </a>
                <button type="button" class="btn btn-secondary" onclick="closeFcmPopup()">
                    Dismiss
                </button>
            </div>

        </div>
    </div>
</div>
<script>
$(document).ready(function () {

    var firebaseConfig = <?php echo $settings['fcm_credentials']; ?>;
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    Notification.requestPermission().then((permission) => {
        if (permission === 'granted') return messaging.getToken();
        else console.warn('Notification permission denied');
    }).then((token) => {
        if (!token) return;
        $.ajax({
            url: "/admin/notification/token/update",
            type: "POST",
            data: { token },
            dataType: "json",
            success: function(response) {},
        });
    }).catch((err) => {
        console.log('Error getting token:', err);
    });

    messaging.onMessage((payload) => {
        console.log('Message received.', payload);

        showFcmPopup(payload.notification.title, payload.notification.body);

        if (Notification.permission === 'granted' && navigator.serviceWorker) {
            navigator.serviceWorker.ready.then(function(registration) {
                registration.showNotification(payload.notification.title, {
                    body: payload.notification.body,
                    icon: payload.notification.icon || '/favicon.ico',
                });
            });
        }
    });

    function showFcmPopup(title, body) {
        document.getElementById('fcm-title').textContent = title;
        document.getElementById('fcm-body').textContent = body;
        $('#fcmModal').modal('show'); 
    }

    function closeFcmPopup() {
        $('#fcmModal').modal('hide');
    }

    window.closeFcmPopup = closeFcmPopup;

});
</script>