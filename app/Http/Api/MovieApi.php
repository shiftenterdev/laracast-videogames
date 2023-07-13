<?php

namespace App\Http\Api;

use Illuminate\Support\Facades\Http;

class MovieApi
{
    public function getRandom()
    {
        $response = Http::withHeaders(config('services.movie.headers'))
            ->withQueryParameters([
//            'list'=>'top_rated_250',
                'info' => 'base_info',
                'genre' => 'Drama',
                'year' => 2010,
                'limit' => 12
            ])->get('https://moviesdatabase.p.rapidapi.com/titles')->json();
        return $this->formatForView($response);
    }

    public function movieDetails($id)
    {
        $response = Http::withHeaders(config('services.movie.headers'))
            ->withQueryParameters([
                'info' => 'base_info',
            ])->get('https://moviesdatabase.p.rapidapi.com/titles/'.$id)->json();
        return $this->formatForView($response);
    }

    private function formatForView($response)
    {
        return $response['results'] ?? [];
    }
}
