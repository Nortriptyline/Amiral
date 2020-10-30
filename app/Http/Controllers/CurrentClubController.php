<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Repositories\ClubRepository;
use Illuminate\Http\Request;

class CurrentClubController extends Controller
{

    private $clubs;

    public function __construct(ClubRepository $clubs)
    {
        $this->clubs = $clubs;
    }

    public function update_informations(Request $request)
    {
        $club = Club::findOrFail($request->user()->current_club_id);
        
        $this->clubs->updateInformations($club, $request->all());
        
        return back();
    }
}
