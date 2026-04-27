<x-app-layout>
    <x-slot name="header">Folha de Pagamento</x-slot>
    <div class="card fade-up" style="margin-bottom:18px;">
        <div class="card-body" style="padding:18px 22px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" action="{{ route('payroll.index') }}" style="display:flex; align-items:center; gap:12px;">
                <label class="form-label" style="margin:0;">Mês:</label>
                <input type="month" name="month" value="{{ $month }}" class="form-input" style="width:200px;">
                <button type="submit" class="btn btn-secondary">Filtrar</button>
            </form>
            <form method="POST" action="{{ route('payroll.calculate') }}" style="margin-left:auto;">
                @csrf
                <input type="hidden" name="month" value="{{ $month }}">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Calcular folha?')">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Calcular Folha
                </button>
            </form>
        </div>
    </div>
    <div class="card fade-up" style="animation-delay:.1s; opacity:0;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <span class="card-title">Resumo — {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F/Y') }}</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Funcionário</th>
                        <th>Horas Trab.</th>
                        <th>Bruto (R$)</th>
                        <th>Líquido (R$)</th>
                        <th>Status</th>
                        <th style="text-align:right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <img src="{{ $p->employee->user->avatar_url }}" style="width:32px; height:32px; border-radius:8px; object-fit:cover; border:1px solid var(--border);" alt="{{ $p->employee->user->name }}">
                                <span style="font-weight:600;font-size:13px;">{{ $p->employee->user->name }}</span>
                            </div>
                        </td>
                        <td style="font-family:'JetBrains Mono',monospace;font-size:13px;">{{ $p->formatted_worked_hours }}</td>
                        <td style="font-family:'JetBrains Mono',monospace;font-size:14px;font-weight:700;">R$ {{ number_format($p->gross_total / 100, 2, ',', '.') }}</td>
                        <td style="font-family:'JetBrains Mono',monospace;font-size:14px;font-weight:700;color:var(--success);">R$ {{ number_format($p->net_total / 100, 2, ',', '.') }}</td>
                        <td>
                            @php
                                $badgeClass = match($p->status) {
                                    'paid'       => 'badge-info',
                                    'closed'     => 'badge-success',
                                    'calculated' => 'badge-warning',
                                    default      => 'badge-secondary',
                                };
                                $dotColor = match($p->status) {
                                    'paid'       => '#3b82f6',
                                    'closed'     => '#10b981',
                                    'calculated' => '#f59e0b',
                                    default      => '#94a3b8',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                <span class="badge-dot" style="background:{{ $dotColor }};"></span>
                                {{ $p->status_label }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex; justify-content:flex-end; gap:8px;">
                                <a href="{{ route('payroll.show', $p) }}" class="btn btn-ghost btn-sm" title="Detalhes">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if(!in_array($p->status, ['closed', 'paid']))
                                <form action="{{ route('payroll.close', $p->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);" onclick="return confirm('Fechar folha?')">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:48px;color:var(--text-muted);">Nenhuma folha calculada. Clique em "Calcular Folha".</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
