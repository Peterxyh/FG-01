<?php
namespace App\Model\Guess;

/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/29
 * Time: 15:50
 */

use Illuminate\Database\Eloquent\Model;

class Guess extends Model
{
    /** @var $table */
    protected $table = 'guess';

    /** @var $status*/
    const GUESS_STATUS_STARTING = 0;
    const GUESS_STATUS_ONE      = 1;
    const GUESS_STATUS_TWO      = 2;
    const GUESS_STATUS_THR      = 3;
    const GUESS_STATUS_STARTED  = 4;

    /**
     * get status
     *
     * @return string || array
     */
    public static function getStatus($status = null)
    {
        $_status = [
            self::GUESS_STATUS_STARTING => 'Not begin',
            self::GUESS_STATUS_ONE      => 'Win One Team',
            self::GUESS_STATUS_TWO      => 'Win Two Team',
            self::GUESS_STATUS_THR      => 'Win-win',
            self::GUESS_STATUS_STARTED  => 'Started',
        ];

        if (!is_null($status)) return $_status[$status];

        return $_status;
    }

    /**
     * category
     */
    public function categorys()
    {
        return $this->belongsTo('App\Model\Guess\Category', 'category_id');
    }

    /**
     * teams One
     */
    public function teamsOne()
    {
        return $this->belongsTo('App\Model\Guess\Teams', 'team_id_one');
    }

    /**
     * teams One
     */
    public function teamsTwo()
    {
        return $this->belongsTo('App\Model\Guess\Teams', 'team_id_two');
    }
}