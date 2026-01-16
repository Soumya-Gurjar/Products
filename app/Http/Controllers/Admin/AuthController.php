<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show admin login form
    public function loginForm()
    {
        return view('admin.login');
    }

    // Handle admin login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return redirect('/admin/products');
        }

        return back()->withErrors([
            'email' => 'Invalid admin credentials',
        ]);
    }

    // Admin logout
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}

