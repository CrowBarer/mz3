<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Matches extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'matches';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    public function setProperties($data): void
    {
        $this->match_number = $data['MatchID'];
        $this->match_dateTime = $data['MatchDateTime'];
        $this->timezone_id = $data['TimeZoneID'];
        $this->league_id = $data['LeagueId'];
        $this->League_name = $data['LeagueName'];
        $this->team1 = $data['Team1']['TeamId'];
        $this->team2 = $data['Team2']['TeamId'];
        $this->last_update_dateTime = $data['LastUpdateDateTime'];
        $this->match_is_finished = $data['MatchIsFinished'];
        $this->group = $data['Group']['GroupID'];
    }

    public function getNextUpcomingMatch()
    {
        return self::where(DB::raw('CAST(match_dateTime AS DATE)'), '>', date('Y-m-d'))
            ->first();
    }

    /**
     * Get all matches by team id
     * @param int $team
     * @return mixed
     */
    public function getTeamMatch(int $team)
    {
        return self::where('team1', '=', $team)
            ->orWhere('team2', '=', $team)
            ->get();
    }

    /** RELATIONS  */

    public function getFirstTeam()
    {
        return $this->hasOne('App\Team', 'team_id', 'team1');
    }

    public function getSecondTeam()
    {
        return $this->hasOne('App\Team', 'team_id', 'team2');
    }

    public function getGroup()
    {
        return $this->hasOne('App\Group', 'group_id', 'group');
    }


    public function getResults()
    {
        return $this->hasMany('App\MatchResults', 'match_id', 'match_number');
    }

}
