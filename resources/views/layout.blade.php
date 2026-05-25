<!DOCTYPE html>
<html>
<head>
    <title>ERP Dashboard</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f7fb;
            color: #111827;
        }
        .wrapper { display: flex; min-height: 100vh; }
        .sidebar {
            width: 220px;
            background: #fff;
            padding: 24px 16px;
            border-right: 1px solid #e5e7eb;
            flex-shrink: 0;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 36px;
            padding: 0 8px;
            color: #111827;
        }
        .menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            margin-bottom: 4px;
            color: #6b7280;
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
        }
        .menu a:hover { background: #f3f4f6; color: #111827; }
        .menu a.active { background: #eef2ff; color: #111827; font-weight: 600; }
        .content { flex: 1; padding: 32px; }
        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }
        .btn {
            background: #111827;
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover { background: #374151; }
        .btn-cancel {
            background: #f3f4f6;
            color: #111827;
            border: none;
            padding: 11px 18px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-cancel:hover { background: #e5e7eb; }
        .card {
            background: white;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 8px 24px rgba(0,0,0,.04);
        }
        .search {
            width: 280px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 14px;
        }
        table { width: 100%; border-collapse: collapse; }
        th {
            text-align: left;
            color: #6b7280;
            font-size: 14px;
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
        }
        td {
            padding: 16px 14px;
            border-bottom: 1px solid #f1f1f1;
            font-size: 14px;
        }
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-active    { background: #dcfce7; color: #15803d; }
        .badge-inactive  { background: #fee2e2; color: #b91c1c; }
        .badge-trial     { background: #fef9c3; color: #854d0e; }
        .badge-isolir    { background: #ffedd5; color: #c2410c; }
        .badge-dismantle { background: #f1f5f9; color: #64748b; }

        /* DROPDOWN ACTION */
        .action-wrap {
            position: relative;
            display: inline-block;
        }
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 8px;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .action-btn:hover { background: #f3f4f6; color: #111827; }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 110%;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,.1);
            min-width: 160px;
            z-index: 100;
            overflow: hidden;
        }
        .dropdown-menu.show { display: block; }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            font-size: 14px;
            color: #111827;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            text-decoration: none;
            font-family: Arial, sans-serif;
        }
        .dropdown-item:hover { background: #f9fafb; }
        .dropdown-item.danger { color: #ef4444; }
        .dropdown-item.danger:hover { background: #fef2f2; }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.4);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }
        .modal-box {
            background: white;
            width: 460px;
            padding: 32px;
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
        }
        .modal-box h3 { margin: 0 0 20px; font-size: 20px; }
        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 8px;
        }
        .form-group { margin-bottom: 14px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        input, textarea, select {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            background: #f9fafb;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #111827;
            background: #fff;
        }
        textarea { resize: vertical; min-height: 80px; }
        .date-row { display: flex; gap: 12px; }
        .date-row .form-group { flex: 1; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <div class="logo">
            <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                <rect width="28" height="28" rx="7" fill="#111827"/>
                <rect x="7" y="7" width="6" height="6" rx="1.5" fill="white"/>
                <rect x="15" y="7" width="6" height="6" rx="1.5" fill="white" opacity=".5"/>
                <rect x="7" y="15" width="6" height="6" rx="1.5" fill="white" opacity=".5"/>
                <rect x="15" y="15" width="6" height="6" rx="1.5" fill="white"/>
            </svg>
            ERP
        </div>
        <div class="menu">
            <a href="/customers-page" class="{{ request()->is('customers-page') || request()->is('/') ? 'active' : '' }}">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Customers
            </a>
            <a href="/services-page" class="{{ request()->is('services-page') ? 'active' : '' }}">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                </svg>
                Services
            </a>
            <a href="/subscriptions-page" class="{{ request()->is('subscriptions-page') ? 'active' : '' }}">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <line x1="10" y1="9" x2="8" y2="9"/>
                </svg>
                Subscription
            </a>
        </div>
    </div>
    <div class="content">
        @yield('content')
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-wrap')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(d => d.classList.remove('show'));
    }
});
function toggleDropdown(btn) {
    const menu = btn.nextElementSibling;
    const isOpen = menu.classList.contains('show');
    document.querySelectorAll('.dropdown-menu.show').forEach(d => d.classList.remove('show'));
    if (!isOpen) menu.classList.add('show');
}
</script>
</body>
</html>