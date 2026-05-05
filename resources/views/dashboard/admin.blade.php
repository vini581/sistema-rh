<x-app-layout>
    <x-slot name="header">Painel Principal</x-slot>

    <div class="mb-8 fade-up">
        <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-50">Dashboard do RH</h2>
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Visão geral em tempo real da jornada e colaboradores.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-up">
        
        <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-400 to-brand-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-12 h-12 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 dark:text-brand-400 mb-6">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <div class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-50 mb-1">{{ $totalEmployees }}</div>
            <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Total de Funcionários</div>
        </div>

        <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-6">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-50 mb-1">{{ $todayLogs->count() }}</div>
            <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Presentes Hoje</div>
        </div>

        <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-amber-400 to-amber-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400 mb-6">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-50 mb-1">{{ $totalEmployees - $todayLogs->count() }}</div>
            <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Ausentes Hoje</div>
        </div>

        <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-rose-400 to-rose-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 dark:text-rose-400 mb-6">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-50 mb-1">{{ $todayLogs->whereNull('clock_out')->count() }}</div>
            <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Jornadas Abertas</div>
        </div>
    </div>

    {{-- Chart Section --}}
    <div class="mb-8 fade-up" style="animation-delay: 0.05s">
        <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Frequência Semanal</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Comparativo de presentes vs ausentes nos últimos 7 dias</p>
                </div>
            </div>
            <div class="h-[300px] w-full">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 fade-up" style="animation-delay: 0.1s">
        
        {{-- Table --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Registros de Hoje</h3>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ now()->format('d/m/Y') }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50">
                                <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Funcionário</th>
                                <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Entrada</th>
                                <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Saída</th>
                                <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                                <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($todayLogs as $log)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold text-sm">
                                                {{ substr($log->employee->user->name, 0, 1) }}
                                            </div>
                                            <span class="font-medium text-slate-900 dark:text-slate-50">{{ $log->employee->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-mono text-sm text-slate-600 dark:text-slate-300">{{ $log->clock_in?->format('H:i') ?? '—' }}</td>
                                    <td class="py-4 px-6 font-mono text-sm text-slate-600 dark:text-slate-300">{{ $log->clock_out?->format('H:i') ?? '—' }}</td>
                                    <td class="py-4 px-6 font-mono text-sm font-semibold text-slate-900 dark:text-slate-50">{{ $log->formatted_hours }}</td>
                                    <td class="py-4 px-6">
                                        @if ($log->clock_out)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Completo
                                            </span>
                                        @elseif($log->clock_in)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Em curso
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ausente
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                        Nenhum registro hoje.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar Widgets --}}
        <div class="space-y-6">
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50 mb-4">Ações Rápidas</h3>
                <div class="space-y-3">
                    <a href="{{ route('employees.create') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-600 text-white rounded-xl font-medium hover:bg-brand-700 transition-colors">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Novo Funcionário
                    </a>
                    <a href="{{ route('employees.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl font-medium hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Ver Funcionários
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50 mb-4">Resumo do Dia</h3>
                @php $pct = $totalEmployees > 0 ? round(($todayLogs->count() / $totalEmployees) * 100) : 0; @endphp
                <div class="mb-6">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Presença hoje</span>
                        <span class="font-bold text-brand-600 dark:text-brand-400">{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-brand-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Completas</span>
                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $todayLogs->whereNotNull('clock_out')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Em andamento</span>
                        <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $todayLogs->whereNotNull('clock_in')->whereNull('clock_out')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Sem registro</span>
                        <span class="font-semibold text-rose-600 dark:text-rose-400">{{ $totalEmployees - $todayLogs->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($chartData);
            
            const labels = chartData.map(d => d.date);
            const presentes = chartData.map(d => d.presentes);
            const ausentes = chartData.map(d => d.ausentes);

            const ctx = document.getElementById('attendanceChart').getContext('2d');
            
            // Cria um gradiente para a linha principal
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(27, 191, 191, 0.2)'); // Brand cor
            gradient.addColorStop(1, 'rgba(27, 191, 191, 0)');

            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Presentes',
                            data: presentes,
                            borderColor: '#1bbfbf',
                            backgroundColor: gradient,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                        },
                        {
                            label: 'Ausentes',
                            data: ausentes,
                            borderColor: '#ef4444',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0.4,
                            fill: false,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: document.documentElement.classList.contains('dark') ? '#09090b' : '#ffffff',
                            titleColor: document.documentElement.classList.contains('dark') ? '#fafafa' : '#09090b',
                            bodyColor: document.documentElement.classList.contains('dark') ? '#a1a1aa' : '#71717a',
                            borderColor: document.documentElement.classList.contains('dark') ? '#27272a' : '#e4e4e7',
                            borderWidth: 1,
                            padding: 10,
                            boxPadding: 4,
                            usePointStyle: true,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#a1a1aa', font: { family: "'Inter', sans-serif", size: 12 } },
                            border: { display: false }
                        },
                        y: {
                            grid: { 
                                color: document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)',
                                drawBorder: false 
                            },
                            ticks: { color: '#a1a1aa', font: { family: "'Inter', sans-serif", size: 12 }, stepSize: 1, beginAtZero: true },
                            border: { display: false }
                        }
                    },
                    interaction: { mode: 'nearest', axis: 'x', intersect: false }
                }
            });

            // Observador para atualizar o gráfico ao alternar Dark Mode
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        const isDark = document.documentElement.classList.contains('dark');
                        chart.options.plugins.tooltip.backgroundColor = isDark ? '#09090b' : '#ffffff';
                        chart.options.plugins.tooltip.titleColor = isDark ? '#fafafa' : '#09090b';
                        chart.options.plugins.tooltip.bodyColor = isDark ? '#a1a1aa' : '#71717a';
                        chart.options.plugins.tooltip.borderColor = isDark ? '#27272a' : '#e4e4e7';
                        chart.options.scales.y.grid.color = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
                        chart.update();
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
        });
    </script>
</x-app-layout>
