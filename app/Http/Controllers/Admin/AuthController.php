<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin; // Make sure to import the Model

class AuthController extends Controller
{
    public function showLogin() {
        return view('admin.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt login using the 'admin' guard
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            
            $admin = Auth::guard('admin')->user();

            /**
             * REDIRECTION LOGIC
             */
            
            // 1. Super Admins -> Team Management page
            if ($admin->isSuperAdmin()) {
                return redirect()->route('admin.admins.index');
            }

            if ($admin->isSupporter()) {
                return redirect()->route('dashboard.tickets.index');
            }

            if ($admin->isSupportAgent()) {
                return redirect()->route('dashboard.tickets.index');
            }

            // 2. Finance Admins -> Dashboard (They will see all events + money)
            if ($admin->isFinanceAdmin()) {
                return redirect()->route('dashboard.tickets.index');
            }

            // 3. Event Admins -> Dashboard (They will see assigned events only)
            if ($admin->isEventAdmin()) {
                return redirect()->route('dashboard.tickets.index');
            }

            // Fallback for any other admin roles
            return redirect()->route('dashboard.register-level-1');
        }

        // If login fails, redirect back with error
        return back()->with('error', 'Invalid Admin Credentials or Access Denied.');
    }

    public function logout(Request $request) {
        Auth::guard('admin')->logout();
        
        // Standard Laravel security: invalidate and regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}