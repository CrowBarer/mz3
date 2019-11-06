<?php

namespace App\Console\Commands;

use App\Team;
use Illuminate\Console\Command;

class Teams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teams:collect {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect Teams for the specified season';

    private $apiUrl = 'https://www.openligadb.de/api/getavailableteams/bl1/%s';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $teams = $this->getTeamsFromApi();
        } catch (\Exception $e) {
            return 'No Data found';
        }

        $model = new Team();
        $model->insertTeams($teams);
    }

    /**
     * @return array
     * @throws \ErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    private function getTeamsFromApi(): array
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
