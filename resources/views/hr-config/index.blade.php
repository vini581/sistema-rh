<x-app-layout>
    <x-slot name="header">Configurações de RH</x-slot>

    <div style="max-width:960px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="font-size:18px; font-weight:700; color:var(--text); margin:0;">Histórico de Vigências</h2>
            <a href="{{ route('hr-config.create') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nova Vigência
            </a>
        </div>

        <div class="card" style="margin-bottom:20px;">
            <div class="card-body">
                <form action="#" method="GET" onsubmit="event.preventDefault(); let v = document.getElementById('emp_select').value; if(v) window.location.href='/employees/' + v + '/config';">
                    <label class="form-label">Configuração Específica por Funcionário</label>
                    <div style="display:flex; gap:12px;">
                        <select id="emp_select" class="form-input" style="flex:1;">
                            @foreach($employees as $e)
                                <option value="{{ $e->id }}">{{ $e->user->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-secondary">Configurar Funcionário</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="padding:0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vigência Início</th>
                            <th>Valor Hora Padrão</th>
                            <th>Carga Mensal</th>
                            <th>Tipo Pagamento</th>
                            <th>HE (Útil)</th>
                            <th style="text-align:right;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($configs as $c)
                        <tr>
                            <td style="font-weight:600;">{{ $c->vigencia_inicio ? $c->vigencia_inicio->format('d/m/Y') : '-' }}</td>
                            <td>R$ {{ number_format($c->default_hourly_rate / 100, 2, ',', '.') }}</td>
                            <td>{{ $c->monthly_hours }}h</td>
                            <td>{{ $c->payment_type === 'monthly' ? 'Mensal' : 'Quinzenal' }}</td>
                            <td>{{ $c->overtime_weekday_pct }}%</td>
                            <td style="text-align:right;">
                                <div style="display:flex; gap:6px; justify-content:flex-end;">
                                    <a href="{{ route('hr-config.edit', $c->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                                    <form method="POST" action="{{ route('hr-config.destroy', $c->id) }}" onsubmit="return confirm('Tem certeza que deseja excluir esta vigência?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
