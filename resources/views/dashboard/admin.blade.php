<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- Stats --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:24px;">

        <div class="stat-card fade-up fade-up-1">
            <div class="stat-glow" style="background:#1BBFBF;"></div>
            <div class="stat-icon" style="background:#e0f7f7;">
                <svg fill="none" stroke="#1BBFBF" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div class="stat-value">{{ $totalEmployees }}</div>
            <div class="stat-label">Total de Funcionários</div>
            <div class="stat-trend up">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                Ativos no sistema
            </div>
        </div>

        <div class="stat-card fade-up fade-up-2">
            <div class="stat-glow" style="background:#10b981;"></div>
            <div class="stat-icon" style="background:#d1fae5;">
                <svg fill="none" stroke="#10b981" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-value">{{ $todayLogs->count() }}</div>
            <div class="stat-label">Presentes Hoje</div>
            <div class="stat-trend up">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                Registros de hoje
            </div>
        </div>

        <div class="stat-card fade-up fade-up-3">
            <div class="stat-glow" style="background:#f59e0b;"></div>
            <div class="stat-icon" style="background:#fef3c7;">
                <svg fill="none" stroke="#f59e0b" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-value">{{ $totalEmployees - $todayLogs->count() }}</div>
            <div class="stat-label">Ausentes Hoje</div>
            <div class="stat-trend down">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
                Sem registro
            </div>
        </div>

        <div class="stat-card fade-up fade-up-4">
            <div class="stat-glow" style="background:#ef4444;"></div>
            <div class="stat-icon" style="background:#fee2e2;">
                <svg fill="none" stroke="#ef4444" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="stat-value">{{ $todayLogs->whereNull('clock_out')->count() }}</div>
            <div class="stat-label">Jornadas Abertas</div>
            <div class="stat-trend down">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
                Sem saída registrada
            </div>
        </div>
    </div>

    {{-- Table + Quick actions --}}
    <div style="display:grid; grid-template-columns:1fr 320px; gap:18px;">

        {{-- Today logs --}}
        <div class="card fade-up" style="animation-delay:.2s; opacity:0;">
            <div class="card-header">
                <span class="card-title">Registros de Ponto — Hoje</span>
                <span style="font-size:12px; color:var(--text-muted);">{{ now()->format('d/m/Y') }}</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Funcionário</th>
                            <th>Entrada</th>
                            <th>Almoço</th>
                            <th>Retorno</th>
                            <th>Saída</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayLogs as $log)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div
                                            style="width:32px;height:32px;border-radius:8px;background:var(--primary-lt);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:var(--primary);">
                                            {{ substr($log->employee->user->name, 0, 1) }}
                                        </div>
                                        <span style="font-weight:500;">{{ $log->employee->user->name }}</span>
                                    </div>
                                </td>
                                <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">
                                    {{ $log->clock_in?->format('H:i') ?? '—' }}</td>
                                <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">
                                    {{ $log->lunch_out?->format('H:i') ?? '—' }}</td>
                                <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">
                                    {{ $log->lunch_in?->format('H:i') ?? '—' }}</td>
                                <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">
                                    {{ $log->clock_out?->format('H:i') ?? '—' }}</td>
                                <td style="font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:600;">
                                    {{ $log->formatted_hours }}</td>
                                <td>
                                    @if ($log->clock_out)
                                        <span class="badge badge-success"><span class="badge-dot"
                                                style="background:#10b981;"></span>Completo</span>
                                    @elseif($log->clock_in)
                                        <span class="badge badge-warning"><span class="badge-dot"
                                                style="background:#f59e0b;"></span>Em curso</span>
                                    @else
                                        <span class="badge badge-danger"><span class="badge-dot"
                                                style="background:#ef4444;"></span>Ausente</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="width:40px;height:40px;margin:0 auto 8px;display:block;opacity:.3">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Nenhum registro hoje
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick actions --}}
        <div style="display:flex; flex-direction:column; gap:18px;">
            <div class="card fade-up" style="animation-delay:.25s; opacity:0;">
                <div class="card-header"><span class="card-title">Ações Rápidas</span></div>
                <div class="card-body" style="display:flex; flex-direction:column; gap:10px;">
                    <a href="{{ route('employees.create') }}" class="btn btn-primary"
                        style="justify-content:center;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Novo Funcionário
                    </a>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary"
                        style="justify-content:center;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Ver Funcionários
                    </a>
                </div>
            </div>

            <div class="card fade-up" style="animation-delay:.3s; opacity:0;">
                <div class="card-header"><span class="card-title">Resumo do Dia</span></div>
                <div class="card-body">
                    @php $pct = $totalEmployees > 0 ? round(($todayLogs->count() / $totalEmployees) * 100) : 0; @endphp
                    <div style="margin-bottom:12px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span style="font-size:13px; color:var(--text-muted);">Presença hoje</span>
                            <span
                                style="font-size:13px; font-weight:700; color:var(--primary);">{{ $pct }}%</span>
                        </div>
                        <div style="height:8px; background:var(--border); border-radius:99px; overflow:hidden;">
                            <div
                                style="height:100%; width:{{ $pct }}%; background:var(--primary); border-radius:99px; transition:width .8s ease;">
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:8px; margin-top:16px;">
                        <div style="display:flex; justify-content:space-between; font-size:13px;">
                            <span style="color:var(--text-muted);">Jornadas completas</span>
                            <span
                                style="font-weight:600; color:var(--success);">{{ $todayLogs->whereNotNull('clock_out')->count() }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:13px;">
                            <span style="color:var(--text-muted);">Em andamento</span>
                            <span
                                style="font-weight:600; color:var(--warning);">{{ $todayLogs->whereNotNull('clock_in')->whereNull('clock_out')->count() }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:13px;">
                            <span style="color:var(--text-muted);">Sem registro</span>
                            <span
                                style="font-weight:600; color:var(--danger);">{{ $totalEmployees - $todayLogs->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
