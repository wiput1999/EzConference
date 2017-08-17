<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Conference;

class DashboardController extends Controller
{
    public function getDashboard() {
        $conferences = Conference::where('open', '1')->get();
        foreach ($conferences as $conference) {
            $conference['owner_name'] = User::find($conference['owner'])['name'];
        }
        return view('dashboard', ['conferences' => $conferences]);
    }

    public function getProfile() {
        return view('profile');
    }
}
