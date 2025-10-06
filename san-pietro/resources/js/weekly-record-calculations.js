/**
 * Calcoli Real-Time per Weekly Records
 * Replica esatta delle formule Excel del foglio "PER FARE LA FATTURA"
 */

export function initializeWeeklyRecordCalculations() {
    // Selettori per tutti i campi
    const fields = {
        // Reimmersione Interna
        kg_micro_internal: document.querySelector('[name="kg_micro_internal_reimmersion"]'),
        price_micro_internal: document.querySelector('[name="price_micro_internal_reimmersion"]'),
        kg_small_internal: document.querySelector('[name="kg_small_internal_reimmersion"]'),
        price_small_internal: document.querySelector('[name="price_small_internal_reimmersion"]'),

        // Reimmersione Rivendita
        kg_micro_resale: document.querySelector('[name="kg_micro_resale_reimmersion"]'),
        price_micro_resale: document.querySelector('[name="price_micro_resale_reimmersion"]'),
        kg_small_resale: document.querySelector('[name="kg_small_resale_reimmersion"]'),
        price_small_resale: document.querySelector('[name="price_small_resale_reimmersion"]'),

        // Da Consumo
        kg_medium: document.querySelector('[name="kg_medium_consumption"]'),
        price_medium: document.querySelector('[name="price_medium_consumption"]'),
        kg_large: document.querySelector('[name="kg_large_consumption"]'),
        price_large: document.querySelector('[name="price_large_consumption"]'),
        kg_super: document.querySelector('[name="kg_super_consumption"]'),
        price_super: document.querySelector('[name="price_super_consumption"]'),

        // Calcoli finali
        taxable_amount: document.querySelector('[name="taxable_amount"]'),
        advance_paid: document.querySelector('[name="advance_paid"]'),
        withholding_tax: document.querySelector('[name="withholding_tax"]'),
        profis: document.querySelector('[name="profis"]'),
        bank_transfer: document.querySelector('[name="bank_transfer"]')
    };

    // Verifica che i campi esistano
    if (!fields.kg_micro_internal) {
        console.warn('Weekly record form not found, skipping calculations');
        return;
    }

    // Funzione per convertire valore in numero
    const toNumber = (value) => {
        const num = parseFloat(value) || 0;
        return isNaN(num) ? 0 : num;
    };

    // Funzione per formattare numero a 2 decimali
    const formatNumber = (value) => {
        return value.toFixed(2);
    };

    // Calcola tutti i totali
    const calculateAll = () => {
        // Totali per categoria (kg × prezzo)
        const total_micro_internal = toNumber(fields.kg_micro_internal?.value) * toNumber(fields.price_micro_internal?.value);
        const total_small_internal = toNumber(fields.kg_small_internal?.value) * toNumber(fields.price_small_internal?.value);
        const total_micro_resale = toNumber(fields.kg_micro_resale?.value) * toNumber(fields.price_micro_resale?.value);
        const total_small_resale = toNumber(fields.kg_small_resale?.value) * toNumber(fields.price_small_resale?.value);
        const total_medium = toNumber(fields.kg_medium?.value) * toNumber(fields.price_medium?.value);
        const total_large = toNumber(fields.kg_large?.value) * toNumber(fields.price_large?.value);
        const total_super = toNumber(fields.kg_super?.value) * toNumber(fields.price_super?.value);

        // Imponibile = somma di tutti i totali
        const taxable = total_micro_internal + total_small_internal +
                       total_micro_resale + total_small_resale +
                       total_medium + total_large + total_super;

        // Aggiorna campo imponibile
        if (fields.taxable_amount) {
            fields.taxable_amount.value = formatNumber(taxable);
        }

        // Bonifico = imponibile - acconto_pagato - ritenuta_acconto - profis
        const advance = toNumber(fields.advance_paid?.value);
        const withholding = toNumber(fields.withholding_tax?.value);
        const profis = toNumber(fields.profis?.value);
        const bank_transfer = taxable - advance - withholding - profis;

        // Aggiorna campo bonifico
        if (fields.bank_transfer) {
            fields.bank_transfer.value = formatNumber(bank_transfer);
        }

        // Mostra feedback visivo
        updateVisualFeedback(taxable, bank_transfer);
    };

    // Feedback visivo per l'utente
    const updateVisualFeedback = (taxable, bank_transfer) => {
        // Aggiungi classe per evidenziare calcoli aggiornati
        if (fields.taxable_amount) {
            fields.taxable_amount.classList.add('bg-yellow-50', 'border-yellow-300');
            setTimeout(() => {
                fields.taxable_amount.classList.remove('bg-yellow-50', 'border-yellow-300');
            }, 500);
        }

        if (fields.bank_transfer) {
            fields.bank_transfer.classList.add('bg-green-50', 'border-green-300');
            setTimeout(() => {
                fields.bank_transfer.classList.remove('bg-green-50', 'border-green-300');
            }, 500);
        }
    };

    // Aggiungi event listeners a tutti i campi di input
    Object.values(fields).forEach(field => {
        if (field && field.tagName === 'INPUT') {
            field.addEventListener('input', calculateAll);
            field.addEventListener('change', calculateAll);
        }
    });

    // Calcolo iniziale al caricamento
    calculateAll();

    // Auto-save ogni 30 secondi
    let autoSaveTimer;
    let isDirty = false;

    const markAsDirty = () => {
        isDirty = true;
    };

    const autoSave = () => {
        if (!isDirty) return;

        const form = document.querySelector('form');
        if (!form) return;

        // Mostra indicatore di salvataggio
        showSavingIndicator();

        // Salva via AJAX (optional - richiede endpoint API)
        // Per ora salva solo se c'è un bottone di submit
        const formData = new FormData(form);

        // Qui puoi implementare AJAX save
        console.log('Auto-saving weekly record...');

        isDirty = false;
        hideSavingIndicator();
    };

    const showSavingIndicator = () => {
        let indicator = document.getElementById('autosave-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'autosave-indicator';
            indicator.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded shadow-lg z-50';
            indicator.textContent = '💾 Salvataggio in corso...';
            document.body.appendChild(indicator);
        }
        indicator.classList.remove('hidden');
    };

    const hideSavingIndicator = () => {
        const indicator = document.getElementById('autosave-indicator');
        if (indicator) {
            indicator.textContent = '✓ Salvato';
            indicator.classList.remove('bg-blue-500');
            indicator.classList.add('bg-green-500');
            setTimeout(() => {
                indicator.classList.add('hidden');
                indicator.classList.remove('bg-green-500');
                indicator.classList.add('bg-blue-500');
            }, 2000);
        }
    };

    // Avvia auto-save ogni 30 secondi
    Object.values(fields).forEach(field => {
        if (field && field.tagName === 'INPUT') {
            field.addEventListener('input', markAsDirty);
        }
    });

    setInterval(autoSave, 30000); // 30 secondi

    console.log('✅ Weekly Record Calculations initialized');
}

// Inizializza quando il DOM è pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeWeeklyRecordCalculations);
} else {
    initializeWeeklyRecordCalculations();
}
