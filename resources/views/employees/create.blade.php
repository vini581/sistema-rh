{{-- employees/create.blade.php — Cadastro detalhado Next.js Style --}}
<x-app-layout>
    <x-slot name="header">Cadastrar Funcionário</x-slot>

    <div class="max-w-4xl mx-auto fade-up">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-50">Novo Funcionário</h2>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Preencha todos os dados para registrar um novo colaborador no sistema.</p>
            </div>
            <a href="{{ route('employees.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-50 bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-xl hover:border-slate-300 dark:hover:border-slate-700 transition-all shadow-sm">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Voltar
            </a>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 rounded-xl flex items-start gap-3">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 text-rose-600 dark:text-rose-400 flex-shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-sm font-semibold text-rose-700 dark:text-rose-400 mb-1">Corrija os erros abaixo antes de continuar:</p>
                @foreach($errors->all() as $error)
                    <p class="text-sm text-rose-600 dark:text-rose-400">• {{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data" id="employee-form">
            @csrf

            {{-- SEÇÃO 1: Foto + Acesso --}}
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 dark:text-brand-400">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Identidade & Acesso</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Dados de autenticação e foto do colaborador</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-6 mb-6 pb-6 border-b border-slate-100 dark:border-slate-800">
                        {{-- Avatar Preview --}}
                        <div class="relative flex-shrink-0">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex items-center justify-center" id="avatar-preview-container">
                                <img id="avatar-preview" src="" alt="" class="hidden w-full h-full object-cover">
                                <svg id="avatar-placeholder" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-8 h-8 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <label for="avatar" class="absolute -bottom-1 -right-1 w-7 h-7 bg-brand-500 hover:bg-brand-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg transition-colors border-2 border-white dark:border-[#09090b]" title="Adicionar foto">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </label>
                            <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="handleAvatarPreview(this)">
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Foto de perfil</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Clique no <strong>+</strong> para selecionar.<br>JPG, PNG, WEBP — máx. 2MB. Opcional.</p>
                            <p id="avatar-filename" class="text-xs font-medium text-brand-600 dark:text-brand-400 mt-2 hidden"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Nome --}}
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nome completo <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Ex: João da Silva"
                                class="w-full px-4 py-2.5 border @error('name') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            @error('name')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">E-mail <span class="text-rose-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="funcionario@empresa.com"
                                class="w-full px-4 py-2.5 border @error('email') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            @error('email')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Senha --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Senha de acesso <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required placeholder="Mínimo 6 caracteres"
                                    class="w-full px-4 py-2.5 pr-11 border @error('password') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                                <button type="button" onclick="togglePassword('password', 'eye-password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                                    <svg id="eye-password" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                            @error('password')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO 2: Dados Profissionais --}}
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Dados Profissionais</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Cargo, admissão e documentação</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        {{-- Cargo --}}
                        <div class="md:col-span-2">
                            <label for="position" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Cargo / Função <span class="text-rose-500">*</span></label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}" required placeholder="Ex: Analista de Suporte"
                                class="w-full px-4 py-2.5 border @error('position') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            @error('position')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Data de admissão --}}
                        <div>
                            <label for="admission_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Admissão <span class="text-rose-500">*</span></label>
                            <input type="date" id="admission_date" name="admission_date" value="{{ old('admission_date', date('Y-m-d')) }}" required
                                class="w-full px-4 py-2.5 border @error('admission_date') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            @error('admission_date')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- CPF --}}
                        <div>
                            <label for="cpf" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">CPF <span class="text-rose-500">*</span></label>
                            <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" required placeholder="000.000.000-00" maxlength="14"
                                class="w-full px-4 py-2.5 border @error('cpf') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            @error('cpf')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- RG --}}
                        <div>
                            <label for="rg" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">RG</label>
                            <input type="text" id="rg" name="rg" value="{{ old('rg') }}" placeholder="00.000.000-0" maxlength="20"
                                class="w-full px-4 py-2.5 border @error('rg') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('rg')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Data de Nascimento --}}
                        <div>
                            <label for="birth_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Data de Nascimento</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                class="w-full px-4 py-2.5 border @error('birth_date') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('birth_date')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Gênero --}}
                        <div>
                            <label for="gender" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Gênero</label>
                            <select id="gender" name="gender" class="w-full px-4 py-2.5 border @error('gender') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                <option value="not_specified" {{ old('gender') === 'not_specified' ? 'selected' : '' }}>Não Informar</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Masculino</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Feminino</option>
                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Outro</option>
                            </select>
                            @error('gender')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Estado Civil --}}
                        <div>
                            <label for="marital_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Estado Civil</label>
                            <select id="marital_status" name="marital_status" class="w-full px-4 py-2.5 border @error('marital_status') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                <option value="single" {{ old('marital_status') === 'single' ? 'selected' : '' }}>Solteiro(a)</option>
                                <option value="married" {{ old('marital_status') === 'married' ? 'selected' : '' }}>Casado(a)</option>
                                <option value="divorced" {{ old('marital_status') === 'divorced' ? 'selected' : '' }}>Divorciado(a)</option>
                                <option value="widowed" {{ old('marital_status') === 'widowed' ? 'selected' : '' }}>Viúvo(a)</option>
                                <option value="other" {{ old('marital_status') === 'other' ? 'selected' : '' }}>Outro</option>
                            </select>
                            @error('marital_status')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Telefone --}}
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Telefone Pessoal</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="(00) 9 0000-0000" maxlength="16"
                                class="w-full px-4 py-2.5 border @error('phone') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('phone')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Endereço --}}
                        <div class="md:col-span-3">
                            <label for="address" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Endereço Residencial <span class="text-rose-500">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}" required placeholder="Rua, número, bairro, cidade — UF"
                                class="w-full px-4 py-2.5 border @error('address') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('address')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Contato de Emergência --}}
                        <div class="md:col-span-2">
                            <label for="emergency_contact_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nome do Contato de Emergência</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Nome do familiar ou amigo"
                                class="w-full px-4 py-2.5 border @error('emergency_contact_name') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('emergency_contact_name')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Telefone de Emergência --}}
                        <div>
                            <label for="emergency_contact_phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Telefone de Emergência</label>
                            <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" placeholder="(00) 9 0000-0000" maxlength="16"
                                class="w-full px-4 py-2.5 border @error('emergency_contact_phone') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('emergency_contact_phone')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO 3: Configuração Financeira (Opcional no cadastro) --}}
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center text-violet-600 dark:text-violet-400">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Configuração Salarial</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Opcional agora — pode ser configurado depois em "Configurações RH"</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">Opcional</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="base_salary" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Valor Base (R$)</label>
                            <input type="number" id="base_salary" name="base_salary" value="{{ old('base_salary') }}" placeholder="0,00" step="0.01" min="0"
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            <p class="mt-1.5 text-xs text-slate-400">Em reais. Salário fixo (se mensal) ou valor/hora (se horista).</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tipo de Pagamento</label>
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center gap-2.5 p-3 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:border-brand-400 dark:hover:border-brand-500 transition has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 dark:has-[:checked]:bg-brand-500/10">
                                    <input type="radio" name="payment_type" value="monthly" {{ old('payment_type', 'monthly') === 'monthly' ? 'checked' : '' }} class="text-brand-500">
                                    <div>
                                        <div class="text-xs font-semibold text-slate-700 dark:text-slate-300">Mensal</div>
                                        <div class="text-[10px] text-slate-500">Salário fixo</div>
                                    </div>
                                </label>
                                <label class="flex-1 flex items-center gap-2.5 p-3 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:border-brand-400 dark:hover:border-brand-500 transition has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 dark:has-[:checked]:bg-brand-500/10">
                                    <input type="radio" name="payment_type" value="hourly" {{ old('payment_type') === 'hourly' ? 'checked' : '' }} class="text-brand-500">
                                    <div>
                                        <div class="text-xs font-semibold text-slate-700 dark:text-slate-300">Por Hora</div>
                                        <div class="text-[10px] text-slate-500">Calculado por horas</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 rounded-xl flex items-start gap-2.5">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-amber-700 dark:text-amber-400">Regras detalhadas de horas extras, DSR, benefícios e vigências são gerenciadas em <strong>Configurações RH</strong> após o cadastro.</p>
                    </div>
                </div>
            </div>

            {{-- Ações --}}
            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Cadastrar Funcionário
                </button>
                <a href="{{ route('employees.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        // Preview de avatar
        function handleAvatarPreview(input) {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];
            if (file.size > 2 * 1024 * 1024) {
                alert('A imagem deve ter no máximo 2MB.');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');
                const filename = document.getElementById('avatar-filename');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                filename.textContent = '✓ ' + file.name;
                filename.classList.remove('hidden');
                // Remover borda pontilhada após upload
                document.getElementById('avatar-preview-container').classList.remove('border-dashed');
                document.getElementById('avatar-preview-container').classList.add('border-solid', 'border-brand-300');
            };
            reader.readAsDataURL(file);
        }

        // Máscara CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length > 11) v = v.slice(0, 11);
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = v;
        });

        // Máscara Telefone
        document.getElementById('phone').addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length > 11) v = v.slice(0, 11);
            if (v.length <= 10) {
                v = v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else {
                v = v.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
            }
            e.target.value = v;
        });

        // Toggle password visibility
        function togglePassword(fieldId, eyeId) {
            const field = document.getElementById(fieldId);
            field.type = field.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-app-layout>