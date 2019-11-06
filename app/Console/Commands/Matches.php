<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Matches as Model;
use App\MatchResults;
use App\Goals;
use Illuminate\Support\Facades\DB;

class Matches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matches:collect {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all matches for current year';


    private $apiUrl = 'https://www.openligadb.de/api/getmatchdata/bl1/%s';
    private $data;

    const SUCCESS_STATUS_CODE = 200;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $this->data = $this->getDataFromApi();
        } catch (\Exception $e) {
            return 'No Data found';
        }

        DB::transaction(function () {
            foreach ($this->data as $match) {
                $model = new Model();
                $model->setProperties($match);
                $model->save();

                if (!empty($match['MatchResults'])) {
                    $this->insertMatchResults($match['MatchResults'], $match['MatchID']);
                }

                if (!empty($match['Goals'])) {
                    $this->insertGoals($match['Goals'], $match['MatchID']);
                }
            }
        });

        return 'ALL MATCH DATA COLLECTED !!';
    }

    private function insertGoals(array $data, int $matchNumber): void
    {
        foreach ($data as $row) {
            $model = new Goals();
            $model->setProperties($row, $matchNumber);
            $model->save();
        }
    }

    /**
     * @param array $data
     * @param int $matchNumber
     */
    private function insertMatchResults(array $data, int $matchNumber): void
    {
        foreach ($data as $row) {
            $model = new MatchResults();
            $model->setProperties($row, $matchNumber);
            $model->save();
        }
    }

    /**
     * @return array
     * @throws \ErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getDataFromApi(): array
    {
        $url = sprintf($this->apiUrl, $this->argument('year'));
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        $statusCode = (int)$response->getStatusCode();
        $content = $response->getBody();

        if ($statusCode !== self::SUCCESS_STATUS_CODE) {
            throw new \ErrorException('Invalid Response');
        }

        return json_decode($content, true);
    }

}
