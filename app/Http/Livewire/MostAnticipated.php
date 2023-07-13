<?php

namespace App\Http\Livewire;

use App\Http\Api\GamesApi;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;

class MostAnticipated extends Component
{
    public $mostAnticipated = [];

    private GamesApi $gamesApi;

    public function boot(GamesApi $gamesApi)
    {
        $this->gamesApi = $gamesApi;
    }

    public function loadMostAnticipated()
    {
        $mostAnticipatedUnformatted = $this->gamesApi->mostAnticipated();
        $this->mostAnticipated = $this->formatForView($mostAnticipatedUnformatted);
    }

    public function render()
    {
        return view('livewire.most-anticipated');
    }

    private function formatForView($games)
    {
        return collect($games)->map(function ($game) {
            return collect($game)->merge([
                'coverImageUrl' => isset($game['cover'])?Str::replaceFirst('thumb','cover_small', $game['cover']['url']):asset('ff7.jpg'),
                'releaseDate' => Carbon::parse($game['first_release_date'])->format('M d, Y'),
            ]);
        })->toArray();
    }
}
