<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Toko;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        ActivityLogger::log('Login', []);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            User::where('id', $user->id)->update([
                'ip_login' => $request->ip(),
                'last_activity' => Carbon::now(),
            ]);

            $route = '';
            if ($user->id_level == 1) {
                $route = route('dashboard.index');
            } elseif ($user->nama_level == 'petugas') {
                $route = redirect('/petugas/dashboard');
            } else {
                $route = route('dashboard.index');
            }
            return response()->json([
                'status_code' => 200,
                'error' => false,
                'message' => "Successfully",
                'data' => array(
                    'route_redirect' => $route
                )
            ], 200);
        } else {
            return response()->json([
                'status_code' => 300,
                'error' => true,
                'message' => "Terjadi Kesalahan",
            ], 300);
        }
    }

    public function dashboard()
    {
        $toko = Toko::all();
        $title = 'Dashboard';
        $menu = ['Dashboard'];

        $senseiUsersCount = User::query()
            ->whereHas('leveluser', function ($query) {
                $query->where('nama_level', 'Sensei');
            })
            ->count();

        $memberCount = Member::query()->count();

        return view('dashboard', compact('title', 'toko', 'menu', 'senseiUsersCount', 'memberCount'));
    }

    public function logout(Request $request)
    {
        ActivityLogger::log('Logout', []);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function index()
    {
        $menu = ['Dashboard'];

        $senseiUsersCount = User::query()
            ->whereHas('leveluser', function ($query) {
                $query->where('nama_level', 'Sensei');
            })
            ->count();

        $memberCount = Member::query()->count();

        return view('dashboard', compact('menu', 'senseiUsersCount', 'memberCount'));
    }
}
