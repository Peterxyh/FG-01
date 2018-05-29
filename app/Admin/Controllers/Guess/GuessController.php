<?php
namespace App\Admin\Controllers\Guess;
/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/29
 * Time: 15:51
 */

use App\Http\Controllers\Controller;
use App\Model\Guess\Category;
use App\Model\Guess\Guess;
use App\Model\Guess\Teams;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class GuessController extends Controller
{
    use ModelForm;

    /** @var $_title */
    protected $_title = 'Guess Games';

    /**
     * index interface
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header($this->_title);
            $content->description(__('admin.list'));

            $content->body($this->grid());
        });
    }

    /**
     * edit interface
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header($this->_title);
            $content->description(__('admin.edit'));

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header($this->_title);
            $content->description(__('admin.new'));

            $content->body($this->form());
        });
    }

    /**
     * make form builder
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Guess::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('game_name', 'Games name');
            $form->select('category_id', 'Category')->options(Category::where('status', 1)->pluck('title', 'id'));

            $form->select('team_id_one', 'Team One')->options(Teams::all()->pluck('team_name', 'id'));
            $form->number('odds_one', 'Team One Odds');

            $form->select('team_id_two', 'Team Two')->options(Teams::all()->pluck('team_name', 'id'));
            $form->number('odds_two', 'Team Two Odds');

            $form->number('odds_draw', 'Draw Odds');

            $form->display('result', 'Result');

            $form->select('status', 'Status')->options(Guess::getStatus());

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
        });
    }

    /**
     * make gridbuilder
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Guess::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'DESC');

            $grid->id('ID')->sortable();
            $grid->game_name('Games name');
            $grid->categorys()->title('Category');

            $grid->teamsOne()->team_name('Team One');
            $grid->odds_one('Team One Odds');
            $grid->teamsTwo()->team_name('Team Two');
            $grid->odds_two('Team Two Odds');

            $grid->odds_draw('Win-win Odds');

            $grid->result('Result');

            $grid->status('Status')->display(function ($status) {
                $_colors = 'error';
                if ($status == Guess::GUESS_STATUS_STARTING)
                    $_colors = 'warning';
                elseif ($status == Guess::GUESS_STATUS_STARTED)
                    $_colors = 'success';
                return '<span class="label label-'.$_colors.'">'.Guess::getStatus($status).'</span>';
            });

            $grid->created_at('Create At')->sortable();
            $grid->updated_at('Update At')->sortable();

            $grid->filter(function ($filter) {
                $filter->like('game_name', 'Games name');
            });
        });
    }
}