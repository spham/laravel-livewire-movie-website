<?php

namespace App\Http\Livewire;

use App\Models\Serie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;

class SerieIndex extends Component
{
    public $search = '';
    public $sort = 'asc';
    public $perPage = 5;

    public $name;
    public $tmdbId;
    public $showSerieModal = false;

    public function generateSerie()
    {
        $newSerie = Http::get('https://api.themoviedb.org/3/tv/90462?api_key=8a11aac3fb4ef5f1f9607ee7e0329793&language=en-US
')->json();
        Serie::create([
            'tmdb_id' => $newSerie['id'],
            'name'    => $newSerie['name'],
            'slug'    => Str::slug($newSerie['name']),
            'created_year' => $newSerie['first_air_date'],
            'poster_path'  => $newSerie['poster_path']
        ]);
    }
    public function closeSerieModal()
    {
        $this->showSerieModal = false;
    }
    public function resetFilters()
    {
        $this->reset();
    }
    public function render()
    {
        return view('livewire.serie-index', [
            'series' => Serie::search('name', $this->search)->orderBy('name', $this->sort)->paginate($this->perPage)
        ]);
    }
}
