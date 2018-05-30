<?php
namespace App\Admin\Controllers\Users;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/30
 * Time: 15:54
 */

use App\Http\Controllers\Controller;
use App\Model\Users\UserJoin;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class UserJoinController extends Controller
{
    use ModelForm;

    /** @var $_title */
    protected $_title = 'User join guess';

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
        return Admin::form(UserJoin::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('users.name', 'Name')->readOnly();
            $form->email('users.email', 'Email')->readOnly();
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
        return Admin::grid(UserJoin::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'DESC');

            $grid->id('ID')->sortable();
            $grid->users()->name('User Name');
            $grid->users()->email('Email');
            $grid->userguess()->game_name('Game name');
            $grid->odds('ODDS');
            $grid->result('Result');
            $grid->amount('Amount');
            $grid->status('Status');
            $grid->created_at('Create At')->sortable();
            $grid->updated_at('Update At')->sortable();

            $grid->filter(function ($filter) {
                $filter->like('users.name', 'User Name');
                $filter->like('users.email', 'User Email');
                $filter->like('userguess.game_name', 'Game name');
            });

            $grid->disableCreateButton();
        });
    }
}