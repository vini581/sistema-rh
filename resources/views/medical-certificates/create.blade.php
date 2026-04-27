<x-app-layout>
    <x-slot name="header">Cadastrar Atestado</x-slot>
    <div style="max-width:680px;">
        <div class="card fade-up">
            <div class="card-header">
                <span class="card-title">Novo Atestado Médico</span>
                <a href="{{ route('certificates.index') }}" class="btn btn-secondary btn-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Voltar
                </a>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
                @endif
                <form method="POST" action="{{ route('certificates.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Funcionário</label>
                            <select name="employee_id" class="form-input" required>
                                <option value="">Selecione...</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('employee_id')==$emp->id?'selected':'' }}>{{ $emp->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Data Início</label>
                            <input type="date" name="start_date" class="form-input" value="{{ old('start_date') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Data Fim</label>
                            <input type="date" name="end_date" class="form-input" value="{{ old('end_date') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo</label>
                            <select name="type" class="form-input" required>
                                <option value="medical" {{ old('type')==='medical'?'selected':'' }}>Médico</option>
                                <option value="dental" {{ old('type')==='dental'?'selected':'' }}>Odontológico</option>
                                <option value="attendance" {{ old('type')==='attendance'?'selected':'' }}>Comparecimento</option>
                                <option value="work_accident" {{ old('type')==='work_accident'?'selected':'' }}>Acidente de Trabalho</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Arquivo (PDF/Imagem)</label>
                            <input type="file" name="file" class="form-input" accept=".pdf,.jpg,.jpeg,.png" style="padding:8px;">
                        </div>
                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Observações</label>
                            <textarea name="observations" class="form-input" rows="3" style="resize:none;" placeholder="Observações opcionais...">{{ old('observations') }}</textarea>
                        </div>
                    </div>
                    <div style="display:flex;gap:12px;margin-top:8px;">
                        <button type="submit" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Registrar atestado
                        </button>
                        <a href="{{ route('certificates.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
