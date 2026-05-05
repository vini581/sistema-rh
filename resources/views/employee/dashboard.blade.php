<x-app-layout>
    <x-slot name="header">Meu Painel</x-slot>

    <div class="fade-up">
        {{-- Welcome Header --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-50">Olá, {{ explode(' ', $employee->user->name)[0] }}</h2>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Aqui está o resumo da sua jornada e benefícios neste mês.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            {{-- Card: Salário Estimado --}}
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-violet-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Previsão Salarial (Líquido)</div>
                <div class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50 font-mono">
                    R$ {{ number_format($payroll->net_total / 100, 2, ',', '.') }}
                </div>
                <div class="mt-6 text-sm flex items-center justify-between">
                    <span class="font-medium text-slate-500 dark:text-slate-400">Bruto: R$ {{ number_format($payroll->gross_total / 100, 2, ',', '.') }}</span>
                    <a href="{{ route('employee.payroll.index') }}" class="font-semibold text-violet-600 dark:text-violet-400 hover:underline">Ver Detalhes →</a>
                </div>
            </div>

            {{-- Card: Frequência --}}
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-400 to-cyan-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Dias Trabalhados</div>
                <div class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">
                    {{ $daysWorked }} <span class="text-lg font-semibold text-slate-400">dias</span>
                </div>
                <div class="mt-6 text-sm flex items-center justify-between">
                    <span class="font-medium text-slate-500 dark:text-slate-400">Atestados: {{ $certificateDays }} dias</span>
                    <a href="{{ route('work-log.history') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">Ver Ponto →</a>
                </div>
            </div>

            {{-- Card: Férias --}}
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Férias Disponíveis</div>
                <div class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">
                    {{ $vacationBalance }} <span class="text-lg font-semibold text-slate-400">dias</span>
                </div>
                <div class="mt-6 text-sm flex items-center justify-between">
                    <span class="font-medium text-slate-500 dark:text-slate-400">Acúmulo de 2.5d / mês</span>
                    <a href="{{ route('employee.vacations.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-slate-900 dark:bg-white dark:text-slate-900 rounded-lg hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors">Solicitar</a>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Quick Actions & Logs --}}
            <div class="lg:col-span-2">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50 mb-4">Ações Rápidas</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('work-log.index') }}" class="group block p-5 bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl hover:border-blue-500 dark:hover:border-blue-500 transition-all shadow-sm hover:shadow-md">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-4 group-hover:scale-110 transition-transform">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="font-semibold text-slate-900 dark:text-slate-50 mb-1">Bater Ponto</div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Registre sua entrada, almoço ou saída.</div>
                    </a>
                    <a href="{{ route('employee.certificates.index') }}" class="group block p-5 bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl hover:border-rose-500 dark:hover:border-rose-500 transition-all shadow-sm hover:shadow-md">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 dark:text-rose-400 mb-4 group-hover:scale-110 transition-transform">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="font-semibold text-slate-900 dark:text-slate-50 mb-1">Enviar Atestado</div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Faça upload de documentos médicos para o RH.</div>
                    </a>
                </div>
            </div>

            {{-- Informações Contratuais --}}
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50 mb-4">Meu Contrato</h3>
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                    <div class="flex flex-col space-y-5">
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Cargo</div>
                            <div class="font-semibold text-slate-900 dark:text-slate-50">{{ $employee->position }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Carga Horária</div>
                            <div class="font-semibold text-slate-900 dark:text-slate-50">{{ $employee->getConfig('monthly_hours') }} horas mensais</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Valor da Hora Base</div>
                            <div class="font-semibold text-slate-900 dark:text-slate-50">R$ {{ number_format($employee->getConfig('hourly_rate') / 100, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
