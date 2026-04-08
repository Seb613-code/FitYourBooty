<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donnee;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $donnees = Donnee::where('user_id', Auth::id())->latest('date')->get();
        return view('dashboard', compact('donnees'));
    }
}
