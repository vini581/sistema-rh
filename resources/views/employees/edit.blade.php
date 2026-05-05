{{-- employees/edit.blade.php — Edição de Funcionário Next.js Style --}}
<x-app-layout>
    <x-slot name="header">Editar Funcionário</x-slot>

    <div class="max-w-4xl mx-auto fade-up">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-50">Editar Funcionário</h2>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">
                    Atualize os dados de <strong>{{ $employee->user->name }}</strong>.
                </p>
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
                <p class="text-sm font-semibold text-rose-700 dark:text-rose-400 mb-1">Corrija os erros antes de continuar:</p>
                @foreach($errors->all() as $error)
                    <p class="text-sm text-rose-600 dark:text-rose-400">• {{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- SEÇÃO 1: Foto + Identidade --}}
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 dark:text-brand-400">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Identidade</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Foto de perfil e nome do colaborador</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-6 mb-6 pb-6 border-b border-slate-100 dark:border-slate-800">
                        <div class="relative flex-shrink-0">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-slate-700 ring-2 ring-offset-2 ring-brand-500/20 dark:ring-offset-[#09090b]">
                                <img id="avatar-preview" src="{{ $employee->user->avatar_url }}" class="w-full h-full object-cover" alt="Avatar" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($employee->user->name) }}&color=1bbfbf&background=e6f9f9&size=96&bold=true'">
                            </div>
                            <label for="avatar" class="absolute -bottom-1 -right-1 w-7 h-7 bg-brand-500 hover:bg-brand-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg transition-colors border-2 border-white dark:border-[#09090b]" title="Alterar foto">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </label>
                            <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="previewImg(this)">
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900 dark:text-slate-50">{{ $employee->user->name }}</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $employee->user->email }}</div>
                            <p class="text-xs text-slate-400 dark:text-slate-600 mt-2">Clique no ícone de câmera para alterar a foto.<br>JPG, PNG, WEBP — máx. 2MB.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nome completo <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $employee->user->name) }}" required
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            @error('name')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">E-mail de acesso <span class="text-rose-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $employee->user->email) }}" required
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            @error('email')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO 2: Dados Profissionais --}}
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden mb-8">
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
                        <div class="md:col-span-2">
                            <label for="position" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Cargo / Função <span class="text-rose-500">*</span></label>
                            <input type="text" id="position" name="position" value="{{ old('position', $employee->position) }}" required
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            @error('position')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="admission_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Admissão <span class="text-rose-500">*</span></label>
                            <input type="date" id="admission_date" name="admission_date" value="{{ old('admission_date', $employee->admission_date->format('Y-m-d')) }}" required
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            @error('admission_date')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="cpf" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">CPF <span class="text-rose-500">*</span></label>
                            <input type="text" id="cpf" name="cpf" value="{{ old('cpf', $employee->cpf) }}" required maxlength="14"
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            @error('cpf')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        {{-- RG --}}
                        <div>
                            <label for="rg" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">RG</label>
                            <input type="text" id="rg" name="rg" value="{{ old('rg', $employee->rg) }}" placeholder="00.000.000-0" maxlength="20"
                                class="w-full px-4 py-2.5 border @error('rg') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('rg')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Data de Nascimento --}}
                        <div>
                            <label for="birth_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Data de Nascimento</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $employee->birth_date ? $employee->birth_date->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-2.5 border @error('birth_date') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('birth_date')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Gênero --}}
                        <div>
                            <label for="gender" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Gênero</label>
                            <select id="gender" name="gender" class="w-full px-4 py-2.5 border @error('gender') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                <option value="not_specified" {{ old('gender', $employee->gender) === 'not_specified' ? 'selected' : '' }}>Não Informar</option>
                                <option value="male" {{ old('gender', $employee->gender) === 'male' ? 'selected' : '' }}>Masculino</option>
                                <option value="female" {{ old('gender', $employee->gender) === 'female' ? 'selected' : '' }}>Feminino</option>
                                <option value="other" {{ old('gender', $employee->gender) === 'other' ? 'selected' : '' }}>Outro</option>
                            </select>
                            @error('gender')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Estado Civil --}}
                        <div>
                            <label for="marital_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Estado Civil</label>
                            <select id="marital_status" name="marital_status" class="w-full px-4 py-2.5 border @error('marital_status') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                                <option value="single" {{ old('marital_status', $employee->marital_status) === 'single' ? 'selected' : '' }}>Solteiro(a)</option>
                                <option value="married" {{ old('marital_status', $employee->marital_status) === 'married' ? 'selected' : '' }}>Casado(a)</option>
                                <option value="divorced" {{ old('marital_status', $employee->marital_status) === 'divorced' ? 'selected' : '' }}>Divorciado(a)</option>
                                <option value="widowed" {{ old('marital_status', $employee->marital_status) === 'widowed' ? 'selected' : '' }}>Viúvo(a)</option>
                                <option value="other" {{ old('marital_status', $employee->marital_status) === 'other' ? 'selected' : '' }}>Outro</option>
                            </select>
                            @error('marital_status')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Telefone --}}
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Telefone Pessoal</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" placeholder="(00) 9 0000-0000" maxlength="16"
                                class="w-full px-4 py-2.5 border @error('phone') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('phone')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Endereço --}}
                        <div class="md:col-span-3">
                            <label for="address" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Endereço Residencial <span class="text-rose-500">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address', $employee->address) }}" required placeholder="Rua, número, bairro, cidade — UF"
                                class="w-full px-4 py-2.5 border @error('address') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('address')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Contato de Emergência --}}
                        <div class="md:col-span-2">
                            <label for="emergency_contact_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nome do Contato de Emergência</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}" placeholder="Nome do familiar ou amigo"
                                class="w-full px-4 py-2.5 border @error('emergency_contact_name') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('emergency_contact_name')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Telefone de Emergência --}}
                        <div>
                            <label for="emergency_contact_phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Telefone de Emergência</label>
                            <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone) }}" placeholder="(00) 9 0000-0000" maxlength="16"
                                class="w-full px-4 py-2.5 border @error('emergency_contact_phone') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm font-medium font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                            @error('emergency_contact_phone')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Salvar Alterações
                </button>
                <a href="{{ route('employees.show', $employee) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        function previewImg(input) {
            if (!input.files || !input.files[0]) return;
            if (input.files[0].size > 2 * 1024 * 1024) { alert('Máximo 2MB.'); input.value=''; return; }
            const reader = new FileReader();
            reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
        document.getElementById('cpf').addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g,'').slice(0,11);
            v = v.replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');
            e.target.value = v;
        });
    </script>
</x-app-layout>