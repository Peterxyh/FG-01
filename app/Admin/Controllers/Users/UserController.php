<?php
namespace App\Admin\Controllers\Users;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/30
 * Time: 15:45
 */
use App\Http\Controllers\Controller;
use App\User;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class UserController extends Controller
{
    use ModelForm;

    /** @var $_title */
    protected $_title = 'Users';

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
        return Admin::form(User::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name', 'Name')->readOnly();
            $form->email('email', 'Email')->readOnly();
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
        return Admin::grid(User::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'DESC');

            $grid->id('ID')->sortable();
            $grid->name('Name');
            $grid->email('Email');
            $grid->created_at('Create At')->sortable();
            $grid->updated_at('Update At')->sortable();

            $grid->filter(function ($filter) {
                $filter->like('name', 'Name');
                $filter->like('email', 'Email');
            });

            $grid->disableCreateButton();
        });
    }
}