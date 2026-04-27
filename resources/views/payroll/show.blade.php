<x-app-layout>
    <x-slot name="header">Detalhes da Folha</x-slot>
    @php $e = $payroll->employee; @endphp
    <div style="max-width:860px;">
        <div class="card fade-up" style="margin-bottom:18px;">
            <div class="card-header">
                <span class="card-title" style="display:flex;align-items:center;gap:12px;">
                    <img src="{{ $e->user->avatar_url }}" style="width:32px; height:32px; border-radius:8px; object-fit:cover; border:1px solid var(--border);" alt="{{ $e->user->name }}">
                    {{ $e->user->name }} — {{ $payroll->reference_month->translatedFormat('F/Y') }}
                </span>
                <a href="{{ route('payroll.index', ['month' => $payroll->reference_month->format('Y-m')]) }}" class="btn btn-secondary btn-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Voltar
                </a>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:32px;">
                    <div style="background:var(--surface2);border:1px solid var(--border);border-radius:12px;padding:24px;text-align:center;">
                        <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:8px;">Horas Trabalhadas</div>
                        <div style="font-size:32px;font-weight:800;font-family:'JetBrains Mono',monospace;color:var(--primary);">{{ $payroll->formatted_worked_hours }}</div>
                    </div>
                    <div style="background:var(--surface2);border:1px solid var(--border);border-radius:12px;padding:24px;text-align:center;">
                        <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:8px;">Valor Bruto</div>
                        <div style="font-size:32px;font-weight:800;font-family:'JetBrains Mono',monospace;color:var(--success);">R$ {{ number_format($payroll->gross_total / 100, 2, ',', '.') }}</div>
                    </div>
                </div>

                <div style="background:var(--surface1);border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:24px;">
                    <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:8px;">
                        <svg style="width:20px;height:20px;color:var(--danger);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Gestão de Descontos
                    </h3>
                    
                    @if(!in_array($payroll->status, ['closed', 'paid']))
                    <form action="{{ route('payroll.update', $payroll) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div style="display:grid;grid-template-columns:200px 1fr;gap:16px;margin-bottom:16px;">
                            <div>
                                <label class="form-label">Descontos (R$)</label>
                                <input type="number" name="deductions" step="0.01" class="form-input" value="{{ number_format($payroll->deductions / 100, 2, '.', '') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Observação do desconto</label>
                                <input type="text" name="deduction_notes" class="form-input" value="{{ $payroll->deduction_notes }}" placeholder="Ex: Adiantamento, Faltas, etc.">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;">Aplicar descontos</button>
                    </form>
                    @else
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div style="padding:12px;background:var(--surface2);border-radius:8px;">
                            <div style="font-size:11px;color:var(--text-muted);">Descontos</div>
                            <div style="font-weight:600;color:var(--danger);">R$ {{ number_format($payroll->deductions / 100, 2, ',', '.') }}</div>
                        </div>
                        <div style="padding:12px;background:var(--surface2);border-radius:8px;">
                            <div style="font-size:11px;color:var(--text-muted);">Observação</div>
                            <div style="font-weight:500;">{{ $payroll->deduction_notes ?: 'Sem observações' }}</div>
                        </div>
                    </div>
                    @endif
                </div>

                <div style="display:flex;justify-content:space-between;padding:20px 24px;background:var(--primary);border-radius:12px;color:white;box-shadow:0 10px 15px -3px rgba(var(--primary-rgb), 0.3);">
                    <div>
                        <div style="font-size:13px;font-weight:600;opacity:0.9;text-transform:uppercase;">Valor Líquido a Receber</div>
                        <div style="font-size:32px;font-weight:800;font-family:'JetBrains Mono',monospace;">R$ {{ number_format($payroll->net_total / 100, 2, ',', '.') }}</div>
                    </div>
                    @if(!in_array($payroll->status, ['closed', 'paid']))
                    <form action="{{ route('payroll.close', $payroll->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.3);height:100%;" onclick="return confirm('Encerrar folha?')">
                            Fechar Folha
                        </button>
                    </form>
                    @else
                    <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);padding:0 16px;border-radius:8px;">
                        <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                        <span style="font-size:14px;font-weight:600;">Folha Encerrada</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
