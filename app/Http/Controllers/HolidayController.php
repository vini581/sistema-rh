<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date', 'desc')->paginate(20);
        return view('holidays.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'name' => 'required|string|max:255',
        ]);

        try {
            Holiday::create($validated);
        } catch (\Illuminate\Database\QueryException $e) {
            // Se der erro de duplicate entry (race condition), apenas ignora pois já existe
            if ($e->errorInfo[1] !== 1062 && $e->errorInfo[1] !== 19) {
                throw $e;
            }
            return redirect()->route('holidays.index')
                ->with('error', 'Esse feriado já existe no calendário.');
        }

        return redirect()->route('holidays.index')
            ->with('success', 'Feriado adicionado ao calendário.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('holidays.index')
            ->with('success', 'Feriado apagado do calendário.');
    }
}
