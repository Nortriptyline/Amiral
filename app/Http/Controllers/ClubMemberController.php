<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Repositories\ClubRepository;
use Illuminate\Http\Request;

class ClubMemberController extends Controller
{
    //

    private $clubs;

    public function __construct(ClubRepository $clubs)
    {
        $this->clubs = $clubs;
    }

    public function store(Request $request, Club $club)
    {
        $this->clubs->add_member($club, $request->all());

        return back();
    }
}
