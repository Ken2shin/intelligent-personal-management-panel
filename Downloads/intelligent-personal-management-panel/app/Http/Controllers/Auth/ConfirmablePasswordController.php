<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! hash_equals((string) $request->session()->pull('auth.password_confirmed_at'), (string) now()->timestamp)) {
            return redirect()->back()->withErrors([
                'password' => ['This password does not match our records.'],
            ]);
        }

        return redirect()->intended();
    }
}
