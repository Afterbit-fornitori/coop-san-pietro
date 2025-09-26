<?php
// Test script per verificare ruoli e permessi
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test Ruoli e Permessi ===\n\n";

// Test 1: Verifica esistenza utenti
echo "1. Controllo utenti:\n";
$superAdmin = \App\Models\User::where('email', 'super@admin.com')->first();
$sanPietroAdmin = \App\Models\User::where('email', 'admin@sanpietro.com')->first();

if ($superAdmin) {
    echo "✓ Super Admin trovato: {$superAdmin->name}\n";
    echo "  Ruoli: " . $superAdmin->getRoleNames()->implode(', ') . "\n";
    echo "  Company ID: " . ($superAdmin->company_id ?? 'null') . "\n";
} else {
    echo "✗ Super Admin NON trovato\n";
}

if ($sanPietroAdmin) {
    echo "✓ San Pietro Admin trovato: {$sanPietroAdmin->name}\n";
    echo "  Ruoli: " . $sanPietroAdmin->getRoleNames()->implode(', ') . "\n";
    echo "  Company ID: {$sanPietroAdmin->company_id}\n";
} else {
    echo "✗ San Pietro Admin NON trovato\n";
}

echo "\n2. Controllo aziende:\n";
$sanPietroCompany = \App\Models\Company::where('name', 'Cooperativa San Pietro')->first();
if ($sanPietroCompany) {
    echo "✓ San Pietro Company trovata: {$sanPietroCompany->name}\n";
    echo "  Tipo: {$sanPietroCompany->type}\n";
    echo "  Domain: {$sanPietroCompany->domain}\n";
    echo "  IsMain(): " . ($sanPietroCompany->isMain() ? 'true' : 'false') . "\n";
} else {
    echo "✗ San Pietro Company NON trovata\n";
}

echo "\n3. Controllo permessi:\n";
if ($superAdmin) {
    echo "Super Admin può vedere aziende: " . ($superAdmin->can('view companies') ? '✓' : '✗') . "\n";
    echo "Super Admin può creare aziende: " . ($superAdmin->can('create companies') ? '✓' : '✗') . "\n";
}

if ($sanPietroAdmin) {
    echo "San Pietro Admin può vedere aziende: " . ($sanPietroAdmin->can('view companies') ? '✓' : '✗') . "\n";
    echo "San Pietro Admin può creare aziende: " . ($sanPietroAdmin->can('create companies') ? '✓' : '✗') . "\n";
}

echo "\n4. Test complete!\n";