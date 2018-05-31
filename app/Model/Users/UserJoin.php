<?php
namespace App\Model\Users;

/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/30
 * Time: 15:43
 */

use Illuminate\Database\Eloquent\Model;

class UserJoin extends Model
{
    /** @var $table */
    protected $table = 'user_join';

    /**
     * users
     */
    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * user guess
     */
    public function userguess()
    {
        return $this->hasOne('App\Model\Guess\Guess', 'categiry_id', 'id');
    }

    /**
     * user teams
     */
    public function teams()
    {
        return $this->belongsTo('App\Model\Guess\Teams', 'teams_id');
    }
}