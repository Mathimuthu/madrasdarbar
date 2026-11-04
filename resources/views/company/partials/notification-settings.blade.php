{{-- Notification settings card (admin settings + staff profile). Talks to window.orderAlerts from the bell partial. --}}
<div class="ns-card">
    <div class="ns-row">
        <div>
            <h3>New Order Notifications</h3>
            <span class="ns-text">Popup, sound and desktop alert when a new order comes in, even while you are working in another tab.</span>
        </div>
        <input type="checkbox" class="on-switch" id="nsToggle" @checked(Auth::user()->order_notifications ?? true) aria-label="New order notifications">
    </div>
    <div class="ns-status" id="nsStatus"></div>
</div>

<style>
    .ns-card { background: #fff; color: #1f2937; border-radius: 10px; padding: 18px 20px; margin: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
    .ns-row { display: flex; justify-content: space-between; align-items: center; gap: 16px; }
    .ns-card h3 { margin: 0 0 4px; font-size: 17px; }
    .ns-text { color: #6b7280; font-size: 13px; }
    .ns-status { margin-top: 12px; font-size: 13px; padding: 8px 12px; border-radius: 6px; }
    .ns-status:empty { display: none; }
    .ns-status.ns-ok { background: #ecfdf5; color: #047857; }
    .ns-status.ns-warn { background: #fff7ed; color: #9a3412; }
    .ns-status.ns-off { background: #f3f4f6; color: #4b5563; }
    .ns-status button { margin-left: 8px; border: 0; border-radius: 5px; padding: 4px 10px; background: #10b981; color: #fff; font-weight: 600; cursor: pointer; }
</style>

<script>
    (function () {
        const toggle = document.getElementById('nsToggle');
        const status = document.getElementById('nsStatus');

        function render() {
            if (!window.orderAlerts) return;
            const on = window.orderAlerts.isEnabled();
            toggle.checked = on;
            status.className = 'ns-status';

            if (!on) {
                status.classList.add('ns-off');
                status.textContent = 'Notifications are off. New orders still show in the bell, without popup or sound.';
                return;
            }
            if (!('Notification' in window)) {
                status.classList.add('ns-warn');
                status.textContent = 'This browser does not support desktop notifications. You will get alerts only while this tab is open in front.';
                return;
            }
            if (Notification.permission === 'granted') {
                status.classList.add('ns-ok');
                status.textContent = 'On. You will be alerted on this browser even when using another tab.';
            } else if (Notification.permission === 'denied') {
                status.classList.add('ns-warn');
                status.textContent = 'Your browser is blocking notifications. Click the lock icon next to the website address, set Notifications to "Allow", then reload the page.';
            } else {
                status.classList.add('ns-warn');
                status.textContent = 'One more step: allow notifications in this browser.';
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = 'Allow';
                btn.addEventListener('click', function () { window.orderAlerts.requestPermission(); });
                status.appendChild(btn);
            }
        }

        toggle.addEventListener('change', function () {
            if (!window.orderAlerts) return;
            toggle.disabled = true;
            window.orderAlerts.setEnabled(toggle.checked).catch(function () {}).finally(function () { toggle.disabled = false; });
        });
        document.addEventListener('order-alerts-changed', render);
        window.addEventListener('load', render);
        render();
    })();
</script>
