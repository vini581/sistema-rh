<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Telecom — Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { background: white; border-radius: 16px; padding: 40px; width: 100%; max-width: 420px; box-shadow: 0 25px 50px rgba(0,0,0,0.4); }
        .login-title { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
        .login-subtitle { font-size: 14px; color: #64748b; margin-bottom: 28px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-input { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 11px 14px; font-size: 14px; color: #111827; outline: none; transition: border .2s; }
        .form-input:focus { border-color: #1BBFBF; box-shadow: 0 0 0 3px rgba(27,191,191,.15); }
        .btn-login { width: 100%; background: #1BBFBF; color: white; padding: 12px; border-radius: 8px; font-size: 15px; font-weight: 600; border: none; cursor: pointer; transition: background .2s; margin-top: 8px; }
        .btn-login:hover { background: #0e8f8f; }
        .alert-error { background: #fee2e2; color: #dc2626; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
        .forgot-link { font-size: 13px; color: #1BBFBF; text-decoration: none; }
        .forgot-link:hover { text-decoration: underline; }
        .divider { border: none; border-top: 1px solid #f1f5f9; margin: 24px 0; }
        .footer-text { text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="login-box">

        <div style="text-align:center; margin-bottom:32px;">
            <img src="{{ asset('images/logo.png') }}" alt="Smart Telecom" style="height:160px; display:block; margin:0 auto;">
            <p style="color:#64748b; font-size:13px; margin-top:8px; letter-spacing:1px; text-transform:uppercase;">Sistema de Gestão de RH</p>
        </div>

        <div class="login-title">Bem-vindo de volta</div>
        <div class="login-subtitle">Entre com suas credenciais para acessar o sistema</div>

        @if($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-input" placeholder="seu@email.com" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">Senha</label>
                <input type="password" name="password"
                       class="form-input" placeholder="••••••••" required>
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                <label style="display:flex; align-items:center; gap:8px; font-size:13px; color:#64748b; cursor:pointer;">
                    <input type="checkbox" name="remember" style="width:16px; height:16px; accent-color:#1BBFBF; cursor:pointer;">
                    Lembrar de mim
                </label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Esqueceu a senha?</a>
                @endif
            </div>

            <button type="submit" class="btn-login">Entrar no sistema</button>
        </form>

        <hr class="divider">
        <p class="footer-text">Smart Telecom — Projetos e Consultoria</p>
    </div>
</body>
</html>