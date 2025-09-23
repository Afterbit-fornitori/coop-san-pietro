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

        if ($user->hasRole('SUPER_ADMIN')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('COMPANY_ADMIN')) {
            return redirect()->route('company.dashboard');
        } elseif ($user->hasRole('COMPANY_USER')) {
            // COMPANY_USER va alla vista operativa
            return view('dashboard.company-user');
        }

        // Default view for guest users
        return view('dashboard.user');
    }
}
