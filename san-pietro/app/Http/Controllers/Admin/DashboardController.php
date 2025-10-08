<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\TransportDocument;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $companiesCount = Company::count();
        $usersCount = User::count();
        $recentActivities = Activity::whereDate('created_at', '>=', now()->subDay())->count();
        $recentCompanies = Company::latest()->take(5)->get();

        // Statistiche aggiuntive
        $transportDocumentsCount = TransportDocument::count();
        $clientsCount = Client::count();
        $productsCount = Product::count();

        Log::info('Dashboard Data:', [
            'companiesCount' => $companiesCount,
            'usersCount' => $usersCount,
            'recentActivities' => $recentActivities,
            'recentCompanies' => $recentCompanies,
            'transportDocumentsCount' => $transportDocumentsCount,
            'clientsCount' => $clientsCount,
            'productsCount' => $productsCount,
        ]);

        return view('admin.dashboard', compact(
            'companiesCount',
            'usersCount',
            'recentActivities',
            'recentCompanies',
            'transportDocumentsCount',
            'clientsCount',
            'productsCount'
        ));
    }
}
