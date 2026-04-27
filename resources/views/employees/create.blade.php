{{-- create.blade.php --}}
<x-app-layout>
    <x-slot name="header">Cadastrar Funcionário</x-slot>

    <div style="max-width:680px;">
        <div class="card fade-up">
            <div class="card-header">
                <span class="card-title">Dados do Funcionário</span>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Voltar
                </a>
            </div>
            <div class="card-body">

                @if($errors->any())
                <div class="alert alert-danger">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Nome completo</label>
                            <div class="input-group">
                                <span class="input-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Nome do funcionário" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="email@empresa.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Senha</label>
                            <div class="input-group">
                                <span class="input-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                                <input type="password" name="password" class="form-input" placeholder="Mínimo 6 caracteres" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">CPF</label>
                            <div class="input-group">
                                <span class="input-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg></span>
                                <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}" class="form-input" placeholder="000.000.000-00" maxlength="14" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Cargo</label>
                            <div class="input-group">
                                <span class="input-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                                <input type="text" name="position" value="{{ old('position') }}" class="form-input" placeholder="Ex: Analista de TI" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Data de Admissão</label>
                            <div class="input-group">
                                <span class="input-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span>
                                <input type="date" name="admission_date" value="{{ old('admission_date') }}" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Endereço</label>
                            <div class="input-group">
                                <span class="input-icon" style="top:14px; transform:none;"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                                <textarea name="address" class="form-input" placeholder="Endereço completo" rows="2" style="padding-left:38px; resize:none;">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Foto de Perfil (Opcional)</label>
                            <input type="file" name="avatar" class="form-input" accept="image/*" style="padding: 6px;">
                        </div>
                    </div>

                    <div style="display:flex; gap:12px; margin-top:8px;">
                        <button type="submit" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Confirmar cadastro
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('cpf').addEventListener('input', function(e) {
        let v = e.target.value.replace(/\D/g, '');
        if (v.length > 11) v = v.slice(0, 11);
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = v;
    });
    </script>
</x-app-layout>