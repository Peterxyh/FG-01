<?php
namespace App\Console\Commands;

/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/29
 * Time: 18:11
 */

use App\Model\Guess\Guess;
use Illuminate\Console\Command;
use App\Services\Reptile;

class GuessTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guess:status-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Guess Status';

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
        $_guess = Guess::where('status', Guess::GUESS_STATUS_STARTING)->whereDate('game_time', '<=', date('Y-m-d H:i:s'))->get();

        if (count($_guess) < 1) return;

        foreach ($_guess as $_gues)
        {
            if (strtotime($_gues->game_time) <= time())
            {
                // 比赛开始
                $_gues->status = Guess::GUESS_STATUS_STARTED;
                $_gues->save();
            } else {
                continue;
            }
        }

        return;
    }
}