<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WeeklyRecord;
use App\Models\Member;
use Illuminate\Http\Request;

class WeeklyRecordController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(WeeklyRecord::class, 'weekly_record');
    }
    public function index()
    {
        $weeklyRecords = WeeklyRecord::with('member')->orderBy('anno', 'desc')->orderBy('settimana', 'desc')->paginate(15);
        return view('weekly-records.index', compact('weeklyRecords'));
    }

    public function create()
    {
        $members = Member::where('active', true)->orderBy('last_name')->orderBy('first_name')->get();
        return view('weekly-records.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'year' => 'required|integer|min:2020|max:2030',
            'week' => 'required|integer|min:1|max:53',

            // Reimmersione Interna
            'kg_micro_reimmersione_interna' => 'nullable|numeric|min:0',
            'prezzo_micro_reimmersione_interna' => 'nullable|numeric|min:0',
            'kg_piccola_reimmersione_interna' => 'nullable|numeric|min:0',
            'prezzo_piccola_reimmersione_interna' => 'nullable|numeric|min:0',

            // Consumo Diretto
            'kg_micro_consumo_diretto' => 'nullable|numeric|min:0',
            'prezzo_micro_consumo_diretto' => 'nullable|numeric|min:0',
            'kg_piccola_consumo_diretto' => 'nullable|numeric|min:0',
            'prezzo_piccola_consumo_diretto' => 'nullable|numeric|min:0',
            'kg_media_consumo_diretto' => 'nullable|numeric|min:0',
            'prezzo_media_consumo_diretto' => 'nullable|numeric|min:0',
            'kg_grande_consumo_diretto' => 'nullable|numeric|min:0',
            'prezzo_grande_consumo_diretto' => 'nullable|numeric|min:0',
            'kg_super_consumo_diretto' => 'nullable|numeric|min:0',
            'prezzo_super_consumo_diretto' => 'nullable|numeric|min:0',
        ]);

        // Imposta valori di default per campi null
        foreach ($validated as $key => $value) {
            if (is_null($value) && (str_contains($key, 'kg_') || str_contains($key, 'prezzo_'))) {
                $validated[$key] = 0;
            }
        }

        WeeklyRecord::create($validated);

        return redirect()->route('weekly-records.index')
            ->with('success', 'Record settimanale creato con successo.');
    }

    public function edit(WeeklyRecord $weeklyRecord)
    {
        $members = Member::where('active', true)->orderBy('last_name')->orderBy('first_name')->get();
        return view('weekly-records.edit', compact('weeklyRecord', 'members'));
    }

    public function update(Request $request, WeeklyRecord $weeklyRecord)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'year' => 'required|integer|min:2020|max:2030',
            'week' => 'required|integer|min:1|max:53',

            // Reimmersione Interna
            'kg_micro_reimmersione_interna' => 'nullable|numeric|min:0',
            'prezzo_micro_reimmersione_interna' => 'nullable|numeric|min:0',
            'kg_piccola_reimmersione_interna' => 'nullable|numeric|min:0',
            'prezzo_piccola_reimmersione_interna' => 'nullable|numeric|min:0',

            // Consumo Diretto
            'kg_micro_consumo_diretto' => 'nullable|numeric|min:0',
            'prezzo_micro_consumo_diretto' => 'nullable|numeric|min:0',
            'kg_piccola_consumo_diretto' => 'nullable|numeric|min:0',
            'prezzo_piccola_consumo_diretto' => 'nullable|numeric|min:0',
            'kg_media_consumo_diretto' => 'nullable|numeric|min:0',
            'prezzo_media_consumo_diretto' => 'nullable|numeric|min:0',
            'kg_grande_consumo_diretto' => 'nullable|numeric|min:0',
            'prezzo_grande_consumo_diretto' => 'nullable|numeric|min:0',
            'kg_super_consumo_diretto' => 'nullable|numeric|min:0',
            'prezzo_super_consumo_diretto' => 'nullable|numeric|min:0',
        ]);

        // Imposta valori di default per campi null
        foreach ($validated as $key => $value) {
            if (is_null($value) && (str_contains($key, 'kg_') || str_contains($key, 'prezzo_'))) {
                $validated[$key] = 0;
            }
        }

        $weeklyRecord->update($validated);

        return redirect()->route('weekly-records.index')
            ->with('success', 'Record settimanale aggiornato con successo.');
    }

    public function destroy(WeeklyRecord $weeklyRecord)
    {
        $weeklyRecord->delete();

        return redirect()->route('weekly-records.index')
            ->with('success', 'Record settimanale eliminato con successo.');
    }

    public function show(WeeklyRecord $weeklyRecord)
    {
        return view('weekly-records.show', compact('weeklyRecord'));
    }
}