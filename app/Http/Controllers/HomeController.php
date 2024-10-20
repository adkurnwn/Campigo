<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Model\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        if (Auth::id()) {        
            $role = Auth::user()->role; 
            if ($role == 'user') {
                return view('home');
            } else if ($role == 'admin') {
                return view('admin');
            } else {
                return view('home');
            }
        }
        
    }

    public function __invoke(Request $request)
    {
        if (Auth::id()) {        
            $role = Auth::user()->role; 
            if ($role == 'user') {
                return view('home');
            } else if ($role == 'admin') {
                return view('admin');
            }
        }else {
            return view('home');
        }
    }
}
