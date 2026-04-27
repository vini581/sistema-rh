<x-app-layout>
    <x-slot name="header">Minhas Folhas de Pagamento</x-slot>

    <div class="card fade-up">
        <div class="card-header"><span class="card-title">Histórico Mensal</span></div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Mês de Referência</th>
                        <th>Horas Trabalhadas</th>
                        <th>Total Bruto</th>
                        <th>Total Líquido</th>
                        <th>Status</th>
                        <th style="text-align:right;">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
                    <tr>
                        <td style="font-weight:700; text-transform:capitalize;">{{ $p->reference_month->translatedFormat('F / Y') }}</td>
                        <td style="font-family:'JetBrains Mono',monospace; text-align:center;">{{ $p->formatted_worked_hours }}</td>
                        <td style="font-family:'JetBrains Mono',monospace; font-weight:700;">R$ {{ number_format($p->gross_total / 100, 2, ',', '.') }}</td>
                        <td style="font-family:'JetBrains Mono',monospace; font-weight:700; color:var(--success);">R$ {{ number_format($p->net_total / 100, 2, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $p->status === 'paid' ? 'badge-success' : 'badge-warning' }}">
                                <span class="badge-dot" style="background: {{ $p->status === 'paid' ? '#10b981' : '#f59e0b' }};"></span>
                                {{ $p->status_label }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <a href="{{ route('employee.payroll.show', $p) }}" class="btn btn-ghost btn-sm" title="Ver Detalhes">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:48px; color:var(--text-muted);">Nenhum contracheque disponível.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payrolls->hasPages())
        <div style="padding:16px; border-top:1px solid var(--border);">{{ $payrolls->links() }}</div>
        @endif
    </div>
</x-app-layout>
