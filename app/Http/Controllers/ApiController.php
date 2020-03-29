<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{

    public function index()
    {
        return [
            'api'    => [
                'version'   => '1.1.0',
            ],
            'author' => [
                'name'  => 'Hans-Helge Bürger',
                'email' => 'santa@wichtel.me',
            ],
        ];
    }
}
