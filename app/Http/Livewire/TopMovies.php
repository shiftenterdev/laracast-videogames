<?php

namespace App\Http\Livewire;

use App\Http\Api\MovieApi;
use Livewire\Component;

class TopMovies extends Component
{
    private MovieApi $movieApi;
    public $topMovies = [];

    public function boot(MovieApi $movieApi)
    {
        $this->movieApi = $movieApi;
    }

    public function loadTopMovies()
    {
        $this->topMovies = $this->movieApi->getRandom();

        collect($this->topMovies)->filter(function ($movie) {
            return $movie['ratingsSummary']['aggregateRating'];
        })->each(function ($movie) {
            $this->emit('gameWithRatingAdded', [
                'slug' => $movie['id'],
                'rating' => $movie['ratingsSummary']['aggregateRating'] / 10
            ]);
        });
    }

    public function render()
    {
        return view('livewire.top-movies');
    }
}
