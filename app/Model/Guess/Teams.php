<?php
namespace App\Model\Guess;
/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/29
 * Time: 16:43
 */

use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    /** @var $table */
    protected $table = 'teams';

    /**
     * category
     */
    public function categorys()
    {
        return $this->belongsTo('App\Model\Guess\Category', 'category_id');
    }

    /**
     * guess games
     */
    public function guess()
    {
        return $this->hasOne('App\Model\Guess\Guess', 'team_id_one', 'id');
    }

    /**
     * join user
     */
    public function joinuser()
    {
        return $this->hasMany('App\Model\Users\UserJoin', 'teams_id', 'id');
    }
}