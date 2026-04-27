<x-app-layout>
    <x-slot name="header">Relatórios</x-slot>

    {{-- Filtro de Mês --}}
    <div class="card fade-up" style="margin-bottom:18px;">
        <div class="card-body" style="padding:18px 22px;">
            <form method="GET" action="{{ route('reports.index') }}" style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                <label class="form-label" style="margin:0; white-space:nowrap;">Período:</label>
                <input type="month" name="month" value="{{ $month }}" class="form-input" style="width:200px;">
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    Filtrar
                </button>
                @php
                    [$y, $m] = explode('-', $month);
                    $monthLabel = \Carbon\Carbon::createFromDate($y, $m, 1)->translatedFormat('F \d\e Y');
                @endphp
                <span style="font-size:13px; color:var(--text-muted); margin-left:auto;">
                    Relatório de: <strong>{{ ucfirst($monthLabel) }}</strong>
                </span>
            </form>
        </div>
    </div>

    {{-- Cards de Resumo --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:24px;">
        <div class="stat-card fade-up fade-up-1">
            <div class="stat-icon" style="background:#e0f7f7;">
                <svg fill="none" stroke="#1BBFBF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="stat-value">{{ $totals['employees'] }}</div>
            <div class="stat-label">Funcionários</div>
        </div>
        <div class="stat-card fade-up fade-up-2">
            <div class="stat-icon" style="background:#d1fae5;">
                <svg fill="none" stroke="#10b981" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div class="stat-value">{{ $totals['total_days'] }}</div>
            <div class="stat-label">Dias Trabalhados</div>
        </div>
        <div class="stat-card fade-up fade-up-3">
            <div class="stat-icon" style="background:#d1fae5;">
                <svg fill="none" stroke="#10b981" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            @php $th = intdiv($totals['total_hours'], 60); $tm = $totals['total_hours'] % 60; @endphp
            <div class="stat-value">{{ sprintf('%d:%02d', $th, $tm) }}</div>
            <div class="stat-label">Total de Horas</div>
        </div>
        <div class="stat-card fade-up fade-up-4">
            <div class="stat-icon" style="background:#fee2e2;">
                <svg fill="none" stroke="#ef4444" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="stat-value">{{ $totals['negative_balance'] }}</div>
            <div class="stat-label">Com Saldo Negativo</div>
        </div>
    </div>

    {{-- Tabela de Relatório --}}
    <div class="card fade-up" style="animation-delay:.15s; opacity:0;">
        <div class="card-header">
            <span class="card-title">Resumo por Funcionário</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Funcionário</th>
                        <th>Cargo</th>
                        <th>Dias Trab.</th>
                        <th>Incompletos</th>
                        <th>Total Horas</th>
                        <th>Esperado</th>
                        <th>Saldo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $row)
                    @php
                        $h  = intdiv(abs($row['total_minutes']), 60);
                        $mi = abs($row['total_minutes']) % 60;
                        $eh = intdiv($row['expected_minutes'], 60);
                        $em = $row['expected_minutes'] % 60;
                        $bh = intdiv(abs($row['balance_minutes']), 60);
                        $bm = abs($row['balance_minutes']) % 60;
                        $sig = $row['balance_minutes'] >= 0 ? '+' : '-';
                    @endphp
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px;height:32px;border-radius:8px;background:var(--primary-lt);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:var(--primary);">
                                    {{ substr($row['employee']->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight:600; font-size:13.5px;">{{ $row['employee']->user->name }}</div>
                                    <div style="font-size:11px; color:var(--text-muted);">{{ $row['employee']->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:12px;">
                            <span style="background:var(--surface2); border:1px solid var(--border); padding:2px 8px; border-radius:6px;">
                                {{ $row['employee']->position }}
                            </span>
                        </td>
                        <td style="font-family:'JetBrains Mono',monospace; font-size:13px; text-align:center;">{{ $row['days_worked'] }}</td>
                        <td style="font-family:'JetBrains Mono',monospace; font-size:13px; text-align:center;">
                            @if($row['days_incomplete'] > 0)
                                <span style="color:var(--warning); font-weight:600;">{{ $row['days_incomplete'] }}</span>
                            @else
                                <span style="color:var(--text-muted);">0</span>
                            @endif
                        </td>
                        <td style="font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:600;">
                            {{ sprintf('%d:%02d', $h, $mi) }}
                        </td>
                        <td style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--text-muted);">
                            {{ sprintf('%d:%02d', $eh, $em) }}
                        </td>
                        <td>
                            <span style="font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:700; color:{{ $row['balance_minutes'] >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                                {{ sprintf('%s%d:%02d', $sig, $bh, $bm) }}
                            </span>
                        </td>
                        <td>
                            @if($row['days_worked'] === 0)
                                <span class="badge badge-danger"><span class="badge-dot" style="background:#ef4444;"></span>Sem registros</span>
                            @elseif($row['balance_minutes'] >= 0)
                                <span class="badge badge-success"><span class="badge-dot" style="background:#10b981;"></span>Regular</span>
                            @else
                                <span class="badge badge-warning"><span class="badge-dot" style="background:#f59e0b;"></span>Defasado</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:48px; color:var(--text-muted);">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:48px;height:48px;margin:0 auto 12px;display:block;opacity:.25">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Nenhum dado para o período selecionado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
