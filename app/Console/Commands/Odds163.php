<?php
namespace App\Console\Commands;

/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/29
 * Time: 18:11
 */

use Illuminate\Console\Command;
use App\Services\Reptile;

class Odds163 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odds163:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Odds form //odds.caipiao.163.com';

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
        $_reptile = new Reptile('http://odds.caipiao.163.com/#;229,126', 'GET');
        $crawler  = $_reptile->request();

        /*$crawler->filter('Core.pageData')->each(function ($node) {
            dump($node->text());
        });*/
    }
}