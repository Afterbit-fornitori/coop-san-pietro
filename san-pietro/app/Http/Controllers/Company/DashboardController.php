<?php

namespace App\Http\Controllers\Company;

use App\Models\User;
use App\Models\Client;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\TransportDocument;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $company = $user->company;

        $companiesCount = Company::count();

        // Statistiche aggiuntive
        $transportDocumentsCount = TransportDocument::count();
        $clientsCount = Client::count();
        $productsCount = Product::count();

        if (!$company) {
            return redirect()->route('dashboard')->with('error', 'Azienda non trovata');
        }

        $data = [
            'company' => $company,
            'usersCount' => $company->users()->count(),
            'activeUsersCount' => $company->users()->where('is_active', true)->count(),
            'latestUsers' => $company->users()->latest()->take(5)->get(),
            'transportDocumentsCount' => $transportDocumentsCount,
            'clientsCount' => $clientsCount,
            'productsCount' => $productsCount,
            'companiesCount' => $companiesCount,
        ];

        return view('dashboard.company-admin', $data);
    }
}
