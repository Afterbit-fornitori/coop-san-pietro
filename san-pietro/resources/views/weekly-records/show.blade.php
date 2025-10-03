@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Registro Settimanale - Settimana {{ $weeklyRecord->week }}/{{ $weeklyRecord->year }}</h2>
            <div class="space-x-2">
                <a href="{{ route('weekly-records.edit', $weeklyRecord) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Modifica
                </a>
                <a href="{{ route('weekly-records.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Torna alla Lista
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informazioni Generali -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-600 pb-2">Informazioni Generali</h3>

                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Socio:</span>
                        <p class="text-base font-semibold">{{ $weeklyRecord->member->full_name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">RPM: {{ $weeklyRecord->member->rpm_registration ?? 'N/A' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Anno:</span>
                            <p class="text-base">{{ $weeklyRecord->year }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Settimana:</span>
                            <p class="text-base">{{ $weeklyRecord->week }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Data Inizio:</span>
                            <p class="text-base">{{ $weeklyRecord->start_date?->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Data Fine:</span>
                            <p class="text-base">{{ $weeklyRecord->end_date?->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    @if($weeklyRecord->invoice_number)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Numero Fattura:</span>
                        <p class="text-base font-mono">{{ $weeklyRecord->invoice_number }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Reimmersione Interna -->
            <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 border-b border-blue-300 dark:border-blue-600 pb-2">Reimmersione Interna</h3>

                <div class="space-y-3">
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Micro:</span>
                            <p class="text-base">{{ number_format($weeklyRecord->kg_micro_internal_reimmersion ?? 0, 2, ',', '.') }} kg</p>
                            <p class="text-xs text-gray-500">€ {{ number_format($weeklyRecord->price_micro_internal_reimmersion ?? 0, 2, ',', '.') }}/kg</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Piccola:</span>
                            <p class="text-base">{{ number_format($weeklyRecord->kg_small_internal_reimmersion ?? 0, 2, ',', '.') }} kg</p>
                            <p class="text-xs text-gray-500">€ {{ number_format($weeklyRecord->price_small_internal_reimmersion ?? 0, 2, ',', '.') }}/kg</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Totale:</span>
                            <p class="text-base font-bold">
                                € {{ number_format(
                                    ($weeklyRecord->kg_micro_internal_reimmersion ?? 0) * ($weeklyRecord->price_micro_internal_reimmersion ?? 0) +
                                    ($weeklyRecord->kg_small_internal_reimmersion ?? 0) * ($weeklyRecord->price_small_internal_reimmersion ?? 0),
                                    2, ',', '.'
                                ) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reimmersione Rivendita -->
            <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 border-b border-green-300 dark:border-green-600 pb-2">Reimmersione Rivendita</h3>

                <div class="space-y-3">
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Micro:</span>
                            <p class="text-base">{{ number_format($weeklyRecord->kg_micro_resale_reimmersion ?? 0, 2, ',', '.') }} kg</p>
                            <p class="text-xs text-gray-500">€ {{ number_format($weeklyRecord->price_micro_resale_reimmersion ?? 0, 2, ',', '.') }}/kg</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Piccola:</span>
                            <p class="text-base">{{ number_format($weeklyRecord->kg_small_resale_reimmersion ?? 0, 2, ',', '.') }} kg</p>
                            <p class="text-xs text-gray-500">€ {{ number_format($weeklyRecord->price_small_resale_reimmersion ?? 0, 2, ',', '.') }}/kg</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Totale:</span>
                            <p class="text-base font-bold">
                                € {{ number_format(
                                    ($weeklyRecord->kg_micro_resale_reimmersion ?? 0) * ($weeklyRecord->price_micro_resale_reimmersion ?? 0) +
                                    ($weeklyRecord->kg_small_resale_reimmersion ?? 0) * ($weeklyRecord->price_small_resale_reimmersion ?? 0),
                                    2, ',', '.'
                                ) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Da Consumo -->
            <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 border-b border-yellow-300 dark:border-yellow-600 pb-2">Da Consumo</h3>

                <div class="space-y-3">
                    <div class="grid grid-cols-3 gap-2 text-sm">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Media:</span>
                            <p class="text-base">{{ number_format($weeklyRecord->kg_medium_consumption ?? 0, 2, ',', '.') }} kg</p>
                            <p class="text-xs text-gray-500">€ {{ number_format($weeklyRecord->price_medium_consumption ?? 0, 2, ',', '.') }}/kg</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Grossa:</span>
                            <p class="text-base">{{ number_format($weeklyRecord->kg_large_consumption ?? 0, 2, ',', '.') }} kg</p>
                            <p class="text-xs text-gray-500">€ {{ number_format($weeklyRecord->price_large_consumption ?? 0, 2, ',', '.') }}/kg</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Super:</span>
                            <p class="text-base">{{ number_format($weeklyRecord->kg_super_consumption ?? 0, 2, ',', '.') }} kg</p>
                            <p class="text-xs text-gray-500">€ {{ number_format($weeklyRecord->price_super_consumption ?? 0, 2, ',', '.') }}/kg</p>
                        </div>
                    </div>
                    <div class="border-t border-yellow-300 dark:border-yellow-600 pt-2">
                        <span class="text-sm font-medium text-gray-500">Totale Consumo:</span>
                        <p class="text-lg font-bold">
                            € {{ number_format(
                                ($weeklyRecord->kg_medium_consumption ?? 0) * ($weeklyRecord->price_medium_consumption ?? 0) +
                                ($weeklyRecord->kg_large_consumption ?? 0) * ($weeklyRecord->price_large_consumption ?? 0) +
                                ($weeklyRecord->kg_super_consumption ?? 0) * ($weeklyRecord->price_super_consumption ?? 0),
                                2, ',', '.'
                            ) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Calcoli Finali -->
            <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg md:col-span-2">
                <h3 class="text-lg font-semibold mb-4 border-b border-purple-300 dark:border-purple-600 pb-2">Calcoli Finali</h3>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Imponibile:</span>
                        <p class="text-lg font-bold text-purple-600 dark:text-purple-300">
                            € {{ number_format($weeklyRecord->taxable_amount ?? 0, 2, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Acconto Pagato:</span>
                        <p class="text-lg font-semibold">
                            € {{ number_format($weeklyRecord->advance_paid ?? 0, 2, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Ritenuta d'Acconto:</span>
                        <p class="text-lg font-semibold">
                            € {{ number_format($weeklyRecord->withholding_tax ?? 0, 2, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">PROFIS:</span>
                        <p class="text-lg font-semibold">
                            € {{ number_format($weeklyRecord->profis ?? 0, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="border-l-4 border-purple-500 pl-4">
                        <span class="text-sm font-medium text-gray-500">Bonifico:</span>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            € {{ number_format($weeklyRecord->bank_transfer ?? 0, 2, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informazioni Sistema -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg md:col-span-2">
                <h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-600 pb-2">Informazioni Sistema</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Creato il:</span>
                        <p class="text-base">{{ $weeklyRecord->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Ultimo aggiornamento:</span>
                        <p class="text-base">{{ $weeklyRecord->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Azienda:</span>
                        <p class="text-base">{{ $weeklyRecord->company->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-between border-t border-gray-300 dark:border-gray-600 pt-4">
            <form action="{{ route('weekly-records.destroy', $weeklyRecord) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo registro? Questa azione non può essere annullata.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Elimina Registro
                </button>
            </form>

            <a href="{{ route('weekly-records.edit', $weeklyRecord) }}" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Modifica Registro
            </a>
        </div>
    </div>
</div>
@endsection
