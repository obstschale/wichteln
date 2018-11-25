<?php

namespace App\Http\Controllers;

class RegistrationController extends Controller
{

    public function create()
    {
        return view('web.register');
    }
}
