<x-app-layout>
    <x-slot name="header">Perfil Completo: {{ $employee->user->name }}</x-slot>

    <div class="max-w-7xl mx-auto fade-up">
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-50">Detalhes do Funcionário</h2>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Visão completa sobre dados cadastrais, ponto e holerites.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('employees.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-50 bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-xl hover:border-slate-300 dark:hover:border-slate-700 transition-all shadow-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Voltar
                </a>
                <a href="{{ route('schedule.edit', $employee) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 rounded-xl transition-all shadow-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Jornada
                </a>
                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-all shadow-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Editar Cadastro
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- COLUNA ESQUERDA: Resumo e Dados Pessoais --}}
            <div class="flex flex-col gap-6">
                {{-- Card de Perfil --}}
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 text-center">
                    <div class="relative w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-slate-50 dark:border-slate-800 shadow-sm ring-1 ring-slate-200 dark:ring-slate-700">
                        <img src="{{ $employee->user->avatar_url }}" class="w-full h-full object-cover" alt="{{ $employee->user->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($employee->user->name) }}&color=1bbfbf&background=e6f9f9&size=96&bold=true'">
                    </div>
                    <div class="text-lg font-bold text-slate-900 dark:text-slate-50">{{ $employee->user->name }}</div>
                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-4">{{ $employee->user->email }}</div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $employee->workSchedule ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $employee->workSchedule ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                        {{ $employee->workSchedule ? 'Jornada Ativa' : 'Sem Jornada' }}
                    </div>
                </div>

                {{-- Dados Pessoais --}}
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Dados Pessoais e Docs</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-5">
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Nascimento</div>
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $employee->birth_date ? $employee->birth_date->format('d/m/Y') : 'Não informado' }}</div>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Estado Civil</div>
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ match($employee->marital_status) { 'single' => 'Solteiro(a)', 'married' => 'Casado(a)', 'divorced' => 'Divorciado(a)', 'widowed' => 'Viúvo(a)', 'other' => 'Outro', default => 'Solteiro(a)' } }}</div>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">RG</div>
                            <div class="text-sm font-mono font-medium text-slate-700 dark:text-slate-300">{{ $employee->rg ?: 'Não informado' }}</div>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Gênero</div>
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ match($employee->gender) { 'male' => 'Masculino', 'female' => 'Feminino', 'other' => 'Outro', default => 'Não Informar' } }}</div>
                        </div>
                    </div>
                </div>

                {{-- Dados Contratuais --}}
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Dados Contratuais</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-5">
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Cargo</div>
                            <div class="text-sm font-semibold text-slate-900 dark:text-slate-50">{{ $employee->position }}</div>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Admissão</div>
                            <div class="text-sm font-semibold text-slate-900 dark:text-slate-50">{{ $employee->admission_date->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">CPF</div>
                            <div class="text-sm font-mono font-medium text-slate-700 dark:text-slate-300">{{ $employee->cpf }}</div>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Regime</div>
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $employee->getConfig('payment_type') === 'hourly' ? 'Horista' : 'Mensalista' }}</div>
                        </div>
                        <div class="col-span-2">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Remuneração Base</div>
                            <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                @if($employee->getConfig('payment_type') === 'hourly')
                                    R$ {{ number_format($employee->getConfig('hourly_rate') / 100, 2, ',', '.') }} <span class="text-xs font-medium text-slate-500">/ hora</span>
                                @else
                                    R$ {{ number_format(($employee->base_salary ?? 0) / 100, 2, ',', '.') }} <span class="text-xs font-medium text-slate-500">/ mês</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contato e Endereço --}}
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Contato, Endereço e Emergência</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-5">
                        <div class="col-span-2">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Endereço Residencial</div>
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300 leading-relaxed">{{ $employee->address ?: 'Não informado' }}</div>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Telefone Pessoal</div>
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $employee->phone ?: 'Não informado' }}</div>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Contato de Emergência</div>
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $employee->emergency_contact_name ?: 'Não informado' }}</div>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Tel. de Emergência</div>
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $employee->emergency_contact_phone ?: 'Não informado' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Banco de Horas Widget --}}
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group">
                    <div class="absolute inset-x-0 bottom-0 h-1 transition-colors {{ $hourBankBalance < 0 ? 'bg-rose-500' : 'bg-emerald-500' }}"></div>
                    <div class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Saldo Banco de Horas</div>
                    <div class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50 font-mono">
                        {{ intdiv(abs($hourBankBalance), 60) }}h {{ abs($hourBankBalance) % 60 }}m
                    </div>
                    <div class="mt-2 text-sm font-semibold {{ $hourBankBalance < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                        {{ $hourBankBalance < 0 ? 'Negativo' : 'Positivo' }}
                    </div>
                </div>
            </div>

            {{-- COLUNA DIREITA: Holerites, Atestados e Histórico --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Holerites --}}
                    <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Últimos Holerites</h3>
                        </div>
                        <div class="p-2">
                            @forelse($recentPayrolls as $payroll)
                                <div class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-xl transition-colors">
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-slate-50">{{ \Carbon\Carbon::parse($payroll->reference_month)->format('M/Y') }}</div>
                                        <div class="text-[10px] font-semibold text-slate-500 uppercase">{{ $payroll->period_type }}</div>
                                    </div>
                                    <div class="text-sm font-mono font-bold {{ $payroll->net_total > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-900 dark:text-slate-50' }}">
                                        R$ {{ number_format($payroll->net_total/100, 2, ',', '.') }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-sm text-slate-500 dark:text-slate-400 text-center p-6">Nenhum holerite gerado.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Atestados --}}
                    <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Atestados Recentes</h3>
                        </div>
                        <div class="p-2">
                            @forelse($recentCertificates as $cert)
                                <div class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-xl transition-colors">
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-slate-50">{{ \Carbon\Carbon::parse($cert->start_date)->format('d/m/Y') }}</div>
                                        <div class="text-[11px] font-medium text-slate-500">{{ $cert->days }} dias</div>
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-[10px] font-bold uppercase rounded-md 
                                        {{ $cert->status == 'approved' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 
                                        ($cert->status == 'rejected' ? 'bg-rose-50 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400' : 
                                        'bg-amber-50 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400') }}">
                                        {{ $cert->status }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-sm text-slate-500 dark:text-slate-400 text-center p-6">Nenhum atestado registrado.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Histórico de Ponto --}}
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-50">Últimos Registros de Ponto</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                                    <th class="py-3 px-6 text-xs font-bold tracking-wider text-slate-500 uppercase">Data</th>
                                    <th class="py-3 px-6 text-xs font-bold tracking-wider text-slate-500 uppercase">Entrada</th>
                                    <th class="py-3 px-6 text-xs font-bold tracking-wider text-slate-500 uppercase">Saída Almoço</th>
                                    <th class="py-3 px-6 text-xs font-bold tracking-wider text-slate-500 uppercase">Volta Almoço</th>
                                    <th class="py-3 px-6 text-xs font-bold tracking-wider text-slate-500 uppercase">Saída Final</th>
                                    <th class="py-3 px-6 text-xs font-bold tracking-wider text-slate-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($workLogs as $log)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                    <td class="py-3 px-6 text-sm font-semibold text-slate-900 dark:text-slate-50">{{ $log->work_date->format('d/m/Y') }}</td>
                                    <td class="py-3 px-6 text-sm font-mono text-slate-600 dark:text-slate-400">{{ $log->clock_in?->format('H:i') ?? '—' }}</td>
                                    <td class="py-3 px-6 text-sm font-mono text-slate-600 dark:text-slate-400">{{ $log->lunch_out?->format('H:i') ?? '—' }}</td>
                                    <td class="py-3 px-6 text-sm font-mono text-slate-600 dark:text-slate-400">{{ $log->lunch_in?->format('H:i') ?? '—' }}</td>
                                    <td class="py-3 px-6 text-sm font-mono text-slate-600 dark:text-slate-400">{{ $log->clock_out?->format('H:i') ?? '—' }}</td>
                                    <td class="py-3 px-6 text-sm font-mono font-bold text-slate-900 dark:text-slate-50">{{ $log->formatted_hours }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="py-8 text-center text-sm text-slate-500 dark:text-slate-400">Sem registros de ponto.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>