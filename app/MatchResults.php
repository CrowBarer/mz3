<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchResults extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'match_results';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * result from the end of the game
     */
    const FINAL_RESULT_TYPE = 2;

    /**
     * half time results
     */
    const HALF_RESULT_TYPE = 2;

    public function setProperties(array $data, int $matchNumber): void
    {
        $this->match_id = $matchNumber;
        $this->result_name = $data['ResultName'];
        $this->points_team_1 = $data['PointsTeam1'];
        $this->points_team_2 = $data['PointsTeam2'];
        $this->result_order = $data['ResultOrderID'];
        $this->result_type = $data['ResultTypeID'];
    }

}
