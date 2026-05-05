<x-app-layout>
    <x-slot name="header">Meu Perfil</x-slot>

    <div class="max-w-4xl mx-auto fade-up">
        <div class="mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-50">Configurações da Conta</h2>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Gerencie sua foto de perfil, nome e senha de acesso.</p>
        </div>

        <div class="space-y-6">

            {{-- Avatar + Info Card --}}
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-slate-50">Informações do Perfil</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Atualize sua foto, nome e e-mail.</p>
                </div>

                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('patch')

                    {{-- Avatar Upload --}}
                    <div class="flex items-center gap-6 mb-8 pb-8 border-b border-slate-100 dark:border-slate-800">
                        {{-- Preview da foto --}}
                        <div class="relative flex-shrink-0">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-slate-700 ring-2 ring-offset-2 ring-brand-500/20 dark:ring-offset-[#09090b]">
                                <img
                                    id="avatar-preview"
                                    src="{{ Auth::user()->avatar_url }}"
                                    alt="{{ Auth::user()->name }}"
                                    class="w-full h-full object-cover"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=1bbfbf&background=e6f9f9&size=96'"
                                >
                            </div>
                            {{-- Badge de câmera --}}
                            <label for="avatar" class="absolute -bottom-1 -right-1 w-8 h-8 bg-brand-500 hover:bg-brand-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg transition-colors border-2 border-white dark:border-[#09090b]" title="Alterar foto">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </label>
                            <input
                                type="file"
                                id="avatar"
                                name="avatar"
                                class="hidden"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                onchange="previewAvatar(this)"
                            >
                        </div>

                        {{-- Info --}}
                        <div>
                            <div class="text-base font-semibold text-slate-900 dark:text-slate-50">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ Auth::user()->email }}</div>
                            <div class="mt-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold 
                                {{ Auth::user()->isAdmin() ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/20 dark:text-brand-400' : 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ Auth::user()->isAdmin() ? 'bg-brand-500' : 'bg-blue-500' }}"></span>
                                {{ Auth::user()->isAdmin() ? 'Gestor de RH' : 'Funcionário' }}
                            </div>
                            <p class="text-xs text-slate-400 dark:text-slate-600 mt-3">Clique no ícone de câmera para alterar sua foto.<br>Formatos aceitos: JPG, PNG, WEBP. Máx. 2MB.</p>
                            @if(Auth::user()->avatar)
                                <label class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-rose-500 hover:text-rose-600 cursor-pointer transition-colors" onclick="document.getElementById('remove_avatar').value='1'; document.getElementById('avatar-preview').src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=1bbfbf&background=e6f9f9&size=96';">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Remover foto
                                </label>
                            @endif
                            <input type="hidden" id="remove_avatar" name="remove_avatar" value="0">
                        </div>
                    </div>

                    {{-- Nome --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nome completo</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', Auth::user()->name) }}"
                                required
                                autocomplete="name"
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition"
                            >
                            @error('name')
                                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">E-mail</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', Auth::user()->email) }}"
                                required
                                autocomplete="email"
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition"
                            >
                            @error('email')
                                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if (Auth::user()->employee)
                        <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800">
                            <h4 class="text-sm font-semibold text-slate-900 dark:text-slate-50 mb-4">Dados Complementares de Funcionário</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {{-- RG --}}
                                <div>
                                    <label for="rg" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">RG</label>
                                    <input type="text" id="rg" name="rg" value="{{ old('rg', Auth::user()->employee->rg) }}" placeholder="00.000.000-0" maxlength="20"
                                        class="w-full px-4 py-2.5 border @error('rg') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                    @error('rg')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Data de Nascimento --}}
                                <div>
                                    <label for="birth_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Data de Nascimento</label>
                                    <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', Auth::user()->employee->birth_date ? Auth::user()->employee->birth_date->format('Y-m-d') : '') }}"
                                        class="w-full px-4 py-2.5 border @error('birth_date') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                    @error('birth_date')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Gênero --}}
                                <div>
                                    <label for="gender" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Gênero</label>
                                    <select id="gender" name="gender" class="w-full px-4 py-2.5 border @error('gender') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                        <option value="not_specified" {{ old('gender', Auth::user()->employee->gender) === 'not_specified' ? 'selected' : '' }}>Não Informar</option>
                                        <option value="male" {{ old('gender', Auth::user()->employee->gender) === 'male' ? 'selected' : '' }}>Masculino</option>
                                        <option value="female" {{ old('gender', Auth::user()->employee->gender) === 'female' ? 'selected' : '' }}>Feminino</option>
                                        <option value="other" {{ old('gender', Auth::user()->employee->gender) === 'other' ? 'selected' : '' }}>Outro</option>
                                    </select>
                                    @error('gender')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Estado Civil --}}
                                <div>
                                    <label for="marital_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Estado Civil</label>
                                    <select id="marital_status" name="marital_status" class="w-full px-4 py-2.5 border @error('marital_status') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                        <option value="single" {{ old('marital_status', Auth::user()->employee->marital_status) === 'single' ? 'selected' : '' }}>Solteiro(a)</option>
                                        <option value="married" {{ old('marital_status', Auth::user()->employee->marital_status) === 'married' ? 'selected' : '' }}>Casado(a)</option>
                                        <option value="divorced" {{ old('marital_status', Auth::user()->employee->marital_status) === 'divorced' ? 'selected' : '' }}>Divorciado(a)</option>
                                        <option value="widowed" {{ old('marital_status', Auth::user()->employee->marital_status) === 'widowed' ? 'selected' : '' }}>Viúvo(a)</option>
                                        <option value="other" {{ old('marital_status', Auth::user()->employee->marital_status) === 'other' ? 'selected' : '' }}>Outro</option>
                                    </select>
                                    @error('marital_status')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Telefone --}}
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Telefone Pessoal</label>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone', Auth::user()->employee->phone) }}" placeholder="(00) 9 0000-0000" maxlength="16"
                                        class="w-full px-4 py-2.5 border @error('phone') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                    @error('phone')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Endereço --}}
                                <div class="lg:col-span-3">
                                    <label for="address" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Endereço Residencial <span class="text-rose-500">*</span></label>
                                    <input type="text" id="address" name="address" value="{{ old('address', Auth::user()->employee->address) }}" required placeholder="Rua, número, bairro, cidade — UF"
                                        class="w-full px-4 py-2.5 border @error('address') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                    @error('address')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Contato de Emergência --}}
                                <div class="md:col-span-2">
                                    <label for="emergency_contact_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nome do Contato de Emergência</label>
                                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', Auth::user()->employee->emergency_contact_name) }}" placeholder="Nome do familiar ou amigo"
                                        class="w-full px-4 py-2.5 border @error('emergency_contact_name') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                    @error('emergency_contact_name')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Telefone de Emergência --}}
                                <div>
                                    <label for="emergency_contact_phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Telefone de Emergência</label>
                                    <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', Auth::user()->employee->emergency_contact_phone) }}" placeholder="(00) 9 0000-0000" maxlength="16"
                                        class="w-full px-4 py-2.5 border @error('emergency_contact_phone') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                    @error('emergency_contact_phone')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    @endif


                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-sm">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Salvar Alterações
                        </button>
                        @if (session('status') === 'profile-updated')
                            <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Perfil atualizado com sucesso!
                            </span>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Alterar Senha --}}
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-slate-50">Segurança — Alterar Senha</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Use uma senha forte e única para manter sua conta protegida.</p>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="p-6">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Senha atual</label>
                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                autocomplete="current-password"
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition"
                            >
                            @error('current_password', 'updatePassword')
                                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nova senha</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                autocomplete="new-password"
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition"
                            >
                            @error('password', 'updatePassword')
                                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Confirmar nova senha</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                autocomplete="new-password"
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition"
                            >
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 dark:bg-slate-100 hover:bg-slate-800 dark:hover:bg-white text-white dark:text-slate-900 font-semibold text-sm rounded-xl transition-colors shadow-sm">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            Atualizar Senha
                        </button>
                        @if (session('status') === 'password-updated')
                            <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Senha atualizada!
                            </span>
                        @endif
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                // Validar tamanho (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('A imagem deve ter no máximo 2MB.');
                    input.value = '';
                    return;
                }
                // Validar tipo
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Formato não aceito. Use JPG, PNG ou WEBP.');
                    input.value = '';
                    return;
                }
                // Se selecionou foto, limpa o campo de remover
                document.getElementById('remove_avatar').value = '0';
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
