<x-app-layout>
    <x-slot name="header">Histórico de Ponto</x-slot>

    {{-- Cards de saldo --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:24px;">
        <div class="stat-card fade-up fade-up-1">
            <div class="stat-icon" style="background:{{ $totalBalance >= 0 ? '#d1fae5' : '#fee2e2' }};">
                <svg fill="none" stroke="{{ $totalBalance >= 0 ? '#10b981' : '#ef4444' }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            @php
                $abs = abs($totalBalance);
                $h   = intdiv($abs, 60);
                $m   = $abs % 60;
                $sig = $totalBalance >= 0 ? '+' : '-';
            @endphp
            <div class="stat-value" style="color:{{ $totalBalance >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                {{ sprintf('%s%02d:%02d', $sig, $h, $m) }}
            </div>
            <div class="stat-label">Saldo Banco de Horas</div>
            <div class="stat-trend {{ $totalBalance >= 0 ? 'up' : 'down' }}">
                {{ $totalBalance >= 0 ? 'Horas a favor' : 'Horas devendo' }}
            </div>
        </div>

        <div class="stat-card fade-up fade-up-2">
            <div class="stat-icon" style="background:#e0f7f7;">
                <svg fill="none" stroke="#1BBFBF" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="stat-value">{{ $logs->total() }}</div>
            <div class="stat-label">Total de Registros</div>
            <div class="stat-trend up">Dias registrados</div>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="card fade-up" style="animation-delay:.15s; opacity:0;">
        <div class="card-header">
            <span class="card-title">Registros de Ponto</span>
            <a href="{{ route('work-log.index') }}" class="btn btn-secondary btn-sm">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Voltar
            </a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Entrada</th>
                        <th>Saída Almoço</th>
                        <th>Volta Almoço</th>
                        <th>Saída</th>
                        <th>Total</th>
                        <th>Banco</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td style="font-weight:600;">{{ $log->work_date->format('d/m/Y') }}</td>
                        <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">{{ $log->clock_in?->format('H:i') ?? '—' }}</td>
                        <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">{{ $log->lunch_out?->format('H:i') ?? '—' }}</td>
                        <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">{{ $log->lunch_in?->format('H:i') ?? '—' }}</td>
                        <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">{{ $log->clock_out?->format('H:i') ?? '—' }}</td>
                        <td style="font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:700;">{{ $log->formatted_hours }}</td>
                        <td>
                            @if($log->hourBank)
                                @php $bal = $log->hourBank->balance_minutes; @endphp
                                <span style="font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:700; color:{{ $bal >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                                    {{ $log->hourBank->formatted_balance }}
                                </span>
                            @else
                                <span style="color:var(--border);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($log->clock_out)
                                <span class="badge badge-success"><span class="badge-dot" style="background:#10b981;"></span>Completo</span>
                            @elseif($log->clock_in)
                                <span class="badge badge-warning"><span class="badge-dot" style="background:#f59e0b;"></span>Em curso</span>
                            @else
                                <span class="badge badge-danger"><span class="badge-dot" style="background:#ef4444;"></span>Ausente</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:48px; color:var(--text-muted);">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:48px;height:48px;margin:0 auto 12px;display:block;opacity:.25">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Nenhum registro encontrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        @if($logs->hasPages())
        <div style="padding:16px 22px; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:13px; color:var(--text-muted);">
                Mostrando {{ $logs->firstItem() }}–{{ $logs->lastItem() }} de {{ $logs->total() }} registros
            </span>
            <div class="pagination">
                @if($logs->onFirstPage())
                    <span class="page-btn" style="opacity:.4; cursor:not-allowed;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                @else
                    <a href="{{ $logs->previousPageUrl() }}" class="page-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif
                @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-btn {{ $page == $logs->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}" class="page-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <span class="page-btn" style="opacity:.4; cursor:not-allowed;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>
</x-app-layout>