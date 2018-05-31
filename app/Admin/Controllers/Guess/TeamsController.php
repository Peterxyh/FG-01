<?php
namespace App\Admin\Controllers\Guess;
/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/29
 * Time: 16:44
 */

use App\Http\Controllers\Controller;
use App\Model\Guess\Teams;
use App\Model\Guess\Category;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class TeamsController extends Controller
{
    use ModelForm;

    /** @var $_title */
    protected $_title = 'Teams';

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
        return Admin::form(Teams::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('team_name', 'Team name');
            $form->text('team_name_cn', 'Team name(CN)');
            $form->select('category_id', 'Category')->options(Category::where('status', 1)->pluck('title', 'id'));
            $form->image('image', 'Images')->uniqueName()->removable();
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
        return Admin::grid(Teams::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'DESC');

            $grid->id('ID')->sortable();

            $grid->image('Images')->image('', 90, 90);

            $grid->team_name('Team name');

            $grid->categorys()->title('Category');

            $grid->created_at('Create At')->sortable();
            $grid->updated_at('Update At')->sortable();

            $grid->filter(function ($filter) {
                $filter->like('team_name', 'Team name');
                $filter->equal('category_id', 'Category')->select(Category::where('status', 1)->pluck('title', 'id'));
            });
        });
    }
}