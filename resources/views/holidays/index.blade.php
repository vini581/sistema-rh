<x-app-layout>
    <x-slot name="header">Feriados</x-slot>
    <div style="max-width:680px;">
        <div class="card fade-up" style="margin-bottom:18px;">
            <div class="card-header"><span class="card-title">Novo Feriado</span></div>
            <div class="card-body">
                <form method="POST" action="{{ route('holidays.store') }}" style="display:flex;gap:12px;align-items:flex-end;">
                    @csrf
                    <div class="form-group" style="margin-bottom:0;flex:1;">
                        <label class="form-label">Data</label>
                        <input type="date" name="date" class="form-input" required value="{{ old('date') }}">
                    </div>
                    <div class="form-group" style="margin-bottom:0;flex:2;">
                        <label class="form-label">Nome do Feriado</label>
                        <input type="text" name="name" class="form-input" required placeholder="Ex: Dia da Independência" value="{{ old('name') }}">
                    </div>
                    <button type="submit" class="btn btn-primary" style="height:42px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Adicionar
                    </button>
                </form>
                @if($errors->any())
                <div class="alert alert-danger" style="margin-top:12px;">
                    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                </div>
                @endif
            </div>
        </div>
        <div class="card fade-up" style="animation-delay:.1s;opacity:0;">
            <div class="card-header"><span class="card-title">Feriados Cadastrados</span></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Data</th><th>Nome</th><th style="text-align:right;">Ação</th></tr></thead>
                    <tbody>
                        @forelse($holidays as $h)
                        <tr>
                            <td style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;">{{ $h->date->format('d/m/Y') }}</td>
                            <td>{{ $h->name }}</td>
                            <td style="text-align:right;">
                                <form method="POST" action="{{ route('holidays.destroy',$h) }}" style="margin:0;" onsubmit="return confirm('Remover feriado?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;padding:32px;color:var(--text-muted);">Nenhum feriado cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($holidays->hasPages())
            <div style="padding:16px 22px;border-top:1px solid var(--border);text-align:center;">{{ $holidays->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
