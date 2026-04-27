<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ajusta o fuso horário da aplicação com base no timezone
 * do navegador do usuário, enviado via cookie "client_timezone".
 *
 * Isso garante que now(), today() e todas as funções Carbon
 * usem o relógio da máquina do usuário.
 */
class SetClientTimezone
{
    /**
     * Timezones válidos do PHP para evitar injeção de valores arbitrários.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tz = $request->cookie('client_timezone');

        if ($tz && in_array($tz, timezone_identifiers_list(), true)) {
            config(['app.timezone' => $tz]);
            date_default_timezone_set($tz);
        }

        return $next($request);
    }
}
