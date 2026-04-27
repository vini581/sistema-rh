<x-app-layout>
    <x-slot name="header">Meu Dashboard</x-slot>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:24px;">
        <div class="stat-card fade-up fade-up-1">
            <div class="stat-icon" style="background:#e0f7f7;">
                <svg fill="none" stroke="#1BBFBF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-value">{{ $todayLog?->formatted_hours ?? '00:00' }}</div>
            <div class="stat-label">Horas trabalhadas hoje</div>
        </div>
        <div class="stat-card fade-up fade-up-2">
            <div class="stat-icon" style="background:#e0f7f7;">
                <svg fill="none" stroke="#1BBFBF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div class="stat-value">
                @if($todayLog?->clock_out) <span style="color:var(--success);">Finalizado</span>
                @elseif($todayLog?->clock_in) <span style="color:var(--warning);">Em curso</span>
                @else <span style="color:var(--text-muted);">Não iniciado</span>
                @endif
            </div>
            <div class="stat-label">Status de hoje</div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 340px; gap:18px;">
        <div class="card fade-up" style="animation-delay:.15s; opacity:0;">
            <div class="card-header"><span class="card-title">Últimos 7 dias</span></div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Entrada</th>
                            <th>Saída</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                        <tr>
                            <td style="font-weight:500;">{{ $log->work_date->format('d/m/Y') }}</td>
                            <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">{{ $log->clock_in?->format('H:i') ?? '—' }}</td>
                            <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">{{ $log->clock_out?->format('H:i') ?? '—' }}</td>
                            <td style="font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:600;">{{ $log->formatted_hours }}</td>
                            <td>
                                @if($log->clock_out)
                                    <span class="badge badge-success"><span class="badge-dot" style="background:#10b981;"></span>Completo</span>
                                @else
                                    <span class="badge badge-warning"><span class="badge-dot" style="background:#f59e0b;"></span>Incompleto</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center; padding:32px; color:var(--text-muted);">Nenhum registro encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card fade-up" style="animation-delay:.2s; opacity:0;">
            <div class="card-header"><span class="card-title">Registro de Hoje</span></div>
            <div class="card-body">
                @php
                    $items = [
                        ['label' => 'Entrada',       'time' => $todayLog?->clock_in,   'color' => '#10b981'],
                        ['label' => 'Saída Almoço',  'time' => $todayLog?->lunch_out,  'color' => '#f59e0b'],
                        ['label' => 'Volta Almoço',  'time' => $todayLog?->lunch_in,   'color' => '#3b82f6'],
                        ['label' => 'Saída Final',   'time' => $todayLog?->clock_out,  'color' => '#8b5cf6'],
                    ];
                @endphp
                <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:20px;">
                    @foreach($items as $item)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:var(--surface2); border-radius:10px; border:1px solid var(--border);">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:8px;height:8px;border-radius:50%;background:{{ $item['color'] }};"></div>
                            <span style="font-size:13px; color:var(--text-muted);">{{ $item['label'] }}</span>
                        </div>
                        <span style="font-family:'JetBrains Mono',monospace; font-size:14px; font-weight:600; color:{{ $item['time'] ? 'var(--text)' : 'var(--border)' }};">
                            {{ $item['time'] ? $item['time']->format('H:i') : '--:--' }}
                        </span>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('work-log.index') }}" class="btn btn-primary" style="width:100%; justify-content:center;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Registrar Ponto
                </a>
            </div>
        </div>
    </div>
</x-app-layout>