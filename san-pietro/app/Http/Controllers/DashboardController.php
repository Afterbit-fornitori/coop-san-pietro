<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        $view = 'dashboard.user';  // Default view for standard users

        $data = [];

        if ($user->hasRole('SUPER_ADMIN')) {
            $view = 'dashboard.super-admin';
            $data['totalCompanies'] = \App\Models\Company::count();
            $data['activeCompanies'] = \App\Models\Company::where('is_active', true)->count();
            $data['totalUsers'] = User::count();
            $data['recentCompanies'] = \App\Models\Company::latest()->take(5)->count();

            // Get latest companies for the dashboard
            $data['latestCompanies'] = \App\Models\Company::latest()->take(5)->get();
        } elseif ($user->hasRole('COMPANY_ADMIN')) {
            $view = 'dashboard.company-admin';
            // Load company-related stats
            $company = $user->company;
            if (!$company) {
                return redirect()->route('dashboard')->with('error', 'Company not found');
            }
            $data['company'] = $company;
            $data['usersCount'] = $company->users()->count();
            $data['activeUsersCount'] = $company->users()->where('is_active', true)->count();

            // Get latest users for the dashboard
            $data['latestUsers'] = $company->users()->latest()->take(5)->get();
        }

        return view($view, $data);
    }
}
