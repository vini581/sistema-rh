<!DOCTYPE html>
<html lang="pt-BR" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: true }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Telecom — RH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#e6f9f9', 100: '#ccf2f2', 500: '#1bbfbf', 600: '#118b8b', 900: '#0a4d4d' },
                        slate: { 850: '#151e2e', 900: '#0f172a', 950: '#020617' }
                    }
                }
            }
        }
    </script>
    @livewireStyles

    <style>
        :root {
            --primary:     #1bbfbf;
            --on-primary:  #ffffff;
            --primary-dk:  #118b8b;
            --primary-lt:  #e6f9f9;
            --secondary:   #71717a;
            --surface:     #ffffff;
            --surface2:    #fafafa;
            --border:      #e4e4e7;
            --text:        #09090b;
            --text-muted:  #71717a;
            --danger:      #ef4444;
            --warning:     #f59e0b;
            --success:     #10b981;
        }
        .dark {
            --primary:     #1bbfbf;
            --on-primary:  #ffffff;
            --primary-dk:  #118b8b;
            --primary-lt:  #1bbfbf1a;
            --secondary:   #a1a1aa;
            --surface:     #09090b;
            --surface2:    #09090b;
            --border:      #27272a;
            --text:        #fafafa;
            --text-muted:  #a1a1aa;
            --danger:      #f87171;
            --warning:     #fbbf24;
            --success:     #34d399;
        }
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background: var(--surface2); color: var(--text); margin: 0; transition: background .3s, color .3s; }

        /* Sidebar */
        .sidebar {
            width: 260px; min-height: 100vh; background: var(--surface);
            position: fixed; left: 0; top: 0; z-index: 50;
            display: flex; flex-direction: column;
            transition: transform .3s ease, width .3s ease;
            border-right: 1px solid var(--border);
        }
        .sidebar.collapsed { width: 72px; }
        .sidebar-brand {
            padding: 24px 20px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 12px; overflow: hidden;
        }
        .brand-icon {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .brand-icon svg { width: 22px; height: 22px; color: var(--text); }
        .brand-text { overflow: hidden; white-space: nowrap; }
        .brand-text h1 { font-size: 15px; font-weight: 600; color: var(--text); margin: 0; letter-spacing: -0.5px; }
        .brand-text p  { font-size: 11px; color: var(--text-muted); margin: 2px 0 0; }

        .nav-section { padding: 24px 16px 8px; font-size: 11px; font-weight: 600;
            color: var(--text-muted); letter-spacing: 0.5px;
            white-space: nowrap; overflow: hidden; }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 16px; margin: 4px 12px; border-radius: 8px;
            color: var(--text-muted); font-size: 13.5px; font-weight: 500;
            text-decoration: none; transition: all .2s; white-space: nowrap; overflow: hidden;
            position: relative;
        }
        .nav-item:hover { background: var(--surface2); color: var(--text); }
        .nav-item.active { background: var(--primary-lt); color: var(--text); font-weight: 600; }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .nav-badge {
            margin-left: auto; background: var(--primary); color: var(--on-primary);
            font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 99px;
        }
        .nav-item.active .nav-badge { opacity: 0.9; }

        .sidebar-footer {
            margin-top: auto; padding: 16px; border-top: 1px solid var(--border);
        }
        .user-card {
            display: flex; align-items: center; gap: 10px; padding: 10px 12px;
            border-radius: 10px; background: var(--surface2);
            overflow: hidden; cursor: pointer; transition: background .2s;
        }
        .user-card:hover { background: var(--border); }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--primary); display: flex; align-items: center;
            justify-content: center; color: var(--on-primary); font-weight: 700; font-size: 14px;
            flex-shrink: 0;
        }
        .user-info { overflow: hidden; flex: 1; }
        .user-name { font-size: 13px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 11px; color: var(--text-muted); white-space: nowrap; }

        /* Main */
        .main-wrap { margin-left: 260px; min-height: 100vh; transition: margin .3s; }
        .main-wrap.expanded { margin-left: 72px; }

        /* Topbar */
        .topbar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0 28px; height: 64px; display: flex; align-items: center;
            justify-content: space-between; position: sticky; top: 0; z-index: 40;
        }
        .dark .topbar { background: rgba(9, 9, 11, 0.7); }
        .topbar-left { display: flex; align-items: center; gap: 16px; }
        .topbar-title { font-size: 17px; font-weight: 600; color: var(--text); }
        .topbar-right { display: flex; align-items: center; gap: 8px; }
        .topbar-btn {
            width: 38px; height: 38px; border-radius: 10px; border: 1px solid var(--border);
            background: var(--surface); color: var(--text-muted); display: flex;
            align-items: center; justify-content: center; cursor: pointer;
            transition: all .2s; position: relative;
        }
        .topbar-btn:hover { background: var(--surface2); color: var(--text); border-color: var(--primary); }
        .topbar-btn svg { width: 18px; height: 18px; }
        .notif-dot {
            position: absolute; top: 7px; right: 7px; width: 7px; height: 7px;
            background: var(--danger); border-radius: 50%; border: 2px solid var(--surface);
        }
        .toggle-btn {
            width: 38px; height: 38px; border-radius: 10px; border: none;
            background: transparent; color: var(--text-muted); display: flex;
            align-items: center; justify-content: center; cursor: pointer; transition: all .2s;
        }
        .toggle-btn:hover { color: var(--primary); }
        .toggle-btn svg { width: 20px; height: 20px; }

        /* Page content */
        .page { padding: 28px; }

        /* Cards */
        .card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        .card-header {
            padding: 18px 22px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title { font-size: 15px; font-weight: 600; color: var(--text); }
        .card-body { padding: 22px; }

        /* Stat cards */
        .stat-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; padding: 22px; position: relative; overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .stat-icon {
            width: 44px; height: 44px; border-radius: 12px; display: flex;
            align-items: center; justify-content: center; margin-bottom: 16px;
        }
        .stat-icon svg { width: 22px; height: 22px; }
        .stat-value { font-size: 30px; font-weight: 700; color: var(--text); line-height: 1; margin-bottom: 4px; }
        .stat-label { font-size: 13px; color: var(--text-muted); font-weight: 400; }
        .stat-trend { font-size: 12px; font-weight: 500; margin-top: 8px; display: flex; align-items: center; gap: 4px; }
        .stat-trend.up { color: var(--success); }
        .stat-trend.down { color: var(--danger); }
        .stat-glow {
            position: absolute; right: -20px; top: -20px; width: 100px; height: 100px;
            border-radius: 50%; opacity: .06;
        }

        /* Table */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: var(--surface2); }
        th {
            padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 600;
            color: var(--text-muted); text-transform: uppercase; letter-spacing: .7px;
            border-bottom: 1px solid var(--border); white-space: nowrap;
        }
        td {
            padding: 14px 16px; font-size: 13.5px; color: var(--text);
            border-bottom: 1px solid var(--border);
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:nth-child(even) td { background: rgba(0,0,0,.015); }
        .dark tbody tr:nth-child(even) td { background: rgba(255,255,255,.02); }
        tbody tr:hover td { background: var(--primary-lt); }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 99px; font-size: 11.5px; font-weight: 600;
        }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .dark .badge-success { background: #064e3b; color: #6ee7b7; }
        .dark .badge-warning { background: #78350f; color: #fde68a; }
        .dark .badge-danger  { background: #7f1d1d; color: #fca5a5; }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px;
            border-radius: 10px; font-size: 13.5px; font-weight: 600; border: none;
            cursor: pointer; text-decoration: none; transition: all .2s; white-space: nowrap;
        }
        .btn svg { width: 16px; height: 16px; }
        .btn-primary { background: var(--primary); color: var(--on-primary); }
        .btn-primary:hover { background: var(--primary-dk); transform: translateY(-1px); }
        .btn-secondary { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { border-color: var(--primary); color: var(--primary); }
        .btn-danger { background: #fee2e2; color: var(--danger); }
        .btn-danger:hover { background: var(--danger); color: white; }
        .btn-ghost { background: transparent; color: var(--text-muted); padding: 7px 10px; }
        .btn-ghost:hover { color: var(--primary); background: var(--primary-lt); }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 8px; }

        /* Form */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 7px; }
        .form-input {
            width: 100%; padding: 10px 14px; border: 1.5px solid var(--border);
            border-radius: 10px; font-size: 14px; color: var(--text);
            background: var(--surface); outline: none; transition: border .2s, box-shadow .2s;
            font-family: 'Inter', sans-serif;
        }
        .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(27,191,191,.12); }
        .form-input::placeholder { color: var(--text-muted); }
        select.form-input { cursor: pointer; }
        .form-hint { font-size: 12px; color: var(--text-muted); margin-top: 5px; }
        .form-error { font-size: 12px; color: var(--danger); margin-top: 5px; font-weight: 500; }
        .input-group { position: relative; }
        .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
        .input-icon svg { width: 16px; height: 16px; }
        .input-group .form-input { padding-left: 38px; }

        /* Alerts */
        .alert { padding: 13px 16px; border-radius: 10px; font-size: 13.5px; font-weight: 500; margin-bottom: 18px; display: flex; align-items: center; gap: 10px; }
        .alert svg { width: 18px; height: 18px; flex-shrink: 0; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* Pagination */
        .pagination { display: flex; align-items: center; gap: 4px; }
        .page-btn {
            width: 34px; height: 34px; border-radius: 8px; border: 1px solid var(--border);
            background: var(--surface); color: var(--text-muted); display: flex;
            align-items: center; justify-content: center; font-size: 13px; font-weight: 500;
            cursor: pointer; text-decoration: none; transition: all .2s;
        }
        .page-btn:hover, .page-btn.active { background: var(--primary); color: white; border-color: var(--primary); }

        /* Skeleton */
        @keyframes shimmer { 0%{background-position:-200% 0} 100%{background-position:200% 0} }
        .skeleton {
            background: linear-gradient(90deg, var(--border) 25%, var(--surface2) 50%, var(--border) 75%);
            background-size: 200% 100%; animation: shimmer 1.5s infinite;
            border-radius: 6px;
        }

        /* Animations */
        @keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
        .fade-up { animation: fadeUp .35s ease forwards; }
        .fade-up-1 { animation-delay: .05s; opacity: 0; }
        .fade-up-2 { animation-delay: .1s;  opacity: 0; }
        .fade-up-3 { animation-delay: .15s; opacity: 0; }
        .fade-up-4 { animation-delay: .2s;  opacity: 0; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-wrap { margin-left: 0 !important; }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }
    </style>
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar" :class="{ 'collapsed': !sidebarOpen }" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon" style="background: transparent;">
            <img src="{{ asset('images/logo.png') }}" alt="Smart" style="width: 100%; height: 100%; object-fit: contain; transform: scale(2.8);">
        </div>
        <div class="brand-text" x-show="sidebarOpen" x-transition>
            <h1>Smart Telecom</h1>
            <p>Gestão de RH</p>
        </div>
    </div>

    <nav style="flex:1; overflow-y:auto; padding: 8px 0;">
        <div class="nav-section" x-show="sidebarOpen">Principal</div>

        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
            <span x-show="sidebarOpen" x-transition>Início</span>
        </a>

        @can('admin')
        <div class="nav-section" x-show="sidebarOpen">Administração</div>

        <a href="{{ route('employees.index') }}" class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span x-show="sidebarOpen" x-transition>Funcionários</span>
        </a>

        <a href="{{ route('payroll.index') }}" class="nav-item {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span x-show="sidebarOpen" x-transition>Folha de Pagamento</span>
        </a>

        <a href="{{ route('certificates.index') }}" class="nav-item {{ request()->routeIs('certificates.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span x-show="sidebarOpen" x-transition>Atestados</span>
        </a>

        <a href="{{ route('holidays.index') }}" class="nav-item {{ request()->routeIs('holidays.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span x-show="sidebarOpen" x-transition>Feriados</span>
        </a>

        <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span x-show="sidebarOpen" x-transition>Relatórios</span>
        </a>

        <a href="{{ route('hr-config.index') }}" class="nav-item {{ request()->routeIs('hr-config.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span x-show="sidebarOpen" x-transition>Configurações RH</span>
        </a>
        @endcan

        @can('employee')
        <div class="nav-section" x-show="sidebarOpen">Meu Painel</div>

        <a href="{{ route('employee.dashboard') }}" class="nav-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            <span x-show="sidebarOpen" x-transition>Previsão Salarial</span>
        </a>

        <a href="{{ route('work-log.index') }}" class="nav-item {{ request()->routeIs('work-log.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span x-show="sidebarOpen" x-transition>Registrar Ponto</span>
        </a>

        <a href="{{ route('employee.payroll.index') }}" class="nav-item {{ request()->routeIs('employee.payroll.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span x-show="sidebarOpen" x-transition>Meus Contracheques</span>
        </a>

        <a href="{{ route('employee.vacations.index') }}" class="nav-item {{ request()->routeIs('employee.vacations.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m18 4v-4M10 21V7a2 2 0 012-2h4a2 2 0 012 2v14M5 21h14M5 21V7a2 2 0 012-2h3M5 21h3"/></svg>
            <span x-show="sidebarOpen" x-transition>Férias</span>
        </a>

        <a href="{{ route('employee.certificates.index') }}" class="nav-item {{ request()->routeIs('employee.certificates.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span x-show="sidebarOpen" x-transition>Meus Atestados</span>
        </a>

        <a href="{{ route('work-log.history') }}" class="nav-item {{ request()->routeIs('work-log.history') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span x-show="sidebarOpen" x-transition>Histórico</span>
        </a>
        @endcan
    </nav>

    <div class="sidebar-footer">
        {{-- Link para perfil (sidebar aberto) --}}
        <a href="{{ route('profile.edit') }}" class="user-card" x-show="sidebarOpen" x-transition style="text-decoration:none;" title="Meu Perfil">
            <div class="user-avatar" style="position:relative; overflow:hidden;">
                <img src="{{ Auth::user()->avatar_url }}" style="width:100%; height:100%; border-radius:10px; object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=1bbfbf&background=e6f9f9&size=96&bold=true'">
            </div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->isAdmin() ? 'Gestor de RH' : 'Funcionário' }}</div>
            </div>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;flex-shrink:0;color:var(--text-muted);opacity:0.6;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- Link para perfil (sidebar colapsado) --}}
        <a href="{{ route('profile.edit') }}" x-show="!sidebarOpen" style="display:flex; justify-content:center; text-decoration:none;" title="Meu Perfil">
            <div class="user-avatar" style="position:relative; overflow:hidden;">
                <img src="{{ Auth::user()->avatar_url }}" style="width:100%; height:100%; border-radius:10px; object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=1bbfbf&background=e6f9f9&size=96&bold=true'">
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}" style="margin-top:8px;">
            @csrf
            <button type="submit" class="nav-item" style="width:100%; border:none; cursor:pointer; background:transparent; text-align:left;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span x-show="sidebarOpen" x-transition style="font-size:13px;">Sair do sistema</span>
            </button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div class="main-wrap" :class="{ 'expanded': !sidebarOpen }">

    {{-- Topbar --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="toggle-btn" @click="sidebarOpen = !sidebarOpen">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <span class="topbar-title">{{ $header ?? '' }}</span>
        </div>
        <div class="topbar-right">
            {{-- Dark mode --}}
            <button class="topbar-btn" @click="darkMode = !darkMode" :title="darkMode ? 'Modo claro' : 'Modo escuro'">
                <svg x-show="!darkMode" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg x-show="darkMode" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>
            {{-- Notifications --}}
            <livewire:notification-bell />
        </div>
    </header>

    {{-- Page --}}
    <main class="page">
        @if(session('success'))
        <div class="alert alert-success fade-up">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger fade-up">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if(session('warning'))
        <div class="alert fade-up" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            {{ session('warning') }}
        </div>
        @endif
        {{ $slot }}
    </main>
</div>

@livewireScripts
<livewire:command-palette />
</body>
</html>