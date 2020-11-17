<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Repositories\TeamRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{

    private $teams;

    public function __construct(TeamRepository $teams)
    {
        $this->teams = $teams;
    }

    public function create()
    {
        return Inertia::render('Teams/Create');
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $team = $this->teams->store($user, $user->current_club, $request->all());

        return redirect()->route('teams.edit', ["team" => $team->id]);
    }

    public function edit(Request $request, Team $team)
    {
        return Inertia::render('Teams/Edit', [
            'team' => $team
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $this->teams->update($team, $request->all());

        return back();
    }
}
