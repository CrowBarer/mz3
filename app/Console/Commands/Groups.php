<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Group;

class Groups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'groups:collect {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all groups for current year';

    private $apiUrl = 'https://www.openligadb.de/api/getavailablegroups/bl1/%s';

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
            $groups = $this->getDataFromApi();
        } catch (\Exception $e) {
            return 'No Data found';
        }

        foreach ($groups as $group) {
            $model = new Group();
            $model->setProperties($group);
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
