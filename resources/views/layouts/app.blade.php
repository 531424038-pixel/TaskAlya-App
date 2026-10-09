```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Daily Task'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            --color-biru-50: #eef4fe;
            --color-biru-100: #dbe8fb;
            --color-biru-200: #c7d9f8;
            --color-biru-300: #a9c6f0;
            --color-biru-400: #8b9ed8;
            --color-biru-500: #6b7fb5;
            --color-biru-600: #4f6494;
            --color-biru-700: #3b4e76;
            --color-biru-800: #2c3b59;
            --color-biru-900: #1e2a40;
            --color-biru-950: #12192c;
            --color-bg: #ffffff;
            --color-surface: #f7f9fc;
            --color-surface-2: #ffffff;
            --color-text: #1b2432;
            --color-text-secondary: #5b6c81;
            --color-border: #e2e8f0;
            --color-success: #15803d;
            --color-success-bg: #f0fdf4;
            --color-warning: #b45309;
            --color-danger: #b91c1c;
            --color-danger-bg: #fef2f2;
            --color-amber: #b45309;
            --radius: 14px;
            --radius-sm: 10px;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
        }

        body {
            font-family: var(--font-sans);
            background: var(--color-bg);
            color: var(--color-text);
        }

        .app-layout {
            display: flex !important;
            flex-direction: row !important;
            align-items: stretch;
            width: 100%;
            min-height: 100vh;
        }

        .sidebar {
            flex: 0 0 326px;
            width: 326px;
            min-width: 326px;
            height: 100vh;
            min-height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, #0f1a2e 0%, #13233f 100%);
            color: #fff;
            border-right: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 24px;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .sidebar-nav {
            display: flex;
            flex: 1;
            flex-direction: column;
            gap: 6px;
            padding: 16px 12px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border-radius: var(--radius-sm);
            color: rgba(255,255,255,.78);
            text-decoration: none;
            font-weight: 600;
            font-size: .95rem;
            transition: background .15s ease, color .15s ease;
        }

        .sidebar-nav a.active {
            background: rgba(107,127,181,.25);
            color: #fff;
        }

        .sidebar-nav a:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
        }

        main.content {
            display: block !important;
            flex: 1 1 auto !important;
            width: auto !important;
            max-width: none !important;
            min-width: 0;
            min-height: 100vh;
            margin: 0 !important;
            padding: 28px 32px;
            background: #fff;
            color: #1b2432;
            visibility: visible !important;
            opacity: 1 !important;
            overflow-wrap: anywhere;
        }

        main.content > .max-w-4xl {
            display: block !important;
            width: 100%;
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
            visibility: visible !important;
            opacity: 1 !important;
        }

        main.content h1,
        main.content h2,
        main.content h3,
        main.content p,
        main.content span,
        main.content a {
            visibility: visible;
        }

        .card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            padding: 20px;
        }

        .card-accent {
            background: var(--color-surface-2);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            padding: 20px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: .875rem;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s ease;
        }

        .btn-primary {
            background: var(--color-biru-600);
            color: #fff;
            border-color: var(--color-biru-600);
        }

        .btn-primary:hover {
            background: var(--color-biru-700);
        }

        .btn-outline {
            background: transparent;
            border-color: var(--color-border);
            color: var(--color-text-secondary);
        }

        .btn-danger {
            background: var(--color-danger-bg);
            color: var(--color-danger);
            border-color: var(--color-border);
        }

        .text-biru-600 { color: var(--color-biru-600); }
        .text-biru-700 { color: var(--color-biru-700); }
        .text-biru-800 { color: var(--color-biru-800); }
        .text-gray-500 { color: #64748b; }
        .text-gray-600 { color: #475569; }
        .text-gray-700 { color: #334155; }
        .text-emerald-600 { color: #059669; }
        .text-amber-600 { color: #d97706; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
        }

        .status-belum-selesai {
            background: #fef2f2;
            color: var(--color-danger);
        }

        .status-sedang-dikerjakan {
            background: #fffbeb;
            color: var(--color-amber);
        }

        .status-selesai {
            background: var(--color-success-bg);
            color: var(--color-success);
        }

        .priority-badge,
        .category-badge {
            display: inline-block;
            font-size: .75rem;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .priority-Tinggi {
            background: #fdecec;
            color: var(--color-danger);
        }

        .priority-Sedang {
            background: #fef3c7;
            color: var(--color-amber);
        }

        .priority-Rendah {
            background: #eef2ff;
            color: var(--color-biru-700);
        }

        .category-badge {
            background: var(--color-surface-2);
            color: var(--color-text-secondary);
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--color-text-secondary);
            font-size: .875rem;
        }

        .pagination .current {
            background: var(--color-biru-600);
            color: #fff;
        }

        @media (max-width: 768px) {
            .app-layout {
                flex-direction: column !important;
            }

            .sidebar {
                flex: 0 0 auto;
                width: 100%;
                min-width: 0;
                height: auto;
                min-height: auto;
                position: relative;
            }

            main.content {
                width: 100% !important;
                min-height: auto;
                padding: 20px 18px;
            }
        }

        @media (prefers-color-scheme: dark) {
            body {
                background: #0b101c;
                color: #e6ecf5;
            }

            main.content {
                background: #0b101c;
                color: #e6ecf5;
            }

            .card {
                background: #121b30;
                border-color: #1f2d4a;
            }

            .card-accent {
                background: #0f1829;
                border-color: #1e2c49;
            }

            .text-biru-600 { color: var(--color-biru-400); }
            .text-biru-700 { color: var(--color-biru-300); }
            .text-biru-800 { color: var(--color-biru-200); }
            .text-gray-500,
            .text-gray-600,
            .text-gray-700 { color: #a5b4c8; }
        }
    </style>
</head>
<body>
<div class="app-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span>📋</span>
            <span>Daily Task</span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span>📊</span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('tasks.index') }}"
               class="{{ request()->routeIs('tasks.index', 'tasks.create', 'tasks.show', 'tasks.edit') ? 'active' : '' }}">
                <span>📅</span>
                <span>Kegiatan</span>
            </a>

            <a href="{{ route('calendar') }}"
               class="{{ request()->routeIs('calendar') ? 'active' : '' }}">
                <span>📆</span>
                <span>Kalender</span>
            </a>

            <a href="{{ route('history') }}"
               class="{{ request()->routeIs('history') ? 'active' : '' }}">
                <span>✅</span>
                <span>Riwayat</span>
            </a>

            <a href="{{ route('settings') }}"
               class="{{ request()->routeIs('settings') ? 'active' : '' }}">
                <span>⚙️</span>
                <span>Pengaturan</span>
            </a>
        </nav>
    </aside>

    <main class="content">
        <div class="max-w-4xl mx-auto">
            @if (session('success'))
                <div style="margin-bottom:24px;padding:16px;border:1px solid #a7f3d0;background:#ecfdf5;color:#065f46;border-radius:10px;">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
```
