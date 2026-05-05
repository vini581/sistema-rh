<div
    x-data="{ open: @entangle('isOpen') }"
    @click.away="open = false"
    class="relative"
    wire:poll.30s
>
    {{-- Bell Button --}}
    <button
        @click="open = !open"
        class="topbar-btn"
        title="Notificações"
        id="notification-bell-btn"
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @if($unreadCount > 0)
            <span class="notif-dot" style="animation: pulse 2s infinite;"></span>
        @endif
    </button>

    {{-- Dropdown Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
        class="absolute right-0 top-full mt-2 w-[380px] z-[99]"
        style="display: none;"
    >
        <div style="
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,.15), 0 1px 4px rgba(0,0,0,.06);
            overflow: hidden;
        ">
            {{-- Header --}}
            <div style="
                padding: 16px 20px;
                border-bottom: 1px solid var(--border);
                display: flex;
                align-items: center;
                justify-content: space-between;
            ">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 15px; font-weight: 700; color: var(--text);">Notificações</span>
                    @if($unreadCount > 0)
                        <span style="
                            background: var(--primary);
                            color: var(--on-primary);
                            font-size: 10px;
                            font-weight: 700;
                            padding: 2px 8px;
                            border-radius: 99px;
                            line-height: 1.5;
                        ">{{ $unreadCount }}</span>
                    @endif
                </div>
                @if($unreadCount > 0)
                    <button
                        wire:click="markAllAsRead"
                        style="
                            font-size: 12px;
                            font-weight: 600;
                            color: var(--primary);
                            background: none;
                            border: none;
                            cursor: pointer;
                            padding: 4px 8px;
                            border-radius: 6px;
                            transition: background .2s;
                        "
                        onmouseover="this.style.background='var(--primary-lt)'"
                        onmouseout="this.style.background='none'"
                    >
                        Marcar todas como lidas
                    </button>
                @endif
            </div>

            {{-- Notification List --}}
            <div style="max-height: 400px; overflow-y: auto;">
                @forelse($notifications as $notification)
                    <a
                        href="{{ $notification->link ?? '#' }}"
                        wire:click="markAsRead({{ $notification->id }})"
                        style="
                            display: flex;
                            gap: 12px;
                            padding: 14px 20px;
                            text-decoration: none;
                            transition: background .15s;
                            border-bottom: 1px solid var(--border);
                            position: relative;
                            {{ !$notification->read_at ? 'background: var(--primary-lt);' : '' }}
                        "
                        onmouseover="this.style.background='{{ !$notification->read_at ? 'var(--primary-lt)' : 'var(--surface2)' }}'"
                        onmouseout="this.style.background='{{ !$notification->read_at ? 'var(--primary-lt)' : 'transparent' }}'"
                    >
                        {{-- Icon --}}
                        <div style="
                            width: 36px;
                            height: 36px;
                            border-radius: 10px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-shrink: 0;
                            background: {{ !$notification->read_at ? 'var(--primary)' : 'var(--surface2)' }};
                            color: {{ !$notification->read_at ? 'var(--on-primary)' : 'var(--text-muted)' }};
                        ">
                            @if(str_contains($notification->title, 'Atestado'))
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @elseif(str_contains($notification->title, 'Férias'))
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @elseif(str_contains($notification->title, 'Holerite') || str_contains($notification->title, 'Folha'))
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div style="flex: 1; min-width: 0;">
                            <div style="
                                font-size: 13px;
                                font-weight: {{ !$notification->read_at ? '600' : '500' }};
                                color: var(--text);
                                margin-bottom: 2px;
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            ">{{ $notification->title }}</div>
                            <div style="
                                font-size: 12px;
                                color: var(--text-muted);
                                line-height: 1.4;
                                display: -webkit-box;
                                -webkit-line-clamp: 2;
                                -webkit-box-orient: vertical;
                                overflow: hidden;
                            ">{{ $notification->message }}</div>
                            <div style="
                                font-size: 11px;
                                color: var(--text-muted);
                                margin-top: 4px;
                                opacity: 0.7;
                            ">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>

                        {{-- Unread dot --}}
                        @if(!$notification->read_at)
                            <div style="
                                width: 8px;
                                height: 8px;
                                border-radius: 50%;
                                background: var(--primary);
                                flex-shrink: 0;
                                align-self: center;
                            "></div>
                        @endif
                    </a>
                @empty
                    <div style="padding: 40px 20px; text-align: center;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:40px; height:40px; margin:0 auto 12px; color: var(--text-muted); opacity:0.3;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <p style="font-size: 13px; font-weight: 600; color: var(--text-muted);">Nenhuma notificação</p>
                        <p style="font-size: 12px; color: var(--text-muted); opacity: 0.7; margin-top: 4px;">Você está em dia!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</div>
