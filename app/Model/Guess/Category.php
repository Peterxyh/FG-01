<?php
namespace App\Model\Guess;
/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/29
 * Time: 15:10
 */

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     use AdminBuilder, ModelTree {
         ModelTree::boot as treeBoot;
     }

     /** @var $table */
     protected $table = 'category';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
     protected $fillable = ['parent_id', 'title', 'status'];

     /**
      * guess games
      */
     public function guess()
     {
         return $this->hasOne('App\Model\Guess\Guess', 'categiry_id', 'id');
     }

     /**
      * teams
      */
     public function teams()
     {
         return $this->hasOne('App\Model\Guess\Teams', 'categiry_id', 'id');
     }
}