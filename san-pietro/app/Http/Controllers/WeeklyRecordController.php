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
        $weeklyRecords = WeeklyRecord::with('member')->orderBy('year', 'desc')->orderBy('week', 'desc')->paginate(15);
        return view('weekly-records.index', compact('weeklyRecords'));
    }

    public function create()
    {
        $members = Member::where('is_active', true)->orderBy('last_name')->orderBy('first_name')->get();
        return view('weekly-records.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'year' => 'required|integer|min:2020|max:2030',
            'week' => 'required|integer|min:1|max:53',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',

            // Internal Reimmersion
            'kg_micro_internal_reimmersion' => 'nullable|numeric|min:0',
            'price_micro_internal_reimmersion' => 'nullable|numeric|min:0',
            'kg_small_internal_reimmersion' => 'nullable|numeric|min:0',
            'price_small_internal_reimmersion' => 'nullable|numeric|min:0',

            // Resale Reimmersion
            'kg_micro_resale_reimmersion' => 'nullable|numeric|min:0',
            'price_micro_resale_reimmersion' => 'nullable|numeric|min:0',
            'kg_small_resale_reimmersion' => 'nullable|numeric|min:0',
            'price_small_resale_reimmersion' => 'nullable|numeric|min:0',

            // Direct Consumption
            'kg_medium_consumption' => 'nullable|numeric|min:0',
            'price_medium_consumption' => 'nullable|numeric|min:0',
            'kg_large_consumption' => 'nullable|numeric|min:0',
            'price_large_consumption' => 'nullable|numeric|min:0',
            'kg_super_consumption' => 'nullable|numeric|min:0',
            'price_super_consumption' => 'nullable|numeric|min:0',

            // Calculations
            'taxable_amount' => 'nullable|numeric|min:0',
            'advance_paid' => 'nullable|numeric|min:0',
            'withholding_tax' => 'nullable|numeric|min:0',
            'profis' => 'nullable|numeric|min:0',
            'bank_transfer' => 'nullable|numeric|min:0'
        ]);

        // Imposta valori di default per campi null
        foreach ($validated as $key => $value) {
            if (is_null($value) && (str_contains($key, 'kg_') || str_contains($key, 'price_') || in_array($key, ['taxable_amount', 'advance_paid', 'withholding_tax', 'profis', 'bank_transfer']))) {
                $validated[$key] = 0;
            }
        }

        // Imposta automaticamente company_id dall'utente corrente
        $validated['company_id'] = auth()->user()->company_id;

        WeeklyRecord::create($validated);

        return redirect()->route('weekly-records.index')
            ->with('success', 'Record settimanale creato con successo.');
    }

    public function edit(WeeklyRecord $weeklyRecord)
    {
        $members = Member::where('is_active', true)->orderBy('last_name')->orderBy('first_name')->get();
        return view('weekly-records.edit', compact('weeklyRecord', 'members'));
    }

    public function update(Request $request, WeeklyRecord $weeklyRecord)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'year' => 'required|integer|min:2020|max:2030',
            'week' => 'required|integer|min:1|max:53',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',

            // Internal Reimmersion
            'kg_micro_internal_reimmersion' => 'nullable|numeric|min:0',
            'price_micro_internal_reimmersion' => 'nullable|numeric|min:0',
            'kg_small_internal_reimmersion' => 'nullable|numeric|min:0',
            'price_small_internal_reimmersion' => 'nullable|numeric|min:0',

            // Resale Reimmersion
            'kg_micro_resale_reimmersion' => 'nullable|numeric|min:0',
            'price_micro_resale_reimmersion' => 'nullable|numeric|min:0',
            'kg_small_resale_reimmersion' => 'nullable|numeric|min:0',
            'price_small_resale_reimmersion' => 'nullable|numeric|min:0',

            // Direct Consumption
            'kg_medium_consumption' => 'nullable|numeric|min:0',
            'price_medium_consumption' => 'nullable|numeric|min:0',
            'kg_large_consumption' => 'nullable|numeric|min:0',
            'price_large_consumption' => 'nullable|numeric|min:0',
            'kg_super_consumption' => 'nullable|numeric|min:0',
            'price_super_consumption' => 'nullable|numeric|min:0',

            // Calculations
            'taxable_amount' => 'nullable|numeric|min:0',
            'advance_paid' => 'nullable|numeric|min:0',
            'withholding_tax' => 'nullable|numeric|min:0',
            'profis' => 'nullable|numeric|min:0',
            'bank_transfer' => 'nullable|numeric|min:0'
        ]);

        // Imposta valori di default per campi null
        foreach ($validated as $key => $value) {
            if (is_null($value) && (str_contains($key, 'kg_') || str_contains($key, 'price_') || in_array($key, ['taxable_amount', 'advance_paid', 'withholding_tax', 'profis', 'bank_transfer']))) {
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