<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $companiesCount = Company::count();
        $usersCount = User::count();
        $recentActivities = Activity::whereDate('created_at', '>=', now()->subDay())->count();
        $recentCompanies = Company::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'companiesCount',
            'usersCount',
            'recentActivities',
            'recentCompanies'
        ));
    }
}
