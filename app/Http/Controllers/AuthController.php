<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class AuthController extends Controller
{
    public function index() {
        return view('login');
    }

    public function Login(Request $request) {

        $credentials = $request->only('email', 'password');

        $response = Http::post('http://127.0.0.1:9000/api/login', [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);

        $loginResponse = $response->json();

        if (isset($loginResponse['access_token'])) {
            $dashboardUrl = $loginResponse['dashboard_url'];

            $userData = $loginResponse['user_data'];

            return Redirect::to($dashboardUrl)->with('user_data', $userData);
        } else {
            return view('login')->with('error', 'Invalid credentials');
        }
    }

    public function adminDashboard(Request $request){
        $userData = session('user_data');
        $userName = $userData['name'];

        return view('admin.dashboard', compact('userName'));
    }

    public function cashierDashboard(Request $request){
        $userData = session('user_data');
        $userName = $userData['name'];

        return view('cashier.dashboard', compact('userName'));
    }
}
