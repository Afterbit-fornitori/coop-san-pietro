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
            return redirect('/admin');
        } elseif ($user->hasRole('COMPANY_ADMIN')) {
            return redirect()->route('company.dashboard');
        }

        // Default view for standard users
        return view('dashboard.user');
    }
}
