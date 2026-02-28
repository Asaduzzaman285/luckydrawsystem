<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Terminal Access Granted. Welcome back, Administrator.');
        }

        if ($user->hasRole('agent')) {
            return redirect()->intended(route('agent.dashboard'))
                ->with('success', 'Agent Portal Authorized. Welcome back.');
        }

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Welcome to your LuckyDraw Hub!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')
            ->with('status', 'Session terminated. We look forward to your return.');
    }
}
