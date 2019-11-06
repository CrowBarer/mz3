<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'teams';

    private $matches;

    const END_RESULT = 2;

    const FIRST_TEAM = 'team1';
    const SECOND_TEAM = 'team2';

    /**
     * @param array $teams
     * @return bool
     */
    public function insertTeams(array $teams): bool
    {
        $hasError = true;
        foreach ($teams as $team) {
            $model = new Team();
            $model->name = $team['TeamName'];
            $model->short_name = $team['ShortName'];
            $model->icon_url = $team['TeamIconUrl'];
            $model->group_name = $team['TeamGroupName'];
            $model->team_id = $team['TeamId'];
            $hasError = $model->save();
        }

        return !$hasError;
    }

    public function setMatches($matces): void
    {
        $this->matches = $matces;
    }


    /**
     * calculate the team ratio
     * @return float
     */
    public function getRatio()
    {
        /**@var $matches Matches */
        $matches = $this->matches->keyBy('match_number');
        $formatMatchesId = $this->splitTeamMatchPosition();
        $wins = 0;
        $loses = 0;

        foreach ($formatMatchesId[self::FIRST_TEAM] as $row) {
            $gameEndResult = $this->getMatchResultByType($matches[$row], MatchResults::FINAL_RESULT_TYPE);
            try {
                if ($this->isFirstTeamWinner($gameEndResult)) {
                    $wins++;
                } else {
                    $loses++;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        foreach ($formatMatchesId[self::SECOND_TEAM] as $row) {
            try {
                $gameEndResult = $this->getMatchResultByType($matches[$row], MatchResults::FINAL_RESULT_TYPE);
                if ($this->isSecondTeamWinner($gameEndResult)) {
                    $wins++;
                } else {
                    $loses++;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return round($wins / $loses, 4);
    }

    /**
     * GET match results  before the half MatchResults::HALF_RESULT_TYPE
     *  OR at the end of the game MatchResults::FINAL_RESULT_TYPE
     * @param $match
     * @bool $type
     * @return mixed
     */
    private function getMatchResultByType($match, $type)
    {
        /**@var $result MatchResults */
        $result = $match->getResults;

        if (count($result)) {
            return $match->getResults->keyBy('result_type')[$type];
        }

        return false;
    }

    private function isFirstTeamWinner($result): bool
    {
        return $result->points_team_1 > $result->points_team_2;
    }

    private function isSecondTeamWinner($result): bool
    {
        return $result['points_team_2'] > $result['points_team_1'];
    }


    private function splitTeamMatchPosition()
    {
        /**@var $matches \App\Matches */
        $matches = $this->matches->keyBy('match_number');

        $fistsTeam = [];
        $secondTeam = [];

        foreach ($matches as $match) {
            if ($match->team1 == $this->team_id) {
                $fistsTeam[] = $match->match_number;
            } else {
                $secondTeam[] = $match->match_number;
            }
        }

        return [
            self::FIRST_TEAM => $fistsTeam,
            self::SECOND_TEAM => $secondTeam
        ];
    }


    public function getMatches($team)
    {
        $model = new Matches();
        return $model->getTeamMatch($team->team_id);
    }


    public function getTeamResult()
    {

    }


    public function getAllTeams()
    {
        return self::get();
    }

}
