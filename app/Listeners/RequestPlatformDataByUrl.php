<?php

namespace App\Listeners;

use App\Events\SaveCategory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class RequestPlatformDataByUrl/* implements ShouldQueue*/
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SaveCategory  $event
     * @return void
     */
    public function handle(SaveCategory $event)
    {
        //
        Log::useFiles(storage_path().'/logs/laravel_1.log')->info('用户注册原始数据:', $event);
    }
}
