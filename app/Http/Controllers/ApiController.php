<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{

    public function index()
    {
        return [
            'api'    => [
                'version'   => '0.2.0',
                'framework' => 'Laravel 5.7',
            ],
            'author' => [
                'name'  => 'Hans-Helge Bürger',
                'email' => 'santa@wichtel.me',
            ],
        ];
    }
}
