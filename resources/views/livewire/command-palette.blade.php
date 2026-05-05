<div 
    x-data="{ open: @entangle('isOpen') }"
    @keydown.window.prevent.ctrl.k="open = true"
    @keydown.window.prevent.cmd.k="open = true"
    @keydown.escape.window="open = false"
    x-show="open"
    class="fixed inset-0 z-[100] overflow-y-auto pt-24"
    style="display: none;"
>
    <!-- Background overlay -->
    <div 
        x-show="open" 
        x-transition.opacity 
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
        @click="open = false"
    ></div>

    <!-- Modal panel -->
    <div 
        x-show="open" 
        x-transition:enter="ease-out duration-200" 
        x-transition:enter-start="opacity-0 scale-95" 
        x-transition:enter-end="opacity-100 scale-100" 
        x-transition:leave="ease-in duration-150" 
        x-transition:leave-start="opacity-100 scale-100" 
        x-transition:leave-end="opacity-0 scale-95" 
        class="relative mx-auto max-w-xl transform divide-y divide-gray-100 overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 transition-all dark:bg-slate-800 dark:divide-slate-700 dark:ring-white/10"
    >
        <div class="relative">
            <svg class="pointer-events-none absolute left-4 top-3.5 h-5 w-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input 
                wire:model.live="search"
                type="text" 
                class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm dark:text-white" 
                placeholder="Buscar funcionários, holerites, atestados... (Ctrl + K)"
                autofocus
            >
        </div>

        @if(strlen($search) > 0 && count($results) > 0)
            <ul class="max-h-80 scroll-py-2 overflow-y-auto p-2 text-sm text-gray-800 dark:text-slate-300">
                @php $lastType = null; @endphp
                @foreach($results as $result)
                    @if($result['type'] !== $lastType)
                        <li class="px-3 pt-3 pb-1">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">{{ $result['type'] }}</span>
                        </li>
                        @php $lastType = $result['type']; @endphp
                    @endif
                    <li>
                        <a href="{{ $result['url'] }}" class="flex cursor-default select-none items-center gap-3 rounded-lg px-3 py-2.5 hover:bg-brand-50 hover:text-brand-700 dark:hover:bg-brand-500/10 dark:hover:text-brand-400 transition-colors" style="text-decoration:none;">
                            {{-- Icon --}}
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: var(--primary-lt); color: var(--primary);">
                                @switch($result['icon'] ?? 'default')
                                    @case('user')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        @break
                                    @case('users')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        @break
                                    @case('money')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @break
                                    @case('document')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @break
                                    @case('calendar')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @break
                                    @case('clock')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @break
                                    @case('settings')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        @break
                                    @case('chart')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @break
                                    @case('home')
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        @break
                                    @default
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endswitch
                            </div>

                            {{-- Content --}}
                            <div class="flex-auto min-w-0">
                                <div class="font-medium text-sm truncate">{{ $result['title'] }}</div>
                                @if(isset($result['subtitle']))
                                    <div class="text-xs text-gray-500 dark:text-slate-400 truncate">{{ $result['subtitle'] }}</div>
                                @endif
                            </div>

                            {{-- Arrow --}}
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 flex-shrink-0 opacity-40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </li>
                @endforeach
            </ul>
        @elseif(strlen($search) > 0)
            <div class="px-6 py-14 text-center text-sm sm:px-14">
                <svg class="mx-auto h-6 w-6 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="mt-4 font-semibold text-gray-900 dark:text-white">Nenhum resultado encontrado</p>
                <p class="mt-2 text-gray-500 dark:text-slate-400">Não encontramos nada para "{{ $search }}". Tente outro termo.</p>
            </div>
        @else
            <div class="px-4 py-5 text-center">
                <p class="text-xs text-gray-500 dark:text-slate-400">Busca global — Funcionários, holerites, atestados e ações rápidas.</p>
                <div class="mt-3 flex items-center justify-center gap-3 text-[10px] text-gray-400 dark:text-slate-500">
                    <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-slate-700 font-mono text-[10px]">↑↓</kbd> navegar</span>
                    <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-slate-700 font-mono text-[10px]">↵</kbd> abrir</span>
                    <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-slate-700 font-mono text-[10px]">Esc</kbd> fechar</span>
                </div>
            </div>
        @endif
    </div>
</div>
