<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('dashboard')->with('error', 'Azienda non trovata');
        }

        $data = [
            'company' => $company,
            'usersCount' => $company->users()->count(),
            'activeUsersCount' => $company->users()->where('is_active', true)->count(),
            'latestUsers' => $company->users()->latest()->take(5)->get(),
        ];

        return view('dashboard.company-admin', $data);
    }
}
