<?php

namespace App\Http\Livewire;

use App\Http\Api\GamesApi;
use Livewire\Component;

class SearchDropdown extends Component
{
    public $search = '';
    public $searchResults = [];

    private GamesApi $gamesApi;

    public function boot(GamesApi $gamesApi)
    {
        $this->gamesApi = $gamesApi;
    }

    public function render()
    {
        if (strlen($this->search) >= 2) {
            $this->searchResults =  $this->gamesApi->searchDropdown($this->search);
        }

        return view('livewire.search-dropdown');
    }
}
