<x-app-layout>
    <x-slot name="header">Vigências — {{ $employee->user->name }}</x-slot>
    <div style="max-width:900px;">

        {{-- Cabeçalho do funcionário --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:44px;height:44px;border-radius:12px;background:var(--primary-lt);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:18px;color:var(--primary);">{{ substr($employee->user->name,0,1) }}</div>
                <div>
                    <div style="font-weight:600;">{{ $employee->user->name }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $employee->position }}</div>
                </div>
            </div>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Voltar
            </a>
        </div>

        <div class="alert alert-success" style="background:#e0f7f7;border-color:#1BBFBF;color:#0e9393;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Se não houver vigência específica, o sistema usa a configuração global do RH.
        </div>

        {{-- Vigências existentes --}}
        @if($configs->count())
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header"><span class="card-title">Vigências deste Funcionário</span></div>
            <div class="card-body" style="padding:0;">
                <table>
                    <thead>
                        <tr>
                            <th>Vigência</th>
                            <th>Valor Hora</th>
                            <th>Carga</th>
                            <th>Pagamento</th>
                            <th>HE Útil</th>
                            <th style="text-align:right;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($configs as $c)
                        <tr>
                            <td style="font-weight:600;">{{ $c->vigencia_inicio->format('d/m/Y') }}</td>
                            <td>R$ {{ number_format($c->default_hourly_rate / 100, 2, ',', '.') }}</td>
                            <td>{{ $c->monthly_hours }}h</td>
                            <td>{{ $c->payment_type === 'monthly' ? 'Mensal' : 'Quinzenal' }}</td>
                            <td>{{ $c->overtime_weekday_pct }}%</td>
                            <td style="text-align:right;">
                                <form method="POST" action="{{ route('employee-config.destroy', [$employee, $c]) }}" onsubmit="return confirm('Excluir esta vigência?');" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Form nova vigência --}}
        <div class="card fade-up">
            <div class="card-header"><span class="card-title">Nova Vigência para {{ $employee->user->name }}</span></div>
            <div class="card-body">
                <form method="POST" action="{{ route('employee-config.update', $employee) }}">
                    @csrf @method('PUT')
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Início da Vigência</label>
                            <input type="date" name="vigencia_inicio" class="form-input" value="{{ old('vigencia_inicio', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Valor da Hora (R$)</label>
                            <input type="number" name="default_hourly_rate" class="form-input" step="0.01" min="0" value="{{ old('default_hourly_rate', $current->default_hourly_rate ? number_format($current->default_hourly_rate / 100, 2, '.', '') : '') }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Carga Horária Mensal (h)</label>
                            <input type="number" name="monthly_hours" class="form-input" min="1" max="744" value="{{ old('monthly_hours', $current->monthly_hours) }}" required>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Tipo de Pagamento</label>
                            <select name="payment_type" class="form-input">
                                <option value="monthly" {{ $current->payment_type === 'monthly' ? 'selected' : '' }}>Mensal</option>
                                <option value="biweekly" {{ $current->payment_type === 'biweekly' ? 'selected' : '' }}>Quinzenal</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% Adic. Noturno</label>
                            <input type="number" name="night_shift_pct" class="form-input" min="0" max="100" value="{{ old('night_shift_pct', $current->night_shift_pct) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Mín. Minutos p/ HE</label>
                            <input type="number" name="overtime_min_minutes" class="form-input" min="0" max="120" value="{{ old('overtime_min_minutes', $current->overtime_min_minutes) }}" required>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% HE Dias Úteis</label>
                            <input type="number" name="overtime_weekday_pct" class="form-input" min="0" max="200" value="{{ old('overtime_weekday_pct', $current->overtime_weekday_pct) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% HE Sábado</label>
                            <input type="number" name="overtime_saturday_pct" class="form-input" min="0" max="200" value="{{ old('overtime_saturday_pct', $current->overtime_saturday_pct) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% HE Domingo</label>
                            <input type="number" name="overtime_sunday_pct" class="form-input" min="0" max="200" value="{{ old('overtime_sunday_pct', $current->overtime_sunday_pct) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% HE Feriado</label>
                            <input type="number" name="overtime_holiday_pct" class="form-input" min="0" max="200" value="{{ old('overtime_holiday_pct', $current->overtime_holiday_pct) }}" required>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr; gap:16px; margin-bottom:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Adicional Férias (%)</label>
                            <input type="number" name="vacation_bonus_pct" class="form-input" step="0.01" min="0" max="100" value="{{ old('vacation_bonus_pct', $current->vacation_bonus_pct ? number_format($current->vacation_bonus_pct, 2, '.', '') : '33.33') }}" required style="max-width:200px;">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Salvar vigência
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
