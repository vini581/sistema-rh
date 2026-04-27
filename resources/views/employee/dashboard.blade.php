<x-app-layout>
    <x-slot name="header">Meu Painel Financeiro</x-slot>

    <div class="fade-up">
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:20px; margin-bottom:30px;">

            <div class="card" style="background: linear-gradient(135deg, var(--primary), #4f46e5); color: white; border:none; position:relative; overflow:hidden;">
                <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
                <div class="card-body" style="padding:24px;">
                    <div style="font-size:13px; font-weight:600; opacity:0.8; text-transform:uppercase; margin-bottom:8px;">Salário Líquido Estimado</div>
                    <div style="font-size:32px; font-weight:800; font-family:'JetBrains Mono',monospace;">R$ {{ number_format($payroll->net_total / 100, 2, ',', '.') }}</div>
                    <div style="margin-top:15px; font-size:11px; display:flex; align-items:center; gap:6px;">
                        <span style="background:rgba(255,255,255,0.2); padding:2px 8px; border-radius:10px;">{{ \Carbon\Carbon::now()->translatedFormat('F/Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="card" style="border-left:4px solid var(--warning);">
                <div class="card-body" style="padding:24px;">
                    <div style="font-size:13px; font-weight:600; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Salário Bruto Estimado</div>
                    <div style="font-size:32px; font-weight:800; font-family:'JetBrains Mono',monospace; color:var(--warning);">R$ {{ number_format($payroll->gross_total / 100, 2, ',', '.') }}</div>
                    <div style="margin-top:15px; font-size:13px; color:var(--text-muted);">
                        Deduções Manuais: <strong style="color:var(--text-main);">R$ {{ number_format($payroll->deductions / 100, 2, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div class="card" style="border-left:4px solid var(--success);">
                <div class="card-body" style="padding:24px;">
                    <div style="font-size:13px; font-weight:600; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Frequência no Mês</div>
                    <div style="font-size:32px; font-weight:800; color:var(--success);">{{ $daysWorked }} <span style="font-size:14px; color:var(--text-muted);">dias</span></div>
                    <div style="margin-top:15px; font-size:13px; color:var(--text-muted);">
                        Atestados: <strong style="color:var(--danger);">{{ $certificateDays }} dias</strong>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px;">

            <div class="card">
                <div class="card-header"><span class="card-title">Resumo da Folha (Estimativa)</span></div>
                <div class="card-body">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div style="background:var(--surface2); padding:20px; border-radius:16px; border:1px solid var(--border);">
                            <div style="color:var(--text-muted); font-size:12px; font-weight:600; text-transform:uppercase; margin-bottom:10px;">Bruto</div>
                            <div style="font-size:24px; font-weight:700; font-family:'JetBrains Mono',monospace;">R$ {{ number_format($payroll->gross_total / 100, 2, ',', '.') }}</div>
                            <div style="font-size:11px; color:var(--text-muted); margin-top:5px;">Sem descontos</div>
                        </div>
                        <div style="background:var(--surface2); padding:20px; border-radius:16px; border:1px solid var(--border);">
                            <div style="color:var(--text-muted); font-size:12px; font-weight:600; text-transform:uppercase; margin-bottom:10px;">Líquido</div>
                            <div style="font-size:24px; font-weight:700; font-family:'JetBrains Mono',monospace; color:var(--primary);">R$ {{ number_format($payroll->net_total / 100, 2, ',', '.') }}</div>
                            <div style="font-size:11px; color:var(--text-muted); margin-top:5px;">A receber</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Informações Gerais</span></div>
                <div class="card-body" style="display:flex; flex-direction:column; gap:15px;">
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:10px;">
                        <span style="color:var(--text-muted); font-size:13px;">Valor da Hora</span>
                        <span style="font-weight:600;">R$ {{ number_format($employee->getConfig('hourly_rate') / 100, 2, ',', '.') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:10px;">
                        <span style="color:var(--text-muted); font-size:13px;">Saldo de Férias</span>
                        <span style="font-weight:600; color:var(--primary);">{{ $vacationBalance }} dias</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:10px;">
                        <span style="color:var(--text-muted); font-size:13px;">Carga Horária</span>
                        <span style="font-weight:600;">{{ $employee->getConfig('monthly_hours') }}h / mês</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
