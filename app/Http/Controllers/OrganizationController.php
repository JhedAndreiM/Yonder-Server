<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function dashboard(){
        return view('organization.dashboard');
    }
}
