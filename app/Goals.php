<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goals extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goals';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function setProperties(array $data, int $matchNumber): void
    {
        $this->match_id = $matchNumber;
        $this->score_team1 = $data['ScoreTeam1'];
        $this->score_team2 = $data['ScoreTeam2'];
        $this->match_minute = $data['MatchMinute'];
        $this->goal_getter = $data['GoalGetterName'];
        $this->is_penalty = $data['IsPenalty'];
        $this->is_own_goal = $data['IsOwnGoal'];
        $this->is_overtime  = $data['IsOvertime'];
    }
}
