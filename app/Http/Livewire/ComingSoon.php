<?php

namespace App\Http\Livewire;

use App\Http\Api\GamesApi;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ComingSoon extends Component
{
    public $comingSoon = [];
    private GamesApi $gamesApi;

    public function boot(GamesApi $gamesApi)
    {
        $this->gamesApi = $gamesApi;
    }

    public function loadComingSoon()
    {
        $comingSoonUnformatted = $this->gamesApi->getComingSoon();

        $this->comingSoon = $this->formatForView($comingSoonUnformatted);
    }

    public function render()
    {
        return view('livewire.coming-soon');
    }

    private function formatForView($games)
    {
        return collect($games)->map(function ($game) {
            return collect($game)->merge([
                'coverImageUrl' => Str::replaceFirst('thumb','cover_small', $game['cover']['url']),
                'releaseDate' => Carbon::parse($game['first_release_date'])->format('M d, Y'),
            ]);
        })->toArray();
    }
}
