{{-- New order notification bell for admin and staff. Polls for new orders, no page refresh needed. --}}
<div class="on-bell-wrap" id="onBellWrap">
    <button type="button" class="on-bell" id="onBell" title="New orders">
        <i class='bx bxs-bell'></i>
        <span class="on-badge" id="onBadge" hidden>0</span>
    </button>
    <div class="on-dropdown" id="onDropdown" hidden>
        <div class="on-dropdown-head">
            <span>New Orders</span>
            <span class="on-head-links">
                <a href="#" id="onMarkAll">Mark all as read</a>
                <a href="#" id="onViewAll">View all</a>
            </span>
        </div>
        <div id="onList" class="on-list">
            <div class="on-empty">No new orders</div>
        </div>
    </div>
</div>

<div class="on-toasts" id="onToasts"></div>

<style>
    .on-bell-wrap { position: relative; display: inline-flex; align-items: center; margin-right: 14px; }
    .on-bell { position: relative; background: none; border: 0; cursor: pointer; font-size: 26px; line-height: 1; color: inherit; padding: 4px; }
    .on-bell.on-ring i { animation: on-ring 0.8s ease-in-out 3; transform-origin: top center; }
    .on-badge { position: absolute; top: -2px; right: -4px; min-width: 18px; height: 18px; padding: 0 5px; border-radius: 9px; background: #e11d48; color: #fff; font-size: 11px; font-weight: 700; line-height: 18px; text-align: center; }
    .on-dropdown { position: absolute; top: 40px; right: 0; width: 320px; max-width: calc(100vw - 32px); background: #fff; color: #1f2937; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,.18); z-index: 9999; overflow: hidden; font-size: 14px; }
    .on-dropdown-head { display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; border-bottom: 1px solid #eee; font-weight: 600; }
    .on-head-links { display: flex; gap: 12px; }
    .on-dropdown-head a { color: #047857; font-weight: 500; font-size: 13px; text-decoration: none; }
    .on-dropdown-head a:hover { text-decoration: underline; }
    .on-list { max-height: 360px; overflow-y: auto; }
    .on-item { position: relative; display: block; padding: 10px 14px 10px 28px; border-bottom: 1px solid #f3f4f6; color: inherit; text-decoration: none; }
    .on-item:hover { background: #f9fafb; }
    .on-item-top { display: flex; justify-content: space-between; font-weight: 500; color: #4b5563; }
    /* Unread: mint background, green dot, bold code */
    .on-item.on-unread { background: #f0fdf4; }
    .on-item.on-unread:hover { background: #dcfce7; }
    .on-item.on-unread .on-item-top { font-weight: 700; color: #111827; }
    .on-item.on-unread::before { content: ''; position: absolute; left: 12px; top: 16px; width: 8px; height: 8px; border-radius: 50%; background: #10b981; }
    .on-item-sub { color: #6b7280; font-size: 12px; margin-top: 2px; }
    .on-empty { padding: 20px 14px; color: #6b7280; text-align: center; }
    .on-toasts { position: fixed; top: 70px; right: 16px; z-index: 10000; display: flex; flex-direction: column; gap: 10px; max-width: calc(100vw - 32px); }
    .on-toast { width: 320px; max-width: 100%; background: #fff; color: #1f2937; border-left: 5px solid #16a34a; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,.2); padding: 12px 14px; font-size: 14px; cursor: pointer; animation: on-slide .3s ease-out; }
    .on-toast strong { display: block; margin-bottom: 2px; }
    .on-toast span { color: #4b5563; font-size: 13px; }
    /* Row highlighted after clicking a notification (?highlight=<order id>) */
    tr[data-order-id] > td { transition: background-color .8s ease, box-shadow .8s ease; }
    tr.on-highlight > td { background-color: #ecfdf5 !important; animation: on-flash 1.2s ease-in-out 2; }
    tr.on-highlight > td:first-child { box-shadow: inset 4px 0 0 #10b981; }
    @keyframes on-flash { 50% { background-color: #d1fae5; } }
    @keyframes on-slide { from { transform: translateX(110%); opacity: 0; } to { transform: none; opacity: 1; } }
    @keyframes on-ring { 0%,100% { transform: rotate(0); } 25% { transform: rotate(18deg); } 75% { transform: rotate(-18deg); } }
</style>

<script>
    (function () {
        const POLL_MS = 10000;
        const url = @json(route('order-notifications'));
        const storeKey = 'order-notified-' + @json(Auth::id());
        const readKey = 'order-read-' + @json(Auth::id());
        const HIGHLIGHT_MS = 5000;

        const bell = document.getElementById('onBell');
        const badge = document.getElementById('onBadge');
        const dropdown = document.getElementById('onDropdown');
        const list = document.getElementById('onList');
        const toasts = document.getElementById('onToasts');
        const viewAll = document.getElementById('onViewAll');
        const markAll = document.getElementById('onMarkAll');

        let ordersUrl = '#';
        let audioCtx = null;
        let lastData = null;
        let highlightTimer = null;

        // Id lists kept across page loads: orders already alerted about, and orders already opened (read)
        function getIds(key) {
            try { return JSON.parse(localStorage.getItem(key)) || []; } catch (e) { return []; }
        }
        function saveIds(key, ids) {
            try { localStorage.setItem(key, JSON.stringify(ids.slice(-200))); } catch (e) {}
        }
        function getNotified() { return getIds(storeKey); }
        function saveNotified(ids) { saveIds(storeKey, ids); }

        function markRead(ids) {
            const read = getIds(readKey);
            saveIds(readKey, read.concat(ids.filter(function (id) { return read.indexOf(id) === -1; })));
            if (lastData) render(lastData, true);
        }

        // Browsers only allow sound after the user has clicked the page once
        document.addEventListener('click', function () {
            if (!audioCtx) {
                try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch (e) {}
            }
        });

        function playSound() {
            if (!audioCtx) return;
            [0, 0.25].forEach(function (offset) {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.frequency.value = 880;
                gain.gain.setValueAtTime(0.2, audioCtx.currentTime + offset);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + offset + 0.2);
                osc.connect(gain).connect(audioCtx.destination);
                osc.start(audioCtx.currentTime + offset);
                osc.stop(audioCtx.currentTime + offset + 0.2);
            });
        }

        // Scroll to the order's row in the table on this page and highlight it
        function highlightOrderRow(orderId) {
            const row = document.querySelector('tr[data-order-id="' + orderId + '"]');
            if (!row) return false;
            document.querySelectorAll('tr.on-highlight').forEach(function (r) { r.classList.remove('on-highlight'); });
            row.style.display = '';
            row.classList.add('on-highlight');
            row.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Fade the highlight out after a few seconds
            clearTimeout(highlightTimer);
            highlightTimer = setTimeout(function () { row.classList.remove('on-highlight'); }, HIGHLIGHT_MS);
            return true;
        }
        window.highlightOrderRow = highlightOrderRow;

        // Highlight in place when the order is already in this page's table, otherwise open its page
        function openOrder(order) {
            dropdown.hidden = true;
            markRead([order.id]);
            const target = new URL(order.url, window.location.origin);
            if (target.pathname === window.location.pathname && highlightOrderRow(order.id)) {
                return;
            }
            window.location.href = order.url;
        }

        function orderLine(order) {
            return order.customer + ' · ' + order.currency + ' ' + order.total + ' · ' + order.location;
        }

        function showToast(order) {
            const toast = document.createElement('div');
            toast.className = 'on-toast';
            const title = document.createElement('strong');
            title.textContent = 'New order ' + (order.order_code || '');
            const body = document.createElement('span');
            body.textContent = orderLine(order);
            toast.append(title, body);
            toast.addEventListener('click', function () { toast.remove(); openOrder(order); });
            toasts.appendChild(toast);
            setTimeout(function () { toast.remove(); }, 8000);
        }

        function showDesktopNotification(order) {
            if (!('Notification' in window) || Notification.permission !== 'granted' || !document.hidden) return;
            const n = new Notification('New order ' + (order.order_code || ''), { body: orderLine(order) });
            n.onclick = function () { window.focus(); openOrder(order); };
        }

        // redraw = true only refreshes read/unread state, without alerting again
        function render(data, redraw) {
            lastData = data;
            ordersUrl = data.orders_url;
            viewAll.href = ordersUrl;

            // Badge counts the new orders this user hasn't opened yet
            const read = getIds(readKey);
            const unread = data.orders.filter(function (o) { return read.indexOf(o.id) === -1; });
            badge.hidden = unread.length === 0;
            badge.textContent = unread.length > 99 ? '99+' : unread.length;
            markAll.hidden = unread.length === 0;

            const notified = getNotified();
            const fresh = redraw ? [] : data.orders.filter(function (o) { return notified.indexOf(o.id) === -1; });

            list.innerHTML = '';
            if (data.orders.length === 0) {
                list.innerHTML = '<div class="on-empty">No new orders</div>';
            }
            data.orders.forEach(function (order) {
                const item = document.createElement('a');
                item.href = order.url;
                item.addEventListener('click', function (e) { e.preventDefault(); openOrder(order); });
                item.className = 'on-item' + (unread.indexOf(order) !== -1 ? ' on-unread' : '');
                item.title = unread.indexOf(order) !== -1 ? 'Unread · click to open' : 'Read';
                const top = document.createElement('div');
                top.className = 'on-item-top';
                const code = document.createElement('span');
                code.textContent = order.order_code || 'Order';
                const time = document.createElement('span');
                time.className = 'on-item-sub';
                time.textContent = order.created_human;
                top.append(code, time);
                const sub = document.createElement('div');
                sub.className = 'on-item-sub';
                sub.textContent = orderLine(order) + (order.delivery_type ? ' · ' + order.delivery_type : '');
                item.append(top, sub);
                list.appendChild(item);
            });

            if (fresh.length > 0) {
                // Oldest first so the newest toast ends up at the bottom
                fresh.slice().reverse().forEach(function (order) {
                    showToast(order);
                    showDesktopNotification(order);
                });
                playSound();
                bell.classList.remove('on-ring');
                void bell.offsetWidth;
                bell.classList.add('on-ring');
                saveNotified(notified.concat(fresh.map(function (o) { return o.id; })));
            }
        }

        function poll() {
            fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                .then(function (res) { return res.ok ? res.json() : null; })
                .then(function (data) { if (data) render(data); })
                .catch(function () {});
        }

        bell.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.hidden = !dropdown.hidden;
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        });
        markAll.addEventListener('click', function (e) {
            e.preventDefault();
            if (lastData) markRead(lastData.orders.map(function (o) { return o.id; }));
        });
        document.addEventListener('click', function (e) {
            if (!document.getElementById('onBellWrap').contains(e.target)) dropdown.hidden = true;
        });
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) poll();
        });

        // Opened from a notification link: highlight that order once the table is on screen
        const highlightId = new URLSearchParams(window.location.search).get('highlight');
        if (highlightId) {
            markRead([highlightId]);
            window.addEventListener('load', function () {
                highlightOrderRow(highlightId);
                // Drop ?highlight= so a refresh doesn't highlight the row again
                const clean = new URL(window.location.href);
                clean.searchParams.delete('highlight');
                history.replaceState(null, '', clean.pathname + clean.search);
            });
        }

        poll();
        setInterval(poll, POLL_MS);
    })();
</script>
