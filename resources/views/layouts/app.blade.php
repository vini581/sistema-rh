<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Telecom — RH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
        :root {
            --smart-primary: #1BBFBF;
            --smart-dark: #0e8f8f;
            --smart-light: #e8fafa;
        }
        .sidebar { width: 240px; min-height: 100vh; background: #0f172a; }
        .sidebar-logo { padding: 24px 20px; border-bottom: 1px solid #1e293b; }
        .sidebar-logo img { height: 48px; }
        .sidebar-logo p { color: #94a3b8; font-size: 11px; margin-top: 4px; letter-spacing: 1px; text-transform: uppercase; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 20px; color: #94a3b8; font-size: 14px; font-weight: 500; transition: all .2s; text-decoration: none; }
        .nav-item:hover, .nav-item.active { background: #1e293b; color: #1BBFBF; border-left: 3px solid #1BBFBF; }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .nav-section { padding: 16px 20px 6px; color: #475569; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; font-weight: 600; }
        .main-content { flex: 1; background: #f8fafc; min-height: 100vh; }
        .topbar { background: white; border-bottom: 1px solid #e2e8f0; padding: 0 32px; height: 64px; display: flex; align-items: center; justify-content: space-between; }
        .topbar-title { font-size: 18px; font-weight: 600; color: #0f172a; }
        .user-menu { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--smart-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; }
        .user-name { font-size: 14px; font-weight: 500; color: #334155; }
        .logout-btn { font-size: 13px; color: #94a3b8; text-decoration: none; padding: 6px 12px; border-radius: 6px; transition: all .2s; }
        .logout-btn:hover { background: #f1f5f9; color: #ef4444; }
        .page-content { padding: 32px; }
        .card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; }
        .card-header { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; }
        .card-header h3 { font-size: 16px; font-weight: 600; color: #0f172a; }
        .card-body { padding: 24px; }
        .stat-card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; }
        .stat-label { font-size: 13px; color: #64748b; font-weight: 500; }
        .stat-value { font-size: 32px; font-weight: 700; color: #0f172a; margin-top: 4px; }
        .btn-primary { background: var(--smart-primary); color: white; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; text-decoration: none; display: inline-block; transition: background .2s; }
        .btn-primary:hover { background: var(--smart-dark); color: white; }
        .btn-secondary { background: #f1f5f9; color: #475569; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-danger { color: #ef4444; font-size: 13px; font-weight: 500; text-decoration: none; }
        .btn-warning { color: #f59e0b; font-size: 13px; font-weight: 500; text-decoration: none; }
        .btn-info { color: #3b82f6; font-size: 13px; font-weight: 500; text-decoration: none; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #f8fafc; }
        th { padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .5px; }
        td { padding: 14px 16px; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }
        .badge-success { background: #dcfce7; color: #16a34a; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .badge-warning { background: #fef9c3; color: #ca8a04; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .alert-success { background: #dcfce7; color: #16a34a; padding: 12px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 16px; }
        .alert-error { background: #fee2e2; color: #dc2626; padding: 12px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-input { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #111827; outline: none; transition: border .2s; box-sizing: border-box; }
        .form-input:focus { border-color: var(--smart-primary); box-shadow: 0 0 0 3px rgba(27,191,191,.1); }
    </style>
</head>
<body style="display:flex; background:#f8fafc; margin:0;">

    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Smart Telecom">
            <p>Gestão de RH</p>
        </div>

        <nav style="margin-top: 16px;">
            <p class="nav-section">Principal</p>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            @can('admin')
            <p class="nav-section">Administração</p>
            <a href="{{ route('employees.index') }}" class="nav-item {{ request()->routeIs('employees.index') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Funcionários
            </a>
            <a href="{{ route('employees.create') }}" class="nav-item {{ request()->routeIs('employees.create') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Cadastrar Funcionário
            </a>
            @endcan

            @cannot('admin')
            <p class="nav-section">Minha Jornada</p>
            <a href="{{ route('work-log.index') }}" class="nav-item {{ request()->routeIs('work-log.index') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Registrar Ponto
            </a>
            <a href="{{ route('work-log.history') }}" class="nav-item {{ request()->routeIs('work-log.history') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Histórico
            </a>
            @endcannot
        </nav>

        <div style="position:absolute; bottom:0; width:240px; padding:16px 20px; border-top:1px solid #1e293b;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none; border:none; cursor:pointer; color:#94a3b8; font-size:13px; display:flex; align-items:center; gap:8px; width:100%;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sair do sistema
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="topbar-title">{{ $header ?? '' }}</div>
            <div class="user-menu">
                <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div>
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div style="font-size:12px; color:#94a3b8;">{{ Auth::user()->isAdmin() ? 'Gestor de RH' : 'Funcionário' }}</div>
                </div>
            </div>
        </div>

        <div class="page-content">
            {{ $slot }}
        </div>
    </div>

</body>
</html>