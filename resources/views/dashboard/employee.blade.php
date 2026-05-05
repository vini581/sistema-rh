<x-app-layout>
    <x-slot name="header">Meu Painel de Ponto</x-slot>

    <div class="mb-8 fade-up">
        <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-50">Resumo da Jornada</h2>
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Acompanhe suas horas trabalhadas e histórico recente.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 fade-up">
        
        <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-400 to-brand-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-12 h-12 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 dark:text-brand-400 mb-6">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="text-4xl font-bold tracking-tight font-mono text-slate-900 dark:text-slate-50 mb-1">{{ $todayLog?->formatted_hours ?? '00:00' }}</div>
            <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Horas trabalhadas hoje</div>
        </div>

        <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-400 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-50 mb-2">
                @if($todayLog?->clock_out) <span class="text-emerald-600 dark:text-emerald-400">Finalizado</span>
                @elseif($todayLog?->clock_in) <span class="text-amber-600 dark:text-amber-400">Em curso</span>
                @else <span class="text-slate-500 dark:text-slate-400">Não iniciado</span>
                @endif
            </div>
            <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Status de hoje</div>
        </div>

    </div>

    {{-- Chart Section --}}
    <div class="mb-8 fade-up" style="animation-delay: 0.05s">
        <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Horas Trabalhadas</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total de horas contabilizadas nos últimos 7 dias</p>
                </div>
            </div>
            <div class="h-[250px] w-full">
                <canvas id="employeeChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 fade-up" style="animation-delay: 0.1s">
        
        {{-- Table --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Últimos 7 dias</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50">
                                <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Data</th>
                                <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Entrada</th>
                                <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Saída</th>
                                <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                                <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse($recentLogs as $log)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                                    <td class="py-4 px-6 font-medium text-slate-900 dark:text-slate-50">{{ $log->work_date->format('d/m/Y') }}</td>
                                    <td class="py-4 px-6 font-mono text-sm text-slate-600 dark:text-slate-300">{{ $log->clock_in?->format('H:i') ?? '—' }}</td>
                                    <td class="py-4 px-6 font-mono text-sm text-slate-600 dark:text-slate-300">{{ $log->clock_out?->format('H:i') ?? '—' }}</td>
                                    <td class="py-4 px-6 font-mono text-sm font-semibold text-slate-900 dark:text-slate-50">{{ $log->formatted_hours }}</td>
                                    <td class="py-4 px-6">
                                        @if($log->clock_out)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Completo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Incompleto
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                        Nenhum registro encontrado.
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
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50 mb-6">Registro de Hoje</h3>
                
                @php
                    $items = [
                        ['label' => 'Entrada',       'time' => $todayLog?->clock_in,   'color' => 'bg-emerald-500'],
                        ['label' => 'Saída Almoço',  'time' => $todayLog?->lunch_out,  'color' => 'bg-amber-500'],
                        ['label' => 'Volta Almoço',  'time' => $todayLog?->lunch_in,   'color' => 'bg-blue-500'],
                        ['label' => 'Saída Final',   'time' => $todayLog?->clock_out,  'color' => 'bg-violet-500'],
                    ];
                @endphp
                
                <div class="space-y-3 mb-8">
                    @foreach($items as $item)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800/60">
                            <div class="flex items-center gap-3">
                                <div class="w-2.5 h-2.5 rounded-full {{ $item['color'] }}"></div>
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $item['label'] }}</span>
                            </div>
                            <span class="font-mono text-sm font-semibold {{ $item['time'] ? 'text-slate-900 dark:text-slate-50' : 'text-slate-400 dark:text-slate-600' }}">
                                {{ $item['time'] ? $item['time']->format('H:i') : '--:--' }}
                            </span>
                        </div>
                    @endforeach
                </div>
                
                <a href="{{ route('work-log.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-brand-600 text-white rounded-xl font-medium hover:bg-brand-700 transition-colors shadow-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Registrar Ponto Agora
                </a>
            </div>

            {{-- Profile Completion Widget --}}
            @if(!empty($profileCompletion) && $profileCompletion['percentage'] < 100)
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm relative overflow-hidden">
                    {{-- Subtle gradient glow --}}
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full opacity-10" style="background: var(--primary);"></div>

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-slate-50">Completude do Perfil</h3>
                        <span class="text-2xl font-bold" style="color: var(--primary);">{{ $profileCompletion['percentage'] }}%</span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="w-full h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 mb-5 overflow-hidden">
                        <div 
                            class="h-full rounded-full transition-all duration-700 ease-out" 
                            style="width: {{ $profileCompletion['percentage'] }}%; background: linear-gradient(90deg, var(--primary), #34d399);"
                        ></div>
                    </div>

                    {{-- Field Checklist --}}
                    <div class="space-y-2.5">
                        @foreach($profileCompletion['fields'] as $field)
                            <div class="flex items-center gap-3 text-sm">
                                @if(!empty($field['value']))
                                    <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-3 h-3 text-emerald-600 dark:text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-slate-500 dark:text-slate-400 line-through">{{ $field['label'] }}</span>
                                @else
                                    <div class="w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                                        <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                    </div>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $field['label'] }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Action --}}
                    <a href="{{ route('profile.edit') }}" class="mt-5 w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 dark:hover:text-brand-400 transition-colors" style="text-decoration:none;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Completar meu perfil
                    </a>
                </div>
            @endif
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($chartData);
            
            const labels = chartData.map(d => d.date);
            const hours = chartData.map(d => d.hours);

            const ctx = document.getElementById('employeeChart').getContext('2d');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 250);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)'); // blue-500
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Horas Trabalhadas',
                            data: hours,
                            borderColor: '#3b82f6',
                            backgroundColor: gradient,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
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
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' horas';
                                }
                            }
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
                            ticks: { color: '#a1a1aa', font: { family: "'Inter', sans-serif", size: 12 }, beginAtZero: true },
                            border: { display: false }
                        }
                    },
                    interaction: { mode: 'nearest', axis: 'x', intersect: false }
                }
            });

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