<?php

namespace App\Http\Controllers;

use App\Matches;
use App\Team;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = new Matches();
        /** @var  $nextMatch Matches */
        $nextMatch = $model->getNextUpcomingMatch();
        return sprintf('Next Upcoming Match Grpup: %s <br> Next match date: %s <br> Team 1: %s <br> Team 2: %s',
            $nextMatch->getGroup->name,
            $nextMatch->match_dateTime,
            $nextMatch->getFirstTeam->name,
            $nextMatch->getSecondTeam->name
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function teamsRatio()
    {
        $model = new Team();
        $teams = $model->getAllTeams();
        $string = ' <br> %s : %s  (win/los) ration <br>';
        if ($teams) {
            /**@var $team Team */
            foreach ($teams as $team) {
                $matches = $team->getMatches($team);
                $team->setMatches($matches);

                if ($matches) {
                    $ratio = $team->getRatio($matches);
                    echo sprintf($string, $team->name, $ratio);
                }
            }
        }
    }

}
