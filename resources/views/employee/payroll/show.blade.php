<x-app-layout>
    <x-slot name="header">Meu Contracheque</x-slot>

    <div style="max-width:800px;" class="fade-up">
        <div class="card">
            <div class="card-header">
                <span class="card-title" style="text-transform:capitalize;">{{ $payroll->reference_month->translatedFormat('F \d\e Y') }}</span>
                <a href="{{ route('employee.payroll.index') }}" class="btn btn-secondary btn-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Voltar
                </a>
            </div>
            <div class="card-body">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:30px;">
                    <div style="background:var(--surface2); padding:20px; border-radius:12px; text-align:center; border:1px solid var(--border);">
                        <div style="font-size:12px; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Horas Trabalhadas</div>
                        <div style="font-size:28px; font-weight:800; font-family:'JetBrains Mono',monospace; color:var(--primary);">{{ $payroll->formatted_worked_hours }}</div>
                    </div>
                    <div style="background:var(--surface2); padding:20px; border-radius:12px; text-align:center; border:1px solid var(--border);">
                        <div style="font-size:12px; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Valor Bruto</div>
                        <div style="font-size:28px; font-weight:800; font-family:'JetBrains Mono',monospace; color:var(--success);">R$ {{ number_format($payroll->gross_total / 100, 2, ',', '.') }}</div>
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:30px;">
                    <div style="display:flex; justify-content:space-between; padding:16px 20px; background:var(--surface2); border-radius:12px; border:1px solid var(--border);">
                        <div style="display:flex; flex-direction:column;">
                            <span style="font-weight:600; color:var(--text);">Descontos</span>
                            @if($payroll->deduction_notes)
                            <span style="font-size:12px; color:var(--text-muted);">{{ $payroll->deduction_notes }}</span>
                            @endif
                        </div>
                        <span style="font-family:'JetBrains Mono',monospace; font-weight:600; color:var(--danger);">- R$ {{ number_format($payroll->deductions / 100, 2, ',', '.') }}</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; padding:24px; background:var(--primary); border-radius:16px; color:white; margin-top:10px; box-shadow:0 8px 16px -4px rgba(var(--primary-rgb), 0.25);">
                        <div>
                            <span style="font-weight:600; opacity:0.9; font-size:14px; text-transform:uppercase; letter-spacing:0.05em;">Líquido a Receber</span>
                            <div style="font-family:'JetBrains Mono',monospace; font-size:32px; font-weight:800; margin-top:4px;">R$ {{ number_format($payroll->net_total / 100, 2, ',', '.') }}</div>
                        </div>
                        <div style="display:flex; align-items:center;">
                            <svg style="width:40px; height:40px; opacity:0.3;" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </div>

                <div style="font-size:12px; color:var(--text-muted); text-align:center; padding:10px; border-top:1px solid var(--border);">
                    Este documento é um informativo de valores e não possui validade fiscal automática.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
