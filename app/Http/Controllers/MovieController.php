<?php

namespace App\Http\Controllers;

use App\Http\Api\MovieApi;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function __construct(private readonly MovieApi $movieApi)
    {
    }

    public function index()
    {
        return view('movies');
    }

    public function show($id)
    {
        $response = $this->movieApi->movieDetails($id);
        dd($response);
        return view('movies-show');
    }
}
