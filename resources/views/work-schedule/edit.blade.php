<x-app-layout>
    <x-slot name="header">Configurar Jornada — {{ $employee->user->name }}</x-slot>

    <div style="max-width:640px;">
        <div class="card fade-up">
            <div class="card-header">
                <span class="card-title">Horários da Jornada</span>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Voltar
                </a>
            </div>
            <div class="card-body">

                @if(session('success'))
                <div class="alert alert-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
                @endif

                {{-- Info do funcionário --}}
                <div style="display:flex; align-items:center; gap:14px; padding:14px 16px; background:var(--surface2); border:1px solid var(--border); border-radius:12px; margin-bottom:24px;">
                    <div style="width:44px;height:44px;border-radius:12px;background:var(--primary-lt);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:18px;color:var(--primary);">
                        {{ substr($employee->user->name, 0, 1) }}
                    </div>
                    <div>
                        <div style="font-weight:600; font-size:14px;">{{ $employee->user->name }}</div>
                        <div style="font-size:12px; color:var(--text-muted);">{{ $employee->position }}</div>
                    </div>
                    @if($employee->workSchedule)
                        <span class="badge badge-success" style="margin-left:auto;"><span class="badge-dot" style="background:#10b981;"></span>Jornada configurada</span>
                    @else
                        <span class="badge badge-warning" style="margin-left:auto;"><span class="badge-dot" style="background:#f59e0b;"></span>Sem jornada</span>
                    @endif
                </div>

                <form method="POST" action="{{ route('schedule.update', $employee) }}">
                    @csrf
                    @method('PUT')

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div class="form-group">
                            <label class="form-label">Horário de Entrada</label>
                            <input type="time" name="clock_in_time" class="form-input"
                                   value="{{ old('clock_in_time', $schedule->clock_in_time) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Saída para Almoço</label>
                            <input type="time" name="lunch_out_time" class="form-input"
                                   value="{{ old('lunch_out_time', $schedule->lunch_out_time) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Volta do Almoço</label>
                            <input type="time" name="lunch_in_time" class="form-input"
                                   value="{{ old('lunch_in_time', $schedule->lunch_in_time) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Horário de Saída</label>
                            <input type="time" name="clock_out_time" class="form-input"
                                   value="{{ old('clock_out_time', $schedule->clock_out_time) }}" required>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:24px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Tolerância de Atraso (minutos)</label>
                            <input type="number" name="tolerance_minutes" class="form-input"
                                   value="{{ old('tolerance_minutes', $schedule->tolerance_minutes) }}"
                                   min="0" max="60" required>
                            <div class="form-hint">Minutos de tolerância sem descontar</div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Carga Horária Diária (horas)</label>
                            <input type="number" name="work_hours_per_day" class="form-input"
                                   value="{{ old('work_hours_per_day', $schedule->work_hours_per_day) }}"
                                   min="1" max="12" required>
                            <div class="form-hint">Base para cálculo do banco de horas</div>
                        </div>
                    </div>

                    <div style="display:flex; gap:12px;">
                        <button type="submit" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Confirmar nova jornada
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>