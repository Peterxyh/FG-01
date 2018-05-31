<?php
namespace App\Console\Commands;
/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/31
 * Time: 10:44
 */

use App\Model\Guess\Category;
use App\Model\Guess\Guess;
use App\Model\Guess\Teams;
use Illuminate\Console\Command;
use App\Services\Reptile;
use DB;

class Pinnacle extends Command
{
    const PLATFORM = 1;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guess:odds-pinnacle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Guess infomation from https://www.pinnacle.com';

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
        $_category = Category::where('status', 1)->get();

        if ($_category && env('GUESS_REQUEST_ORIGIN'))
        {
            foreach ($_category as $_cates)
            {
                $_reptile = new Reptile($_cates->url, 'GET');
                $_events  = $_reptile->request();
                $this->handelDatas($_events);
            }

            return;
        }

        $_filePath = storage_path('logs') . '\json.json';
        $_dataJson = file_get_contents($_filePath);
        $this->handelDatas(json_decode($_dataJson, true));

        return;
    }

    /**
     * handel data
     */
    protected function handelDatas($_events)
    {
        $_guess  = [];

        DB::beginTransaction();

        foreach ($_events['Leagues'][0]['Events'] as $_event)
        {
            if ($_event['IsMoneyLineEmpty']) continue;

            $_guessData = [];
            $_isDraw    = 0;

            foreach ($_event['Participants'] as $participant)
            {
                if (isset($participant['Name'])) {
                    $_temas = Teams::where('origin_id', $participant['Id'])->where('category_id', $_category->id)->first();
                    if (!$_temas)
                    {
                        $_temas = new Teams();
                        $_temas->category_id    = $_category->id;
                        $_temas->origin_id      = (int)$participant['Id'];
                        $_temas->team_name      = $participant['Name'];
                        $_temas->save();
                    }

                    $_guessData[$participant['Type']] = [
                        'id' => $_temas->id,
                        'team_name' => $_temas->team_name,
                        'odds'      => isset($participant['MoneyLine']) ? $this->getOdds($participant['MoneyLine']) : 0,
                    ];
                }

                if ($participant['IsDraw'])
                {
                    $_isDraw = isset($participant['MoneyLine']) ? $this->getOdds($participant['MoneyLine']) : 0;
                }
            }

            $fields = [];
            $values = [];
            $updates = [];
            $_guess = [
                'category_id'   => $_category->id,
                'game_name'     => $_guessData['Team1']['team_name'] . ' -VS- ' . $_guessData['Team2']['team_name'],
                'team_id_one'   => $_guessData['Team1']['id'],
                'team_id_two'   => $_guessData['Team2']['id'],
                'odds_one'      => $_guessData['Team1']['odds'],
                'odds_two'      => $_guessData['Team2']['odds'],
                'odds_draw'     => $_isDraw,
                'event_id'      => $_event['EventId'],
                'platform'      => self::PLATFORM,
                'status'        => Guess::GUESS_STATUS_STARTING,
                'game_time'     => date('Y-m-d H:i:s', strtotime($_event['DateAndTime'])),
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ];
            foreach ($_guess as $field => $value) {
                $fields[] = $field;
                $values[] = "'".addslashes("{$value}")."'";

                if ('event_id' !== $field && 'platform' !== $field && 'created_at' !== $field)
                    $updates[] = sprintf('%s = %s', $field, "'".addslashes("{$value}")."'");
            }

            $sql = 'INSERT INTO guess ('.implode(',', $fields).') VALUES ('.implode(',', $values).') ON DUPLICATE KEY UPDATE ' . implode(', ', $updates);
            DB::insert($sql);
        }

        try {
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return;
    }

    /**
     * get odds
     */
    protected function getOdds($_value)
    {
        $_value  = floatval($_value);

        if ($_value < 0)
        {
            $_result = 100 / abs($_value) + 1;
        } else {
            $_result = $_value / 100 + 1;
        }

        return sprintf('%.3f', $_result);
    }
}