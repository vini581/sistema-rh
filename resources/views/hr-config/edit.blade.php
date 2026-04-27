<x-app-layout>
    <x-slot name="header">Configurações de RH</x-slot>

    <div style="max-width:860px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="font-size:18px; font-weight:700; color:var(--text); margin:0;">{{ $config->id ? 'Editar Vigência' : 'Nova Vigência' }}</h2>
            <a href="{{ route('hr-config.index') }}" class="btn btn-secondary btn-sm">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Voltar
            </a>
        </div>

        <form method="POST" action="{{ $config->id ? route('hr-config.update', $config->id) : route('hr-config.store') }}">
            @csrf
            @if($config->id)
                @method('PUT')
            @endif

            {{-- Vigência e Valores --}}
            <div class="card fade-up" style="margin-bottom:18px;">
                <div class="card-header"><span class="card-title">Vigência e Valores</span></div>
                <div class="card-body">
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:16px;">
                        <div class="form-group">
                            <label class="form-label">Início da Vigência</label>
                            <input type="date" name="vigencia_inicio" class="form-input" value="{{ old('vigencia_inicio', $config->vigencia_inicio ? $config->vigencia_inicio->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Valor Hora Padrão (R$)</label>
                            <input type="number" name="default_hourly_rate" class="form-input" step="0.01" min="0" value="{{ old('default_hourly_rate', $config->id ? number_format($config->default_hourly_rate / 100, 2, '.', '') : '') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Carga Horária Mensal (h)</label>
                            <input type="number" name="monthly_hours" class="form-input" min="1" max="744" value="{{ old('monthly_hours', $config->monthly_hours) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo de Pagamento</label>
                            <select name="payment_type" class="form-input">
                                <option value="monthly" {{ $config->payment_type === 'monthly' ? 'selected' : '' }}>Mensal</option>
                                <option value="biweekly" {{ $config->payment_type === 'biweekly' ? 'selected' : '' }}>Quinzenal</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Horas Extras --}}
            <div class="card fade-up fade-up-1" style="margin-bottom:18px;">
                <div class="card-header"><span class="card-title">Horas Extras</span></div>
                <div class="card-body">
                    <div style="display:grid; grid-template-columns:repeat(5, 1fr); gap:16px; margin-bottom:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% Dias Úteis</label>
                            <input type="number" name="overtime_weekday_pct" class="form-input" min="0" max="200" value="{{ old('overtime_weekday_pct', $config->overtime_weekday_pct) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% Sábado</label>
                            <input type="number" name="overtime_saturday_pct" class="form-input" min="0" max="200" value="{{ old('overtime_saturday_pct', $config->overtime_saturday_pct) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% Domingo</label>
                            <input type="number" name="overtime_sunday_pct" class="form-input" min="0" max="200" value="{{ old('overtime_sunday_pct', $config->overtime_sunday_pct) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% Feriado</label>
                            <input type="number" name="overtime_holiday_pct" class="form-input" min="0" max="200" value="{{ old('overtime_holiday_pct', $config->overtime_holiday_pct) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% Adic. Noturno</label>
                            <input type="number" name="night_shift_pct" class="form-input" min="0" max="100" value="{{ old('night_shift_pct', $config->night_shift_pct) }}" required>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Mínimo (min.) p/ HE</label>
                            <input type="number" name="overtime_min_minutes" class="form-input" min="0" max="120" value="{{ old('overtime_min_minutes', $config->overtime_min_minutes) }}" required>
                            <div class="form-hint">Min. de minutos extras para considerar HE</div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Sábado como HE?</label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding-top:10px;">
                                <input type="hidden" name="saturday_is_overtime" value="0">
                                <input type="checkbox" name="saturday_is_overtime" value="1" {{ $config->saturday_is_overtime ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                                <span style="font-size:13px;">Considerar sábado como hora extra</span>
                            </label>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Banco de Horas</label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding-top:10px;">
                                <input type="hidden" name="use_hour_bank" value="0">
                                <input type="checkbox" name="use_hour_bank" value="1" {{ $config->use_hour_bank ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                                <span style="font-size:13px;">Utilizar banco de horas</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quinzenas --}}
            <div class="card fade-up fade-up-2" style="margin-bottom:18px;">
                <div class="card-header"><span class="card-title">Quinzenas</span></div>
                <div class="card-body">
                    @if($errors->has('biweekly_first_pct'))
                    <div class="alert alert-danger" style="margin-bottom:16px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $errors->first('biweekly_first_pct') }}
                    </div>
                    @endif
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">1ª Quinzena: Dia Início</label>
                            <input type="number" name="biweekly_first_start" class="form-input" min="1" max="31" value="{{ old('biweekly_first_start', $config->biweekly_first_start) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">1ª Quinzena: Dia Fim</label>
                            <input type="number" name="biweekly_first_end" class="form-input" min="1" max="31" value="{{ old('biweekly_first_end', $config->biweekly_first_end) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% Pago na 1ª</label>
                            <input type="number" name="biweekly_first_pct" class="form-input" min="0" max="100" value="{{ old('biweekly_first_pct', $config->biweekly_first_pct) }}" required>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">2ª Quinzena: Dia Início</label>
                            <input type="number" name="biweekly_second_start" class="form-input" min="1" max="31" value="{{ old('biweekly_second_start', $config->biweekly_second_start) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">2ª Quinzena: Dia Fim</label>
                            <input type="number" name="biweekly_second_end" class="form-input" min="1" max="31" value="{{ old('biweekly_second_end', $config->biweekly_second_end) }}" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">% Pago na 2ª</label>
                            <input type="number" name="biweekly_second_pct" class="form-input" min="0" max="100" value="{{ old('biweekly_second_pct', $config->biweekly_second_pct) }}" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Férias --}}
            <div class="card fade-up fade-up-3" style="margin-bottom:18px;">
                <div class="card-header"><span class="card-title">Férias</span></div>
                <div class="card-body">
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Adicional de Férias (%)</label>
                            <input type="number" name="vacation_bonus_pct" class="form-input" step="0.01" min="0" max="100" value="{{ old('vacation_bonus_pct', $config->id ? number_format($config->vacation_bonus_pct, 2, '.', '') : '') }}" required>
                            <div class="form-hint">1/3 constitucional = 33.33%</div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Média de HE nas Férias?</label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding-top:10px;">
                                <input type="hidden" name="vacation_include_overtime_avg" value="0">
                                <input type="checkbox" name="vacation_include_overtime_avg" value="1" {{ $config->vacation_include_overtime_avg ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                                <span style="font-size:13px;">Incluir média de horas extras</span>
                            </label>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Parcelamento de Férias?</label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding-top:10px;">
                                <input type="hidden" name="vacation_allow_split" value="0">
                                <input type="checkbox" name="vacation_allow_split" value="1" {{ $config->vacation_allow_split ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                                <span style="font-size:13px;">Permitir parcelamento</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Atestados Médicos --}}
            <div class="card fade-up fade-up-4" style="margin-bottom:24px;">
                <div class="card-header"><span class="card-title">Atestados Médicos</span></div>
                <div class="card-body">
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Abona Faltas?</label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding-top:10px;">
                                <input type="hidden" name="certificate_excuses_absence" value="0">
                                <input type="checkbox" name="certificate_excuses_absence" value="1" {{ $config->certificate_excuses_absence ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                                <span style="font-size:13px;">Atestado abona faltas</span>
                            </label>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Conta como Trabalhado?</label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding-top:10px;">
                                <input type="hidden" name="certificate_counts_as_worked" value="0">
                                <input type="checkbox" name="certificate_counts_as_worked" value="1" {{ $config->certificate_counts_as_worked ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                                <span style="font-size:13px;">Considerar como dia trabalhado</span>
                            </label>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Dias Pagos pela Empresa</label>
                            <input type="number" name="certificate_company_paid_days" class="form-input" min="0" value="{{ old('certificate_company_paid_days', $config->certificate_company_paid_days) }}" required>
                            <div class="form-hint">Após limite → INSS</div>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Desconto após X dias</label>
                            <input type="number" name="certificate_discount_after_days" class="form-input" min="0" value="{{ old('certificate_discount_after_days', $config->certificate_discount_after_days) }}" required>
                            <div class="form-hint">0 = nunca descontar</div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Descontar VT?</label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding-top:10px;">
                                <input type="hidden" name="certificate_discount_transport" value="0">
                                <input type="checkbox" name="certificate_discount_transport" value="1" {{ $config->certificate_discount_transport ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                                <span style="font-size:13px;">Desconta vale transporte</span>
                            </label>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Descontar VA?</label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding-top:10px;">
                                <input type="hidden" name="certificate_discount_food" value="0">
                                <input type="checkbox" name="certificate_discount_food" value="1" {{ $config->certificate_discount_food ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                                <span style="font-size:13px;">Desconta vale alimentação</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="padding:14px 28px; font-size:15px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Aplicar essas regras
            </button>
        </form>
    </div>
</x-app-layout>
